@php
    $formattedLogs = collect($securityLogs->items())->map(function ($log) {
        return [
            'id' => $log->id,
            'activity' => $log->activity,
            'date' => $log->created_at->format('M d, Y • H:i:s'),
            'is_suspicious' => (bool) $log->is_suspicious,
            'ip_address' => $log->ip_address,
            'user_agent' => $log->user_agent,
            'user' => $log->user ? [
                'name' => $log->user->name,
                'role' => $log->user->role,
                'profile_photo_url' => $log->user->profile_photo_url
            ] : null
        ];
    });
@endphp
<x-app-layout :title="app()->getLocale() == 'en' ? 'Business Intelligence & Owner KPI' : 'Intelijen Bisnis & KPI Pemilik'">
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6 page-fade-in">
        <div>
            <div class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">
                <span>Security Console</span>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-gold-600">Intelligence Center</span>
            </div>
            <h1 class="text-3xl font-black text-slate-900 font-jakarta tracking-tight uppercase">Security Intelligence</h1>
            <p class="text-sm text-slate-500 font-medium mt-1">Deep analysis of system transmissions, security logs, and operative activity.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="px-4 py-2 bg-emerald-50 rounded-xl border border-emerald-100 flex items-center gap-3">
                <span class="flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-[10px] font-black text-emerald-700 uppercase tracking-widest">Global Watch Active</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Security Logs (Left) -->
        <div class="lg:col-span-8 space-y-8">
            <div class="table-container page-fade-in stagger-1" x-data="securityConsole">
                <div class="px-10 py-8 border-b border-slate-100 bg-slate-50/30 flex items-center justify-between">
                    <div>
                        <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-lg">Access & Identity Logs</h3>
                        <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mt-1">Manual Verification Requests & Login Attempts</p>
                    </div>
                    <div class="p-3 bg-white border border-slate-200 rounded-xl">
                        <i data-lucide="shield-check" class="w-5 h-5 text-gold-600"></i>
                    </div>
                </div>

                <!-- Tabbed Navigation -->
                <div class="px-10 py-4 border-b border-slate-100 flex gap-6 text-xs font-bold uppercase tracking-wider bg-slate-50/10">
                    <button 
                        @click="activeTab = 'all'" 
                        class="pb-2 transition-all duration-300 border-b-2" 
                        :class="activeTab === 'all' ? 'text-gold-600 border-gold-500 font-black' : 'text-slate-400 border-transparent hover:text-slate-600'">
                        {{ app()->getLocale() == 'en' ? 'All Events' : 'Semua Log' }}
                    </button>
                    <button 
                        @click="activeTab = 'login'" 
                        class="pb-2 transition-all duration-300 border-b-2" 
                        :class="activeTab === 'login' ? 'text-gold-600 border-gold-500 font-black' : 'text-slate-400 border-transparent hover:text-slate-600'">
                        {{ app()->getLocale() == 'en' ? 'Login Attempts' : 'Upaya Login' }}
                    </button>
                    <button 
                        @click="activeTab = 'high_risk'" 
                        class="pb-2 transition-all duration-300 border-b-2" 
                        :class="activeTab === 'high_risk' ? 'text-gold-600 border-gold-500 font-black' : 'text-slate-400 border-transparent hover:text-slate-600'">
                        {{ app()->getLocale() == 'en' ? 'High Risk' : 'Risiko Tinggi' }}
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="table-header">
                                <th class="px-10 py-5">Event</th>
                                <th class="px-10 py-5">Identity</th>
                                <th class="px-10 py-5">Network Node</th>
                                <th class="px-10 py-5">Risk</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <!-- Template for active filtered logs -->
                            <template x-for="log in filteredLogs" :key="log.id">
                                <tr class="table-row-premium transition-all duration-300">
                                    <td class="px-10 py-6">
                                        <div class="flex flex-col">
                                            <span class="text-[13px] font-black text-slate-900 tracking-tight" x-text="log.activity"></span>
                                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1" x-text="log.date"></span>
                                        </div>
                                    </td>
                                    <td class="px-10 py-6">
                                        <template x-if="log.user">
                                            <div class="flex items-center gap-3">
                                                <img :src="log.user.profile_photo_url" class="w-8 h-8 rounded-lg object-cover">
                                                <div class="flex flex-col">
                                                    <span class="text-[12px] font-bold text-slate-900" x-text="log.user.name"></span>
                                                    <span class="text-[10px] text-slate-400 font-bold uppercase" x-text="log.user.role"></span>
                                                </div>
                                            </div>
                                        </template>
                                        <template x-if="!log.user">
                                            <span class="text-xs text-slate-400 font-medium italic">Unregistered Operative</span>
                                        </template>
                                    </td>
                                    <td class="px-10 py-6">
                                        <div class="flex flex-col">
                                            <span class="text-[12px] font-mono font-bold text-slate-700" x-text="log.ip_address"></span>
                                            <span class="text-[9px] text-slate-400 truncate max-w-[200px]" :title="log.user_agent" x-text="log.user_agent"></span>
                                        </div>
                                    </td>
                                    <td class="px-10 py-6">
                                        <span class="inline-flex px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest"
                                              :class="log.is_suspicious ? 'bg-rose-50 text-rose-600 border border-rose-100' : 'bg-slate-50 text-slate-500 border border-slate-100'"
                                              x-text="log.is_suspicious ? 'High Risk' : 'Low Risk'">
                                        </span>
                                    </td>
                                </tr>
                            </template>

                            <!-- Empty State -->
                            <tr x-show="filteredLogs.length === 0" style="display: none;">
                                <td colspan="4" class="px-10 py-20 text-center text-slate-400 italic">
                                    {{ app()->getLocale() == 'en' ? 'No security events detected in current timeframe.' : 'Tidak ada log keamanan yang terdeteksi untuk kategori ini.' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="px-10 py-6 border-t border-slate-50">
                    {{ $securityLogs->appends(['notif_page' => $notifications->currentPage()])->links() }}
                </div>
            </div>
        </div>

        <!-- Notifications (Right) -->
        <div class="lg:col-span-4 space-y-8">
            <div class="glass-card p-10 page-fade-in stagger-2">
                <div class="flex items-center justify-between mb-10">
                    <h3 class="font-black text-slate-900 font-jakarta uppercase tracking-tight text-lg">Notifications</h3>
                    <span class="px-3 py-1 bg-gold-500 text-slate-950 text-[10px] font-black rounded-full uppercase tracking-widest">
                        {{ auth()->user()->unreadNotifications->count() }} Unread
                    </span>
                </div>

                <div class="space-y-6">
                    @forelse($notifications as $notification)
                        <div class="relative pl-12 group">
                            <div class="absolute left-0 top-0 w-8 h-8 rounded-xl {{ $notification->read_at ? 'bg-slate-100 text-slate-400' : 'bg-gold-50 text-gold-600' }} flex items-center justify-center transition-all group-hover:scale-110">
                                <i data-lucide="{{ match($notification->data['type'] ?? 'system') { 'security' => 'shield-alert', 'finance' => 'wallet', 'critical' => 'alert-triangle', default => 'bell' } }}" class="w-4 h-4"></i>
                            </div>
                            <div class="space-y-1">
                                <div class="flex items-center justify-between">
                                    <p class="text-[13px] font-black text-slate-900 leading-tight uppercase tracking-tight">{{ $notification->data['title'] ?? 'System Update' }}</p>
                                    @if(!$notification->read_at)
                                        <a href="{{ route('intelligence.read', $notification->id) }}" class="p-1 text-slate-300 hover:text-gold-600 transition-colors" title="Mark as read">
                                            <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                                        </a>
                                    @endif
                                </div>
                                <p class="text-[12px] text-slate-500 font-medium leading-relaxed">{{ $notification->data['message'] ?? 'Status updated.' }}</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest pt-1">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <i data-lucide="inbox" class="w-10 h-10 text-slate-200 mx-auto mb-4"></i>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Intelligence feed clear.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-10 pt-10 border-t border-slate-100">
                    {{ $notifications->appends(['logs_page' => $securityLogs->currentPage()])->links() }}
                </div>
            </div>

            <!-- Security Stats -->
            <div class="glass-card p-10 bg-slate-900 text-white overflow-hidden relative page-fade-in stagger-3">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gold-500/10 rounded-full -mr-16 -mt-16 blur-2xl"></div>
                <h4 class="text-xs font-black uppercase tracking-[0.3em] text-gold-400 mb-6">Security Integrity</h4>
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-400">Total Logs</span>
                        <span class="text-xl font-black">{{ \App\Models\SecurityLog::count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-400">Suspicious Events</span>
                        <span class="text-xl font-black text-rose-400">{{ \App\Models\SecurityLog::where('is_suspicious', true)->count() }}</span>
                    </div>
                    <div class="pt-6 border-t border-white/5">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span class="text-[10px] font-black uppercase tracking-[0.2em]">Firewall Active</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-gold-500"></span>
                            <span class="text-[10px] font-black uppercase tracking-[0.2em]">Encryption Verified</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            registerSecurityConsole();
        });

        if (window.Alpine) {
            registerSecurityConsole();
        }

        function registerSecurityConsole() {
            if (window.securityConsoleRegistered) return;
            window.securityConsoleRegistered = true;

            Alpine.data('securityConsole', () => ({
                activeTab: 'all',
                logs: @json($formattedLogs),
                get filteredLogs() {
                    if (this.activeTab === 'login') {
                        return this.logs.filter(log => log.activity.toLowerCase().includes('login'));
                    }
                    if (this.activeTab === 'high_risk') {
                        return this.logs.filter(log => log.is_suspicious);
                    }
                    return this.logs;
                }
            }));
        }
    </script>
    @endpush
</x-app-layout>
