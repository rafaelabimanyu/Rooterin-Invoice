<x-app-layout>
    <div x-data="{ 
        editModalOpen: false, 
        currentUser: {},
        loading: true,
        showPassword: false,
        password: '',
        strength: 0,
        copied: false,
        
        generatePassword() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+';
            let generated = '';
            for (let i = 0; i < 16; i++) {
                generated += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            this.password = generated;
            this.showPassword = true;
        },
        async copyPassword() {
            await navigator.clipboard.writeText(this.password);
            this.copied = true;
            setTimeout(() => this.copied = false, 2000);
        }
    }" x-init="setTimeout(() => loading = false, 800)">
        
        <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6 page-fade-in">
            <div>
                <div class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">
                    <span>Administration</span>
                    <i data-lucide="chevron-right" class="w-3 h-3"></i>
                    <span class="text-indigo-600">Team Control Center</span>
                </div>
                <h1 class="text-3xl font-black text-slate-900 font-jakarta tracking-tight uppercase">{{ __('ui.users') ?? 'Team Management' }}</h1>
                <p class="text-sm text-slate-500 font-medium mt-1">Manage operatives, security clearances, and operational status.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="px-4 py-2 bg-white rounded-xl border border-slate-200 shadow-sm flex items-center gap-3">
                    <span class="flex h-2 w-2 rounded-full bg-emerald-500"></span>
                    <span class="text-xs font-bold text-slate-600">{{ $users->count() }} Total Operatives</span>
                </div>
                <a href="{{ route('users.create') }}" class="btn-premium group">
                    <i data-lucide="user-plus" class="w-4 h-4 transition-transform group-hover:rotate-12"></i>
                    <span>Add New Operative</span>
                </a>
            </div>
        </div>

        <!-- Skeleton Loading -->
        <div x-show="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <template x-for="i in 6">
                <div class="glass-card p-8 animate-pulse">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 bg-slate-100 rounded-2xl"></div>
                        <div class="flex-1 space-y-2">
                            <div class="h-4 bg-slate-100 rounded w-3/4"></div>
                            <div class="h-3 bg-slate-100 rounded w-1/2"></div>
                        </div>
                    </div>
                    <div class="h-20 bg-slate-50 rounded-xl"></div>
                </div>
            </template>
        </div>

        <!-- User Cards Grid -->
        <div x-show="!loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($users as $user)
                <div class="glass-card p-8 group hover:-translate-y-2 hover:shadow-[0_30px_60px_rgba(0,0,0,0.12)] transition-all duration-500 relative overflow-hidden page-fade-in stagger-{{ $loop->iteration % 5 }} {{ !$user->is_active ? 'opacity-75 grayscale-[0.5]' : '' }}">
                    <!-- Status Indicator -->
                    <div class="absolute top-0 right-0 p-4">
                        <div class="flex items-center gap-2 px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ $user->is_active ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-rose-50 text-rose-600 border border-rose-100' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500' }}"></span>
                            {{ $user->is_active ? 'Active' : 'Suspended' }}
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
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Productivity</p>
                            <div class="flex items-center gap-2">
                                <i data-lucide="file-text" class="w-3.5 h-3.5 text-indigo-500"></i>
                                <span class="text-sm font-black text-slate-900">{{ $user->invoices_count }} Invoices</span>
                            </div>
                        </div>
                        <div class="p-4 bg-slate-50/50 rounded-2xl border border-slate-100 group-hover:bg-white group-hover:border-emerald-100 transition-all duration-500">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Status</p>
                            <div class="flex items-center gap-2">
                                <i data-lucide="clock" class="w-3.5 h-3.5 text-emerald-500"></i>
                                <span class="text-[11px] font-black text-slate-900">{{ $user->last_login_at ? $user->last_login_at->diffForHumans(null, true) : 'Offline' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-6 border-t border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black border uppercase tracking-wider
                                {{ $user->role === 'owner' ? 'bg-indigo-50 text-indigo-700 border-indigo-100' : 
                                   ($user->role === 'admin' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-slate-50 text-slate-700 border-slate-100') }}">
                                {{ $user->role }}
                            </span>
                        </div>
                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-all duration-300">
                            <button 
                                @click="editModalOpen = true; password = ''; currentUser = { 
                                    id: '{{ $user->id }}', 
                                    name: '{{ $user->name }}', 
                                    email: '{{ $user->email }}', 
                                    role: '{{ $user->role }}',
                                    is_active: {{ $user->is_active ? 'true' : 'false' }},
                                    photo: '{{ $user->profile_photo_url }}',
                                    last_login: '{{ $user->last_login_at ? $user->last_login_at->format('M d, H:i') : 'Never' }}',
                                    last_ip: '{{ $user->last_login_ip ?? 'N/A' }}',
                                    last_pass_change: '{{ $user->last_password_change_at ? $user->last_password_change_at->diffForHumans() : 'Unknown' }}',
                                    logs: @json($user->activityLogs->map(fn($log) => ['desc' => $log->description, 'time' => $log->created_at->diffForHumans()]))
                                }"
                                class="p-2.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all"
                                title="Manage Operative"
                            >
                                <i data-lucide="settings-2" class="w-5 h-5"></i>
                            </button>
                            @if($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Deep purge this operative data?')">
                                    @csrf @method('DELETE')
                                    <button class="p-2.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all" title="Archive Account">
                                        <i data-lucide="shield-off" class="w-5 h-5"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Advanced Management Modal -->
        <template x-teleport="body">
            <div 
                x-show="editModalOpen" 
                class="fixed inset-0 z-[110] flex items-center justify-center p-6" 
                x-cloak
            >
                <div 
                    x-show="editModalOpen"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    class="absolute inset-0 bg-slate-900/80 backdrop-blur-xl" 
                    @click="editModalOpen = false"
                ></div>

                <div 
                    x-show="editModalOpen"
                    x-transition:enter="ease-out duration-500 cubic-bezier-spring"
                    x-transition:enter-start="opacity-0 scale-95 translate-y-12"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    class="relative w-full max-w-4xl bg-white rounded-[40px] shadow-2xl overflow-hidden border border-slate-200"
                >
                    <div class="px-10 py-8 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-slate-900 text-white rounded-2xl">
                                <i data-lucide="shield" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight">Security & Command Center</h2>
                                <p class="text-xs text-slate-500 font-bold uppercase tracking-widest">ID: <span x-text="currentUser.id"></span> • Status: <span :class="currentUser.is_active ? 'text-emerald-600' : 'text-rose-600'" x-text="currentUser.is_active ? 'Authorized' : 'Suspended'"></span></p>
                            </div>
                        </div>
                        <button @click="editModalOpen = false" class="p-3 text-slate-400 hover:text-slate-900 hover:bg-slate-100 rounded-2xl transition-all">
                            <i data-lucide="x" class="w-6 h-6"></i>
                        </button>
                    </div>

                    <form :action="`/users/${currentUser.id}`" method="POST" class="flex flex-col lg:flex-row h-[600px]">
                        @csrf
                        @method('PUT')
                        
                        <!-- Left Column: Settings -->
                        <div class="lg:w-1/2 p-10 space-y-8 overflow-y-auto border-r border-slate-100">
                            <div class="flex items-center gap-6 mb-10">
                                <img :src="currentUser.photo" class="w-20 h-20 rounded-[28px] object-cover shadow-lg border-4 border-white ring-1 ring-slate-100">
                                <div>
                                    <h3 class="text-lg font-black text-slate-900" x-text="currentUser.name"></h3>
                                    <p class="text-sm text-slate-500 font-medium" x-text="currentUser.email"></p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Full Identity</label>
                                    <input type="text" name="name" x-model="currentUser.name" class="w-full px-5 py-3.5 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-900">
                                </div>

                                <div class="grid grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Access Level</label>
                                        <select name="role" x-model="currentUser.role" class="w-full px-5 py-3.5 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-900 uppercase text-xs tracking-widest">
                                            <option value="owner">Owner</option>
                                            <option value="admin">Admin</option>
                                            <option value="staff">Staff</option>
                                        </select>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Account Status</label>
                                        <div class="flex items-center h-full">
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="hidden" name="is_active" value="0">
                                                <input type="checkbox" name="is_active" value="1" x-model="currentUser.is_active" class="sr-only peer">
                                                <div class="w-14 h-7 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                                <span class="ms-3 text-[11px] font-black text-slate-500 uppercase tracking-widest" x-text="currentUser.is_active ? 'Active' : 'Suspended'"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Security Center -->
                            <div class="p-8 bg-slate-900 rounded-[32px] text-white space-y-6 relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16 blur-2xl"></div>
                                <div>
                                    <h4 class="text-sm font-black uppercase tracking-[0.2em] text-indigo-400 mb-1">Overrule Password</h4>
                                    <p class="text-[11px] text-slate-400 font-medium">Reset this operative's credentials manually.</p>
                                </div>
                                <div class="relative">
                                    <input x-bind:type="showPassword ? 'text' : 'password'" name="password" x-model="password" class="w-full bg-white/5 border-white/10 rounded-2xl py-3.5 px-5 text-sm font-mono tracking-wider focus:border-indigo-500 focus:ring-0 transition-all" placeholder="Enter new password...">
                                    <button type="button" @click="showPassword = !showPassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-white">
                                        <i x-show="!showPassword" data-lucide="eye" class="w-4 h-4"></i>
                                        <i x-show="showPassword" data-lucide="eye-off" class="w-4 h-4"></i>
                                    </button>
                                </div>
                                <div class="flex items-center gap-3">
                                    <button type="button" @click="generatePassword()" class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 rounded-xl text-[10px] font-black uppercase tracking-[0.2em] transition-all flex items-center justify-center gap-2">
                                        <i data-lucide="sparkles" class="w-3 h-3"></i>
                                        Generate
                                    </button>
                                    <button type="button" x-show="password.length > 0" @click="copyPassword()" class="px-4 py-2 bg-white/10 hover:bg-white/20 rounded-xl text-[10px] font-black uppercase tracking-[0.2em] transition-all flex items-center gap-2">
                                        <i :data-lucide="copied ? 'check' : 'copy'" class="w-3 h-3" :class="copied ? 'text-emerald-400' : ''"></i>
                                        <span x-text="copied ? 'Copied' : 'Copy'"></span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Monitoring -->
                        <div class="lg:w-1/2 p-10 bg-slate-50/50 flex flex-col">
                            <div class="mb-8">
                                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-6">Operative Intelligence</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="p-5 bg-white rounded-[24px] border border-slate-100 shadow-sm">
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Last Sync</p>
                                        <p class="text-sm font-black text-slate-900" x-text="currentUser.last_login"></p>
                                        <p class="text-[10px] text-slate-400 font-bold" x-text="'IP: ' + currentUser.last_ip"></p>
                                    </div>
                                    <div class="p-5 bg-white rounded-[24px] border border-slate-100 shadow-sm">
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Pass Age</p>
                                        <p class="text-sm font-black text-slate-900" x-text="currentUser.last_pass_change"></p>
                                        <p class="text-[10px] text-slate-400 font-bold">Last Identity Reset</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex-1 flex flex-col min-h-0">
                                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-6">Activity Timeline</h4>
                                <div class="flex-1 overflow-y-auto pr-4 space-y-6 custom-scrollbar">
                                    <template x-for="log in currentUser.logs">
                                        <div class="flex gap-4 relative">
                                            <div class="shrink-0 w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center relative z-10">
                                                <i data-lucide="zap" class="w-4 h-4"></i>
                                            </div>
                                            <div class="flex-1 pt-1">
                                                <p class="text-xs font-bold text-slate-800 leading-tight" x-text="log.desc"></p>
                                                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mt-1.5" x-text="log.time"></p>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div class="mt-10">
                                <button type="submit" class="w-full btn-premium py-5 rounded-[24px]">
                                    <span class="text-sm font-black uppercase tracking-[0.2em]">Deploy Changes</span>
                                    <i data-lucide="send" class="w-5 h-5 ml-2"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>
</x-app-layout>
