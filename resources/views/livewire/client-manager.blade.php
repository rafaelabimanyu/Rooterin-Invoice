<div class="space-y-6">
    <!-- Top Actions & Filters -->
    <div class="glass-card p-6 flex flex-col lg:flex-row items-center justify-between gap-6">
        <div class="relative w-full lg:max-w-md">
            <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="{{ app()->getLocale() == 'en' ? 'Search by name, company, or ID...' : 'Cari berdasarkan nama, perusahaan, atau ID...' }}" 
                   class="w-full pl-12 pr-6 py-3.5 bg-slate-50/50 border-transparent rounded-2xl focus:bg-white focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all font-bold text-slate-900 text-sm">
        </div>
        
        <div class="flex items-center gap-4 w-full lg:w-auto">
            <div class="flex items-center p-1 bg-slate-100 rounded-xl font-jakarta">
                <button wire:click="$set('status', '')" class="px-4 py-2 rounded-lg text-[11px] font-black uppercase tracking-widest transition-all {{ $status === '' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">{{ app()->getLocale() == 'en' ? 'All' : 'Semua' }}</button>
                <button wire:click="$set('status', 'aktif')" class="px-4 py-2 rounded-lg text-[11px] font-black uppercase tracking-widest transition-all {{ $status === 'aktif' ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">{{ app()->getLocale() == 'en' ? 'Active' : 'Aktif' }}</button>
                <button wire:click="$set('status', 'nonaktif')" class="px-4 py-2 rounded-lg text-[11px] font-black uppercase tracking-widest transition-all {{ $status === 'nonaktif' ? 'bg-white text-rose-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">{{ app()->getLocale() == 'en' ? 'Inactive' : 'Nonaktif' }}</button>
            </div>
            <button wire:click="openCreate" class="btn-premium whitespace-nowrap ml-auto lg:ml-0">
                <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i>
                {{ app()->getLocale() == 'en' ? 'Register Client' : 'Daftarkan Klien' }}
            </button>
        </div>
    </div>

    <!-- Results Display -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-6">
        @forelse($clients as $client)
            <div wire:key="client-{{ $client->id }}" class="glass-card group hover:-translate-y-2 hover:shadow-2xl transition-all duration-500 relative overflow-hidden border-transparent hover:border-indigo-500/10">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 font-black text-sm shadow-inner group-hover:bg-slate-900 group-hover:text-white transition-colors duration-500">
                                {{ $this->getInitial($client->nama_client) }}
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-slate-900 leading-tight truncate max-w-[140px]">{{ $client->nama_client }}</h3>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">{{ $client->kode_client }}</p>
                            </div>
                        </div>
                        <x-badge :status="$client->status" />
                    </div>

                    <div class="space-y-3 mb-6">
                        <div class="flex items-center gap-3 text-slate-500">
                            <i data-lucide="building" class="w-3.5 h-3.5"></i>
                            <span class="text-[12px] font-bold truncate">{{ $client->nama_perusahaan ?? (app()->getLocale() == 'en' ? 'Personal Account' : 'Akun Pribadi') }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-slate-500">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                            <span class="text-[12px] font-bold">{{ $client->kota }}, {{ $client->provinsi }}</span>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-50 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <button wire:click="openView({{ $client->id }})" class="p-2 text-slate-400 hover:text-indigo-600 transition-colors">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                            <button wire:click="openEdit({{ $client->id }})" class="p-2 text-slate-400 hover:text-indigo-600 transition-colors">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </button>
                        </div>
                        <div class="flex items-center gap-1.5 translate-x-10 group-hover:translate-x-0 transition-transform duration-500 opacity-0 group-hover:opacity-100">
                            <a href="mailto:{{ $client->email }}" class="p-2 bg-slate-50 text-slate-600 rounded-lg hover:bg-slate-900 hover:text-white transition-all shadow-sm">
                                <i data-lucide="mail" class="w-3.5 h-3.5"></i>
                            </a>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $client->no_hp) }}" target="_blank" class="p-2 bg-emerald-50 text-emerald-600 rounded-lg hover:bg-emerald-600 hover:text-white transition-all shadow-sm">
                                <i data-lucide="phone" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Progress Line Mock -->
                <div class="absolute bottom-0 left-0 w-full h-1 bg-slate-100">
                    <div class="h-full bg-indigo-500/20 group-hover:bg-indigo-500 transition-all duration-700" style="width: 100%"></div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 bg-white border border-slate-200/80 rounded-[32px]">
                <x-empty-state icon="users" :title="app()->getLocale() == 'en' ? 'No matching clients found' : 'Tidak ada klien yang cocok ditemukan'" :description="app()->getLocale() == 'en' ? 'Adjust your filters or register a new client.' : 'Sesuaikan filter Anda atau daftarkan klien baru.'" />
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-10">
        {{ $clients->links() }}
    </div>

    <!-- Modal: Create / Edit -->
    <div x-show="$wire.showEditModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-6 sm:p-10">
        <div x-show="$wire.showEditModal" x-transition.opacity class="absolute inset-0 bg-slate-900/60 backdrop-blur-md" @click="$wire.showEditModal = false"></div>
        
        <div x-show="$wire.showEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" 
             class="bg-white w-full max-w-3xl rounded-[40px] shadow-2xl relative overflow-hidden flex flex-col max-h-[90vh]">
            
            <div class="px-10 py-8 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div>
                    <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight font-outfit">{{ $editingClient ? (app()->getLocale() == 'en' ? 'Update Profile' : 'Perbarui Profil') : (app()->getLocale() == 'en' ? 'Register Enterprise' : 'Daftarkan Perusahaan') }}</h2>
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">{{ $kode_client }}</p>
                </div>
                <button @click="$wire.showEditModal = false" class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center text-slate-400 hover:text-slate-900 hover:scale-110 transition-all">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form wire:submit.prevent="save" class="flex flex-col h-full">
                <div class="p-10 overflow-y-auto flex-1 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest font-outfit">{{ app()->getLocale() == 'en' ? 'Full Name / Contact' : 'Nama Lengkap / Kontak' }}</label>
                            <input type="text" wire:model="nama_client" class="w-full px-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 transition-all font-bold text-slate-900">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest font-outfit">{{ app()->getLocale() == 'en' ? 'Company / Legal Entity' : 'Perusahaan / Badan Hukum' }}</label>
                            <input type="text" wire:model="nama_perusahaan" class="w-full px-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 transition-all font-bold text-slate-900">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest font-outfit">{{ app()->getLocale() == 'en' ? 'Email Address' : 'Alamat Email' }}</label>
                            <input type="email" wire:model="email" class="w-full px-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 transition-all font-bold text-slate-900">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest font-outfit">{{ app()->getLocale() == 'en' ? 'Phone / WhatsApp' : 'Telepon / WhatsApp' }}</label>
                            <input type="text" wire:model="no_hp" class="w-full px-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 transition-all font-bold text-slate-900">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest font-outfit">{{ app()->getLocale() == 'en' ? 'Physical Address' : 'Alamat Fisik' }}</label>
                        <textarea wire:model="alamat" rows="3" class="w-full px-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 transition-all font-bold text-slate-900"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest font-outfit">{{ app()->getLocale() == 'en' ? 'City' : 'Kota' }}</label>
                            <input type="text" wire:model="kota" class="w-full px-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 transition-all font-bold text-slate-900">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest font-outfit">{{ app()->getLocale() == 'en' ? 'Province' : 'Provinsi' }}</label>
                            <input type="text" wire:model="provinsi" class="w-full px-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 transition-all font-bold text-slate-900">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest font-outfit">{{ app()->getLocale() == 'en' ? 'Account Status' : 'Status Akun' }}</label>
                            <select wire:model="status_field" class="w-full px-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 transition-all font-bold text-slate-900 text-sm">
                                <option value="aktif">{{ app()->getLocale() == 'en' ? 'Active' : 'Aktif' }}</option>
                                <option value="nonaktif">{{ app()->getLocale() == 'en' ? 'Inactive' : 'Nonaktif' }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="px-10 py-8 bg-slate-50 border-t border-slate-100 flex items-center justify-between font-jakarta">
                    <button type="button" @click="$wire.showEditModal = false" class="text-[11px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-900 transition-colors">{{ app()->getLocale() == 'en' ? 'Discard Changes' : 'Batalkan Perubahan' }}</button>
                    <button type="submit" class="btn-premium px-10">
                        <i data-lucide="check" class="w-4 h-4 mr-2"></i>
                        {{ $editingClient ? (app()->getLocale() == 'en' ? 'Save Changes' : 'Simpan Perubahan') : (app()->getLocale() == 'en' ? 'Confirm Registration' : 'Konfirmasi Pendaftaran') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
