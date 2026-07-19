<!-- Modal 3: Advanced Profile / Credentials Management Modal -->
<div x-show="showEditModal" 
     class="relative z-[100]" 
     x-cloak 
     style="display: none;" 
     wire:key="modal-edit"
>
    <!-- BACKDROP OVERLAY -->
    <div x-show="showEditModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] bg-slate-950/45 backdrop-blur-sm"
    ></div>

    <!-- SCROLLABLE AREA -->
    <div x-show="showEditModal" 
         class="fixed inset-0 z-[101] overflow-y-auto"
    >
        <!-- ALIGNMENT CONTAINER -->
        <div class="flex min-h-full justify-center py-10 px-4 sm:px-6" @click.self="showEditModal = false">
            <!-- MAIN MODAL CONTAINER -->
            <div x-show="showEditModal" 
                 x-transition:enter="transition ease-out duration-300" 
                 x-transition:enter-start="opacity-0 scale-95 -translate-y-4" 
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
                 x-transition:leave="transition ease-in duration-200" 
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0" 
                 x-transition:leave-end="opacity-0 scale-95 -translate-y-4" 
                 class="relative w-full max-w-4xl bg-white rounded-2xl shadow-2xl h-fit transform overflow-hidden transition-all text-left"
            >
                <!-- Header -->
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-white">
                    <div class="flex items-center gap-4">
                        <div class="p-2.5 bg-slate-900 text-white rounded-xl">
                            <i data-lucide="shield" class="w-5 h-5"></i>
                        </div>
                        <!-- Shimmer Header Text during Loading -->
                        <div wire:loading wire:target="openEditModal" class="space-y-2 animate-pulse">
                            <div class="h-4 w-48 bg-slate-200 rounded"></div>
                            <div class="h-3 w-64 bg-slate-100 rounded"></div>
                        </div>
                        <!-- Real Header Text -->
                        <div wire:loading.remove wire:target="openEditModal">
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

                <!-- Body -->
                <div class="p-6 space-y-6 bg-white">
                    <!-- Skeleton Shimmer Loader -->
                    <div wire:loading wire:target="openEditModal" class="animate-pulse space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Left Column Shimmer -->
                            <div class="space-y-6">
                                <!-- Profile Card Shimmer -->
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-slate-200 rounded-2xl"></div>
                                    <div class="space-y-2 flex-1">
                                        <div class="h-4 w-1/2 bg-slate-200 rounded"></div>
                                        <div class="h-3 w-1/3 bg-slate-100 rounded"></div>
                                    </div>
                                </div>
                                <!-- Fields Shimmer -->
                                <div class="space-y-4">
                                    @for($i=0; $i<3; $i++)
                                        <div class="space-y-2">
                                            <div class="h-3 w-28 bg-slate-200 rounded"></div>
                                            <div class="h-10 w-full bg-slate-100 rounded-xl"></div>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                            <!-- Right Column Shimmer -->
                            <div class="space-y-6">
                                <div class="space-y-2">
                                    <div class="h-3 w-36 bg-slate-200 rounded"></div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="h-16 bg-slate-50 border border-slate-200/50 rounded-xl"></div>
                                        <div class="h-16 bg-slate-50 border border-slate-200/50 rounded-xl"></div>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div class="h-3 w-32 bg-slate-200 rounded"></div>
                                    @for($i=0; $i<3; $i++)
                                        <div class="flex gap-3">
                                            <div class="w-7 h-7 bg-slate-100 rounded-full shrink-0"></div>
                                            <div class="flex-1 space-y-2 pt-1">
                                                <div class="h-3 w-3/4 bg-slate-200 rounded"></div>
                                                <div class="h-2 w-1/4 bg-slate-100 rounded"></div>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actual Content -->
                    <div wire:loading.remove wire:target="openEditModal" class="space-y-6">
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
                                        <input type="text" id="editingName" name="editingName" wire:model="editingName" class="w-full px-4 py-2.5 bg-slate-50 border-transparent rounded-xl focus:bg-white focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 transition-all font-bold text-slate-900 text-xs">
                                    </div>

                                    <div class="space-y-1">
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ app()->getLocale() == 'en' ? 'Email Address' : 'Alamat Email' }}</label>
                                        <input type="email" id="editingEmail" name="editingEmail" wire:model="editingEmail" class="w-full px-4 py-2.5 bg-slate-50 border-transparent rounded-xl focus:bg-white focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 transition-all font-bold text-slate-900 text-xs">
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ app()->getLocale() == 'en' ? 'Access Level' : 'Tingkat Akses' }}</label>
                                            <select id="editingRole" name="editingRole" wire:model="editingRole" class="w-full px-4 py-2.5 bg-slate-50 border-transparent rounded-xl focus:bg-white focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 transition-all font-bold text-slate-900 uppercase text-xs tracking-widest">
                                                <option value="owner">Owner</option>
                                                <option value="admin">Admin</option>
                                                <option value="staff">Staff</option>
                                            </select>
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ app()->getLocale() == 'en' ? 'Account Status' : 'Status Akun' }}</label>
                                            <div class="flex items-center h-full">
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" id="editingIsActive" name="editingIsActive" wire:model="editingIsActive" class="sr-only peer">
                                                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-gold-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gold-500"></div>
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
                                        <h4 class="text-xs font-black uppercase tracking-[0.2em] text-gold-400 mb-0.5">{{ app()->getLocale() == 'en' ? 'Overrule Password' : 'Ganti Kata Sandi' }}</h4>
                                        <p class="text-[10px] text-slate-400 font-medium">{{ app()->getLocale() == 'en' ? "Reset this operative's credentials manually." : 'Reset kredensial pelaksana ini secara manual.' }}</p>
                                    </div>
                                    <div class="relative">
                                        <input :type="$wire.showPassword ? 'text' : 'password'" id="editingPassword" name="editingPassword" wire:model="editingPassword" class="w-full bg-white/5 border-white/10 rounded-xl py-2 px-4 text-xs font-mono tracking-wider focus:border-gold-500 focus:ring-0 transition-all text-white" placeholder="{{ app()->getLocale() == 'en' ? 'Enter new password...' : 'Masukkan kata sandi baru...' }}">
                                        <button type="button" wire:click="$toggle('showPassword')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-white">
                                            @if($showPassword)
                                                <i data-lucide="eye-off" class="w-3.5 h-3.5"></i>
                                            @else
                                                <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                            @endif
                                        </button>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button type="button" wire:click="generatePassword()" class="flex-1 px-3 py-1.5 bg-gold-600 hover:bg-gold-500 rounded-lg text-[9px] font-black uppercase tracking-[0.2em] transition-all flex items-center justify-center gap-1.5 text-white" wire:loading.attr="disabled" wire:target="generatePassword">
                                            <span wire:loading.remove wire:target="generatePassword" class="flex items-center gap-1.5">
                                                <i data-lucide="sparkles" class="w-3 h-3"></i>
                                                <span>{{ app()->getLocale() == 'en' ? 'Generate' : 'Buat' }}</span>
                                            </span>
                                            <span wire:loading wire:target="generatePassword">
                                                <svg class="animate-spin w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </span>
                                        </button>
                                        @if($editingPassword)
                                            <button type="button" @click="navigator.clipboard.writeText($wire.editingPassword); $wire.copyPassword()" class="px-3 py-1.5 bg-white/10 hover:bg-white/20 rounded-lg text-[9px] font-black uppercase tracking-[0.2em] transition-all flex items-center gap-1.5 text-gold-300">
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
                                        <button type="button" wire:confirm="{{ app()->getLocale() == 'en' ? 'Deep purge this operative data?' : 'Hapus permanen data pelaksana ini?' }}" wire:click="deleteUser({{ $editingUser?->id ?? 0 }})" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-xs font-bold transition-all" wire:loading.attr="disabled" wire:target="deleteUser">
                                            <span wire:loading.remove wire:target="deleteUser">{{ app()->getLocale() == 'en' ? 'Delete Operative' : 'Hapus Pelaksana' }}</span>
                                            <span wire:loading wire:target="deleteUser">
                                                <svg class="animate-spin w-4 h-4 text-rose-600" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </span>
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

                                <div class="flex-1 flex flex-col min-h-0 mt-6">
                                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-4">{{ app()->getLocale() == 'en' ? 'Activity Timeline' : 'Lini Masa Aktivitas' }}</h4>
                                    <!-- Scrollable Timeline -->
                                    <div class="overflow-y-auto max-h-[300px] pr-2 space-y-4 custom-scrollbar">
                                        @forelse($selectedUserLogs as $log)
                                            <div class="flex gap-3 relative">
                                                <div class="shrink-0 w-7 h-7 rounded-full bg-gold-50 text-gold-600 flex items-center justify-center relative z-10">
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
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="p-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                    <button type="button" @click="showEditModal = false" class="text-xs font-bold text-slate-500 hover:text-slate-800" wire:loading.attr="disabled" wire:target="saveEdit">{{ app()->getLocale() == 'en' ? 'Cancel' : 'Batal' }}</button>
                    <button type="button" wire:click="saveEdit" class="btn-premium px-6 py-2.5 rounded-lg text-xs uppercase tracking-wider font-bold" wire:loading.attr="disabled" wire:target="saveEdit">
                        <span wire:loading.remove wire:target="saveEdit" class="flex items-center justify-center">
                            <span>{{ app()->getLocale() == 'en' ? 'Deploy Changes' : 'Terapkan Perubahan' }}</span>
                            <i data-lucide="send" class="w-4 h-4 ml-1.5"></i>
                        </span>
                        <span wire:loading wire:target="saveEdit" class="flex items-center gap-1.5 justify-center">
                            <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>{{ app()->getLocale() == 'en' ? 'Saving...' : 'Menyimpan...' }}</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
