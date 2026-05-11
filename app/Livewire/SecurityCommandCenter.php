<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\SecurityLog;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Str;

class SecurityCommandCenter extends Component
{
    public $isVerified = false;
    public $sudoPassword = '';
    public $activeTab = 'sessions';

    // 2FA properties
    public $show2FASetup = false;
    public $qrCodeSvg = '';
    public $twoFactorSecret = '';
    public $twoFactorCode = '';
    public $recoveryCodes = [];

    // Session properties
    public $sessions = [];
    public $confirmingTermination = false;

    public function mount()
    {
        // Sudo mode session check
        if (session()->has('sudo_verified_at')) {
            $verifiedAt = \Illuminate\Support\Carbon::parse(session('sudo_verified_at'));
            if ($verifiedAt->diffInHours(now()) < 2) {
                $this->isVerified = true;
            }
        }

        if ($this->isVerified) {
            $this->loadSessions();
        }
    }

    public function verifySudo()
    {
        $this->validate(['sudoPassword' => 'required']);

        $throttleKey = 'sudo-verify:' . auth()->id();
        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($throttleKey);
            $this->addError('sudoPassword', "Too many attempts. Locked for $seconds seconds.");
            return;
        }

        if (Hash::check($this->sudoPassword, auth()->user()->password)) {
            \Illuminate\Support\Facades\RateLimiter::clear($throttleKey);
            $this->isVerified = true;
            session(['sudo_verified_at' => now()]);
            $this->loadSessions();
            $this->logActivity('Security Command Center Accessed (Sudo Verified)');
        } else {
            \Illuminate\Support\Facades\RateLimiter::hit($throttleKey, 300); // 5 min lock
            $this->addError('sudoPassword', 'Unauthorized access attempt. Identity mismatch.');
            $this->logActivity('Failed Sudo Verification Attempt', true);
        }
    }

    public function loadSessions()
    {
        $this->sessions = DB::table('sessions')
            ->where('user_id', auth()->id())
            ->get()
            ->map(function ($session) {
                $agent = new \Jenssegers\Agent\Agent();
                $agent->setUserAgent($session->user_agent);
                
                // Map Browser Icons
                $browser = $agent->browser();
                $browserIcon = match(strtolower($browser)) {
                    'chrome' => 'chrome',
                    'firefox' => 'globe',
                    'safari' => 'compass',
                    'edge' => 'layout',
                    default => 'browser'
                };

                // Map OS Icons
                $platform = $agent->platform();
                $platformIcon = match(strtolower($platform)) {
                    'windows' => 'monitor',
                    'os x', 'ios', 'mac os x' => 'apple',
                    'android' => 'smartphone',
                    'linux' => 'terminal',
                    default => 'cpu'
                };

                return [
                    'id' => $session->id,
                    'ip_address' => $session->ip_address,
                    'is_current_device' => $session->id === session()->getId(),
                    'last_active' => \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                    'browser' => $browser,
                    'browser_icon' => $browserIcon,
                    'platform' => $platform,
                    'platform_icon' => $platformIcon,
                    'device_type' => $agent->isMobile() ? 'smartphone' : 'monitor',
                ];
            });
    }

    public function terminateSession($sessionId)
    {
        if ($sessionId === session()->getId()) return;

        DB::table('sessions')->where('id', $sessionId)->delete();
        $this->loadSessions();
        $this->dispatch('notify', ['message' => 'Remote session terminated.', 'type' => 'success']);
        $this->logActivity('Remote Session Terminated');
    }

    public function confirmTerminateOthers()
    {
        $this->confirmingTermination = true;
    }

    public function terminateOtherSessions()
    {
        DB::table('sessions')
            ->where('user_id', auth()->id())
            ->where('id', '!=', session()->getId())
            ->delete();
        
        $this->confirmingTermination = false;
        $this->loadSessions();
        $this->dispatch('notify', ['message' => 'All other sessions purged.', 'type' => 'success']);
        $this->logActivity('All Other Sessions Purged');
    }

    public function initiate2FA()
    {
        $google2fa = new Google2FA();
        $this->twoFactorSecret = $google2fa->generateSecretKey();
        
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            auth()->user()->email,
            $this->twoFactorSecret
        );

        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $this->qrCodeSvg = $writer->writeString($qrCodeUrl);
        $this->show2FASetup = true;
    }

    public function confirm2FA()
    {
        $google2fa = new Google2FA();
        
        if ($google2fa->verifyKey($this->twoFactorSecret, $this->twoFactorCode)) {
            $user = auth()->user();
            $user->two_factor_secret = encrypt($this->twoFactorSecret);
            
            $codes = [];
            for ($i = 0; $i < 8; $i++) {
                $codes[] = Str::random(10) . '-' . Str::random(10);
            }
            $user->two_factor_recovery_codes = encrypt(json_encode($codes));
            $user->two_factor_confirmed_at = now();
            $user->save();

            $this->recoveryCodes = $codes;
            $this->show2FASetup = false;
            $this->dispatch('notify', ['message' => '2FA Protection Activated.', 'type' => 'success']);
            $this->logActivity('Multi-Factor Authentication Enabled');
        } else {
            $this->addError('twoFactorCode', 'Invalid verification code.');
        }
    }

    public function disable2FA()
    {
        $user = auth()->user();
        $user->two_factor_secret = null;
        $user->two_factor_recovery_codes = null;
        $user->two_factor_confirmed_at = null;
        $user->save();

        $this->dispatch('notify', ['message' => 'Security downgraded. 2FA disabled.', 'type' => 'warning']);
        $this->logActivity('Multi-Factor Authentication Disabled', true);
    }

    private function logActivity($activity, $isSuspicious = false)
    {
        SecurityLog::create([
            'user_id' => auth()->id(),
            'activity' => $activity,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'is_suspicious' => $isSuspicious,
        ]);
    }

    public function render()
    {
        $logs = SecurityLog::where('user_id', auth()->id())->latest()->take(10)->get();
        
        return view('livewire.security-command-center', [
            'auditLogs' => $logs
        ]);
    }
}
