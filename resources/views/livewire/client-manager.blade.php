<div class="space-y-6">
    <!-- Top Actions & Filters -->
    <div class="glass-card p-4 sm:p-6 flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4 sm:gap-6">
        <div class="relative w-full lg:max-w-md">
            <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="{{ app()->getLocale() == 'en' ? 'Search by name, company, or ID...' : 'Cari berdasarkan nama, perusahaan, atau ID...' }}" 
                   class="w-full pl-12 pr-12 py-3.5 bg-slate-50/50 border-transparent rounded-2xl focus:bg-white focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all font-bold text-slate-900 text-sm">
            <div wire:loading wire:target="search" class="absolute right-4 top-1/2 -translate-y-1/2">
                <i data-lucide="loader-2" class="w-4 h-4 text-indigo-600 animate-spin"></i>
            </div>
        </div>
        
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 w-full lg:w-auto">
            <div class="relative flex items-center p-1 bg-slate-100 rounded-xl font-jakarta w-full sm:w-auto" x-data="{ activeTab: @entangle('status') }">
                <!-- Sliding background indicator -->
                <div class="absolute top-1 bottom-1 rounded-lg bg-white shadow-sm transition-all duration-300 ease-in-out"
                     :class="{
                         'left-1 w-[calc(33.333%-6px)] sm:w-24': activeTab === '',
                         'left-[calc(33.333%+2px)] w-[calc(33.333%-6px)] sm:left-[104px] sm:w-24': activeTab === 'aktif',
                         'left-[calc(66.666%+3px)] w-[calc(33.333%-6px)] sm:left-[204px] sm:w-24': activeTab === 'nonaktif'
                     }">
                </div>

                <button wire:click="$set('status', '')" class="relative z-10 flex-1 sm:flex-none sm:w-24 text-center px-4 py-2 rounded-lg text-[10px] sm:text-[11px] font-black uppercase tracking-widest transition-all duration-300" :class="activeTab === '' ? 'text-slate-950 font-black' : 'text-slate-500 hover:text-slate-700'">{{ app()->getLocale() == 'en' ? 'All' : 'Semua' }}</button>
                <button wire:click="$set('status', 'aktif')" class="relative z-10 flex-1 sm:flex-none sm:w-24 text-center px-4 py-2 rounded-lg text-[10px] sm:text-[11px] font-black uppercase tracking-widest transition-all duration-300" :class="activeTab === 'aktif' ? 'text-emerald-600 font-black' : 'text-slate-500 hover:text-slate-700'">{{ app()->getLocale() == 'en' ? 'Active' : 'Aktif' }}</button>
                <button wire:click="$set('status', 'nonaktif')" class="relative z-10 flex-1 sm:flex-none sm:w-24 text-center px-4 py-2 rounded-lg text-[10px] sm:text-[11px] font-black uppercase tracking-widest transition-all duration-300" :class="activeTab === 'nonaktif' ? 'text-rose-600 font-black' : 'text-slate-500 hover:text-slate-700'">{{ app()->getLocale() == 'en' ? 'Inactive' : 'Nonaktif' }}</button>
            </div>
            <button wire:click="openCreate" class="btn-premium whitespace-nowrap justify-center py-3 sm:py-2.5 w-full sm:w-auto">
                <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i>
                {{ app()->getLocale() == 'en' ? 'Register Client' : 'Daftarkan Klien' }}
            </button>
        </div>
    </div>

    <!-- Results Display -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($clients as $client)
            <div wire:key="client-{{ $client->id }}" class="glass-card group hover:-translate-y-1.5 hover:shadow-xl hover:border-indigo-500/20 hover:ring-4 hover:ring-indigo-500/5 transition-all duration-300 relative overflow-hidden border-transparent flex flex-col justify-between">
                <div class="p-6 space-y-4">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 font-black text-sm shadow-inner group-hover:bg-slate-900 group-hover:text-white transition-colors duration-500">
                                {{ $this->getInitial($client->nama_client) }}
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-slate-800 leading-tight truncate max-w-[150px]">{{ $client->nama_client }}</h3>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">{{ $client->kode_client }}</p>
                            </div>
                        </div>
                        <x-badge :status="$client->status" />
                    </div>

                    <!-- Client Type & Industry Sector Badges -->
                    <div class="flex flex-wrap gap-1.5">
                        @if($client->client_type)
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg text-[9px] font-bold uppercase tracking-wider bg-indigo-50/60 text-indigo-700 border border-indigo-100/50">
                                <i data-lucide="{{ $client->type_icon }}" class="w-3 h-3"></i>
                                {{ $client->client_type_label }}
                            </span>
                        @endif
                        @if($client->industry_sector)
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg text-[9px] font-bold uppercase tracking-wider bg-slate-100/60 text-slate-700 border border-slate-200/50">
                                <i data-lucide="{{ $client->sector_icon }}" class="w-3 h-3"></i>
                                {{ $client->industry_sector_label }}
                            </span>
                        @endif
                    </div>

                    <div class="space-y-2.5 pt-2">
                        <div class="flex items-center gap-3 text-slate-500">
                            <i data-lucide="building" class="w-3.5 h-3.5 text-slate-400"></i>
                            <span class="text-[12px] font-bold truncate">{{ $client->nama_perusahaan ?? (app()->getLocale() == 'en' ? 'Personal Account' : 'Akun Pribadi') }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-slate-400">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400"></i>
                            <span class="text-[12px] font-bold truncate">{{ $client->kota ? $client->kota . ', ' . $client->provinsi : '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Footer Minimal Action Bar -->
                <div class="px-5 py-3.5 bg-slate-50/60 border-t border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-1">
                        <button wire:click="openView({{ $client->id }})" class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50/50 transition-colors rounded-lg">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                        </button>
                        <button wire:click="openEdit({{ $client->id }})" class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50/50 transition-colors rounded-lg">
                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <div class="flex items-center gap-1.5">
                        @if($client->email)
                            <a href="mailto:{{ $client->email }}" class="p-1.5 bg-white text-slate-600 border border-slate-200/60 rounded-lg hover:bg-slate-900 hover:text-white transition-all shadow-sm">
                                <i data-lucide="mail" class="w-3.5 h-3.5"></i>
                            </a>
                        @endif
                        @if($client->no_hp)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $client->no_hp) }}" target="_blank" class="p-1.5 bg-emerald-50 text-emerald-600 border border-emerald-100/50 rounded-lg hover:bg-emerald-600 hover:text-white transition-all shadow-sm">
                                <i data-lucide="phone" class="w-3.5 h-3.5"></i>
                            </a>
                        @endif
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
    <div x-show="$wire.showEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div x-show="$wire.showEditModal" x-transition.opacity class="absolute inset-0 bg-slate-900/60 backdrop-blur-md" @click="$wire.showEditModal = false"></div>
        
        <div x-show="$wire.showEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" 
             class="w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-2xl bg-white shadow-2xl relative flex flex-col">
            
            <div class="px-6 py-5 sm:px-10 sm:py-8 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div>
                    <h2 class="text-lg sm:text-xl font-black text-slate-900 uppercase tracking-tight font-outfit">{{ $editingClient ? (app()->getLocale() == 'en' ? 'Update Profile' : 'Perbarui Profil') : (app()->getLocale() == 'en' ? 'Register Enterprise' : 'Daftarkan Klien') }}</h2>
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">{{ $kode_client }}</p>
                </div>
                <button @click="$wire.showEditModal = false" class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center text-slate-400 hover:text-slate-900 hover:scale-110 transition-all">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form wire:submit.prevent="save" class="flex flex-col">
                <div class="px-6 py-6 sm:p-10 space-y-6 sm:space-y-8">
                    <!-- Client Type & Industry Sector Selection -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest font-outfit">{{ __('ui.client_type_label') }}</label>
                            <select wire:model.live="client_type" class="w-full px-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 transition-all font-bold text-slate-900 text-sm">
                                <option value="individual">{{ __('ui.individual') }}</option>
                                <option value="corporate">{{ __('ui.corporate') }}</option>
                                <option value="government">{{ __('ui.government') }}</option>
                                <option value="foreign">{{ __('ui.foreign') }}</option>
                                <option value="other">{{ __('ui.other_type') }}</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest font-outfit">{{ __('ui.industry_sector_label') }}</label>
                            <select wire:model.live="industry_sector" class="w-full px-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 transition-all font-bold text-slate-900 text-sm">
                                <option value="general">{{ __('ui.general') }}</option>
                                <option value="fnb">{{ __('ui.fnb') }}</option>
                                <option value="healthcare">{{ __('ui.healthcare') }}</option>
                                <option value="manufacturing">{{ __('ui.manufacturing') }}</option>
                                <option value="tech">{{ __('ui.tech') }}</option>
                                <option value="education">{{ __('ui.education') }}</option>
                                <option value="other">{{ __('ui.other_sector') }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Custom Type & Sector Inputs -->
                    @if($client_type === 'other' || $industry_sector === 'other')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8 bg-slate-50/50 p-4 sm:p-6 rounded-2xl border border-slate-100">
                            @if($client_type === 'other')
                                <div class="space-y-2">
                                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest font-outfit">{{ __('ui.other_type') }}</label>
                                    <input type="text" wire:model="custom_client_type" placeholder="{{ app()->getLocale() == 'en' ? 'e.g. Co-op, Foundation' : 'Misal: Koperasi, Yayasan' }}" class="w-full px-5 py-4 bg-white border border-slate-200/60 rounded-2xl focus:ring-2 focus:ring-indigo-500/20 transition-all font-bold text-slate-900">
                                    @error('custom_client_type') <span class="text-xs text-rose-600 font-bold">{{ $message }}</span> @enderror
                                </div>
                            @else
                                <div class="hidden md:block"></div>
                            @endif

                            @if($industry_sector === 'other')
                                <div class="space-y-2">
                                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest font-outfit">{{ __('ui.other_sector') }}</label>
                                    <input type="text" wire:model="custom_industry_sector" placeholder="{{ app()->getLocale() == 'en' ? 'e.g. Agriculture, Energy' : 'Misal: Pertanian, Energi' }}" class="w-full px-5 py-4 bg-white border border-slate-200/60 rounded-2xl focus:ring-2 focus:ring-indigo-500/20 transition-all font-bold text-slate-900">
                                    @error('custom_industry_sector') <span class="text-xs text-rose-600 font-bold">{{ $message }}</span> @enderror
                                </div>
                            @else
                                <div class="hidden md:block"></div>
                            @endif
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest font-outfit">{{ app()->getLocale() == 'en' ? 'Full Name / Contact' : 'Nama Lengkap / Kontak' }}</label>
                            <input type="text" wire:model="nama_client" class="w-full px-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 transition-all font-bold text-slate-900">
                            @error('nama_client') <span class="text-xs text-rose-600 font-bold">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest font-outfit">{{ app()->getLocale() == 'en' ? 'Company / Legal Entity' : 'Perusahaan / Badan Hukum' }}</label>
                            <input type="text" wire:model="nama_perusahaan" class="w-full px-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 transition-all font-bold text-slate-900">
                            @error('nama_perusahaan') <span class="text-xs text-rose-600 font-bold">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest font-outfit">{{ app()->getLocale() == 'en' ? 'Email Address' : 'Alamat Email' }}</label>
                            <input type="email" wire:model="email" class="w-full px-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 transition-all font-bold text-slate-900">
                            @error('email') <span class="text-xs text-rose-600 font-bold">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest font-outfit">{{ app()->getLocale() == 'en' ? 'Phone / WhatsApp' : 'Telepon / WhatsApp' }}</label>
                            <input type="text" wire:model="no_hp" class="w-full px-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 transition-all font-bold text-slate-900">
                            @error('no_hp') <span class="text-xs text-rose-600 font-bold">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest font-outfit">NPWP</label>
                            <input type="text" wire:model="npwp" placeholder="00.000.000.0-000.000" class="w-full px-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 transition-all font-bold text-slate-900">
                            @error('npwp') <span class="text-xs text-rose-600 font-bold">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest font-outfit">{{ app()->getLocale() == 'en' ? 'Account Status' : 'Status Akun' }}</label>
                            <select wire:model="status_field" class="w-full px-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 transition-all font-bold text-slate-900 text-sm">
                                <option value="aktif">{{ app()->getLocale() == 'en' ? 'Active' : 'Aktif' }}</option>
                                <option value="nonaktif">{{ app()->getLocale() == 'en' ? 'Inactive' : 'Nonaktif' }}</option>
                            </select>
                            @error('status_field') <span class="text-xs text-rose-600 font-bold">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest font-outfit">{{ app()->getLocale() == 'en' ? 'Physical Address' : 'Alamat Fisik' }}</label>
                        <textarea wire:model="alamat" rows="3" class="w-full px-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 transition-all font-bold text-slate-900"></textarea>
                        @error('alamat') <span class="text-xs text-rose-600 font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest font-outfit">{{ app()->getLocale() == 'en' ? 'City' : 'Kota' }}</label>
                            <input type="text" wire:model="kota" class="w-full px-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 transition-all font-bold text-slate-900">
                            @error('kota') <span class="text-xs text-rose-600 font-bold">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest font-outfit">{{ app()->getLocale() == 'en' ? 'Province' : 'Provinsi' }}</label>
                            <input type="text" wire:model="provinsi" class="w-full px-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 transition-all font-bold text-slate-900">
                            @error('provinsi') <span class="text-xs text-rose-600 font-bold">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest font-outfit">{{ app()->getLocale() == 'en' ? 'Internal Notes' : 'Catatan Internal' }}</label>
                        <textarea wire:model="catatan" rows="3" class="w-full px-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 transition-all font-bold text-slate-900"></textarea>
                        @error('catatan') <span class="text-xs text-rose-600 font-bold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="px-6 py-6 sm:px-10 sm:py-8 bg-slate-50 border-t border-slate-100 flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-between gap-4 font-jakarta">
                    <button type="button" @click="$wire.showEditModal = false" class="text-[11px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-900 transition-colors py-3 sm:py-0 text-center">{{ app()->getLocale() == 'en' ? 'Discard Changes' : 'Batalkan Perubahan' }}</button>
                    <button type="submit" class="btn-premium px-10 py-3.5 sm:py-2.5 justify-center">
                        <i data-lucide="check" class="w-4 h-4 mr-2"></i>
                        {{ $editingClient ? (app()->getLocale() == 'en' ? 'Save Changes' : 'Simpan Perubahan') : (app()->getLocale() == 'en' ? 'Confirm Registration' : 'Konfirmasi Pendaftaran') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
