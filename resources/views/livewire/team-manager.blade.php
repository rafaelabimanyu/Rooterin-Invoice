<div>
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
            <div class="glass-card p-8 group hover:-translate-y-2 hover:shadow-[0_30px_60px_rgba(0,0,0,0.12)] transition-all duration-500 relative overflow-hidden page-fade-in stagger-{{ $loop->iteration % 5 }} {{ !$user->is_active ? 'opacity-75 bg-slate-100/50' : '' }}">
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
                            class="p-2.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all"
                            title="{{ app()->getLocale() == 'en' ? 'Atur Hak Akses Staf' : 'Atur Hak Akses Staf' }}"
                        >
                            <i data-lucide="sliders" class="w-5 h-5"></i>
                        </button>

                        <!-- Action 2: Settings (Profile settings) -->
                        <button 
                            wire:click="openEditModal({{ $user->id }})"
                            class="p-2.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all"
                            title="{{ app()->getLocale() == 'en' ? 'Edit Profil & Kredensial' : 'Edit Profil & Kredensial' }}"
                        >
                            <i data-lucide="settings-2" class="w-5 h-5"></i>
                        </button>

                        <!-- Action 3: Shield Off (Suspend/Deactivate) -->
                        @if($user->id !== auth()->id())
                            <button 
                                wire:click="confirmSuspend({{ $user->id }})"
                                class="p-2.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all"
                                title="{{ app()->getLocale() == 'en' ? 'Nonaktifkan Akses Staf' : 'Nonaktifkan Akses Staf' }}"
                            >
                                <i data-lucide="shield-off" class="w-5 h-5"></i>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal 1: Manage Staff Permissions (Spatie integration) -->
    @if($showPermissionsModal && $selectedUser)
        <div class="fixed inset-0 z-[110] flex items-center justify-center p-6">
            <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-xl transition-opacity duration-300" wire:click="$set('showPermissionsModal', false)"></div>

            <div class="relative w-full max-w-2xl bg-white rounded-[40px] shadow-2xl overflow-hidden border border-slate-200 transition-all duration-300 transform">
                <div class="px-10 py-8 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-indigo-600 text-white rounded-2xl">
                            <i data-lucide="sliders" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight">{{ app()->getLocale() == 'en' ? 'Manage Staff Permissions' : 'Manajemen Hak Akses Staf' }}</h2>
                            <p class="text-xs text-slate-500 font-bold uppercase tracking-widest">
                                {{ $selectedUser->name }} • {{ $selectedUser->email }}
                            </p>
                        </div>
                    </div>
                    <button wire:click="$set('showPermissionsModal', false)" class="p-3 text-slate-400 hover:text-slate-900 hover:bg-slate-100 rounded-2xl transition-all">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>

                <div class="p-10 space-y-8">
                    <!-- Access Role Select -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ app()->getLocale() == 'en' ? 'Access Level / Role' : 'Tingkat Akses / Peran' }}</label>
                        <select wire:model="selectedUserRole" class="w-full px-5 py-3.5 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-900 uppercase text-xs tracking-widest">
                            <option value="owner">Owner</option>
                            <option value="admin">Admin</option>
                            <option value="staff">Staff</option>
                        </select>
                    </div>

                    <!-- Spatie Permissions Grid -->
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ app()->getLocale() == 'en' ? 'System Capabilities' : 'Kapabilitas Sistem (Hak Akses)' }}</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($allPermissions as $perm)
                                <label class="flex items-start gap-3 p-4 bg-slate-55 hover:bg-slate-100 border border-slate-200/60 rounded-2xl cursor-pointer transition-all">
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

                <div class="px-10 py-6 bg-slate-50/50 border-t border-slate-100 flex justify-end gap-3">
                    <button type="button" wire:click="$set('showPermissionsModal', false)" class="text-xs font-bold text-slate-500 hover:text-slate-800">{{ app()->getLocale() == 'en' ? 'Cancel' : 'Batal' }}</button>
                    <button type="button" wire:click="savePermissions" class="btn-premium px-8 py-3 rounded-xl text-xs uppercase tracking-wider font-bold">
                        {{ app()->getLocale() == 'en' ? 'Deploy Permissions' : 'Terapkan Hak Akses' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal 2: Suspend/Restore Confirmation Modal -->
    @if($showSuspendModal && $userToSuspend)
        <div class="fixed inset-0 z-[110] flex items-center justify-center p-6">
            <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-xl transition-opacity duration-300" wire:click="$set('showSuspendModal', false)"></div>

            <div class="relative w-full max-w-md bg-white rounded-[32px] shadow-2xl overflow-hidden border border-slate-200 transition-all duration-300 transform">
                <div class="p-8 text-center space-y-6">
                    <div class="w-16 h-16 rounded-3xl mx-auto flex items-center justify-center border-2 {{ $userToSuspend->is_active ? 'bg-rose-50 border-rose-200 text-rose-600' : 'bg-emerald-50 border-emerald-200 text-emerald-600' }}">
                        <i data-lucide="{{ $userToSuspend->is_active ? 'shield-off' : 'shield' }}" class="w-8 h-8"></i>
                    </div>
                    
                    <div class="space-y-2">
                        <h3 class="text-lg font-black text-slate-900 uppercase tracking-tight">
                            {{ $userToSuspend->is_active ? (app()->getLocale() == 'en' ? 'Suspend Access?' : 'Nonaktifkan Akses?') : (app()->getLocale() == 'en' ? 'Restore Access?' : 'Pulihkan Akses?') }}
                        </h3>
                        <p class="text-xs text-slate-500 font-medium leading-relaxed">
                            @if($userToSuspend->is_active)
                                {{ app()->getLocale() == 'en' ? 'Are you sure you want to suspend security clearance for ' : 'Apakah Anda yakin ingin menonaktifkan izin masuk untuk ' }}
                                <strong>{{ $userToSuspend->name }}</strong>?
                                {{ app()->getLocale() == 'en' ? 'They will be locked out of the Rooterin system immediately.' : 'Staf ini akan langsung terblokir dan tidak dapat login ke sistem Rooterin.' }}
                            @else
                                {{ app()->getLocale() == 'en' ? 'Are you sure you want to restore access clearance for ' : 'Apakah Anda yakin ingin memulihkan kembali izin masuk untuk ' }}
                                <strong>{{ $userToSuspend->name }}</strong>?
                                {{ app()->getLocale() == 'en' ? 'They will be allowed to log back in.' : 'Staf ini akan diizinkan kembali untuk masuk ke sistem.' }}
                            @endif
                        </p>
                    </div>
                </div>

                <div class="px-8 py-5 bg-slate-50/50 border-t border-slate-100 flex items-center justify-center gap-3">
                    <button type="button" wire:click="$set('showSuspendModal', false)" class="flex-1 py-3 text-xs font-bold text-slate-500 hover:text-slate-800 bg-white border border-slate-200 rounded-xl transition-all">{{ app()->getLocale() == 'en' ? 'Cancel' : 'Batal' }}</button>
                    <button type="button" wire:click="toggleSuspend" class="flex-1 py-3 text-xs font-black uppercase tracking-wider text-white rounded-xl shadow-lg transition-all {{ $userToSuspend->is_active ? 'bg-rose-600 hover:bg-rose-500 shadow-rose-600/10' : 'bg-emerald-600 hover:bg-emerald-500 shadow-emerald-600/10' }}">
                        {{ $userToSuspend->is_active ? (app()->getLocale() == 'en' ? 'Suspend Account' : 'Nonaktifkan Akses') : (app()->getLocale() == 'en' ? 'Restore Account' : 'Pulihkan Akses') }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal 3: Advanced Profile / Credentials Management Modal -->
    @if($showEditModal && $editingUser)
        <div class="fixed inset-0 z-[110] flex items-center justify-center p-6">
            <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-xl transition-opacity duration-300" wire:click="$set('showEditModal', false)"></div>

            <div class="relative w-full max-w-4xl bg-white rounded-[40px] shadow-2xl overflow-hidden border border-slate-200 transition-all duration-300 transform">
                <div class="px-10 py-8 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-slate-900 text-white rounded-2xl">
                            <i data-lucide="shield" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight">{{ app()->getLocale() == 'en' ? 'Security & Command Center' : 'Pusat Keamanan & Kontrol' }}</h2>
                            <p class="text-xs text-slate-500 font-bold uppercase tracking-widest">
                                ID: {{ $editingUser->id }} • 
                                Status: <span class="{{ $editingIsActive ? 'text-emerald-600' : 'text-rose-600' }}">{{ $editingIsActive ? (app()->getLocale() == 'en' ? 'Authorized' : 'Diizinkan') : (app()->getLocale() == 'en' ? 'Suspended' : 'Ditangguhkan') }}</span> • 
                                Presence: <span class="{{ $editingUser->isOnline() ? 'text-emerald-500' : 'text-slate-400' }}">{{ $editingUser->isOnline() ? 'Online' : 'Offline' }}</span>
                            </p>
                        </div>
                    </div>
                    <button wire:click="$set('showEditModal', false)" class="p-3 text-slate-400 hover:text-slate-900 hover:bg-slate-100 rounded-2xl transition-all">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>

                <div class="flex flex-col lg:flex-row h-[600px] overflow-hidden">
                    <!-- Left Column: Settings -->
                    <div class="lg:w-1/2 p-10 space-y-8 overflow-y-auto border-r border-slate-100 custom-scrollbar">
                        <div class="flex items-center gap-6 mb-10">
                            <img src="{{ $editingUser->profile_photo_url }}" class="w-20 h-20 rounded-[28px] object-cover shadow-lg border-4 border-white ring-1 ring-slate-100">
                            <div>
                                <h3 class="text-lg font-black text-slate-900">{{ $editingName }}</h3>
                                <p class="text-sm text-slate-500 font-medium">{{ $editingEmail }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ app()->getLocale() == 'en' ? 'Full Identity' : 'Identitas Lengkap' }}</label>
                                <input type="text" wire:model="editingName" class="w-full px-5 py-3.5 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-900 text-sm">
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ app()->getLocale() == 'en' ? 'Email Address' : 'Alamat Email' }}</label>
                                <input type="email" wire:model="editingEmail" class="w-full px-5 py-3.5 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-900 text-sm">
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ app()->getLocale() == 'en' ? 'Access Level' : 'Tingkat Akses' }}</label>
                                    <select wire:model="editingRole" class="w-full px-5 py-3.5 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-900 uppercase text-xs tracking-widest">
                                        <option value="owner">Owner</option>
                                        <option value="admin">Admin</option>
                                        <option value="staff">Staff</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ app()->getLocale() == 'en' ? 'Account Status' : 'Status Akun' }}</label>
                                    <div class="flex items-center h-full">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" wire:model="editingIsActive" class="sr-only peer">
                                            <div class="w-14 h-7 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                            <span class="ms-3 text-[11px] font-black text-slate-500 uppercase tracking-widest">
                                                {{ $editingIsActive ? (app()->getLocale() == 'en' ? 'Active' : 'Aktif') : (app()->getLocale() == 'en' ? 'Suspended' : 'Ditangguhkan') }}
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Password Center -->
                        <div class="p-8 bg-slate-900 rounded-[32px] text-white space-y-6 relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16 blur-2xl"></div>
                            <div>
                                <h4 class="text-sm font-black uppercase tracking-[0.2em] text-indigo-400 mb-1">{{ app()->getLocale() == 'en' ? 'Overrule Password' : 'Ganti Kata Sandi' }}</h4>
                                <p class="text-[11px] text-slate-400 font-medium">{{ app()->getLocale() == 'en' ? "Reset this operative's credentials manually." : 'Reset kredensial pelaksana ini secara manual.' }}</p>
                            </div>
                            <div class="relative">
                                <input :type="$wire.showPassword ? 'text' : 'password'" wire:model="editingPassword" class="w-full bg-white/5 border-white/10 rounded-2xl py-3.5 px-5 text-sm font-mono tracking-wider focus:border-indigo-500 focus:ring-0 transition-all text-white" placeholder="{{ app()->getLocale() == 'en' ? 'Enter new password...' : 'Masukkan kata sandi baru...' }}">
                                <button type="button" wire:click="$toggle('showPassword')" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-white">
                                    @if($showPassword)
                                        <i data-lucide="eye-off" class="w-4 h-4"></i>
                                    @else
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    @endif
                                </button>
                            </div>
                            <div class="flex items-center gap-3">
                                <button type="button" wire:click="generatePassword()" class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 rounded-xl text-[10px] font-black uppercase tracking-[0.2em] transition-all flex items-center justify-center gap-2 text-white">
                                    <i data-lucide="sparkles" class="w-3 h-3"></i>
                                    {{ app()->getLocale() == 'en' ? 'Generate' : 'Buat' }}
                                </button>
                                @if($editingPassword)
                                    <button type="button" @click="navigator.clipboard.writeText($wire.editingPassword); $wire.copyPassword()" class="px-4 py-2 bg-white/10 hover:bg-white/20 rounded-xl text-[10px] font-black uppercase tracking-[0.2em] transition-all flex items-center gap-2 text-indigo-300">
                                        <i data-lucide="{{ $copied ? 'check' : 'copy' }}" class="w-3 h-3 {{ $copied ? 'text-emerald-400' : '' }}"></i>
                                        <span>{{ $copied ? (app()->getLocale() == 'en' ? 'Copied' : 'Disalin') : (app()->getLocale() == 'en' ? 'Copy' : 'Salin') }}</span>
                                    </button>
                                @endif
                            </div>
                        </div>

                        <!-- Archive Option -->
                        @if($editingUser->id !== auth()->id())
                            <div class="pt-6 border-t border-slate-100 flex justify-between items-center">
                                <div class="space-y-1">
                                    <h4 class="text-xs font-black text-rose-600 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Purge Record' : 'Hapus Akun' }}</h4>
                                    <p class="text-[10px] text-slate-400 font-medium">{{ app()->getLocale() == 'en' ? 'Permanently remove this team member from the registry.' : 'Hapus data pelaksana ini secara permanen dari sistem.' }}</p>
                                </div>
                                <button type="button" wire:confirm="{{ app()->getLocale() == 'en' ? 'Deep purge this operative data?' : 'Hapus permanen data pelaksana ini?' }}" wire:click="deleteUser({{ $editingUser->id }})" class="px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl text-xs font-bold transition-all">
                                    {{ app()->getLocale() == 'en' ? 'Delete Operative' : 'Hapus Pelaksana' }}
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- Right Column: Monitoring -->
                    <div class="lg:w-1/2 p-10 bg-slate-50/50 flex flex-col overflow-y-auto custom-scrollbar">
                        <div class="mb-8">
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-6">{{ app()->getLocale() == 'en' ? 'Operative Intelligence' : 'Informasi Pelaksana' }}</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-5 bg-white rounded-[24px] border border-slate-100 shadow-sm">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ app()->getLocale() == 'en' ? 'Last Sync' : 'Sinkronisasi Terakhir' }}</p>
                                    <p class="text-sm font-black text-slate-900">{{ $editingUser->last_seen ? $editingUser->last_seen->format('M d, H:i') : (app()->getLocale() == 'en' ? 'Never' : 'Tidak Pernah') }}</p>
                                    <p class="text-[10px] text-slate-400 font-bold">IP: {{ $editingUser->last_login_ip ?? 'N/A' }}</p>
                                </div>
                                <div class="p-5 bg-white rounded-[24px] border border-slate-100 shadow-sm">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ app()->getLocale() == 'en' ? 'Pass Age' : 'Umur Sandi' }}</p>
                                    <p class="text-sm font-black text-slate-900">{{ $editingUser->last_password_change_at ? $editingUser->last_password_change_at->diffForHumans() : (app()->getLocale() == 'en' ? 'Never' : 'Tidak Pernah') }}</p>
                                    <p class="text-[10px] text-slate-400 font-bold">{{ app()->getLocale() == 'en' ? 'Last Identity Reset' : 'Reset Identitas Terakhir' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex-1 flex flex-col min-h-0">
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-6">{{ app()->getLocale() == 'en' ? 'Activity Timeline' : 'Lini Masa Aktivitas' }}</h4>
                            <div class="flex-1 overflow-y-auto pr-4 space-y-6 custom-scrollbar">
                                @forelse($selectedUserLogs as $log)
                                    <div class="flex gap-4 relative">
                                        <div class="shrink-0 w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center relative z-10">
                                            <i data-lucide="zap" class="w-4 h-4"></i>
                                        </div>
                                        <div class="flex-1 pt-1">
                                            <p class="text-xs font-bold text-slate-800 leading-tight">{{ $log['desc'] }}</p>
                                            <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mt-1.5">{{ $log['time'] }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-6">
                                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'No recent activities recorded' : 'Tidak ada aktivitas tercatat baru-baru ini' }}</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <div class="mt-10">
                            <button type="button" wire:click="saveEdit" class="w-full btn-premium py-5 rounded-[24px] text-white flex items-center justify-center">
                                <span class="text-sm font-black uppercase tracking-[0.2em]">{{ app()->getLocale() == 'en' ? 'Deploy Changes' : 'Terapkan Perubahan' }}</span>
                                <i data-lucide="send" class="w-5 h-5 ml-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

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
