<x-app-layout>
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">
                <a href="{{ route('clients.index') }}" class="hover:text-indigo-600 transition-colors">Client Management</a>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-slate-900">{{ $client->kode_client }}</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 font-outfit">{{ $client->nama_client }}</h1>
            <p class="text-sm text-slate-500">{{ $client->nama_perusahaan }} — {{ $client->kota }}, {{ $client->provinsi }}</p>
        </div>
        <div class="flex items-center gap-3">
            <x-badge :status="$client->status" />
            <a href="{{ route('clients.edit', $client) }}" class="btn-secondary">Edit Account</a>
            <a href="{{ route('invoices.create', ['client_id' => $client->id]) }}" class="btn-premium">Create Invoice</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Sidebar Info -->
        <div class="space-y-8">
            <div class="glass-card p-8">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest mb-6">Contact Information</h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <i data-lucide="mail" class="w-4 h-4 text-slate-400"></i>
                        <span class="text-sm text-slate-600">{{ $client->email ?? 'No email set' }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i data-lucide="phone" class="w-4 h-4 text-slate-400"></i>
                        <span class="text-sm text-slate-600">{{ $client->no_hp ?? 'No phone set' }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i data-lucide="map-pin" class="w-4 h-4 text-slate-400"></i>
                        <span class="text-sm text-slate-600 leading-relaxed">{{ $client->alamat }}</span>
                    </div>
                </div>
            </div>

            <div class="glass-card p-8 bg-[#0f172a] text-white">
                <h3 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-4">Account Stats</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xl font-black font-outfit">Rp {{ number_format($client->invoices()->sum('total'), 0, ',', '.') }}</p>
                        <p class="text-[9px] font-bold text-slate-500 uppercase">Total Billing</p>
                    </div>
                    <div>
                        <p class="text-xl font-black font-outfit">{{ $client->invoices()->count() }}</p>
                        <p class="text-[9px] font-bold text-slate-500 uppercase">Total Invoices</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main History -->
        <div class="lg:col-span-2 space-y-8">
            <div class="glass-card overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/30">
                    <h3 class="font-bold text-slate-900 font-outfit">Invoice History</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[10px] font-bold uppercase tracking-widest text-slate-400 bg-slate-50/50">
                                <th class="px-8 py-4 border-b border-slate-100">Reference</th>
                                <th class="px-8 py-4 border-b border-slate-100">Date</th>
                                <th class="px-8 py-4 border-b border-slate-100">Total</th>
                                <th class="px-8 py-4 border-b border-slate-100">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($client->invoices()->latest()->get() as $invoice)
                            <tr class="table-row-premium cursor-pointer" onclick="window.location='{{ route('invoices.show', $invoice) }}'">
                                <td class="px-8 py-4"><span class="text-[13px] font-bold text-slate-900">{{ $invoice->invoice_number }}</span></td>
                                <td class="px-8 py-4"><span class="text-[12px] text-slate-500">{{ $invoice->tanggal_invoice->format('M d, Y') }}</span></td>
                                <td class="px-8 py-4"><span class="text-[13px] font-black text-slate-900">Rp {{ number_format($invoice->total, 0, ',', '.') }}</span></td>
                                <td class="px-8 py-4"><x-badge :status="$invoice->status" /></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="glass-card overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/30">
                    <h3 class="font-bold text-slate-900 font-outfit">Quotation History</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[10px] font-bold uppercase tracking-widest text-slate-400 bg-slate-50/50">
                                <th class="px-8 py-4 border-b border-slate-100">Reference</th>
                                <th class="px-8 py-4 border-b border-slate-100">Date</th>
                                <th class="px-8 py-4 border-b border-slate-100">Total</th>
                                <th class="px-8 py-4 border-b border-slate-100">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($client->quotations()->latest()->get() as $quotation)
                            <tr class="table-row-premium cursor-pointer" onclick="window.location='{{ route('quotations.show', $quotation) }}'">
                                <td class="px-8 py-4"><span class="text-[13px] font-bold text-slate-900">{{ $quotation->quotation_number }}</span></td>
                                <td class="px-8 py-4"><span class="text-[12px] text-slate-500">{{ $quotation->tanggal_quotation->format('M d, Y') }}</span></td>
                                <td class="px-8 py-4"><span class="text-[13px] font-black text-slate-900">Rp {{ number_format($quotation->total, 0, ',', '.') }}</span></td>
                                <td class="px-8 py-4"><x-badge :status="$quotation->status" /></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
