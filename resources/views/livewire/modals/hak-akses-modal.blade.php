<!-- Modal 1: Manage Staff Permissions (Spatie integration) -->
<div x-show="showPermissionsModal" 
     class="relative z-[100]" 
     x-cloak 
     style="display: none;" 
     wire:key="modal-permissions"
>
    <!-- BACKDROP OVERLAY -->
    <div x-show="showPermissionsModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] bg-slate-950/45 backdrop-blur-sm"
    ></div>

    <!-- SCROLLABLE AREA -->
    <div x-show="showPermissionsModal" 
         class="fixed inset-0 z-[101] overflow-y-auto"
    >
        <!-- ALIGNMENT CONTAINER -->
        <div class="flex min-h-full justify-center py-10 px-4 sm:px-6" @click.self="showPermissionsModal = false">
            <!-- MAIN MODAL CONTAINER -->
            <div x-show="showPermissionsModal" 
                 x-transition:enter="transition ease-out duration-300" 
                 x-transition:enter-start="opacity-0 scale-95 -translate-y-4" 
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
                 x-transition:leave="transition ease-in duration-200" 
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0" 
                 x-transition:leave-end="opacity-0 scale-95 -translate-y-4" 
                 class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl h-fit transform overflow-hidden transition-all text-left"
            >
                <!-- Header -->
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-white">
                    <div class="flex items-center gap-4">
                        <div class="p-2.5 bg-gold-500 text-slate-950 rounded-xl">
                            <i data-lucide="sliders" class="w-5 h-5"></i>
                        </div>
                        <!-- Shimmer Header Text during Loading -->
                        <div wire:loading wire:target="openPermissions" class="space-y-2 animate-pulse">
                            <div class="h-4 w-48 bg-slate-200 rounded"></div>
                            <div class="h-3 w-32 bg-slate-100 rounded"></div>
                        </div>
                        <!-- Real Header Text -->
                        <div wire:loading.remove wire:target="openPermissions">
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
                <div class="p-6 space-y-6 bg-white">
                    <!-- Skeleton Shimmer Loader -->
                    <div wire:loading wire:target="openPermissions" class="space-y-6 animate-pulse">
                        <!-- Skeleton Role Select -->
                        <div class="space-y-2">
                            <div class="h-3 w-28 bg-slate-200 rounded"></div>
                            <div class="h-11 w-full bg-slate-100 rounded-xl"></div>
                        </div>
                        <!-- Skeleton Spatie Grid -->
                        <div class="space-y-4">
                            <div class="h-3 w-40 bg-slate-200 rounded"></div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @for($i=0; $i<6; $i++)
                                    <div class="p-3.5 bg-slate-50 border border-slate-200/50 rounded-xl flex items-start gap-3">
                                        <div class="w-4 h-4 bg-slate-200 rounded shrink-0 mt-0.5"></div>
                                        <div class="flex-1 space-y-2">
                                            <div class="h-3 w-24 bg-slate-200 rounded"></div>
                                            <div class="h-2.5 w-full bg-slate-100 rounded"></div>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <!-- Actual Content -->
                    <div wire:loading.remove wire:target="openPermissions" class="space-y-6">
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
                </div>

                <!-- Footer -->
                <div class="p-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                    <button type="button" @click="showPermissionsModal = false" class="text-xs font-bold text-slate-500 hover:text-slate-800" wire:loading.attr="disabled" wire:target="savePermissions">{{ app()->getLocale() == 'en' ? 'Cancel' : 'Batal' }}</button>
                    <button type="button" wire:click="savePermissions" class="btn-premium px-6 py-2.5 rounded-lg text-xs uppercase tracking-wider font-bold" wire:loading.attr="disabled" wire:target="savePermissions">
                        <span wire:loading.remove wire:target="savePermissions">{{ app()->getLocale() == 'en' ? 'Deploy Permissions' : 'Terapkan Hak Akses' }}</span>
                        <span wire:loading wire:target="savePermissions" class="flex items-center gap-1.5 justify-center">
                            <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>{{ app()->getLocale() == 'en' ? 'Deploying...' : 'Menerapkan...' }}</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
