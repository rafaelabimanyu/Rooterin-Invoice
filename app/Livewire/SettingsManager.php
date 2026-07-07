<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingsManager extends Component
{
    use WithFileUploads;

    public $activeTab = 'general';
    public $settings = [];
    public $companyLogo;
    public $isDirty = false;
    public $lastBackup = '2 hours ago';
    public $serverStatus = 'Operational';

    public function mount()
    {
        $this->settings = Setting::pluck('value', 'key')->toArray();
        
        // Ensure defaults for new fields
        $defaults = [
            'currency_symbol' => 'Rp',
            'currency_position' => 'prefix',
            'decimal_places' => '0',
            'date_format' => 'd M Y',
            'timezone' => 'Asia/Jakarta',
            'language' => 'id',
            'smtp_host' => 'smtp.mailtrap.io',
            'email_template_header' => 'Dear Valued Client,',
            'email_template_footer' => 'Best regards, J&J GROUP Team',
            'primary_color' => '#6366f1',
        ];

        foreach ($defaults as $key => $value) {
            if (!isset($this->settings[$key])) {
                $this->settings[$key] = $value;
            }
        }
    }

    public function updatedSettings()
    {
        $this->isDirty = true;
    }

    public function save()
    {
        foreach ($this->settings as $key => $value) {
            Setting::set($key, $value);
        }

        if ($this->companyLogo) {
            $path = $this->companyLogo->store('branding', 'public');
            Setting::set('invoice_logo', $path);
        }

        $this->isDirty = false;
        $this->dispatch('notify', ['message' => 'Settings secured successfully.', 'type' => 'success']);
    }

    public function discard()
    {
        $this->mount();
        $this->isDirty = false;
    }

    public function render()
    {
        return view('livewire.settings-manager');
    }
}
