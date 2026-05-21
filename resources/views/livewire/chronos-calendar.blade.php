<div class="space-y-6 w-full min-w-0" x-data="{ 
    init() {
        window.addEventListener('reminderSaved', e => {
            if (typeof showToast === 'function') {
                showToast(e.detail.message, 'success');
            }
        });
    }
}">
    <!-- Top Filter Bar -->
    <div class="flex flex-wrap items-center gap-6 p-6 glass-card border-slate-100 shadow-xl shadow-indigo-500/5 bg-white/70 backdrop-blur-md rounded-3xl">
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
                <option value="sent">Sent (Blue)</option>
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
            <button wire:click="$set('clientId', ''); $set('status', ''); $set('staffId', '')" class="p-3 text-slate-400 hover:text-indigo-650 transition-colors" title="Reset Filters">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Main Calendar Card -->
    <div class="glass-card p-6 md:p-8 shadow-2xl shadow-indigo-500/10 border-slate-100 bg-white/80 backdrop-blur-md rounded-3xl flex flex-col w-full min-w-0">
        <!-- Calendar Header -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <button wire:click="prevMonth" class="p-2.5 bg-slate-50 hover:bg-slate-100 text-slate-600 rounded-xl transition-all border border-slate-200/60 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"></path>
                    </svg>
                </button>
                <h2 class="text-md font-black text-slate-850 font-jakarta uppercase tracking-wider min-w-[140px] text-center select-none">
                    {{ Carbon\Carbon::create($year, $month, 1)->translatedFormat('F Y') }}
                </h2>
                <button wire:click="nextMonth" class="p-2.5 bg-slate-50 hover:bg-slate-100 text-slate-600 rounded-xl transition-all border border-slate-200/60 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"></path>
                    </svg>
                </button>
            </div>
            
            <button wire:click="openAddModal('{{ Carbon\Carbon::create($year, $month, 1)->toDateString() }}')" class="btn-premium flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                </svg>
                <span>{{ app()->getLocale() == 'en' ? 'Add Reminder' : 'Tambah Pengingat' }}</span>
            </button>
        </div>

        <!-- Days of Week Header -->
        <div class="grid grid-cols-7 gap-3 text-center text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 border-b border-slate-100 pb-3">
            <div>Mon</div>
            <div>Tue</div>
            <div>Wed</div>
            <div>Thu</div>
            <div>Fri</div>
            <div class="text-rose-450">Sat</div>
            <div class="text-rose-450">Sun</div>
        </div>

        <!-- Calendar Days Grid -->
        <div class="grid grid-cols-7 gap-3 auto-rows-[100px] md:auto-rows-[120px] lg:auto-rows-[140px] xl:auto-rows-[150px]">
            @foreach($days as $day)
                @if($day['date'] === null)
                    <!-- Empty Padding Day -->
                    <div class="bg-slate-50/20 border border-dashed border-slate-200/50 rounded-2xl"></div>
                @else
                    <!-- Active Calendar Day -->
                    <div wire:click="openAddModal('{{ $day['date'] }}')"
                        class="relative group border border-slate-100 bg-white hover:bg-slate-50/50 rounded-2xl p-3 transition-all flex flex-col justify-between cursor-pointer min-w-0 select-none
                        {{ $day['is_today'] ? 'border-2 border-indigo-500/80 bg-indigo-50/10 shadow-sm shadow-indigo-500/5' : '' }}"
                    >
                        <!-- Date Number & Add Event Trigger -->
                        <div class="flex items-center justify-between w-full">
                            <span class="text-xs font-extrabold 
                                {{ $day['is_today'] ? 'text-indigo-650 bg-indigo-50 px-2 py-0.5 rounded-lg border border-indigo-100/50' : 'text-slate-700' }}"
                            >
                                {{ $day['day'] }}
                            </span>
                            
                            <button wire:click.stop="openAddModal('{{ $day['date'] }}')" 
                                class="opacity-0 group-hover:opacity-100 p-1 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-all active:scale-90"
                                title="Add reminder for this day"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Events/Reminders List inside Day -->
                        <div class="flex-1 overflow-y-auto space-y-1.5 mt-2.5 chat-scroll">
                            @foreach($day['events'] as $event)
                                @if($event['type'] === 'invoice')
                                    <button wire:click.stop="viewInvoiceDetails({{ $event['id'] }})"
                                        class="w-full text-left truncate text-[10px] font-bold px-2 py-1.5 rounded-xl border flex items-center gap-1.5 transition-all hover:scale-[1.02] active:scale-98
                                        {{ $event['color'] === 'emerald' ? 'bg-emerald-50 text-emerald-700 border-emerald-100/80' : '' }}
                                        {{ $event['color'] === 'rose' ? 'bg-rose-50 text-rose-700 border-rose-100/80' : '' }}
                                        {{ $event['color'] === 'amber' ? 'bg-amber-50 text-amber-700 border-amber-100/80' : '' }}
                                        {{ $event['color'] === 'slate' ? 'bg-slate-50 text-slate-700 border-slate-200/80' : '' }}
                                        "
                                        title="{{ $event['title'] }} - {{ $event['client_name'] }}"
                                    >
                                        <span class="w-1.5 h-1.5 rounded-full shrink-0
                                            {{ $event['color'] === 'emerald' ? 'bg-emerald-500' : '' }}
                                            {{ $event['color'] === 'rose' ? 'bg-rose-500' : '' }}
                                            {{ $event['color'] === 'amber' ? 'bg-amber-500' : '' }}
                                            {{ $event['color'] === 'slate' ? 'bg-slate-400' : '' }}
                                        "></span>
                                        <span class="truncate flex-1 tracking-tight">{{ $event['title'] }}</span>
                                    </button>
                                @else
                                    <button wire:click.stop="openEditModal({{ $event['id'] }})"
                                        class="w-full text-left truncate text-[10px] font-bold px-2 py-1.5 rounded-xl border flex items-center gap-1.5 transition-all hover:scale-[1.02] active:scale-98
                                        {{ $event['color'] === 'indigo' ? 'bg-indigo-50 text-indigo-700 border-indigo-100/80' : '' }}
                                        {{ $event['color'] === 'emerald' ? 'bg-emerald-50 text-emerald-700 border-emerald-100/80' : '' }}
                                        {{ $event['color'] === 'amber' ? 'bg-amber-50 text-amber-700 border-amber-100/80' : '' }}
                                        {{ $event['color'] === 'rose' ? 'bg-rose-50 text-rose-700 border-rose-100/80' : '' }}
                                        {{ $event['color'] === 'slate' ? 'bg-slate-50 text-slate-700 border-slate-200/80' : '' }}
                                        "
                                        title="{{ $event['title'] }}"
                                    >
                                        <span class="w-1.5 h-1.5 rounded-full shrink-0
                                            {{ $event['color'] === 'indigo' ? 'bg-indigo-500' : '' }}
                                            {{ $event['color'] === 'emerald' ? 'bg-emerald-500' : '' }}
                                            {{ $event['color'] === 'amber' ? 'bg-amber-500' : '' }}
                                            {{ $event['color'] === 'rose' ? 'bg-rose-500' : '' }}
                                            {{ $event['color'] === 'slate' ? 'bg-slate-400' : '' }}
                                        "></span>
                                        <span class="truncate flex-1 tracking-tight">{{ $event['title'] }}</span>
                                    </button>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    <!-- Modal: Add/Edit Reminder -->
    @if($showModal)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" wire:click="$set('showModal', false)"></div>
        
        <!-- Modal Content Container -->
        <div class="relative bg-white rounded-[32px] shadow-2xl w-full max-w-lg overflow-hidden transform transition-all page-fade-in p-8 border border-slate-100">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-50">
                <h3 class="text-base font-black text-slate-900 font-jakarta uppercase tracking-wider">
                    {{ $selectedReminderId ? (app()->getLocale() == 'en' ? 'Edit Reminder' : 'Ubah Pengingat') : (app()->getLocale() == 'en' ? 'Add Reminder' : 'Tambah Pengingat') }}
                </h3>
                <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-650 transition-colors p-1.5 hover:bg-slate-50 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form wire:submit.prevent="saveReminder" class="space-y-5">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Reminder Title</label>
                    <input type="text" wire:model="reminderTitle" class="premium-input w-full" placeholder="{{ app()->getLocale() == 'en' ? 'e.g. Work on Feature A & B' : 'misal: Pengerjaan Fitur A & B' }}" required>
                </div>
                
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Description</label>
                    <textarea wire:model="reminderDescription" rows="3" class="premium-input w-full" placeholder="{{ app()->getLocale() == 'en' ? 'Enter reminder details...' : 'Masukkan detail pengingat...' }}"></textarea>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Category</label>
                        <select wire:model="reminderCategory" class="premium-input w-full">
                            <option value="internal">Internal Dev</option>
                            <option value="meeting">Meeting</option>
                            <option value="ai_update">AI Update</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Color Indicator</label>
                        <select wire:model="reminderColor" class="premium-input w-full">
                            <option value="indigo">Indigo</option>
                            <option value="emerald">Emerald</option>
                            <option value="amber">Amber</option>
                            <option value="rose">Rose</option>
                            <option value="slate">Slate</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Related Client (Optional)</label>
                        <select wire:model="reminderClientId" class="premium-input w-full">
                            <option value="">None / General</option>
                            @foreach($clients as $c)
                                <option value="{{ $c->id }}">{{ $c->nama_client }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Reminder Date</label>
                        <input type="date" wire:model="selectedDate" class="premium-input w-full" required>
                    </div>
                </div>
                
                <div class="pt-6 border-t border-slate-50 flex justify-between items-center gap-4">
                    @if($selectedReminderId)
                        <button type="button" wire:click="deleteReminder({{ $selectedReminderId }})" class="px-4.5 py-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold text-[11px] transition-colors flex items-center gap-1.5 active:scale-95">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"></path>
                            </svg>
                            <span>{{ app()->getLocale() == 'en' ? 'Delete' : 'Hapus' }}</span>
                        </button>
                    @else
                        <div></div>
                    @endif
                    
                    <div class="flex gap-3">
                        <button type="button" wire:click="$set('showModal', false)" class="px-4.5 py-2.5 text-[11px] font-bold text-slate-500 hover:text-slate-900 transition-colors">{{ app()->getLocale() == 'en' ? 'Cancel' : 'Batal' }}</button>
                        <button type="submit" class="btn-premium py-2.5 px-5 text-[11px]">{{ app()->getLocale() == 'en' ? 'Save Reminder' : 'Simpan Pengingat' }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Modal: View Invoice Details -->
    @if($showInvoiceModal && $viewedInvoice)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" wire:click="$set('showInvoiceModal', false)"></div>
        
        <!-- Modal Content Container -->
        <div class="relative bg-white rounded-[32px] shadow-2xl w-full max-w-lg overflow-hidden transform transition-all page-fade-in border border-slate-100">
            <div class="p-8">
                <div class="flex items-center justify-between mb-8">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-white bg-indigo-600/10 border border-indigo-200/40">
                        <svg class="w-8 h-8 text-indigo-650" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">{{ app()->getLocale() == 'en' ? 'Total Amount' : 'Jumlah Total' }}</p>
                        <p class="text-xl font-black text-slate-900 font-jakarta">Rp {{ number_format($viewedInvoice->total, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">{{ app()->getLocale() == 'en' ? 'Client Entity' : 'Entitas Klien' }}</p>
                        <p class="text-sm font-bold text-slate-800">{{ $viewedInvoice->client?->nama_client }}</p>
                        <p class="text-[10px] text-slate-450 font-medium mt-0.5">{{ $viewedInvoice->client?->nama_perusahaan }}</p>
                    </div>
                </div>
            </div>
            <div class="p-6 bg-slate-50 border-t border-slate-100 flex justify-end gap-4">
                <button wire:click="$set('showInvoiceModal', false)" class="px-6 py-3 text-sm font-bold text-slate-500 hover:text-slate-900 transition-colors">{{ app()->getLocale() == 'en' ? 'Close' : 'Tutup' }}</button>
                <a href="/invoices/{{ $viewedInvoice->id }}" class="btn-premium">{{ app()->getLocale() == 'en' ? 'View Full Invoice' : 'Lihat Faktur Lengkap' }}</a>
            </div>
        </div>
    </div>
    @endif
</div>
