<div x-data="{ 
    showPermissionsModal: @entangle('showPermissionsModal'),
    showSuspendModal: @entangle('showSuspendModal'),
    showEditModal: @entangle('showEditModal') 
}">
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6 page-fade-in">
        <div>
            <div class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">
                <span>{{ app()->getLocale() == 'en' ? 'Administration' : 'Administrasi' }}</span>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-indigo-600">{{ app()->getLocale() == 'en' ? 'Team Control Center' : 'Pusat Kontrol Tim' }}</span>
            </div>
            <h1 class="text-3xl font-black text-slate-900 font-jakarta tracking-tight uppercase">{{ __('ui.users') ?? (app()->getLocale() == 'en' ? 'Team Management' : 'Manajemen Tim') }}</h1>
            <p class="text-sm text-slate-500 font-medium mt-1">{{ app()->getLocale() == 'en' ? 'Manage operatives, security clearances, and operational status.' : 'Kelola staf pelaksana, izin keamanan, dan status operasional.' }}</p>
        </div>
        <div class="flex items-center gap-3">
            <!-- Search field -->
            <div class="relative w-64">
                <input type="text" wire:model.live="search" placeholder="{{ app()->getLocale() == 'en' ? 'Search operative...' : 'Cari pelaksana...' }}" class="w-full pl-10 pr-4 py-2 bg-white rounded-xl border border-slate-200 shadow-sm text-xs font-bold text-slate-700 outline-none focus:ring-2 focus:ring-indigo-500/10 transition-all">
                <div class="absolute left-3 top-2.5 text-slate-400">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="px-4 py-2 bg-white rounded-xl border border-slate-200 shadow-sm flex items-center gap-3 shrink-0">
                <span class="flex h-2 w-2 rounded-full bg-emerald-500"></span>
                <span class="text-xs font-bold text-slate-600">{{ $users->count() }} {{ app()->getLocale() == 'en' ? 'Total Operatives' : 'Total Staf' }}</span>
            </div>
            <a href="{{ route('users.create') }}" class="btn-premium group shrink-0">
                <i data-lucide="user-plus" class="w-4 h-4 transition-transform group-hover:rotate-12"></i>
                <span>{{ app()->getLocale() == 'en' ? 'Add New Operative' : 'Tambah Pelaksana' }}</span>
            </a>
        </div>
    </div>

    <!-- User Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($users as $user)
            <div wire:key="user-card-{{ $user->id }}" class="glass-card p-8 group hover:-translate-y-2 hover:shadow-[0_30px_60px_rgba(0,0,0,0.12)] transition-all duration-500 relative overflow-hidden page-fade-in stagger-{{ $loop->iteration % 5 }} {{ !$user->is_active ? 'opacity-75 bg-slate-100/50' : '' }}">
                <!-- Status Indicator -->
                <div class="absolute top-0 right-0 p-4">
                    <div class="flex flex-col items-end gap-1.5">
                        <div class="flex items-center gap-2 px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ $user->is_active ? 'bg-indigo-50 text-indigo-600 border border-indigo-100' : 'bg-rose-50 text-rose-600 border border-rose-100' }}">
                            {{ $user->is_active ? (app()->getLocale() == 'en' ? 'Authorized' : 'Diizinkan') : (app()->getLocale() == 'en' ? 'Suspended' : 'Ditangguhkan') }}
                        </div>
                        @if($user->isOnline())
                            <div class="flex items-center gap-1.5 px-2.5 py-1 bg-emerald-500 text-white rounded-full text-[8px] font-black uppercase tracking-widest shadow-[0_0_10px_rgba(16,185,129,0.4)]">
                                <span class="w-1.5 h-1.5 bg-white rounded-full animate-ping"></span>
                                Online
                            </div>
                        @else
                            <div class="flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 text-slate-400 rounded-full text-[8px] font-black uppercase tracking-widest">
                                <span class="w-1.5 h-1.5 bg-slate-300 rounded-full"></span>
                                Offline
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Role Accent -->
                <div class="absolute top-0 left-0 w-1.5 h-full {{ $user->role === 'owner' ? 'bg-indigo-500' : ($user->role === 'admin' ? 'bg-emerald-500' : 'bg-slate-300') }}"></div>
                
                <div class="flex items-start justify-between mb-8">
                    <div class="flex items-center gap-5">
                        <div class="relative">
                            <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-2xl object-cover shadow-xl shadow-slate-900/10 group-hover:scale-110 group-hover:rotate-3 transition-all duration-500 border-2 border-white">
                            @if($user->last_login_at && $user->last_login_at->isToday())
                                <div class="absolute -top-1 -right-1 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full shadow-lg"></div>
                            @endif
                        </div>
                        <div class="flex flex-col">
                            <div class="flex items-center gap-2">
                                <h3 class="text-lg font-black text-slate-900 tracking-tight group-hover:text-indigo-600 transition-colors">{{ $user->name }}</h3>
                                @if($user->two_factor_secret)
                                    <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-500" title="2FA Enabled"></i>
                                @endif
                            </div>
                            <p class="text-[11px] text-slate-400 font-bold uppercase tracking-tight">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Stats Row -->
                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="p-4 bg-slate-50/50 rounded-2xl border border-slate-100 group-hover:bg-white group-hover:border-indigo-100 transition-all duration-500">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ app()->getLocale() == 'en' ? 'Productivity' : 'Produktivitas' }}</p>
                        <div class="flex items-center gap-2">
                            <i data-lucide="file-text" class="w-3.5 h-3.5 text-indigo-500"></i>
                            <span class="text-sm font-black text-slate-900">{{ $user->invoices_count }} {{ app()->getLocale() == 'en' ? 'Invoices' : 'Faktur' }}</span>
                        </div>
                    </div>
                    <div class="p-4 bg-slate-50/50 rounded-2xl border border-slate-100 group-hover:bg-white group-hover:border-emerald-100 transition-all duration-500">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ app()->getLocale() == 'en' ? 'Status' : 'Status' }}</p>
                        <div class="flex items-center gap-2">
                            <i data-lucide="clock" class="w-3.5 h-3.5 {{ $user->isOnline() ? 'text-emerald-500' : 'text-slate-400' }}"></i>
                            <span class="text-[11px] font-black text-slate-900">{{ $user->isOnline() ? (app()->getLocale() == 'en' ? 'Active Now' : 'Aktif Sekarang') : ($user->last_seen ? $user->last_seen->diffForHumans() : (app()->getLocale() == 'en' ? 'Never' : 'Tidak Pernah')) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Footer / Actions -->
                <div class="flex items-center justify-between pt-6 border-t border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black border uppercase tracking-wider
                            {{ $user->role === 'owner' ? 'bg-indigo-50 text-indigo-700 border-indigo-100' : 
                               ($user->role === 'admin' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-slate-50 text-slate-700 border-slate-100') }}">
                            {{ $user->role }}
                        </span>
                    </div>
                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-all duration-300">
                        <!-- Action 1: Sliders (Permissions settings) -->
                        <button 
                            wire:click="openPermissions({{ $user->id }})"
                            @click="showPermissionsModal = true"
                            class="p-2.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all relative flex items-center justify-center"
                            title="{{ app()->getLocale() == 'en' ? 'Atur Hak Akses Staf' : 'Atur Hak Akses Staf' }}"
                            wire:loading.class="opacity-50 pointer-events-none"
                            wire:target="openPermissions({{ $user->id }})"
                        >
                            <i data-lucide="sliders" class="w-5 h-5" wire:loading.remove wire:target="openPermissions({{ $user->id }})"></i>
                            <svg class="animate-spin w-5 h-5 text-indigo-600 hidden" wire:loading.class="!block" wire:target="openPermissions({{ $user->id }})" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>

                        <!-- Action 2: Settings (Profile settings) -->
                        <button 
                            wire:click="openEditModal({{ $user->id }})"
                            @click="showEditModal = true"
                            class="p-2.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all relative flex items-center justify-center"
                            title="{{ app()->getLocale() == 'en' ? 'Edit Profil & Kredensial' : 'Edit Profil & Kredensial' }}"
                            wire:loading.class="opacity-50 pointer-events-none"
                            wire:target="openEditModal({{ $user->id }})"
                        >
                            <i data-lucide="settings-2" class="w-5 h-5" wire:loading.remove wire:target="openEditModal({{ $user->id }})"></i>
                            <svg class="animate-spin w-5 h-5 text-indigo-600 hidden" wire:loading.class="!block" wire:target="openEditModal({{ $user->id }})" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>

                        <!-- Action 3: Shield Off (Suspend/Deactivate) -->
                        @if($user->id !== auth()->id())
                            <button 
                                wire:click="confirmSuspend({{ $user->id }})"
                                @click="showSuspendModal = true"
                                class="p-2.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all relative flex items-center justify-center"
                                title="{{ app()->getLocale() == 'en' ? 'Nonaktifkan Akses Staf' : 'Nonaktifkan Akses Staf' }}"
                                wire:loading.class="opacity-50 pointer-events-none"
                                wire:target="confirmSuspend({{ $user->id }})"
                            >
                                <i data-lucide="shield-off" class="w-5 h-5" wire:loading.remove wire:target="confirmSuspend({{ $user->id }})"></i>
                                <svg class="animate-spin w-5 h-5 text-rose-600 hidden" wire:loading.class="!block" wire:target="confirmSuspend({{ $user->id }})" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal 1: Manage Staff Permissions (Spatie integration) -->
    <div x-show="showPermissionsModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak style="display: none;" wire:key="modal-permissions">
        <div x-show="showPermissionsModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showPermissionsModal = false"></div>
        
        <div x-show="showPermissionsModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto z-10 p-6">
            <!-- Header -->
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="p-2.5 bg-indigo-600 text-white rounded-xl">
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
            <div class="py-6 space-y-6">
                <!-- Access Role Select -->
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ app()->getLocale() == 'en' ? 'Access Level / Role' : 'Tingkat Akses / Peran' }}</label>
                    <select wire:model="selectedUserRole" class="w-full px-4 py-3 bg-slate-50 border-transparent rounded-xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-900 uppercase text-xs tracking-widest">
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
                                <input type="checkbox" wire:model="selectedUserPermissions.{{ $perm }}" class="mt-1 text-indigo-600 focus:ring-indigo-500 rounded border-slate-300">
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
            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <button type="button" @click="showPermissionsModal = false" class="text-xs font-bold text-slate-500 hover:text-slate-800">{{ app()->getLocale() == 'en' ? 'Cancel' : 'Batal' }}</button>
                <button type="button" wire:click="savePermissions" class="btn-premium px-6 py-2.5 rounded-lg text-xs uppercase tracking-wider font-bold">
                    {{ app()->getLocale() == 'en' ? 'Deploy Permissions' : 'Terapkan Hak Akses' }}
                </button>
            </div>

            <!-- Loading Overlay when opening permissions -->
            <div wire:loading wire:target="openPermissions" class="absolute inset-0 bg-white/70 flex items-center justify-center z-50">
                <div class="flex flex-col items-center gap-3">
                    <svg class="animate-spin w-10 h-10 text-indigo-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs font-black text-slate-500 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Loading Permissions...' : 'Memuat Hak Akses...' }}</span>
                </div>
            </div>

            <!-- Saving Overlay -->
            <div class="absolute inset-0 bg-white/60 backdrop-blur-[1px] flex items-center justify-center z-50" wire:loading wire:target="savePermissions">
                <div class="flex flex-col items-center gap-3">
                    <svg class="animate-spin w-10 h-10 text-indigo-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs font-black text-slate-500 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Updating Permissions...' : 'Memperbarui Hak Akses...' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal 2: Suspend/Restore Confirmation Modal -->
    <div x-show="showSuspendModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak style="display: none;" wire:key="modal-suspend">
        <div x-show="showSuspendModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showSuspendModal = false"></div>
        
        <div x-show="showSuspendModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto z-10 p-6">
            <div class="text-center space-y-4">
                <div class="w-12 h-12 rounded-2xl mx-auto flex items-center justify-center border-2 {{ $userToSuspend?->is_active ? 'bg-rose-50 border-rose-200 text-rose-600' : 'bg-emerald-50 border-emerald-200 text-emerald-600' }}">
                    <i data-lucide="{{ $userToSuspend?->is_active ? 'shield-off' : 'shield' }}" class="w-6 h-6"></i>
                </div>
                
                <div class="space-y-1">
                    <h3 class="text-base font-black text-slate-900 uppercase tracking-tight">
                        {{ $userToSuspend?->is_active ? (app()->getLocale() == 'en' ? 'Suspend Access?' : 'Nonaktifkan Akses?') : (app()->getLocale() == 'en' ? 'Restore Access?' : 'Pulihkan Akses?') }}
                    </h3>
                    <p class="text-xs text-slate-500 font-medium leading-relaxed">
                        @if($userToSuspend?->is_active)
                            {{ app()->getLocale() == 'en' ? 'Are you sure you want to suspend security clearance for ' : 'Apakah Anda yakin ingin menonaktifkan izin masuk untuk ' }}
                            <strong>{{ $userToSuspend?->name }}</strong>?
                            {{ app()->getLocale() == 'en' ? 'They will be locked out of the Rooterin system immediately.' : 'Staf ini akan langsung terblokir dan tidak dapat login ke sistem Rooterin.' }}
                        @else
                            {{ app()->getLocale() == 'en' ? 'Are you sure you want to restore access clearance for ' : 'Apakah Anda yakin ingin memulihkan kembali izin masuk untuk ' }}
                            <strong>{{ $userToSuspend?->name }}</strong>?
                            {{ app()->getLocale() == 'en' ? 'They will be allowed to log back in.' : 'Staf ini akan diizinkan kembali untuk masuk ke sistem.' }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-center gap-3">
                <button type="button" @click="showSuspendModal = false" class="flex-1 py-2.5 text-xs font-bold text-slate-500 hover:text-slate-800 bg-white border border-slate-200 rounded-lg transition-all">{{ app()->getLocale() == 'en' ? 'Cancel' : 'Batal' }}</button>
                <button type="button" wire:click="toggleSuspend" class="flex-1 py-2.5 text-xs font-black uppercase tracking-wider text-white rounded-lg shadow-lg transition-all flex items-center justify-center gap-2 {{ $userToSuspend?->is_active ? 'bg-rose-600 hover:bg-rose-500 shadow-rose-600/10' : 'bg-emerald-600 hover:bg-emerald-500 shadow-emerald-600/10' }}">
                    {{ $userToSuspend?->is_active ? (app()->getLocale() == 'en' ? 'Suspend Account' : 'Nonaktifkan Akses') : (app()->getLocale() == 'en' ? 'Restore Account' : 'Pulihkan Akses') }}
                </button>
            </div>

            <!-- Loading Overlay when confirming suspend -->
            <div wire:loading wire:target="confirmSuspend" class="absolute inset-0 bg-white/70 flex items-center justify-center z-50">
                <div class="flex flex-col items-center gap-3">
                    <svg class="animate-spin w-10 h-10 text-rose-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs font-black text-slate-500 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Loading Clearance...' : 'Memuat Status...' }}</span>
                </div>
            </div>

            <!-- Processing Overlay -->
            <div class="absolute inset-0 bg-white/60 backdrop-blur-[1px] flex items-center justify-center z-50" wire:loading wire:target="toggleSuspend">
                <div class="flex flex-col items-center gap-3">
                    <svg class="animate-spin w-10 h-10 text-rose-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs font-black text-slate-500 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Synchronizing Clearance...' : 'Menyelaraskan Status...' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal 3: Advanced Profile / Credentials Management Modal -->
    <div x-show="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak style="display: none;" wire:key="modal-edit">
        <div x-show="showEditModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showEditModal = false"></div>
        
        <div x-show="showEditModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto z-10 p-6">
            <!-- Header -->
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                <div class="flex items-center gap-4">
                    <div class="p-2.5 bg-slate-900 text-white rounded-xl">
                        <i data-lucide="shield" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-black text-slate-900 uppercase tracking-tight">{{ app()->getLocale() == 'en' ? 'Security & Command Center' : 'Pusat Keamanan & Kontrol' }}</h2>
                        <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest">
                            ID: {{ $editingUser?->id }} • 
                            Status: <span class="{{ $editingIsActive ? 'text-emerald-600' : 'text-rose-600' }}">{{ $editingIsActive ? (app()->getLocale() == 'en' ? 'Authorized' : 'Diizinkan') : (app()->getLocale() == 'en' ? 'Suspended' : 'Ditangguhkan') }}</span> • 
                            Presence: <span class="{{ $editingUser?->isOnline() ? 'text-emerald-500' : 'text-slate-400' }}">{{ $editingUser?->isOnline() ? 'Online' : 'Offline' }}</span>
                        </p>
                    </div>
                </div>
                <button @click="showEditModal = false" class="p-2 text-slate-400 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-all">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Two Column Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column: Settings -->
                <div class="space-y-6">
                    <div class="flex items-center gap-4">
                        <img src="{{ $editingUser?->profile_photo_url }}" class="w-16 h-16 rounded-2xl object-cover shadow-lg border-2 border-slate-100">
                        <div>
                            <h3 class="text-base font-black text-slate-900">{{ $editingName }}</h3>
                            <p class="text-xs text-slate-500 font-medium">{{ $editingEmail }}</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ app()->getLocale() == 'en' ? 'Full Identity' : 'Identitas Lengkap' }}</label>
                            <input type="text" wire:model="editingName" class="w-full px-4 py-2.5 bg-slate-50 border-transparent rounded-xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-900 text-xs">
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ app()->getLocale() == 'en' ? 'Email Address' : 'Alamat Email' }}</label>
                            <input type="email" wire:model="editingEmail" class="w-full px-4 py-2.5 bg-slate-50 border-transparent rounded-xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-900 text-xs">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ app()->getLocale() == 'en' ? 'Access Level' : 'Tingkat Akses' }}</label>
                                <select wire:model="editingRole" class="w-full px-4 py-2.5 bg-slate-50 border-transparent rounded-xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-900 uppercase text-xs tracking-widest">
                                    <option value="owner">Owner</option>
                                    <option value="admin">Admin</option>
                                    <option value="staff">Staff</option>
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ app()->getLocale() == 'en' ? 'Account Status' : 'Status Akun' }}</label>
                                <div class="flex items-center h-full">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" wire:model="editingIsActive" class="sr-only peer">
                                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                        <span class="ms-2 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                                            {{ $editingIsActive ? (app()->getLocale() == 'en' ? 'Active' : 'Aktif') : (app()->getLocale() == 'en' ? 'Suspended' : 'Ditangguhkan') }}
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Password Center -->
                    <div class="p-6 bg-slate-900 rounded-2xl text-white space-y-4 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-white/5 rounded-full -mr-12 -mt-12 blur-xl"></div>
                        <div>
                            <h4 class="text-xs font-black uppercase tracking-[0.2em] text-indigo-400 mb-0.5">{{ app()->getLocale() == 'en' ? 'Overrule Password' : 'Ganti Kata Sandi' }}</h4>
                            <p class="text-[10px] text-slate-400 font-medium">{{ app()->getLocale() == 'en' ? "Reset this operative's credentials manually." : 'Reset kredensial pelaksana ini secara manual.' }}</p>
                        </div>
                        <div class="relative">
                            <input :type="$wire.showPassword ? 'text' : 'password'" wire:model="editingPassword" class="w-full bg-white/5 border-white/10 rounded-xl py-2 px-4 text-xs font-mono tracking-wider focus:border-indigo-500 focus:ring-0 transition-all text-white" placeholder="{{ app()->getLocale() == 'en' ? 'Enter new password...' : 'Masukkan kata sandi baru...' }}">
                            <button type="button" wire:click="$toggle('showPassword')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-white">
                                @if($showPassword)
                                    <i data-lucide="eye-off" class="w-3.5 h-3.5"></i>
                                @else
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                @endif
                            </button>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" wire:click="generatePassword()" class="flex-1 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-500 rounded-lg text-[9px] font-black uppercase tracking-[0.2em] transition-all flex items-center justify-center gap-1.5 text-white">
                                <i data-lucide="sparkles" class="w-3 h-3"></i>
                                {{ app()->getLocale() == 'en' ? 'Generate' : 'Buat' }}
                            </button>
                            @if($editingPassword)
                                <button type="button" @click="navigator.clipboard.writeText($wire.editingPassword); $wire.copyPassword()" class="px-3 py-1.5 bg-white/10 hover:bg-white/20 rounded-lg text-[9px] font-black uppercase tracking-[0.2em] transition-all flex items-center gap-1.5 text-indigo-300">
                                    <i data-lucide="{{ $copied ? 'check' : 'copy' }}" class="w-3 h-3 {{ $copied ? 'text-emerald-400' : '' }}"></i>
                                    <span>{{ $copied ? (app()->getLocale() == 'en' ? 'Copied' : 'Disalin') : (app()->getLocale() == 'en' ? 'Copy' : 'Salin') }}</span>
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Archive Option -->
                    @if($editingUser?->id !== auth()->id())
                        <div class="pt-4 border-t border-slate-100 flex justify-between items-center">
                            <div class="space-y-0.5">
                                <h4 class="text-xs font-black text-rose-600 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Purge Record' : 'Hapus Akun' }}</h4>
                                <p class="text-[9px] text-slate-400 font-medium">{{ app()->getLocale() == 'en' ? 'Permanently remove this team member.' : 'Hapus data pelaksana ini secara permanen.' }}</p>
                            </div>
                            <button type="button" wire:confirm="{{ app()->getLocale() == 'en' ? 'Deep purge this operative data?' : 'Hapus permanen data pelaksana ini?' }}" wire:click="deleteUser({{ $editingUser?->id ?? 0 }})" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-xs font-bold transition-all">
                                {{ app()->getLocale() == 'en' ? 'Delete Operative' : 'Hapus Pelaksana' }}
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Right Column: Monitoring & Timeline -->
                <div class="space-y-6 flex flex-col justify-between">
                    <div>
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-4">{{ app()->getLocale() == 'en' ? 'Operative Intelligence' : 'Informasi Pelaksana' }}</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 shadow-sm">
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5">{{ app()->getLocale() == 'en' ? 'Last Sync' : 'Sinkronisasi Terakhir' }}</p>
                                <p class="text-xs font-black text-slate-900">{{ $editingUser?->last_seen ? $editingUser->last_seen->format('M d, H:i') : (app()->getLocale() == 'en' ? 'Never' : 'Tidak Pernah') }}</p>
                                <p class="text-[9px] text-slate-400 font-bold">IP: {{ $editingUser?->last_login_ip ?? 'N/A' }}</p>
                            </div>
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 shadow-sm">
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5">{{ app()->getLocale() == 'en' ? 'Pass Age' : 'Umur Sandi' }}</p>
                                <p class="text-xs font-black text-slate-900">{{ $editingUser?->last_password_change_at ? $editingUser->last_password_change_at->diffForHumans() : (app()->getLocale() == 'en' ? 'Never' : 'Tidak Pernah') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex-1 flex flex-col min-h-0">
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-4">{{ app()->getLocale() == 'en' ? 'Activity Timeline' : 'Lini Masa Aktivitas' }}</h4>
                        <!-- Scrollable Timeline -->
                        <div class="overflow-y-auto max-h-[400px] pr-2 space-y-4 custom-scrollbar">
                            @forelse($selectedUserLogs as $log)
                                <div class="flex gap-3 relative">
                                    <div class="shrink-0 w-7 h-7 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center relative z-10">
                                        <i data-lucide="zap" class="w-3.5 h-3.5"></i>
                                    </div>
                                    <div class="flex-1 pt-0.5">
                                        <p class="text-xs font-bold text-slate-800 leading-tight">{{ $log['desc'] }}</p>
                                        <p class="text-[9px] text-slate-400 font-black uppercase tracking-widest mt-1">{{ $log['time'] }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'No recent activities recorded' : 'Tidak ada aktivitas tercatat baru-baru ini' }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        <button type="button" wire:click="saveEdit" class="w-full btn-premium py-3 rounded-xl text-white flex items-center justify-center" wire:loading.attr="disabled" wire:target="saveEdit">
                            <span wire:loading.remove wire:target="saveEdit" class="flex items-center justify-center">
                                <span class="text-xs font-black uppercase tracking-[0.2em]">{{ app()->getLocale() == 'en' ? 'Deploy Changes' : 'Terapkan Perubahan' }}</span>
                                <i data-lucide="send" class="w-4 h-4 ml-1.5"></i>
                            </span>
                            <span wire:loading wire:target="saveEdit" class="flex items-center gap-1.5">
                                <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ app()->getLocale() == 'en' ? 'Saving Changes...' : 'Menyimpan Perubahan...' }}
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Loading Overlay when opening profile edit -->
            <div wire:loading wire:target="openEditModal" class="absolute inset-0 bg-white/70 flex items-center justify-center z-50">
                <div class="flex flex-col items-center gap-3">
                    <svg class="animate-spin w-10 h-10 text-indigo-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs font-black text-slate-500 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Loading Operative...' : 'Memuat Data Pelaksana...' }}</span>
                </div>
            </div>

            <!-- Saving Profile Overlay -->
            <div class="absolute inset-0 bg-white/60 backdrop-blur-[1px] flex items-center justify-center z-50" wire:loading wire:target="saveEdit, generatePassword">
                <div class="flex flex-col items-center gap-3">
                    <svg class="animate-spin w-10 h-10 text-indigo-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs font-black text-slate-500 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Updating Operative...' : 'Memperbarui Data Pelaksana...' }}</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('notify', () => {
            setTimeout(() => {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }, 50);
        });
        window.addEventListener('refreshLucide', () => {
            setTimeout(() => {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }, 50);
        });
    </script>
</div>
