<x-app-layout :title="app()->getLocale() == 'en' ? 'Transactions List' : 'Daftar Transaksi'">
    <div class="animate-fade-in-up">
        <!-- Header Section -->
        <div class="mb-12 flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div>
                <div class="flex items-center gap-2 text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-3">
                    <span>Enterprise</span>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                    <span class="text-gold-600 truncate">{{ app()->getLocale() == 'en' ? 'Transaction Ledger' : 'Buku Besar Transaksi' }}</span>
                </div>
                <h1 class="text-5xl font-extrabold text-slate-900 tracking-tight mb-2 font-outfit">Transactions</h1>
                <p class="text-[15px] text-slate-400 font-medium">{{ app()->getLocale() == 'en' ? 'Manage unified invoices and receipts within a single ledger.' : 'Kelola invoice dan kwitansi terpadu dalam satu buku besar.' }}</p>
            </div>
            <div class="flex items-center">
                <a href="{{ route('transactions.create') }}" class="btn-premium-glass group transition-all duration-300">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                    <span>Create Transaction</span>
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="glass-card p-6 mb-10">
            <form action="{{ route('transactions.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-4 lg:gap-6 items-end">
                <!-- Search Text -->
                <div class="space-y-2 md:col-span-2 lg:col-span-4">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Search Transaction' : 'Cari Transaksi' }}</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ app()->getLocale() == 'en' ? 'Transaction number, client name...' : 'Nomor transaksi, nama klien...' }}" class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:border-gold-500 focus:bg-white transition-colors font-medium">
                        <div class="absolute left-3.5 top-2.5 text-slate-400">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Mode Filter -->
                <div class="space-y-2 md:col-span-1 lg:col-span-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Type / Mode</label>
                    <select name="mode" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:border-gold-500 focus:bg-white transition-colors font-medium">
                        <option value="">All Types</option>
                        <option value="invoice" {{ request('mode') == 'invoice' ? 'selected' : '' }}>Invoice</option>
                        <option value="receipt" {{ request('mode') == 'receipt' ? 'selected' : '' }}>Receipt</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="space-y-2 md:col-span-1 lg:col-span-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status</label>
                    <select name="status" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:border-gold-500 focus:bg-white transition-colors font-medium">
                        <option value="">All Status</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>DRAFT</option>
                        <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>UNPAID</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>PAID</option>
                    </select>
                </div>
                
                <!-- Buttons -->
                <div class="flex gap-2 md:col-span-2 lg:col-span-4 w-full">
                    <button type="submit" class="flex-1 btn-premium py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider text-center transition-all duration-300">
                        Filter
                    </button>
                    <a href="{{ route('transactions.index') }}" class="btn-secondary py-2.5 px-4 rounded-xl text-xs uppercase tracking-wider flex items-center justify-center transition-all duration-300 shrink-0">Reset</a>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-12">
            <div class="card-premium group w-full transition-all duration-300">
                <div class="absolute top-0 left-0 w-1.5 h-full bg-gold-500/80"></div>
                <div class="flex items-center justify-between mb-4">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Total Transactions</p>
                    <i data-lucide="file-text" class="w-4 h-4 text-slate-300 group-hover:text-gold-500 transition-colors duration-300"></i>
                </div>
                <h3 class="text-3xl font-bold text-slate-900 font-outfit">{{ $transactions->total() }}</h3>
                <p class="text-[10px] text-slate-400 font-bold mt-2 uppercase tracking-tighter">Invoices and Receipts registered</p>
            </div>
            
            <div class="card-premium group w-full transition-all duration-300">
                <div class="absolute top-0 left-0 w-1.5 h-full bg-emerald-500/80"></div>
                <div class="flex items-center justify-between mb-4">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Total Collected</p>
                    <i data-lucide="check-circle" class="w-4 h-4 text-slate-300 group-hover:text-emerald-500 transition-colors duration-300"></i>
                </div>
                <h3 class="text-3xl font-bold text-emerald-600 font-outfit">Rp {{ number_format(\App\Models\Transaction::where('status', 'paid')->sum('total'), 0, ',', '.') }}</h3>
                <p class="text-[10px] text-slate-400 font-bold mt-2 uppercase tracking-tighter">Fully paid transactions</p>
            </div>

            <div class="card-premium group w-full transition-all duration-300">
                <div class="absolute top-0 left-0 w-1.5 h-full bg-amber-500/80"></div>
                <div class="flex items-center justify-between mb-4">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Receivables</p>
                    <i data-lucide="clock" class="w-4 h-4 text-slate-300 group-hover:text-amber-500 transition-colors duration-300"></i>
                </div>
                <h3 class="text-3xl font-bold text-slate-900 font-outfit">Rp {{ number_format(\App\Models\Transaction::where('status', 'unpaid')->sum('total'), 0, ',', '.') }}</h3>
                <p class="text-[10px] text-slate-400 font-bold mt-2 uppercase tracking-tighter">Unpaid invoice total</p>
            </div>
        </div>

        <!-- List View -->
        <div class="hidden md:block overflow-x-auto pb-4">
            <div class="min-w-[1000px] space-y-4 pr-4">
                <!-- List Header -->
                <div class="grid grid-cols-12 gap-8 px-10 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 bg-slate-50/50 rounded-2xl mb-2">
                    <div class="col-span-2">Number</div>
                    <div class="col-span-3">Customer Details</div>
                    <div class="col-span-2">Unit / Type</div>
                    <div class="col-span-2">Total Amount</div>
                    <div class="col-span-2">Date / Info</div>
                    <div class="col-span-1 text-right">Actions</div>
                </div>

                @forelse($transactions as $txn)
                    <div class="row-floating grid grid-cols-12 gap-8 items-center px-10 py-6 group transition-all duration-300">
                        <div class="col-span-2 min-w-0">
                            <a href="{{ route('transactions.show', $txn) }}" class="text-[14px] font-bold text-slate-900 hover:text-gold-600 transition-colors duration-300 tracking-tight block truncate">
                                {{ $txn->transaction_number }}
                            </a>
                        </div>

                        <div class="col-span-3 min-w-0">
                            <div class="flex flex-col min-w-0">
                                <span class="text-[14px] font-bold text-slate-800 truncate">{{ $txn->client->nama_client }}</span>
                                <span class="text-[12px] text-slate-400 font-medium truncate">{{ $txn->client->nama_perusahaan }}</span>
                            </div>
                        </div>

                        <div class="col-span-2 min-w-0">
                            <span class="text-[10px] font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded-lg mr-2 uppercase">
                                {{ $txn->businessUnit ? $txn->businessUnit->name : '-' }}
                            </span>
                            <span class="text-[10px] font-bold text-white px-2 py-0.5 rounded-lg uppercase {{ $txn->mode === 'receipt' ? 'bg-emerald-500' : 'bg-blue-500' }}">
                                {{ $txn->mode }}
                            </span>
                        </div>

                        <div class="col-span-2 min-w-0">
                            <span class="text-[15px] font-black text-slate-900 tracking-tight">Rp {{ number_format($txn->total, 0, ',', '.') }}</span>
                        </div>

                        <div class="col-span-2 min-w-0 text-[12px]">
                            @if($txn->mode === 'receipt')
                                <span class="text-emerald-600 font-bold">Paid today: {{ $txn->payment_date ? $txn->payment_date->format('d M Y') : '-' }}</span>
                            @else
                                <span class="text-slate-500 font-medium">Due: {{ $txn->due_date ? $txn->due_date->format('d M Y') : '-' }}</span>
                            @endif
                        </div>

                        <div class="col-span-1">
                            <div class="flex items-center justify-end gap-3 opacity-40 group-hover:opacity-100 transition-all duration-300">
                                <a href="{{ route('transactions.show', $txn) }}" class="p-1 text-slate-400 hover:text-gold-600 transition-colors duration-300">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('transactions.pdf', $txn) }}" class="p-1 text-slate-400 hover:text-gold-600 transition-colors duration-300">
                                    <i data-lucide="download" class="w-4 h-4"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white border border-dashed border-slate-200 rounded-[32px] p-24 text-center">
                        <div class="flex flex-col items-center max-w-sm mx-auto">
                            <div class="w-20 h-20 bg-slate-50 rounded-[24px] flex items-center justify-center mb-6">
                                <i data-lucide="file-text" class="w-10 h-10 text-slate-300"></i>
                            </div>
                            <h4 class="text-xl font-bold text-slate-900 mb-2">No Transactions Found</h4>
                            <p class="text-[14px] text-slate-400 font-medium">Start by adding a new transaction.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if($transactions->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
