<x-app-layout>
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">
                <span>Administration</span>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-indigo-600">Access Control</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white font-outfit">Team Management</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Configure roles and access permissions for your staff.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('users.create') }}" class="btn-premium">
                <i data-lucide="user-plus" class="w-4 h-4 mr-2 inline"></i>Add User
            </a>
        </div>
    </div>

    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-bold uppercase tracking-[0.1em] text-slate-400 bg-slate-50/50 dark:bg-slate-800/40">
                        <th class="px-8 py-4 border-b border-slate-100 dark:border-slate-800">User Details</th>
                        <th class="px-8 py-4 border-b border-slate-100 dark:border-slate-800">Assigned Role</th>
                        <th class="px-8 py-4 border-b border-slate-100 dark:border-slate-800">Joined Date</th>
                        <th class="px-8 py-4 border-b border-slate-100 dark:border-slate-800 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50">
                    @foreach($users as $user)
                        <tr class="table-row-premium">
                            <td class="px-8 py-4.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-xs font-bold text-slate-600">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[13px] font-bold text-slate-900 dark:text-white">{{ $user->name }}</span>
                                        <span class="text-[11px] text-slate-500">{{ $user->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-4.5">
                                <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold border 
                                    {{ $user->role === 'owner' ? 'bg-indigo-50 text-indigo-700 border-indigo-100' : 
                                       ($user->role === 'admin' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-slate-50 text-slate-700 border-slate-100') }}">
                                    {{ strtoupper($user->role) }}
                                </span>
                            </td>
                            <td class="px-8 py-4.5">
                                <span class="text-[12px] text-slate-500 font-medium">{{ $user->created_at->format('M d, Y') }}</span>
                            </td>
                            <td class="px-8 py-4.5 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('users.edit', $user) }}" class="p-2 text-slate-400 hover:text-indigo-600 transition-colors">
                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Delete this user?')">
                                        @csrf @method('DELETE')
                                        <button class="p-2 text-slate-400 hover:text-rose-600 transition-colors">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
