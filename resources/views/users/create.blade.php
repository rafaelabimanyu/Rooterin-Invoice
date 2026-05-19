<x-app-layout>
    <div class="mb-10">
        <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">
            <a href="{{ route('users.index') }}" class="hover:text-indigo-600 transition-colors">{{ app()->getLocale() == 'en' ? 'Team Management' : 'Manajemen Tim' }}</a>
            <i data-lucide="chevron-right" class="w-3 h-3"></i>
            <span class="text-slate-900">{{ app()->getLocale() == 'en' ? 'New User' : 'Pengguna Baru' }}</span>
        </div>
        <h1 class="text-2xl font-bold text-slate-900 font-outfit">{{ app()->getLocale() == 'en' ? 'Add New Team Member' : 'Tambah Anggota Tim Baru' }}</h1>
        <p class="text-sm text-slate-500">{{ app()->getLocale() == 'en' ? 'Register a new staff member and assign their role.' : 'Daftarkan anggota staf baru dan tetapkan peran mereka.' }}</p>
    </div>

    <div class="max-w-2xl">
        <form action="{{ route('users.store') }}" method="POST" class="glass-card p-10 space-y-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Full Name' : 'Nama Lengkap' }}</label>
                    <input type="text" name="name" required class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200/60 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/10">
                </div>
                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Email Address' : 'Alamat Email' }}</label>
                    <input type="email" name="email" required class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200/60 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/10">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Access Role' : 'Peran Akses' }}</label>
                <select name="role" required class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200/60 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/10">
                    <option value="staff">{{ app()->getLocale() == 'en' ? 'Staff (Limited Access)' : 'Staf (Akses Terbatas)' }}</option>
                    <option value="admin">{{ app()->getLocale() == 'en' ? 'Administrator (Full Access)' : 'Administrator (Akses Penuh)' }}</option>
                    <option value="owner">{{ app()->getLocale() == 'en' ? 'Owner (Full Access + Billing)' : 'Owner (Akses Penuh + Penagihan)' }}</option>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Password' : 'Kata Sandi' }}</label>
                    <input type="password" name="password" required class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200/60 rounded-lg text-sm outline-none">
                </div>
                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Confirm Password' : 'Konfirmasi Kata Sandi' }}</label>
                    <input type="password" name="password_confirmation" required class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200/60 rounded-lg text-sm outline-none">
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('users.index') }}" class="text-sm font-bold text-slate-500 hover:text-slate-800">{{ app()->getLocale() == 'en' ? 'Cancel' : 'Batal' }}</a>
                <button type="submit" class="btn-premium px-10">{{ app()->getLocale() == 'en' ? 'Register User' : 'Daftarkan Pengguna' }}</button>
            </div>
        </form>
    </div>
</x-app-layout>
