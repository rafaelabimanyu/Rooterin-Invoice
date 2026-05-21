<x-app-layout>
    <div class="animate-fade-in-up">
        <div class="mb-10 page-fade-in px-4 md:px-0">
            <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">
                <span>{{ app()->getLocale() == 'en' ? 'System' : 'Sistem' }}</span>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-indigo-600">{{ app()->getLocale() == 'en' ? 'User Profile' : 'Profil Pengguna' }}</span>
            </div>
            <h1 class="text-3xl font-black text-slate-900 font-jakarta tracking-tight truncate">{{ __('Profile Information') }}</h1>
            <p class="text-sm text-slate-500 font-medium">{{ app()->getLocale() == 'en' ? 'Manage your identity, security preferences, and system localization.' : 'Kelola identitas, preferensi keamanan, dan lokalisasi sistem Anda.' }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            <!-- Left Column: Profile Summary -->
            <div class="lg:col-span-4 space-y-8">
                <div class="glass-card p-10 text-center relative overflow-hidden group">
                    <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>
                    
                    <!-- Profile Image -->
                    <div class="relative inline-block mb-6">
                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-xl mx-auto">
                        <div class="absolute -bottom-2 left-1/2 -translate-x-1/2">
                            <x-badge :status="$user->role" :label="$user->role_badge['label']" />
                        </div>
                    </div>

                    <h3 class="text-xl font-black text-slate-900 font-jakarta mt-4">{{ $user->name }}</h3>
                    <p class="text-sm text-slate-500 font-medium mb-8">{{ $user->email }}</p>

                    <div class="grid grid-cols-1 gap-4">
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-center gap-4 text-left">
                            <div class="p-2.5 bg-indigo-100 rounded-xl text-indigo-600">
                                <i data-lucide="calendar" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Member Since' : 'Anggota Sejak' }}</p>
                                <p class="text-sm font-bold text-slate-900">{{ $user->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Security Quick Info -->
                    <div class="mt-10 pt-8 border-t border-slate-50 space-y-6 text-left">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">{{ app()->getLocale() == 'en' ? 'Security Overview' : 'Ikhtisar Keamanan' }}</p>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-3 bg-emerald-50 rounded-xl border border-emerald-100">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="shield-check" class="w-4 h-4 text-emerald-600"></i>
                                        <span class="text-xs font-bold text-emerald-900">{{ app()->getLocale() == 'en' ? '2FA Protected' : 'Dilindungi 2FA' }}</span>
                                    </div>
                                    <div class="relative inline-flex items-center cursor-pointer">
                                        <div class="w-8 h-4 bg-emerald-500 rounded-full transition-colors"></div>
                                        <div class="absolute left-4 w-3 h-3 bg-white rounded-full transition-transform"></div>
                                    </div>
                                </div>
                                <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                                    <div class="flex items-center gap-2 mb-1">
                                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400"></i>
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'Last Login' : 'Login Terakhir' }}</span>
                                    </div>
                                    <p class="text-xs font-bold text-slate-900">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : (app()->getLocale() == 'en' ? 'Never' : 'Belum Pernah') }}</p>
                                    <p class="text-[10px] text-slate-400 font-medium mt-0.5">{{ $user->last_login_ip ?? '0.0.0.0' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Activity Log -->
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">{{ app()->getLocale() == 'en' ? 'Recent Activity' : 'Aktivitas Terbaru' }}</p>
                            <div class="space-y-4">
                                @forelse($activityLogs as $log)
                                    <div class="flex gap-3">
                                        <div class="shrink-0 w-1.5 h-1.5 rounded-full bg-indigo-500 mt-1.5"></div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-slate-700 leading-tight">{{ $log->description }}</p>
                                            <p class="text-[10px] text-slate-400 mt-1 font-medium">{{ $log->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-xs text-slate-400 italic">{{ app()->getLocale() == 'en' ? 'No recent activity recorded.' : 'Tidak ada aktivitas terbaru yang tercatat.' }}</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Action Center -->
            <div class="lg:col-span-8 space-y-10">
                <!-- Profile Info -->
                <div class="glass-card p-8 md:p-10">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <!-- Password Update -->
                <div class="glass-card p-8 md:p-10">
                    @include('profile.partials.update-password-form')
                </div>

                <!-- Danger Zone -->
                <div class="p-8 md:p-10 bg-rose-50/30 rounded-[32px] border-2 border-dashed border-rose-100">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="p-3 bg-rose-100 text-rose-600 rounded-2xl">
                            <i data-lucide="trash-2" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-black text-slate-900 font-jakarta">{{ app()->getLocale() == 'en' ? 'Security: Danger Zone' : 'Keamanan: Zona Bahaya' }}</h4>
                            <p class="text-sm text-slate-500 font-medium">{{ app()->getLocale() == 'en' ? 'Irreversible actions that will remove your access permanently.' : 'Tindakan tidak dapat dibatalkan yang akan menghapus akses Anda secara permanen.' }}</p>
                        </div>
                    </div>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
