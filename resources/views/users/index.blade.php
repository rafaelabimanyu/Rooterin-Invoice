<x-app-layout>
    <div x-data="{ 
        editModalOpen: false, 
        currentUser: {},
        loading: true
    }" x-init="setTimeout(() => loading = false, 800)">
        
        <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6 page-fade-in">
            <div>
                <div class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">
                    <span>Administration</span>
                    <i data-lucide="chevron-right" class="w-3 h-3"></i>
                    <span class="text-indigo-600">Access Control</span>
                </div>
                <h1 class="text-3xl font-black text-slate-900 font-jakarta tracking-tight uppercase">{{ __('ui.users') ?? 'Team Management' }}</h1>
                <p class="text-sm text-slate-500 font-medium mt-1">Configure roles and access permissions for your staff.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('users.create') }}" class="btn-premium group">
                    <i data-lucide="user-plus" class="w-4 h-4 transition-transform group-hover:rotate-12"></i>
                    <span>Add User</span>
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
                    <div class="h-10 bg-slate-100 rounded-xl"></div>
                </div>
            </template>
        </div>

        <!-- User Cards Grid -->
        <div x-show="!loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($users as $user)
                <div class="glass-card p-8 group hover:-translate-y-2 hover:shadow-[0_20px_50px_rgba(0,0,0,0.08)] transition-all duration-500 relative overflow-hidden page-fade-in stagger-{{ $loop->iteration % 5 }}">
                    <!-- Role Indicator Line -->
                    <div class="absolute top-0 left-0 right-0 h-1.5 {{ $user->role === 'owner' ? 'bg-indigo-500' : ($user->role === 'admin' ? 'bg-emerald-500' : 'bg-slate-400') }}"></div>
                    
                    <div class="flex items-start justify-between mb-8">
                        <div class="flex items-center gap-5">
                            <div class="w-16 h-16 rounded-2xl bg-slate-900 flex items-center justify-center text-xl font-black text-white shadow-xl shadow-slate-900/10 group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div class="flex flex-col">
                                <h3 class="text-lg font-black text-slate-900 tracking-tight group-hover:text-indigo-600 transition-colors">{{ $user->name }}</h3>
                                <p class="text-[12px] text-slate-500 font-medium">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center gap-1">
                            <button 
                                @click="editModalOpen = true; currentUser = { id: '{{ $user->id }}', name: '{{ $user->name }}', email: '{{ $user->email }}', role: '{{ $user->role }}' }"
                                class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all"
                            >
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </button>
                            @if($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Archive this operative?')">
                                    @csrf @method('DELETE')
                                    <button class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-6 border-t border-slate-50">
                        <div class="flex flex-col">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Access Level</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black border uppercase tracking-wider
                                {{ $user->role === 'owner' ? 'bg-indigo-50 text-indigo-700 border-indigo-100 shadow-[0_0_12px_rgba(79,70,229,0.1)]' : 
                                   ($user->role === 'admin' ? 'bg-emerald-50 text-emerald-700 border-emerald-100 shadow-[0_0_12px_rgba(16,185,129,0.1)]' : 'bg-slate-50 text-slate-700 border-slate-100') }}">
                                {{ $user->role }}
                            </span>
                        </div>
                        <div class="flex flex-col text-right">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Affiliation</span>
                            <span class="text-[11px] font-bold text-slate-600 uppercase tracking-tight">{{ $user->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Edit User Modal -->
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
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="absolute inset-0 bg-slate-900/60 backdrop-blur-md" 
                    @click="editModalOpen = false"
                ></div>

                <div 
                    x-show="editModalOpen"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-8 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-8 sm:scale-95"
                    class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-200"
                >
                    <div class="px-8 py-10 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight">Edit Operative</h2>
                            <p class="text-sm text-slate-500 font-medium">Update profile and permissions</p>
                        </div>
                        <button @click="editModalOpen = false" class="p-2 text-slate-400 hover:text-slate-900 transition-colors">
                            <i data-lucide="x" class="w-6 h-6"></i>
                        </button>
                    </div>

                    <form :action="`/users/${currentUser.id}`" method="POST" class="p-8 space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Full Name</label>
                            <input type="text" name="name" x-model="currentUser.name" class="w-full px-5 py-3.5 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-bold text-slate-900">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Email Address</label>
                            <input type="email" name="email" x-model="currentUser.email" class="w-full px-5 py-3.5 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-bold text-slate-900">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Security Clearance (Role)</label>
                            <select name="role" x-model="currentUser.role" class="w-full px-5 py-3.5 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-bold text-slate-900">
                                <option value="owner">Owner</option>
                                <option value="admin">Admin</option>
                                <option value="staff">Staff</option>
                            </select>
                        </div>

                        <div class="pt-6">
                            <button type="submit" class="w-full btn-premium py-4">
                                <span>Save Changes</span>
                                <i data-lucide="check" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>
</x-app-layout>
