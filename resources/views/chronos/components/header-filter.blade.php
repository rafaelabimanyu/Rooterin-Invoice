<!-- Header & Color Indicators Legend -->
<div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-8 page-fade-in">
    <div>
        <h1 class="text-3xl font-black text-slate-900 font-jakarta tracking-tight mb-2 uppercase">
            J&J GROUP Chronos
        </h1>
        <p class="text-sm text-slate-500 font-medium tracking-tight">
            {{ __('Billing Calendar & Operational Workflows') }}
        </p>
    </div>
    <div class="flex items-center gap-4">
        <div class="flex flex-wrap items-center gap-4 px-5 py-2.5 bg-white/40 backdrop-blur-lg rounded-2xl border border-white/20 shadow-lg shadow-indigo-500/5 select-none">
            <div class="flex items-center gap-2 px-2.5 py-1.5 rounded-xl bg-indigo-50 border border-indigo-100/60">
                <span class="w-2 h-2 rounded-full bg-indigo-500 shadow-[0_0_8px_rgba(79,70,229,0.6)]"></span>
                <span class="text-[9px] font-black uppercase text-indigo-650 tracking-wider">
                    {{ __('Internal') }}
                </span>
            </div>
            <div class="flex items-center gap-2 px-2.5 py-1.5 rounded-xl bg-emerald-50 border border-emerald-100/60">
                <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.6)]"></span>
                <span class="text-[9px] font-black uppercase text-emerald-650 tracking-wider">
                    {{ __('Paid / Meeting') }}
                </span>
            </div>
            <div class="flex items-center gap-2 px-2.5 py-1.5 rounded-xl bg-amber-50 border border-amber-100/60">
                <span class="w-2 h-2 rounded-full bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.6)]"></span>
                <span class="text-[9px] font-black uppercase text-amber-650 tracking-wider">
                    {{ __('Draft / Planning') }}
                </span>
            </div>
            <div class="flex items-center gap-2 px-2.5 py-1.5 rounded-xl bg-rose-50 border border-rose-100/60">
                <span class="w-2 h-2 rounded-full bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.6)]"></span>
                <span class="text-[9px] font-black uppercase text-rose-650 tracking-wider">
                    {{ __('Overdue') }}
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Top Filter Bar -->
<div class="grid grid-cols-1 md:flex md:flex-wrap md:items-center gap-6 p-6 glass-card border-slate-100 shadow-xl shadow-indigo-500/5 bg-white/70 backdrop-blur-md rounded-3xl select-none relative z-30">
    
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
             class="absolute left-0 z-50 mt-2 w-full bg-white/95 backdrop-blur-md border border-slate-100 rounded-2xl shadow-xl max-h-60 overflow-y-auto chat-scroll p-1.5"
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
             class="absolute left-0 z-50 mt-2 w-full bg-white/95 backdrop-blur-md border border-slate-100 rounded-2xl shadow-xl max-h-60 overflow-y-auto chat-scroll p-1.5"
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
