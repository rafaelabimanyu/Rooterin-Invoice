<!-- Modal 1: Manage Staff Permissions (Spatie integration) -->
<div x-show="showPermissionsModal" 
     x-transition:enter="transition ease-out duration-200" 
     x-transition:enter-start="opacity-0" 
     x-transition:enter-end="opacity-100" 
     x-transition:leave="transition ease-in duration-150" 
     x-transition:leave-start="opacity-100" 
     x-transition:leave-end="opacity-0" 
     class="fixed inset-0 z-[100] flex items-center justify-center p-4" 
     x-cloak 
     style="display: none;" 
     wire:key="modal-permissions"
>
    <!-- BACKDROP OVERLAY (Latar Hitam Transparan) -->
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="showPermissionsModal = false"></div>

    <!-- MAIN MODAL CONTAINER (Wajib Relative & Overflow Hidden) -->
    <div x-show="showPermissionsModal" 
         x-transition:enter="transition ease-out duration-200" 
         x-transition:enter-start="opacity-0 scale-95" 
         x-transition:enter-end="opacity-100 scale-100" 
         x-transition:leave="transition ease-in duration-150" 
         x-transition:leave-start="opacity-100 scale-100" 
         x-transition:leave-end="opacity-0 scale-95" 
         class="relative bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden z-10"
    >
        <!-- TOTAL OVERLAY LOADING SCREEN (Mengunci Sempurna di Dalam Box Putih) -->
        <div wire:loading wire:target="openPermissions, savePermissions" class="absolute inset-0 bg-white/90 z-50 flex flex-col items-center justify-center">
            <div class="flex flex-col items-center gap-3">
                <div class="w-10 h-10 border-4 border-gold-500 border-t-transparent rounded-full animate-spin"></div>
                <span class="text-xs font-bold text-slate-600 tracking-wide">Sinkronisasi Data...</span>
            </div>
        </div>

        <!-- Header -->
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-white">
            <div class="flex items-center gap-4">
                <div class="p-2.5 bg-gold-500 text-slate-950 rounded-xl">
                    <i data-lucide="sliders" class="w-5 h-5"></i>
                </div>
                <div>
                    <h2 class="text-lg font-black text-slate-900 uppercase tracking-tight">{{ app()->getLocale() == 'en' ? 'Manage Staff Permissions' : 'Manajemen Hak Akses Staf' }}</h2>
                    <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest">
                        {{ $selectedUser?->name }} • {{ $selectedUser?->email }}
                    </p>
                </div>
            </div>
            <button @click="showPermissionsModal = false" class="p-2 text-slate-400 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-all">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 overflow-y-auto space-y-6 flex-1 bg-white">
            <!-- Access Role Select -->
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ app()->getLocale() == 'en' ? 'Access Level / Role' : 'Tingkat Akses / Peran' }}</label>
                <select id="selectedUserRole" name="selectedUserRole" wire:model="selectedUserRole" class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-xl focus:bg-white focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 transition-all font-bold text-slate-900 uppercase text-xs tracking-widest">
                    <option value="owner">Owner</option>
                    <option value="admin">Admin</option>
                    <option value="staff">Staff</option>
                </select>
            </div>

            <!-- Spatie Permissions Grid -->
            <div class="space-y-4">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ app()->getLocale() == 'en' ? 'System Capabilities' : 'Kapabilitas Sistem (Hak Akses)' }}</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($allPermissions as $perm)
                        <label class="flex items-start gap-3 p-3 bg-slate-50 hover:bg-slate-100 border border-slate-200/60 rounded-xl cursor-pointer transition-all">
                            <input type="checkbox" id="perm-{{ $perm }}" name="perm-{{ $perm }}" wire:model="selectedUserPermissions.{{ $perm }}" class="mt-1 text-gold-600 focus:ring-gold-500 rounded border-slate-300">
                            <div class="flex flex-col">
                                <span class="text-xs font-black text-slate-900 font-mono">{{ $perm }}</span>
                                <span class="text-[10px] text-slate-400 mt-0.5 leading-relaxed">
                                    @if($perm === 'view-chronos')
                                        {{ app()->getLocale() == 'en' ? 'Access to view the Operational Chronos Calendar' : 'Akses untuk melihat Kalender Operasional Chronos' }}
                                    @elseif($perm === 'edit-chronos')
                                        {{ app()->getLocale() == 'en' ? 'Access to insert/update calendar operational tasks' : 'Akses untuk membuat/memperbarui tugas kalender' }}
                                    @elseif($perm === 'view-financial-projections')
                                        {{ app()->getLocale() == 'en' ? 'Access to analyze metrics & forecast dashboards' : 'Akses untuk melihat dasbor proyeksi & grafik keuangan' }}
                                    @elseif($perm === 'manage-users')
                                        {{ app()->getLocale() == 'en' ? 'Security access to manage team members permissions' : 'Akses keamanan untuk mengelola izin anggota tim' }}
                                    @elseif($perm === 'view-all-invoices')
                                        {{ app()->getLocale() == 'en' ? 'Unrestricted view of all invoices across clients' : 'Akses tanpa batas melihat seluruh data invoice klien' }}
                                    @elseif($perm === 'view-assigned-invoices')
                                        {{ app()->getLocale() == 'en' ? 'Restricted access only to own assigned billing duties' : 'Akses terbatas hanya melihat tugas invoice milik sendiri' }}
                                    @else
                                        System capability key: {{ $perm }}
                                    @endif
                                </span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="p-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
            <button type="button" @click="showPermissionsModal = false" class="text-xs font-bold text-slate-500 hover:text-slate-800">{{ app()->getLocale() == 'en' ? 'Cancel' : 'Batal' }}</button>
            <button type="button" wire:click="savePermissions" class="btn-premium px-6 py-2.5 rounded-lg text-xs uppercase tracking-wider font-bold">
                {{ app()->getLocale() == 'en' ? 'Deploy Permissions' : 'Terapkan Hak Akses' }}
            </button>
        </div>
    </div>
</div>
