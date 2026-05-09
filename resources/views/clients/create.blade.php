<x-app-layout>
    <div class="mb-8">
        <a href="{{ route('clients.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors mb-4">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Back to Clients
        </a>
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white font-outfit">Add New Client</h1>
        <p class="text-slate-500 dark:text-slate-400">Register a new customer for your business</p>
    </div>

    <form action="{{ route('clients.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Basic Information -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-6">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <i data-lucide="user" class="w-5 h-5 text-indigo-600"></i>
                        Basic Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-input label="Client Name" name="nama_client" required placeholder="e.g. John Doe" />
                        <x-input label="Company Name" name="nama_perusahaan" placeholder="e.g. Rooterin Co." />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-input label="Email Address" name="email" type="email" placeholder="john@example.com" />
                        <x-input label="Phone Number / WA" name="no_hp" placeholder="08123456789" />
                    </div>

                    <x-input label="NPWP (Optional)" name="npwp" placeholder="00.000.000.0-000.000" />
                </div>

                <!-- Address -->
                <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-6">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <i data-lucide="map-pin" class="w-5 h-5 text-indigo-600"></i>
                        Address Details
                    </h3>
                    
                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Full Address</label>
                        <textarea name="alamat" rows="3" class="w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none text-slate-900 dark:text-white">{{ old('alamat') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-input label="City" name="kota" placeholder="e.g. Jakarta" />
                        <x-input label="Province" name="provinsi" placeholder="e.g. DKI Jakarta" />
                    </div>
                </div>
            </div>

            <!-- Settings & Notes -->
            <div class="space-y-6">
                <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-6">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <i data-lucide="settings" class="w-5 h-5 text-indigo-600"></i>
                        Settings
                    </h3>

                    <x-input label="Client Code" name="kode_client" value="{{ $kode_client }}" readonly class="bg-slate-50 dark:bg-slate-800/50 cursor-not-allowed" />

                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Status</label>
                        <select name="status" class="w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none text-slate-900 dark:text-white">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Internal Notes</label>
                        <textarea name="catatan" rows="4" class="w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none text-slate-900 dark:text-white">{{ old('catatan') }}</textarea>
                    </div>
                </div>

                <div class="p-6 bg-indigo-50 dark:bg-indigo-500/10 rounded-3xl border border-indigo-100 dark:border-indigo-500/20">
                    <p class="text-sm text-indigo-700 dark:text-indigo-400 font-medium mb-4">Make sure all information is correct before saving.</p>
                    <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold transition-all shadow-lg shadow-indigo-600/25">
                        Save Client
                    </button>
                </div>
            </div>
        </div>
    </form>
</x-app-layout>
