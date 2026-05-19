<x-app-layout>
    <div class="mb-8">
        <a href="{{ route('clients.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors mb-4 font-jakarta font-bold">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            {{ app()->getLocale() == 'en' ? 'Back to Clients' : 'Kembali ke Klien' }}
        </a>
        <h1 class="text-3xl font-bold text-slate-900 font-outfit">{{ app()->getLocale() == 'en' ? 'Add New Client' : 'Tambah Klien Baru' }}</h1>
        <p class="text-slate-500 font-medium text-sm">{{ app()->getLocale() == 'en' ? 'Register a new customer for your business' : 'Daftarkan pelanggan baru untuk bisnis Anda' }}</p>
    </div>

    <form action="{{ route('clients.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Basic Information -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-8 rounded-3xl border border-slate-200/80 shadow-sm space-y-6">
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-4 flex items-center gap-2 font-outfit">
                        <i data-lucide="user" class="w-4 h-4 text-indigo-600"></i>
                        {{ app()->getLocale() == 'en' ? 'Basic Information' : 'Informasi Dasar' }}
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-input label="{{ app()->getLocale() == 'en' ? 'Client Name' : 'Nama Klien' }}" name="nama_client" required placeholder="e.g. John Doe" />
                        <x-input label="{{ app()->getLocale() == 'en' ? 'Company Name' : 'Nama Perusahaan' }}" name="nama_perusahaan" placeholder="e.g. Rooterin Co." />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-input label="{{ app()->getLocale() == 'en' ? 'Email Address' : 'Alamat Email' }}" name="email" type="email" placeholder="john@example.com" />
                        <x-input label="{{ app()->getLocale() == 'en' ? 'Phone Number / WA' : 'Nomor Telepon / WA' }}" name="no_hp" placeholder="08123456789" />
                    </div>

                    <x-input label="{{ app()->getLocale() == 'en' ? 'NPWP (Optional)' : 'NPWP (Opsional)' }}" name="npwp" placeholder="00.000.000.0-000.000" />
                </div>

                <!-- Address -->
                <div class="bg-white p-8 rounded-3xl border border-slate-200/80 shadow-sm space-y-6">
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-4 flex items-center gap-2 font-outfit">
                        <i data-lucide="map-pin" class="w-4 h-4 text-indigo-600"></i>
                        {{ app()->getLocale() == 'en' ? 'Address Details' : 'Rincian Alamat' }}
                    </h3>
                    
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Full Address' : 'Alamat Lengkap' }}</label>
                        <textarea name="alamat" rows="3" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none text-slate-900">{{ old('alamat') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-input label="{{ app()->getLocale() == 'en' ? 'City' : 'Kota' }}" name="kota" placeholder="e.g. Jakarta" />
                        <x-input label="{{ app()->getLocale() == 'en' ? 'Province' : 'Provinsi' }}" name="provinsi" placeholder="e.g. DKI Jakarta" />
                    </div>
                </div>
            </div>

            <!-- Settings & Notes -->
            <div class="space-y-6">
                <div class="bg-white p-8 rounded-3xl border border-slate-200/80 shadow-sm space-y-6">
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-4 flex items-center gap-2 font-outfit">
                        <i data-lucide="settings" class="w-4 h-4 text-indigo-600"></i>
                        {{ app()->getLocale() == 'en' ? 'Settings' : 'Pengaturan' }}
                    </h3>

                    <x-input label="{{ app()->getLocale() == 'en' ? 'Client Code' : 'Kode Klien' }}" name="kode_client" value="{{ $kode_client }}" readonly class="bg-slate-50 cursor-not-allowed" />

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Status' : 'Status' }}</label>
                        <select name="status" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none text-slate-900 font-bold text-sm">
                            <option value="aktif">{{ app()->getLocale() == 'en' ? 'Active' : 'Aktif' }}</option>
                            <option value="nonaktif">{{ app()->getLocale() == 'en' ? 'Inactive' : 'Nonaktif' }}</option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Internal Notes' : 'Catatan Internal' }}</label>
                        <textarea name="catatan" rows="4" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none text-slate-900">{{ old('catatan') }}</textarea>
                    </div>
                </div>

                <div class="p-6 bg-indigo-50 rounded-3xl border border-indigo-100">
                    <p class="text-xs text-indigo-700 font-bold mb-4 font-jakarta uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Make sure all information is correct before saving.' : 'Pastikan semua informasi sudah benar sebelum disimpan.' }}</p>
                    <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-black uppercase tracking-widest text-xs transition-all shadow-lg shadow-indigo-600/25">
                        {{ app()->getLocale() == 'en' ? 'Save Client' : 'Simpan Klien' }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</x-app-layout>
