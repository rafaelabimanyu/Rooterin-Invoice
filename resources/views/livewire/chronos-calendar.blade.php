<div class="mb-8 flex flex-wrap items-center gap-6 p-6 glass-card border-slate-100 shadow-xl shadow-indigo-500/5">
    <div class="flex-1 min-w-[200px]">
        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Filter Client</label>
        <select wire:model.live="clientId" class="premium-input w-full">
            <option value="">All Clients</option>
            @foreach($clients as $client)
                <option value="{{ $client->id }}">{{ $client->nama_client }}</option>
            @endforeach
        </select>
    </div>

    <div class="flex-1 min-w-[200px]">
        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Invoice Status</label>
        <select wire:model.live="status" class="premium-input w-full">
            <option value="">All Status</option>
            <option value="draft">Draft (Amber)</option>
            <option value="paid">Paid (Emerald)</option>
            <option value="overdue">Overdue (Rose)</option>
            <option value="sent">Sent</option>
        </select>
    </div>

    @if(!auth()->user()->hasRole('staff'))
    <div class="flex-1 min-w-[200px]">
        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Responsible Staff</label>
        <select wire:model.live="staffId" class="premium-input w-full">
            <option value="">All Staff</option>
            @foreach($staffs as $staff)
                <option value="{{ $staff->id }}">{{ $staff->name }}</option>
            @endforeach
        </select>
    </div>
    @endif

    <div class="flex items-end h-full pt-6">
        <button wire:click="$set('clientId', ''); $set('status', ''); $set('staffId', '')" class="p-3 text-slate-400 hover:text-indigo-600 transition-colors" title="Reset Filters">
            <i data-lucide="refresh-ccw" class="w-5 h-5"></i>
        </button>
    </div>
</div>
