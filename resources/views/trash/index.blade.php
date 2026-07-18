<x-app-layout>
    <div class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ activeTab: 'invoices' }">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-slate-100 pb-6 mb-8">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                    <i data-lucide="trash-2" class="w-6 h-6 text-slate-500"></i>
                    {{ __('ui.trash') }}
                </h1>
                <p class="mt-1.5 text-xs font-semibold text-slate-500 max-w-2xl">
                    {{ app()->getLocale() == 'en' ? 'Manage soft deleted invoices and receipts. Restored records will return to operation, while purged items will be permanently erased.' : 'Kelola invoice dan kwitansi yang dihapus secara temporer. Data yang dipulihkan akan kembali aktif, sedangkan data yang dibersihkan akan dihapus permanen.' }}
                </p>
            </div>
        </div>

        <!-- Session Status Flash Messages -->
        @if (session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold flex items-center gap-2">
                <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-600"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Tab Controls -->
        <div class="flex border-b border-slate-200 mb-6 bg-slate-50/50 p-1.5 rounded-xl gap-2 w-max">
            <button 
                @click="activeTab = 'invoices'" 
                :class="activeTab === 'invoices' ? 'bg-white shadow-sm text-slate-900' : 'text-slate-500 hover:text-slate-900'"
                class="px-4 py-2 text-xs font-black rounded-lg transition-all duration-200 flex items-center gap-2"
            >
                <i data-lucide="file-text" class="w-4 h-4"></i>
                {{ __('ui.invoices') }}
                <span class="ml-1.5 px-2 py-0.5 rounded-full text-[10px] font-black" :class="activeTab === 'invoices' ? 'bg-slate-100 text-slate-900' : 'bg-slate-200/50 text-slate-600'">
                    {{ $invoices->count() }}
                </span>
            </button>
            <button 
                @click="activeTab = 'receipts'" 
                :class="activeTab === 'receipts' ? 'bg-white shadow-sm text-slate-900' : 'text-slate-500 hover:text-slate-900'"
                class="px-4 py-2 text-xs font-black rounded-lg transition-all duration-200 flex items-center gap-2"
            >
                <i data-lucide="receipt" class="w-4 h-4"></i>
                {{ __('ui.receipts') }}
                <span class="ml-1.5 px-2 py-0.5 rounded-full text-[10px] font-black" :class="activeTab === 'receipts' ? 'bg-white text-slate-900' : 'bg-slate-200/50 text-slate-600'">
                    {{ $receipts->count() }}
                </span>
            </button>
            <button 
                @click="activeTab = 'clients'" 
                :class="activeTab === 'clients' ? 'bg-white shadow-sm text-slate-900' : 'text-slate-500 hover:text-slate-900'"
                class="px-4 py-2 text-xs font-black rounded-lg transition-all duration-200 flex items-center gap-2"
            >
                <i data-lucide="users" class="w-4 h-4"></i>
                {{ __('ui.clients') }}
                <span class="ml-1.5 px-2 py-0.5 rounded-full text-[10px] font-black" :class="activeTab === 'clients' ? 'bg-white text-slate-900' : 'bg-slate-200/50 text-slate-600'">
                    {{ $clients->count() }}
                </span>
            </button>
        </div>

        <!-- Invoices Tab Contents -->
        <div x-show="activeTab === 'invoices'" x-cloak class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm">
            @if ($invoices->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-12 h-12 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-400">
                        <i data-lucide="file-text" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-sm font-black text-slate-900">{{ app()->getLocale() == 'en' ? 'No Deleted Invoices' : 'Tidak Ada Invoice di Tempat Sampah' }}</h3>
                    <p class="text-xs text-slate-500 mt-1">{{ app()->getLocale() == 'en' ? 'Soft-deleted invoices will appear here.' : 'Invoice yang dihapus sementara akan muncul di sini.' }}</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-left">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Invoice Number' : 'Nomor Invoice' }}</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest">{{ __('ui.company') }}</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Deleted By & Reason' : 'Dihapus Oleh & Alasan' }}</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Deleted At' : 'Dihapus Pada' }}</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest text-right">{{ __('ui.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($invoices as $invoice)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-black text-slate-900 text-sm">{{ $invoice->invoice_number }}</div>
                                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ $invoice->businessUnit->name ?? 'General' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-700 text-sm">{{ $invoice->client->nama_perusahaan ?? '-' }}</div>
                                        <div class="text-xs text-slate-500">{{ $invoice->client->nama_client ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-800 text-sm">{{ $invoice->deleter->name ?? '-' }}</div>
                                        <div class="text-xs text-slate-500 italic mt-0.5">"{{ $invoice->deletion_reason ?? '-' }}"</div>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-bold text-slate-500">
                                        {{ $invoice->deleted_at->timezone(auth()->user()->timezone ?? 'Asia/Jakarta')->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <!-- Restore Action -->
                                            <form action="{{ route('trash.invoices.restore', $invoice->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 border border-emerald-100 text-emerald-700 text-xs font-black transition-all flex items-center gap-1.5">
                                                    <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                                                    {{ app()->getLocale() == 'en' ? 'Restore' : 'Pulihkan' }}
                                                </button>
                                            </form>
                                            <!-- Purge (Force Delete) Action -->
                                            <form action="{{ route('trash.invoices.purge', $invoice->id) }}" method="POST" id="purge-invoice-form-{{ $invoice->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button 
                                                    type="button" 
                                                    onclick="confirmPurgeInvoice('{{ $invoice->id }}', '{{ $invoice->invoice_number }}')" 
                                                    class="px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 border border-rose-100 text-rose-700 text-xs font-black transition-all flex items-center gap-1.5"
                                                >
                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                    {{ app()->getLocale() == 'en' ? 'Purge' : 'Hapus Permanen' }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Receipts Tab Contents -->
        <div x-show="activeTab === 'receipts'" x-cloak class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm">
            @if ($receipts->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-12 h-12 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-400">
                        <i data-lucide="receipt" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-sm font-black text-slate-900">{{ app()->getLocale() == 'en' ? 'No Deleted Receipts' : 'Tidak Ada Kwitansi di Tempat Sampah' }}</h3>
                    <p class="text-xs text-slate-500 mt-1">{{ app()->getLocale() == 'en' ? 'Soft-deleted receipts will appear here.' : 'Kwitansi yang dihapus sementara akan muncul di sini.' }}</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-left">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Receipt Number' : 'Nomor Kwitansi' }}</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Linked Invoice' : 'Invoice Terkait' }}</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Deleted By & Reason' : 'Dihapus Oleh & Alasan' }}</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Deleted At' : 'Dihapus Pada' }}</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest text-right">{{ __('ui.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($receipts as $receipt)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-black text-slate-900 text-sm">{{ $receipt->receipt_number }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($receipt->invoice)
                                            <div class="font-bold text-slate-700 text-sm">{{ $receipt->invoice->invoice_number }}</div>
                                            <div class="text-xs text-slate-500">{{ $receipt->invoice->client->nama_client ?? '-' }}</div>
                                        @else
                                            <span class="text-xs text-slate-400 italic">{{ app()->getLocale() == 'en' ? 'Orphaned Invoice' : 'Invoice Asal Dihapus Permanen' }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-800 text-sm">{{ $receipt->deleter->name ?? '-' }}</div>
                                        <div class="text-xs text-slate-500 italic mt-0.5">"{{ $receipt->deletion_reason ?? '-' }}"</div>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-bold text-slate-500">
                                        {{ $receipt->deleted_at->timezone(auth()->user()->timezone ?? 'Asia/Jakarta')->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <!-- Restore Action -->
                                            <form action="{{ route('trash.receipts.restore', $receipt->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 border border-emerald-100 text-emerald-700 text-xs font-black transition-all flex items-center gap-1.5">
                                                    <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                                                    {{ app()->getLocale() == 'en' ? 'Restore' : 'Pulihkan' }}
                                                </button>
                                            </form>
                                            <!-- Purge (Force Delete) Action -->
                                            <form action="{{ route('trash.receipts.purge', $receipt->id) }}" method="POST" id="purge-receipt-form-{{ $receipt->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button 
                                                    type="button" 
                                                    onclick="confirmPurgeReceipt('{{ $receipt->id }}', '{{ $receipt->receipt_number }}')" 
                                                    class="px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 border border-rose-100 text-rose-700 text-xs font-black transition-all flex items-center gap-1.5"
                                                >
                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                    {{ app()->getLocale() == 'en' ? 'Purge' : 'Hapus Permanen' }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Clients Tab Contents -->
        <div x-show="activeTab === 'clients'" x-cloak class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm">
            @if ($clients->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-12 h-12 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-400">
                        <i data-lucide="users" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-sm font-black text-slate-900">{{ app()->getLocale() == 'en' ? 'No Deleted Clients' : 'Tidak Ada Klien di Tempat Sampah' }}</h3>
                    <p class="text-xs text-slate-500 mt-1">{{ app()->getLocale() == 'en' ? 'Soft-deleted clients will appear here.' : 'Klien yang dihapus sementara akan muncul di sini.' }}</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-left">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Client Code & Name' : 'Kode & Nama Klien' }}</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest">{{ __('ui.company') }}</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Deleted By & Reason' : 'Dihapus Oleh & Alasan' }}</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Deleted At' : 'Dihapus Pada' }}</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest text-right">{{ __('ui.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($clients as $client)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-black text-slate-900 text-sm">{{ $client->kode_client }}</div>
                                        <div class="text-xs text-slate-500 mt-0.5">{{ $client->nama_client }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-700 text-sm">{{ $client->nama_perusahaan ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-800 text-sm">{{ $client->deleter->name ?? '-' }}</div>
                                        <div class="text-xs text-slate-500 italic mt-0.5">"{{ $client->deletion_reason ?? '-' }}"</div>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-bold text-slate-500">
                                        {{ $client->deleted_at->timezone(auth()->user()->timezone ?? 'Asia/Jakarta')->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <!-- Restore Action -->
                                            <form action="{{ route('trash.clients.restore', $client->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 border border-emerald-100 text-emerald-700 text-xs font-black transition-all flex items-center gap-1.5">
                                                    <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                                                    {{ app()->getLocale() == 'en' ? 'Restore' : 'Pulihkan' }}
                                                </button>
                                            </form>
                                            <!-- Purge (Force Delete) Action -->
                                            <form action="{{ route('trash.clients.purge', $client->id) }}" method="POST" id="purge-client-form-{{ $client->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button 
                                                    type="button" 
                                                    onclick="confirmPurgeClient('{{ $client->id }}', '{{ $client->nama_client }}')" 
                                                    class="px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 border border-rose-100 text-rose-700 text-xs font-black transition-all flex items-center gap-1.5"
                                                >
                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                    {{ app()->getLocale() == 'en' ? 'Purge' : 'Hapus Permanen' }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        const isEnglish = "{{ app()->getLocale() }}" === "en";

        function confirmPurgeInvoice(id, invoiceNumber) {
            const titleText = isEnglish ? 'Are you absolutely sure?' : 'Apakah Anda benar-benar yakin?';
            const textText = isEnglish 
                ? `Invoice #${invoiceNumber} and all its attachment files will be permanently deleted from disk. This action is irreversible!`
                : `Invoice #${invoiceNumber} dan seluruh file lampirannya akan dihapus permanen dari disk. Tindakan ini tidak dapat dibatalkan!`;
            
            const doubleConfirmTitle = isEnglish ? 'Final Confirmation Needed' : 'Konfirmasi Tahap Akhir';
            const doubleConfirmText = isEnglish
                ? 'This is your last chance. Confirming this will permanently erase this invoice record. Do you want to proceed?'
                : 'Ini adalah kesempatan terakhir Anda. Mengonfirmasi hal ini akan menghapus data invoice selamanya. Lanjutkan?';

            // Double Confirmation Logic using SweetAlert2 with browser Native fallback
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: titleText,
                    text: textText,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48', // rose-600
                    cancelButtonColor: '#64748b', // slate-500
                    confirmButtonText: isEnglish ? 'Yes, delete permanently' : 'Ya, hapus permanen',
                    cancelButtonText: isEnglish ? 'Cancel' : 'Batal',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl font-bold px-4 py-2 text-sm',
                        cancelButton: 'rounded-xl font-bold px-4 py-2 text-sm'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Second confirmation prompt
                        Swal.fire({
                            title: doubleConfirmTitle,
                            text: doubleConfirmText,
                            icon: 'error',
                            showCancelButton: true,
                            confirmButtonColor: '#be123c', // rose-700
                            cancelButtonColor: '#64748b',
                            confirmButtonText: isEnglish ? 'Confirm Permanent Purge' : 'Konfirmasi Hapus Permanen',
                            cancelButtonText: isEnglish ? 'Cancel' : 'Batal',
                            customClass: {
                                popup: 'rounded-2xl',
                                confirmButton: 'rounded-xl font-bold px-4 py-2 text-sm',
                                cancelButton: 'rounded-xl font-bold px-4 py-2 text-sm'
                            }
                        }).then((secondResult) => {
                            if (secondResult.isConfirmed) {
                                document.getElementById(`purge-invoice-form-${id}`).submit();
                            }
                        });
                    }
                });
            } else {
                // Native confirm fallback
                const firstConfirm = confirm(textText);
                if (firstConfirm) {
                    const secondConfirm = confirm(doubleConfirmText);
                    if (secondConfirm) {
                        document.getElementById(`purge-invoice-form-${id}`).submit();
                    }
                }
            }
        }

        function confirmPurgeReceipt(id, receiptNumber) {
            const titleText = isEnglish ? 'Are you absolutely sure?' : 'Apakah Anda benar-benar yakin?';
            const textText = isEnglish 
                ? `Receipt #${receiptNumber} will be permanently deleted. This action is irreversible!`
                : `Kwitansi #${receiptNumber} akan dihapus permanen. Tindakan ini tidak dapat dibatalkan!`;
            
            const doubleConfirmTitle = isEnglish ? 'Final Confirmation Needed' : 'Konfirmasi Tahap Akhir';
            const doubleConfirmText = isEnglish
                ? 'This is your last chance. Confirming this will permanently erase this receipt record. Do you want to proceed?'
                : 'Ini adalah kesempatan terakhir Anda. Mengonfirmasi hal ini akan menghapus data kwitansi selamanya. Lanjutkan?';

            // Double Confirmation Logic using SweetAlert2 with browser Native fallback
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: titleText,
                    text: textText,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48', // rose-600
                    cancelButtonColor: '#64748b', // slate-500
                    confirmButtonText: isEnglish ? 'Yes, delete permanently' : 'Ya, hapus permanen',
                    cancelButtonText: isEnglish ? 'Cancel' : 'Batal',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl font-bold px-4 py-2 text-sm',
                        cancelButton: 'rounded-xl font-bold px-4 py-2 text-sm'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Second confirmation prompt
                        Swal.fire({
                            title: doubleConfirmTitle,
                            text: doubleConfirmText,
                            icon: 'error',
                            showCancelButton: true,
                            confirmButtonColor: '#be123c', // rose-700
                            cancelButtonColor: '#64748b',
                            confirmButtonText: isEnglish ? 'Confirm Permanent Purge' : 'Konfirmasi Hapus Permanen',
                            cancelButtonText: isEnglish ? 'Cancel' : 'Batal',
                            customClass: {
                                popup: 'rounded-2xl',
                                confirmButton: 'rounded-xl font-bold px-4 py-2 text-sm',
                                cancelButton: 'rounded-xl font-bold px-4 py-2 text-sm'
                            }
                        }).then((secondResult) => {
                            if (secondResult.isConfirmed) {
                                document.getElementById(`purge-receipt-form-${id}`).submit();
                            }
                        });
                    }
                });
            } else {
                // Native confirm fallback
                const firstConfirm = confirm(textText);
                if (firstConfirm) {
                    const secondConfirm = confirm(doubleConfirmText);
                    if (secondConfirm) {
                        document.getElementById(`purge-receipt-form-${id}`).submit();
                    }
                }
            }
        }

        function confirmPurgeClient(id, clientName) {
            const titleText = isEnglish ? 'Are you absolutely sure?' : 'Apakah Anda benar-benar yakin?';
            const textText = isEnglish 
                ? `Client "${clientName}" will be permanently deleted. This action is irreversible!`
                : `Klien "${clientName}" akan dihapus permanen. Tindakan ini tidak dapat dibatalkan!`;
            
            const doubleConfirmTitle = isEnglish ? 'Final Confirmation Needed' : 'Konfirmasi Tahap Akhir';
            const doubleConfirmText = isEnglish
                ? 'This is your last chance. Confirming this will permanently erase this client record. Do you want to proceed?'
                : 'Ini adalah kesempatan terakhir Anda. Mengonfirmasi hal ini akan menghapus data klien selamanya. Lanjutkan?';

            // Double Confirmation Logic using SweetAlert2 with browser Native fallback
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: titleText,
                    text: textText,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48', // rose-600
                    cancelButtonColor: '#64748b', // slate-500
                    confirmButtonText: isEnglish ? 'Yes, delete permanently' : 'Ya, hapus permanen',
                    cancelButtonText: isEnglish ? 'Cancel' : 'Batal',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl font-bold px-4 py-2 text-sm',
                        cancelButton: 'rounded-xl font-bold px-4 py-2 text-sm'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Second confirmation prompt
                        Swal.fire({
                            title: doubleConfirmTitle,
                            text: doubleConfirmText,
                            icon: 'error',
                            showCancelButton: true,
                            confirmButtonColor: '#be123c', // rose-700
                            cancelButtonColor: '#64748b',
                            confirmButtonText: isEnglish ? 'Confirm Permanent Purge' : 'Konfirmasi Hapus Permanen',
                            cancelButtonText: isEnglish ? 'Cancel' : 'Batal',
                            customClass: {
                                popup: 'rounded-2xl',
                                confirmButton: 'rounded-xl font-bold px-4 py-2 text-sm',
                                cancelButton: 'rounded-xl font-bold px-4 py-2 text-sm'
                            }
                        }).then((secondResult) => {
                            if (secondResult.isConfirmed) {
                                document.getElementById(`purge-client-form-${id}`).submit();
                            }
                        });
                    }
                });
            } else {
                // Native confirm fallback
                const firstConfirm = confirm(textText);
                if (firstConfirm) {
                    const secondConfirm = confirm(doubleConfirmText);
                    if (secondConfirm) {
                        document.getElementById(`purge-client-form-${id}`).submit();
                    }
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
