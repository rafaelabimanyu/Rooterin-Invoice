<div class="space-y-6 w-full min-w-0" 
     x-data="{}" 
     @reminder-saved.window="if (typeof showToast === 'function') showToast($event.detail.message || ($event.detail[0] && $event.detail[0].message), 'success')"
>
    <!-- Top Filter Bar -->
    <div class="grid grid-cols-1 md:flex md:flex-wrap md:items-center gap-6 p-6 glass-card border-slate-100 shadow-xl shadow-indigo-500/5 bg-white/70 backdrop-blur-md rounded-3xl select-none relative">
        
        <!-- Filter Client (Custom Dropdown) -->
        <div class="relative flex-1 min-w-[200px]" x-data="{ open: false }" @click.outside="open = false" :class="{ 'z-30': open, 'z-10': !open }">
            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                {{ __('Filter Client') }}
            </label>
            <button @click="open = !open" type="button" class="premium-input w-full flex items-center justify-between text-left bg-white/70 border border-slate-200/80 rounded-2xl px-4 py-3 text-xs font-bold text-slate-700 shadow-sm transition-all hover:bg-slate-50/50">
                <div class="flex items-center gap-2 truncate">
                    <svg class="w-4 h-4 text-slate-455 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 .552-.448 1-1 1H4.75c-.552 0-1-.448-1-1v-4.25m16.5 0a3 3 0 00-3-3H6.75a3 3 0 00-3 3m16.5 0v-4.25c0-.552-.448-1-1-1H4.75c-.552 0-1 .448-1 1v4.25m16.5 0a9.75 9.75 0 01-6.75 6.75m0 0a9.75 9.75 0 01-6.75-6.75M9 3.75h6"></path>
                    </svg>
                    <span class="truncate">
                        {{ $clientId ? ($clients->firstWhere('id', $clientId)->nama_client ?? __('All Clients')) : __('All Clients') }}
                    </span>
                </div>
                <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"></path>
                </svg>
            </button>
            
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                 class="absolute left-0 z-[60] mt-2 w-full bg-white/95 backdrop-blur-md border border-slate-100 rounded-2xl shadow-xl max-h-60 overflow-y-auto chat-scroll p-1.5"
                 style="display: none;"
            >
                <button wire:click="$set('clientId', '');" @click="open = false" type="button" class="w-full text-left px-3.5 py-2.5 text-xs font-bold rounded-xl text-slate-655 hover:bg-slate-50 hover:text-indigo-650 transition-colors">
                    {{ __('All Clients') }}
                </button>
                @foreach($clients as $client)
                    <button wire:click="$set('clientId', {{ $client->id }});" @click="open = false" type="button" class="w-full text-left px-3.5 py-2.5 text-xs font-bold rounded-xl transition-colors
                        {{ $clientId == $client->id ? 'bg-indigo-50 text-indigo-755' : 'text-slate-655 hover:bg-slate-50 hover:text-indigo-650' }}"
                    >
                        {{ $client->nama_client }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Invoice Status (Inline Pills) -->
        <div class="flex-1 min-w-[250px] md:flex-initial">
            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                {{ __('Invoice Status') }}
            </label>
            <div class="flex items-center gap-1.5 overflow-x-auto scrollbar-none py-0.5">
                <button wire:click="$set('status', '')" type="button" 
                    class="px-3.5 py-2.5 rounded-xl text-xs font-bold border transition-all duration-200 active:scale-95
                    {{ $status === '' ? 'bg-indigo-600 text-white border-indigo-600 shadow-md shadow-indigo-600/15' : 'bg-white/70 text-slate-600 border-slate-200/60 hover:bg-slate-50' }}"
                >
                    {{ __('All') }}
                </button>
                <button wire:click="$set('status', 'draft')" type="button" 
                    class="px-3.5 py-2.5 rounded-xl text-xs font-bold border transition-all duration-200 active:scale-95
                    {{ $status === 'draft' ? 'bg-amber-500 text-white border-amber-500 shadow-md shadow-amber-500/15' : 'bg-white/70 text-slate-600 border-slate-200/60 hover:bg-slate-50' }}"
                >
                    {{ __('Draft') }}
                </button>
                <button wire:click="$set('status', 'paid')" type="button" 
                    class="px-3.5 py-2.5 rounded-xl text-xs font-bold border transition-all duration-200 active:scale-95
                    {{ $status === 'paid' ? 'bg-emerald-550 text-white border-emerald-500 shadow-md shadow-emerald-500/15' : 'bg-white/70 text-slate-600 border-slate-200/60 hover:bg-slate-50' }}"
                >
                    {{ __('Paid') }}
                </button>
                <button wire:click="$set('status', 'overdue')" type="button" 
                    class="px-3.5 py-2.5 rounded-xl text-xs font-bold border transition-all duration-200 active:scale-95
                    {{ $status === 'overdue' ? 'bg-rose-550 text-white border-rose-500 shadow-md shadow-rose-500/15' : 'bg-white/70 text-slate-600 border-slate-200/60 hover:bg-slate-50' }}"
                >
                    {{ __('Overdue') }}
                </button>
                <button wire:click="$set('status', 'sent')" type="button" 
                    class="px-3.5 py-2.5 rounded-xl text-xs font-bold border transition-all duration-200 active:scale-95
                    {{ $status === 'sent' ? 'bg-blue-500 text-white border-blue-500 shadow-md shadow-blue-500/15' : 'bg-white/70 text-slate-600 border-slate-200/60 hover:bg-slate-50' }}"
                >
                    {{ __('Sent') }}
                </button>
            </div>
        </div>

        <!-- Responsible Staff (Custom Dropdown) -->
        @if(!auth()->user()->hasRole('staff'))
        <div class="relative flex-1 min-w-[200px]" x-data="{ open: false }" @click.outside="open = false" :class="{ 'z-20': open, 'z-10': !open }">
            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                {{ __('Responsible Staff') }}
            </label>
            <button @click="open = !open" type="button" class="premium-input w-full flex items-center justify-between text-left bg-white/70 border border-slate-200/80 rounded-2xl px-4 py-3 text-xs font-bold text-slate-700 shadow-sm transition-all hover:bg-slate-50/50">
                <div class="flex items-center gap-2 truncate">
                    <svg class="w-4 h-4 text-slate-455 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path>
                    </svg>
                    <span class="truncate">
                        {{ $staffId ? ($staffs->firstWhere('id', $staffId)->name ?? __('All Staff')) : __('All Staff') }}
                    </span>
                </div>
                <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"></path>
                </svg>
            </button>
            
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                 class="absolute left-0 z-[60] mt-2 w-full bg-white/95 backdrop-blur-md border border-slate-100 rounded-2xl shadow-xl max-h-60 overflow-y-auto chat-scroll p-1.5"
                 style="display: none;"
            >
                <button wire:click="$set('staffId', '');" @click="open = false" type="button" class="w-full text-left px-3.5 py-2.5 text-xs font-bold rounded-xl text-slate-655 hover:bg-slate-50 hover:text-indigo-650 transition-colors">
                    {{ __('All Staff') }}
                </button>
                @foreach($staffs as $staff)
                    <button wire:click="$set('staffId', {{ $staff->id }});" @click="open = false" type="button" class="w-full text-left px-3.5 py-2.5 text-xs font-bold rounded-xl transition-colors
                        {{ $staffId == $staff->id ? 'bg-indigo-50 text-indigo-755' : 'text-slate-655 hover:bg-slate-50 hover:text-indigo-650' }}"
                    >
                        {{ $staff->name }}
                    </button>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Reset Button -->
        <div class="flex items-end h-full pt-6 md:pt-0">
            <button wire:click="$set('clientId', ''); $set('status', ''); $set('staffId', '')" 
                class="p-3 text-slate-400 hover:text-indigo-650 hover:bg-indigo-50/50 rounded-2xl transition-all duration-200 active:scale-90 border border-transparent hover:border-slate-100" 
                title="{{ __('Reset Filters') }}"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Main Calendar Card -->
    <div class="glass-card p-6 md:p-8 shadow-2xl shadow-indigo-500/10 border-slate-100 bg-white/80 backdrop-blur-md rounded-3xl flex flex-col w-full min-w-0"
         x-data="chronosCalendar()"
         @reminder-saved.window="refetch()"
         @refresh-calendar.window="refetch()"
    >
        <!-- Calendar Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 w-full">
                <div class="flex items-center justify-between sm:justify-start gap-3 w-full sm:w-auto">
                    <button @click="goToPrevMonth()" class="w-12 h-12 shrink-0 flex items-center justify-center bg-slate-50 hover:bg-slate-100 text-slate-600 rounded-xl transition-all border border-slate-200/60 active:scale-95" title="Previous Month">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"></path>
                        </svg>
                    </button>
                    <h2 id="calendar-title" class="text-xs sm:text-md font-black text-slate-850 font-jakarta uppercase tracking-wider flex-1 sm:flex-none text-center select-none truncate px-2">
                        ...
                    </h2>
                    <button @click="goToNextMonth()" class="w-12 h-12 shrink-0 flex items-center justify-center bg-slate-50 hover:bg-slate-100 text-slate-600 rounded-xl transition-all border border-slate-200/60 active:scale-95" title="Next Month">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"></path>
                        </svg>
                    </button>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto sm:ml-auto">
                    <button @click="goToToday()" 
                        class="w-full sm:w-auto flex items-center justify-center px-5 py-3 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-extrabold text-xs rounded-2xl transition-all duration-200 active:scale-95"
                    >
                        {{ __('Today') }}
                    </button>
                    <button @click="openCreateModalForToday()" 
                        class="w-full sm:w-auto flex items-center justify-center gap-2 px-5 py-3 bg-white hover:bg-slate-50 text-slate-700 font-extrabold text-xs border border-slate-200/80 rounded-2xl shadow-sm hover:shadow transition-all duration-200 active:scale-95"
                    >
                        <svg class="w-4 h-4 text-indigo-650" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                        </svg>
                        <span>{{ __('+ Add Event / Reminder') }}</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- FullCalendar Render Target with Micro Loading Indicator -->
        <div class="relative w-full min-w-0">
            <!-- Syncing Micro-Loader overlay -->
            <div x-show="loading" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute inset-0 bg-white/65 backdrop-blur-[2px] z-20 flex items-center justify-center rounded-3xl transition-all"
                 style="display: none;"
            >
                <div class="flex items-center gap-3 px-5 py-3.5 bg-white shadow-xl rounded-2xl border border-slate-100/80">
                    <div class="w-5 h-5 border-3 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
                    <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest">
                        {{ __('Syncing Calendar...') }}
                    </span>
                </div>
            </div>
            
            <div wire:ignore class="w-full min-w-0" id="fullcalendar-target"></div>
        </div>
    </div>

    <!-- Modal: Add/Edit Reminder -->
    <template x-teleport="body">
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4"
             x-data="{ 
                 open: @entangle('showModal'), 
                 localLoading: false,
                 expanded: false,
                 touchStart: 0,
                 init() {
                     this.$watch('open', value => {
                         if (value) {
                             this.expanded = false;
                             this.localLoading = true;
                             setTimeout(() => {
                                 this.localLoading = false;
                             }, 350);
                         }
                     });
                 }
             }"
             x-show="open"
             x-cloak
        >
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
                 x-show="open"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="open = false"
            ></div>
            
            <!-- Modal Content Container (Mobile: Bottom Sheet | Desktop: Compact Center Modal) -->
            <div class="fixed md:relative bottom-0 md:bottom-auto left-0 right-0 md:left-auto md:right-auto w-full md:max-w-md bg-white rounded-t-3xl md:rounded-[32px] rounded-b-none md:rounded-b-[32px] shadow-2xl md:shadow-[0_25px_60px_-15px_rgba(0,0,0,0.2)] border border-slate-100 md:border-slate-100/80 z-[110] p-6 transform transition-all duration-300 ease-in-out flex flex-col"
                 x-show="open"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-y-full md:translate-y-0 md:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 md:scale-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 translate-y-0 md:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-full md:translate-y-0 md:scale-95"
                 :class="expanded ? 'translate-y-0 h-[88vh]' : 'translate-y-[calc(100%-82px)] h-[82px] overflow-hidden md:h-auto md:overflow-visible md:translate-y-0'"
                 @touchstart="touchStart = $event.touches[0].clientY"
                 @touchend="if (touchStart - $event.changedTouches[0].clientY > 50) expanded = true; if ($event.changedTouches[0].clientY - touchStart > 50) expanded = false;"
            >
                <!-- Drag Handle Pill (Mobile Only) -->
                <div class="md:hidden flex justify-center pb-3 cursor-pointer select-none" @click="expanded = !expanded">
                    <div class="w-12 h-1.5 bg-slate-200 hover:bg-slate-350 rounded-full transition-colors"></div>
                </div>

                <!-- Premium Loading Micro-Interactions -->
                <div x-show="localLoading" class="absolute inset-0 bg-white/80 backdrop-blur-md z-50 flex flex-col items-center justify-center rounded-t-3xl md:rounded-[32px] rounded-b-none md:rounded-b-[32px] transition-all duration-350" style="display: none;">
                    <div class="flex flex-col items-center gap-4">
                        <div class="relative flex items-center justify-center">
                            <div class="w-12 h-12 border-4 border-indigo-500/20 rounded-full absolute"></div>
                            <div class="w-12 h-12 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
                        </div>
                        <p class="text-[10px] font-black text-indigo-650 uppercase tracking-[0.2em] animate-pulse">
                            {{ __('Processing Event...') }}
                        </p>
                    </div>
                </div>
                <div wire:loading class="absolute inset-0 bg-white/80 backdrop-blur-md z-50 flex flex-col items-center justify-center rounded-t-3xl md:rounded-[32px] rounded-b-none md:rounded-b-[32px] transition-all duration-350">
                    <div class="flex flex-col items-center gap-4">
                        <div class="relative flex items-center justify-center">
                            <div class="w-12 h-12 border-4 border-indigo-500/20 rounded-full absolute"></div>
                            <div class="w-12 h-12 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
                        </div>
                        <p class="text-[10px] font-black text-indigo-650 uppercase tracking-[0.2em] animate-pulse">
                            {{ __('Processing Event...') }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-50 cursor-pointer md:cursor-default select-none shrink-0" @click="if (window.innerWidth < 768) expanded = !expanded">
                    <h3 class="text-xs sm:text-base font-black text-slate-900 font-jakarta uppercase tracking-wider">
                        {{ $selectedReminderId ? __('Edit Reminder') : __('Add Reminder') }}
                    </h3>
                    <button type="button" wire:click="$set('showModal', false)" @click.stop class="text-slate-400 hover:text-slate-655 transition-colors p-1.5 hover:bg-slate-50 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form wire:submit.prevent="saveReminder" class="flex-1 flex flex-col min-h-0 space-y-5">
                    <!-- Scrollable Input Fields Container -->
                    <div class="flex-1 overflow-y-auto pb-10 space-y-5 pr-1 md:pr-0 md:pb-0 scrollbar-none">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                                {{ __('Reminder Title') }}
                            </label>
                            <input type="text" wire:model="reminderTitle" class="premium-input w-full" placeholder="{{ __('e.g. Work on Feature A & B') }}" required>
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                                {{ __('Description') }}
                            </label>
                            <textarea wire:model="reminderDescription" rows="3" class="premium-input w-full" placeholder="{{ __('Enter reminder details...') }}"></textarea>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                                    {{ __('Category') }}
                                </label>
                                <select wire:model="reminderCategory" class="premium-input w-full">
                                    <option value="internal">{{ __('Internal Dev') }}</option>
                                    <option value="meeting">{{ __('Meeting') }}</option>
                                    <option value="draft">{{ __('Draft / Planning') }}</option>
                                    <option value="overdue">{{ __('Overdue Task') }}</option>
                                    <option value="other">{{ __('Other') }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                                    {{ __('Color Indicator') }}
                                </label>
                                <select wire:model="reminderColor" class="premium-input w-full">
                                    <option value="indigo">{{ __('Indigo') }}</option>
                                    <option value="emerald">{{ __('Emerald') }}</option>
                                    <option value="amber">{{ __('Amber') }}</option>
                                    <option value="rose">{{ __('Rose') }}</option>
                                    <option value="slate">{{ __('Slate') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                                    {{ __('Start Date') }}
                                </label>
                                <input type="date" wire:model="selectedDate" class="premium-input w-full" required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                                    {{ __('End Date (Inclusive)') }}
                                </label>
                                <input type="date" wire:model="selectedEndDate" class="premium-input w-full">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                                    {{ __('Related Client (Optional)') }}
                                </label>
                                <select wire:model="reminderClientId" class="premium-input w-full">
                                    <option value="">{{ __('None / General') }}</option>
                                    @foreach($clients as $c)
                                        <option value="{{ $c->id }}">{{ $c->nama_client }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                                    {{ __('Assignee (Staff)') }}
                                </label>
                                <select wire:model="reminderUserId" class="premium-input w-full">
                                    <option value="">{{ __('Assign to Me') }}</option>
                                    @foreach($staffs as $s)
                                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sticky Footer Buttons -->
                    <div class="pt-4 border-t border-slate-50 flex justify-between items-center gap-4 bg-white shrink-0">
                        @if($selectedReminderId)
                            <button type="button" wire:click="deleteReminder({{ $selectedReminderId }})" class="px-4.5 py-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold text-[11px] transition-colors flex items-center gap-1.5 active:scale-95">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"></path>
                                </svg>
                                <span>
                                    {{ __('Delete') }}
                                </span>
                            </button>
                        @else
                            <div></div>
                        @endif
                        
                        <div class="flex gap-3">
                            <button type="button" wire:click="$set('showModal', false)" class="px-4.5 py-2.5 text-[11px] font-bold text-slate-500 hover:text-slate-900 transition-colors">
                                {{ __('Cancel') }}
                            </button>
                            <button type="submit" class="btn-premium py-2.5 px-5 text-[11px]">
                                {{ __('Save Reminder') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </template>

    <!-- Modal: View Invoice Details -->
    <template x-teleport="body">
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4"
             x-data="{ open: @entangle('showInvoiceModal') }"
             x-show="open"
             x-cloak
        >
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
                 x-show="open"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="open = false"
            ></div>
            
            <!-- Modal Content Container -->
            <div class="relative bg-white rounded-[32px] shadow-[0_25px_60px_-15px_rgba(0,0,0,0.2)] w-full max-w-lg overflow-hidden transform border border-slate-100 z-10"
                 x-show="open"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 scale-95"
            >
                <!-- Premium Loading Micro-Interaction -->
                <div wire:loading wire:target="viewInvoiceDetails" class="absolute inset-0 bg-white/80 backdrop-blur-md z-50 flex flex-col items-center justify-center rounded-[32px] transition-all duration-355">
                    <div class="flex flex-col items-center gap-4">
                        <div class="relative flex items-center justify-center">
                            <div class="w-12 h-12 border-4 border-indigo-500/20 rounded-full absolute"></div>
                            <div class="w-12 h-12 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
                        </div>
                        <p class="text-[10px] font-black text-indigo-650 uppercase tracking-[0.2em] animate-pulse">
                            {{ __('Loading Details...') }}
                        </p>
                    </div>
                </div>

                @if($viewedInvoice)
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-8">
                            <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-white bg-indigo-655/10 border border-indigo-200/40">
                                <svg class="w-8 h-8 text-indigo-655" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path>
                                </svg>
                            </div>
                            <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest
                                {{ $viewedInvoice->status === 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100/50' : '' }}
                                {{ $viewedInvoice->status === 'overdue' ? 'bg-rose-50 text-rose-700 border border-rose-100/50' : '' }}
                                {{ $viewedInvoice->status === 'draft' ? 'bg-amber-50 text-amber-700 border border-amber-100/50' : '' }}
                                {{ $viewedInvoice->status === 'sent' ? 'bg-blue-50 text-blue-700 border border-blue-100/50' : '' }}
                            ">
                                {{ $viewedInvoice->status }}
                            </span>
                        </div>
                        
                        <h3 class="text-2xl font-black text-slate-900 mb-2 font-jakarta">{{ $viewedInvoice->invoice_number }}</h3>
                        <p class="text-sm text-slate-500 font-medium mb-8">
                            {{ app()->getLocale() == 'en' ? 'Due on' : 'Jatuh tempo pada' }} {{ $viewedInvoice->due_date->translatedFormat('d F Y') }}
                        </p>
                        
                        <div class="grid grid-cols-2 gap-8 py-8 border-y border-slate-100">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">
                                    {{ __('Total Amount') }}
                                </p>
                                <p class="text-xl font-black text-slate-900 font-jakarta">Rp {{ number_format($viewedInvoice->total, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">
                                    {{ __('Client Entity') }}
                                </p>
                                <p class="text-sm font-bold text-slate-800">{{ $viewedInvoice->client?->nama_client }}</p>
                                <p class="text-[10px] text-slate-450 font-medium mt-0.5">{{ $viewedInvoice->client?->nama_perusahaan }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 bg-slate-50 border-t border-slate-100 flex justify-end gap-4">
                        <button type="button" wire:click="$set('showInvoiceModal', false)" class="px-6 py-3 text-sm font-bold text-slate-500 hover:text-slate-900 transition-colors">
                            {{ __('Close') }}
                        </button>
                        <a href="/invoices/{{ $viewedInvoice->id }}" class="btn-premium">
                            {{ __('View Full Invoice') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </template>

    <!-- Floating Preview Card Tooltip -->
    <div id="calendar-tooltip" class="absolute hidden z-[200] w-72 bg-white/95 backdrop-blur-md border border-slate-200/50 shadow-2xl rounded-2xl p-4 pointer-events-none transition-all duration-200">
        <div class="flex items-center justify-between mb-2">
            <span id="tooltip-badge" class="px-2.5 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-wider"></span>
            <span id="tooltip-date" class="text-[9px] font-bold text-slate-400"></span>
        </div>
        <h4 id="tooltip-title" class="text-xs font-black text-slate-900 font-jakarta leading-snug mb-1"></h4>
        <p id="tooltip-desc" class="text-[10px] text-slate-500 font-medium leading-relaxed mb-2.5"></p>
        <div class="grid grid-cols-2 gap-2 pt-2.5 border-t border-slate-100">
            <div>
                <p class="text-[8px] font-black uppercase text-slate-400 mb-0.5">Client</p>
                <p id="tooltip-client" class="text-[9px] font-bold text-slate-800 truncate"></p>
            </div>
            <div>
                <p class="text-[8px] font-black uppercase text-slate-400 mb-0.5">Assignee</p>
                <p id="tooltip-staff" class="text-[9px] font-bold text-slate-800 truncate"></p>
            </div>
        </div>
    </div>
    
    <!-- Self-Contained Assets (FullCalendar Library & Stylesheet) safe for wire:navigate SPA -->
    <div wire:ignore>
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet" />
        <style>
            .fc {
                font-family: 'Outfit', 'Inter', sans-serif;
            }
            .fc-theme-standard td, .fc-theme-standard th {
                border-color: #f1f5f9 !important;
            }
            .fc-theme-standard .fc-scrollgrid {
                border-color: #e2e8f0 !important;
                border-radius: 20px;
                overflow: hidden;
            }
            .fc .fc-col-header-cell {
                background-color: #f8fafc;
                padding: 12px 0 !important;
                font-size: 10px;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.1em;
                color: #64748b !important;
            }
            .fc .fc-daygrid-day-top {
                flex-direction: row;
                justify-content: space-between;
                padding: 8px 10px 0 10px;
            }
            .fc-daygrid-day-number {
                font-size: 12px;
                font-weight: 800;
                color: #334155 !important;
                padding: 4px 8px !important;
                border-radius: 8px;
            }
            .fc-daygrid-day:hover {
                background-color: rgba(248, 250, 252, 0.5) !important;
            }
            .fc-daygrid-day {
                position: relative;
            }
            
            /* Premium Input Field Styling with rounder shapes & focus rings */
            .premium-input {
                background-color: rgba(255, 255, 255, 0.85) !important;
                border: 1.5px solid #e2e8f0 !important;
                border-radius: 16px !important;
                padding: 11px 15px !important;
                font-size: 13px !important;
                font-weight: 700 !important;
                color: #334155 !important;
                outline: none !important;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.04) !important;
                transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
            }
            .premium-input:focus {
                background-color: #ffffff !important;
                border-color: #4f46e5 !important;
                box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.15), 0 10px 15px -3px rgba(0, 0, 0, 0.05) !important;
                transform: translateY(-1px);
            }
            
            /* Responsive Grid Heights for Desktop */
            @media (min-width: 768px) {
                .fc .fc-daygrid-day-frame {
                    min-height: 120px !important;
                }
            }
            
            /* Desktop interactive add button overlay on hover */
            .fc-daygrid-day::after {
                content: '+';
                position: absolute;
                bottom: 8px;
                right: 8px;
                width: 24px;
                height: 24px;
                line-height: 22px;
                text-align: center;
                background-color: #4f46e5;
                color: white;
                border-radius: 9999px;
                font-size: 14px;
                font-weight: 800;
                opacity: 0;
                transform: scale(0.8);
                transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
                pointer-events: none;
                box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3);
            }
            @media (min-width: 768px) {
                .fc-daygrid-day:hover::after {
                    opacity: 1;
                    transform: scale(1);
                }
            }
            
            /* Premium Popover for "+ more" events */
            .fc-popover {
                background-color: rgba(255, 255, 255, 0.95) !important;
                backdrop-filter: blur(10px);
                border: 1px solid #f1f5f9 !important;
                border-radius: 24px !important;
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
                overflow: hidden;
                z-index: 80 !important;
            }
            .fc-popover-header {
                background-color: #f8fafc !important;
                padding: 10px 14px !important;
                font-size: 10px !important;
                font-weight: 800 !important;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: #64748b !important;
                border-bottom: 1px solid #f1f5f9 !important;
            }
            .fc-popover-body {
                padding: 10px !important;
                display: flex;
                flex-direction: column;
                gap: 6px;
            }
            
            /* Premium FullCalendar List View styling for Mobile */
            .fc-list {
                border: none !important;
                background: transparent !important;
            }
            .fc-list-day {
                background-color: #f8fafc !important;
            }
            .fc-list-day-cushion {
                padding: 12px 16px !important;
                background-color: #f8fafc !important;
                font-size: 10px !important;
                font-weight: 800 !important;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: #64748b !important;
            }
            .fc-list-event {
                background-color: white !important;
                transition: all 0.2s ease;
                border-bottom: 1px solid #f1f5f9 !important;
            }
            .fc-list-event:hover {
                background-color: #faf5ff !important;
                transform: translateX(4px);
            }
            .fc-list-event td {
                padding: 12px 16px !important;
                border: none !important;
            }
            .fc-list-event-dot {
                border-width: 4px !important;
                width: 8px !important;
                height: 8px !important;
                border-radius: 9999px !important;
            }
            .fc-list-event-title a {
                font-size: 12px !important;
                font-weight: 850 !important;
                color: #1e293b !important;
                text-decoration: none !important;
            }
            .fc-list-empty {
                background-color: white !important;
                padding: 40px 20px !important;
                text-align: center;
                font-size: 12px;
                font-weight: 700;
                color: #94a3b8;
                border-radius: 20px;
            }
            
            .fc-day-today {
                background-color: rgba(79, 70, 229, 0.03) !important;
                border: 2px solid rgba(79, 70, 229, 0.4) !important;
                box-shadow: inset 0 0 12px rgba(79, 70, 229, 0.03), 4px 4px 20px rgba(79, 70, 229, 0.06) !important;
                position: relative;
            }
            .fc-day-today .fc-daygrid-day-number {
                color: #4f46e5 !important;
                background-color: #f5f3ff;
                border: 1px solid #ddd6fe;
            }
            .fc-event {
                background: transparent !important;
                border: none !important;
                padding: 0 !important;
                box-shadow: none !important;
            }
            #calendar-tooltip {
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
                border: 1px solid #f1f5f9;
                transition: opacity 0.15s ease-out, transform 0.15s ease-out;
            }
            .fc-header-toolbar {
                display: none !important;
            }
            .fc-daygrid-event-harness {
                margin: 2px 4px !important;
            }
        </style>
        
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js" data-navigate-once></script>
        <script>
            function chronosCalendar() {
                return {
                    calendar: null,
                    loading: false,
                    init() {
                        // Safe delayed initialization to guarantee target is ready in DOM
                        setTimeout(() => {
                            this.initCalendar();
                        }, 50);
                        
                        // Screen resize trigger to switch between DayGrid (Desktop) and List (Mobile) view dynamically
                        window.addEventListener('resize', () => {
                            if (!this.calendar) return;
                            const isMobile = window.innerWidth < 768;
                            const targetView = isMobile ? 'listMonth' : 'dayGridMonth';
                            if (this.calendar.view.type !== targetView) {
                                this.calendar.changeView(targetView);
                            }
                        });
                        
                        this.$watch(() => this.$wire.clientId, () => this.refetch());
                        this.$watch(() => this.$wire.status, () => this.refetch());
                        this.$watch(() => this.$wire.staffId, () => this.refetch());
                    },
                    initCalendar() {
                        const calendarEl = document.getElementById('fullcalendar-target');
                        if (!calendarEl) return;
                        
                        // Fallback resilience mechanism in case network load for FullCalendar CDN takes longer
                        if (typeof FullCalendar === 'undefined') {
                            setTimeout(() => this.initCalendar(), 100);
                            return;
                        }
                        
                        const isMobile = window.innerWidth < 768;
                        const initialView = isMobile ? 'listMonth' : 'dayGridMonth';
                        
                        this.calendar = new FullCalendar.Calendar(calendarEl, {
                            initialView: initialView,
                            locale: '{{ app()->getLocale() }}',
                            firstDay: 1,
                            editable: true,
                            droppable: true,
                            selectable: false,
                            dayMaxEvents: 3,
                            events: (info, successCallback, failureCallback) => {
                                this.loading = true;
                                let url = '{{ route("chronos.events") }}';
                                let params = new URLSearchParams({
                                    start: info.startStr,
                                    end: info.endStr,
                                    client_id: this.$wire.clientId || '',
                                    status: this.$wire.status || '',
                                    staff_id: this.$wire.staffId || ''
                                });
                                
                                axios.get(url + '?' + params.toString())
                                    .then(response => successCallback(response.data))
                                    .catch(error => {
                                        console.error(error);
                                        if (typeof window.showToast === 'function') {
                                            window.showToast('Failed to fetch events', 'danger');
                                        }
                                        failureCallback(error);
                                    })
                                    .finally(() => {
                                        this.loading = false;
                                    });
                            },
                            datesSet: (info) => {
                                const titleEl = document.getElementById('calendar-title');
                                if (titleEl) titleEl.innerText = info.view.title;
                            },
                            dateClick: (info) => {
                                this.$wire.openAddModal(info.dateStr);
                            },
                            eventClick: (info) => {
                                let type = info.event.extendedProps.type;
                                let dbId = info.event.extendedProps.dbId;
                                if (type === 'invoice') {
                                    this.$wire.viewInvoiceDetails(dbId);
                                } else if (type === 'reminder') {
                                    this.$wire.openEditModal(dbId);
                                }
                            },
                            eventDrop: (info) => {
                                this.updateEventDate(info.event, info.oldEvent, info.revert);
                            },
                            eventResize: (info) => {
                                this.updateEventDate(info.event, info.oldEvent, info.revert);
                            },
                            eventMouseEnter: (info) => {
                                this.showTooltip(info);
                            },
                            eventMouseLeave: (info) => {
                                this.hideTooltip();
                            },
                            eventContent: (arg) => {
                                let type = arg.event.extendedProps.type;
                                let status = arg.event.extendedProps.status || arg.event.extendedProps.status_type;
                                let title = arg.event.title;
                                
                                let colorClass = 'bg-slate-50 text-slate-700 border-slate-200/80';
                                let bulletColor = 'bg-slate-400';
                                
                                if (type === 'invoice') {
                                    if (status === 'paid') {
                                        colorClass = 'bg-emerald-50 text-emerald-700 border-emerald-100/80';
                                        bulletColor = 'bg-emerald-500';
                                    } else if (status === 'overdue') {
                                        colorClass = 'bg-rose-50 text-rose-700 border-rose-100/80';
                                        bulletColor = 'bg-rose-500';
                                    } else if (status === 'draft') {
                                        colorClass = 'bg-amber-50 text-amber-700 border-amber-100/80';
                                        bulletColor = 'bg-amber-500';
                                    } else if (status === 'sent') {
                                        colorClass = 'bg-blue-50 text-blue-700 border-blue-100/80';
                                        bulletColor = 'bg-blue-500';
                                    }
                                } else {
                                    if (status === 'internal') {
                                        colorClass = 'bg-indigo-50 text-indigo-700 border-indigo-100/80';
                                        bulletColor = 'bg-indigo-500';
                                    } else if (status === 'meeting') {
                                        colorClass = 'bg-emerald-50 text-emerald-700 border-emerald-100/80';
                                        bulletColor = 'bg-emerald-500';
                                    } else if (status === 'draft') {
                                        colorClass = 'bg-amber-50 text-amber-700 border-amber-100/80';
                                        bulletColor = 'bg-amber-500';
                                    } else if (status === 'overdue') {
                                        colorClass = 'bg-rose-50 text-rose-700 border-rose-100/80';
                                        bulletColor = 'bg-rose-500';
                                    }
                                }

                                return {
                                    html: `
                                        <div class="w-full text-left truncate text-[10px] font-extrabold px-2 py-1 rounded-lg border flex items-center gap-1.5 transition-all hover:scale-[1.02] active:scale-98 ${colorClass}">
                                            <span class="w-1.5 h-1.5 rounded-full shrink-0 ${bulletColor}"></span>
                                            <span class="truncate flex-1 tracking-tight">${title}</span>
                                        </div>
                                    `
                                };
                            }
                        });
                        this.calendar.render();
                    },
                    refetch() {
                        if (this.calendar) this.calendar.refetchEvents();
                    },
                    goToPrevMonth() {
                        if (this.calendar) this.calendar.prev();
                    },
                    goToNextMonth() {
                        if (this.calendar) this.calendar.next();
                    },
                    goToToday() {
                        if (this.calendar) this.calendar.today();
                    },
                    openCreateModalForToday() {
                        let todayStr = new Date().toISOString().split('T')[0];
                        this.$wire.openAddModal(todayStr);
                    },
                    updateEventDate(event, oldEvent, revertFunc) {
                        let id = event.id;
                        let start = event.startStr;
                        let end = event.endStr;
                        
                        axios.post('{{ route("chronos.update-event") }}', {
                            id: id,
                            start: start,
                            end: end
                        })
                        .then(response => {
                            if (response.data.success) {
                                if (typeof window.showToast === 'function') {
                                    window.showToast(response.data.message || 'Date updated successfully!', 'success');
                                }
                            } else {
                                if (typeof window.showToast === 'function') {
                                    window.showToast(response.data.error || 'Failed to update date.', 'danger');
                                }
                                revertFunc();
                            }
                        })
                        .catch(error => {
                            console.error(error);
                            let errorMsg = 'Failed to update date.';
                            if (error.response && error.response.data && error.response.data.error) {
                                errorMsg = error.response.data.error;
                            }
                            if (typeof window.showToast === 'function') {
                                window.showToast(errorMsg, 'danger');
                            }
                            revertFunc();
                        });
                    },
                    showTooltip(info) {
                        const tooltip = document.getElementById('calendar-tooltip');
                        if (!tooltip) return;
                        const props = info.event.extendedProps;
                        const type = props.type;
                        
                        const titleEl = document.getElementById('tooltip-title');
                        const badgeEl = document.getElementById('tooltip-badge');
                        const descEl = document.getElementById('tooltip-desc');
                        const clientEl = document.getElementById('tooltip-client');
                        const staffEl = document.getElementById('tooltip-staff');
                        const dateEl = document.getElementById('tooltip-date');
                        
                        if (titleEl) titleEl.innerText = info.event.title;
                        
                        if (type === 'invoice') {
                            if (badgeEl) {
                                badgeEl.innerText = 'Invoice: ' + props.status;
                                badgeEl.className = `px-2.5 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-wider ` + 
                                    (props.status === 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100/30' : 
                                     (props.status === 'overdue' ? 'bg-rose-50 text-rose-700 border border-rose-100/30' : 'bg-amber-50 text-amber-700 border border-amber-100/30'));
                            }
                            if (descEl) descEl.innerText = 'Invoice: ' + props.invoice_number + ' | Amount: ' + props.total;
                        } else {
                            if (badgeEl) {
                                badgeEl.innerText = 'Reminder: ' + props.status_type;
                                badgeEl.className = `px-2.5 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-100/30`;
                            }
                            if (descEl) descEl.innerText = props.description || 'No description provided';
                        }
                        
                        if (clientEl) clientEl.innerText = props.client || 'N/A';
                        if (staffEl) staffEl.innerText = props.responsible_staff || 'N/A';
                        
                        let dateStr = info.event.start.toLocaleDateString('{{ app()->getLocale() }}', { day: 'numeric', month: 'short' });
                        if (info.event.end) {
                            let dispEnd = new Date(info.event.end);
                            dispEnd.setDate(dispEnd.getDate() - 1);
                            if (dispEnd > info.event.start) {
                                dateStr += ' - ' + dispEnd.toLocaleDateString('{{ app()->getLocale() }}', { day: 'numeric', month: 'short' });
                            }
                        }
                        if (dateEl) dateEl.innerText = dateStr;
                        
                        const rect = info.el.getBoundingClientRect();
                        const tooltipWidth = tooltip.offsetWidth || 288;
                        const tooltipHeight = tooltip.offsetHeight || 150;
                        
                        let top = window.scrollY + rect.top - tooltipHeight - 10;
                        let left = window.scrollX + rect.left + (rect.width / 2) - (tooltipWidth / 2);
                        
                        if (top < window.scrollY) {
                            top = window.scrollY + rect.bottom + 10;
                        }
                        if (left < 10) {
                            left = 10;
                        } else if (left + tooltipWidth > window.innerWidth - 10) {
                            left = window.innerWidth - tooltipWidth - 10;
                        }
                        
                        tooltip.style.top = top + 'px';
                        tooltip.style.left = left + 'px';
                        tooltip.classList.remove('hidden');
                    },
                    hideTooltip() {
                        const tooltip = document.getElementById('calendar-tooltip');
                        if (tooltip) tooltip.classList.add('hidden');
                    }
                };
            }
        </script>
    </div>
</div>
