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

    <!-- Notification Popover -->
    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-95"
        class="absolute right-0 mt-4 w-[400px] bg-white rounded-[32px] shadow-2xl border border-slate-100 overflow-hidden z-[100] origin-top-right"
        x-cloak
    >
        <!-- Header -->
        <div class="px-8 py-6 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
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
        <div class="max-h-[450px] overflow-y-auto custom-scrollbar">
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
                    class="px-8 py-5 border-b border-slate-50 hover:bg-slate-50/80 transition-all cursor-pointer group relative {{ $notification->read_at ? 'opacity-60' : '' }}"
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
                <div class="py-20 px-10 text-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-[32px] flex items-center justify-center mx-auto mb-6 text-slate-200">
                        <i data-lucide="inbox" class="w-10 h-10"></i>
                    </div>
                    <h4 class="text-sm font-black text-slate-900 uppercase tracking-tight">All Caught Up!</h4>
                    <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mt-2">No pending transmissions detected.</p>
                </div>
            @endforelse
        </div>

        <!-- Footer -->
        <div class="p-6 bg-slate-50/50 border-t border-slate-100">
            <a href="#" class="flex items-center justify-center gap-2 w-full py-3 bg-white border border-slate-200 rounded-2xl text-[10px] font-black text-slate-900 uppercase tracking-widest hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all shadow-sm">
                View All Intelligence
                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
        </div>
    </div>
</div>
