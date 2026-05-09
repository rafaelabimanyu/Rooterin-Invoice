<x-app-layout>
    <div class="mb-10">
        <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">
            <a href="{{ route('users.index') }}" class="hover:text-indigo-600 transition-colors">Team Management</a>
            <i data-lucide="chevron-right" class="w-3 h-3"></i>
            <span class="text-slate-900 dark:text-white">New User</span>
        </div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white font-outfit">Add New Team Member</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">Register a new staff member and assign their role.</p>
    </div>

    <div class="max-w-2xl">
        <form action="{{ route('users.store') }}" method="POST" class="glass-card p-10 space-y-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Full Name</label>
                    <input type="text" name="name" required class="w-full px-4 py-2.5 bg-slate-50/50 dark:bg-slate-800 border border-slate-200/60 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/10">
                </div>
                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Email Address</label>
                    <input type="email" name="email" required class="w-full px-4 py-2.5 bg-slate-50/50 dark:bg-slate-800 border border-slate-200/60 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/10">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Access Role</label>
                <select name="role" required class="w-full px-4 py-2.5 bg-slate-50/50 dark:bg-slate-800 border border-slate-200/60 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/10">
                    <option value="staff">Staff (Limited Access)</option>
                    <option value="admin">Administrator (Full Access)</option>
                    <option value="owner">Owner (Full Access + Billing)</option>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Password</label>
                    <input type="password" name="password" required class="w-full px-4 py-2.5 bg-slate-50/50 dark:bg-slate-800 border border-slate-200/60 rounded-lg text-sm outline-none">
                </div>
                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Confirm Password</label>
                    <input type="password" name="password_confirmation" required class="w-full px-4 py-2.5 bg-slate-50/50 dark:bg-slate-800 border border-slate-200/60 rounded-lg text-sm outline-none">
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-3">
                <a href="{{ route('users.index') }}" class="text-sm font-bold text-slate-500 hover:text-slate-800">Cancel</a>
                <button type="submit" class="btn-premium px-10">Register User</button>
            </div>
        </form>
    </div>
</x-app-layout>
