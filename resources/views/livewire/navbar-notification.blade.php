<div class="relative" x-data="{ open: false }" @click.away="open = false" wire:poll.30s="loadNotifications">
    <!-- Trigger Button -->
    <button @click="open = !open" class="relative p-2.5 rounded-xl bg-slate-50 text-slate-400 hover:text-slate-900 hover:bg-white hover:shadow-sm transition-all group">
        <i data-lucide="bell" class="w-5 h-5 group-hover:rotate-12 transition-transform"></i>
        
        @if($unreadCount > 0)
            <span class="absolute top-1.5 right-1.5 w-5 h-5 bg-rose-500 text-white text-[10px] font-black rounded-full flex items-center justify-center border-2 border-white animate-pulse">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Mobile Backdrop -->
    <div 
        x-show="open" 
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[90] md:hidden" 
        @click="open = false" 
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-cloak
    ></div>

    <!-- Notification Popover / Bottom Sheet -->
    <div 
        x-show="open"
        x-transition:enter="transition-all transform ease-out duration-300"
        x-transition:enter-start="translate-y-full md:opacity-0 md:translate-y-4 md:scale-95"
        x-transition:enter-end="translate-y-0 md:opacity-100 md:scale-100"
        x-transition:leave="transition-all transform ease-in duration-300"
        x-transition:leave-start="translate-y-0 md:opacity-100 md:scale-100"
        x-transition:leave-end="translate-y-full md:opacity-0 md:translate-y-4 md:scale-95"
        class="fixed inset-x-0 bottom-0 z-[100] w-full bg-white rounded-t-[32px] shadow-[0_-10px_40px_rgba(0,0,0,0.1)] overflow-hidden md:absolute md:left-auto md:-right-4 md:bottom-auto md:top-full md:mt-4 md:w-96 md:rounded-[32px] md:border md:border-slate-100 md:shadow-xl md:origin-top flex flex-col max-h-[85vh] md:max-h-[32rem]"
        x-cloak
    >
        <!-- Mobile drag handle -->
        <div class="w-full flex justify-center pt-4 pb-2 md:hidden bg-slate-50/50 cursor-grab active:cursor-grabbing" @click="open = false">
            <div class="w-12 h-1.5 bg-slate-200 rounded-full"></div>
        </div>

        <!-- Header -->
        <div class="px-6 py-5 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-tight">Notifications</h3>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-0.5">{{ $unreadCount }} Unread transmissions</p>
            </div>
            @if($unreadCount > 0)
                <button wire:click="markAllAsRead" class="text-[10px] font-black text-indigo-600 uppercase tracking-widest hover:text-indigo-700 transition-colors">
                    Mark all read
                </button>
            @endif
        </div>

        <!-- Body -->
        <div class="max-h-[60vh] md:max-h-96 overflow-y-auto custom-scrollbar">
            @forelse($notifications as $notification)
                @php
                    $type = $notification->data['type'] ?? 'system';
                    $icon = match($type) {
                        'finance' => 'wallet',
                        'security' => 'shield-check',
                        'critical' => 'alert-octagon',
                        default => 'cpu'
                    };
                    $colorClass = match($type) {
                        'finance' => 'bg-emerald-50 text-emerald-600',
                        'security' => 'bg-amber-50 text-amber-600',
                        'critical' => 'bg-rose-50 text-rose-600',
                        default => 'bg-blue-50 text-blue-600'
                    };
                @endphp
                <div 
                    class="px-6 py-5 border-b border-slate-50 hover:bg-slate-50/80 transition-all cursor-pointer group relative {{ $notification->read_at ? 'opacity-60' : '' }}"
                    @click="$wire.markAsRead('{{ $notification->id }}')"
                >
                    @if(!$notification->read_at)
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 bg-indigo-500 rounded-full shadow-[0_0_8px_rgba(79,70,229,0.5)]"></div>
                    @endif

                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-xl {{ $colorClass }} shrink-0 flex items-center justify-center transition-transform group-hover:scale-110">
                            <i data-lucide="{{ $icon }}" class="w-5 h-5"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <h4 class="text-[13px] font-black text-slate-900 leading-tight truncate uppercase tracking-tight">
                                    {{ $notification->data['title'] ?? 'Transmission Received' }}
                                </h4>
                                <span class="text-[9px] text-slate-400 font-bold uppercase whitespace-nowrap">
                                    {{ $notification->created_at->diffForHumans(null, true) }}
                                </span>
                            </div>
                            <p class="text-[12px] text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                                {{ $notification->data['message'] ?? 'Data packet integrity verified.' }}
                            </p>
                            
                            @if(isset($notification->data['action_url']))
                                <div class="mt-3 flex items-center gap-2">
                                    <a href="{{ $notification->data['action_url'] }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-[10px] font-black text-slate-900 uppercase tracking-widest hover:border-slate-900 transition-all">
                                        {{ $notification->data['action_label'] ?? 'Execute View' }}
                                        <i data-lucide="external-link" class="w-3 h-3"></i>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-16 md:py-20 px-10 text-center flex flex-col items-center justify-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-[32px] shadow-inner flex items-center justify-center mx-auto mb-5 text-slate-300 relative">
                        <i data-lucide="bell-off" class="w-10 h-10 relative z-10"></i>
                        <div class="absolute inset-0 bg-indigo-500/5 rounded-[32px] blur-md"></div>
                    </div>
                    <h4 class="text-[13px] md:text-sm font-black text-slate-900 uppercase tracking-tight">All Caught Up!</h4>
                    <p class="text-[10px] md:text-[11px] text-slate-400 font-bold uppercase tracking-widest mt-2 leading-relaxed max-w-[200px] mx-auto">No pending transmissions detected.</p>
                </div>
            @endforelse
        </div>

        <!-- Footer -->
        <div class="p-6 bg-slate-50/50 border-t border-slate-100">
            <a href="{{ route('intelligence.index') }}" class="flex items-center justify-center gap-2 w-full py-3 bg-white border border-slate-200 rounded-2xl text-[10px] font-black text-slate-900 uppercase tracking-widest hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all shadow-sm">
                View All Intelligence
                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
        </div>
    </div>
</div>
