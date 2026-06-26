<x-app-layout :title="app()->getLocale() == 'en' ? 'Team & Staff Management' : 'Manajemen Tim & Staf Operasional'">
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
        <form action="{{ route('users.store') }}" method="POST" autocomplete="off" class="glass-card p-10 space-y-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Full Name' : 'Nama Lengkap' }}</label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200/60 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/10">
                    @error('nama_lengkap')
                        <p class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Email Address' : 'Alamat Email' }}</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autocomplete="new-password" class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200/60 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/10">
                    @error('email')
                        <p class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Access Role' : 'Peran Akses' }}</label>
                <select name="peran_akses" required class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200/60 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/10">
                    <option value="staff" {{ old('peran_akses') == 'staff' ? 'selected' : '' }}>{{ app()->getLocale() == 'en' ? 'Staff (Limited Access)' : 'Staf (Akses Terbatas)' }}</option>
                    <option value="admin" {{ old('peran_akses') == 'admin' ? 'selected' : '' }}>{{ app()->getLocale() == 'en' ? 'Administrator (Full Access)' : 'Administrator (Akses Penuh)' }}</option>
                    <option value="owner" {{ old('peran_akses') == 'owner' ? 'selected' : '' }}>{{ app()->getLocale() == 'en' ? 'Owner (Full Access + Billing)' : 'Owner (Akses Penuh + Penagihan)' }}</option>
                </select>
                @error('peran_akses')
                    <p class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Password' : 'Kata Sandi' }}</label>
                        <button type="button" onclick="generatePassword()" class="text-[10px] font-bold text-indigo-600 hover:text-indigo-800 transition-colors flex items-center gap-1 uppercase tracking-wider">
                            <i data-lucide="key" class="w-3 h-3"></i>
                            <span>{{ app()->getLocale() == 'en' ? 'Generate' : 'Hasilkan' }}</span>
                        </button>
                    </div>
                    <div class="relative">
                        <input type="password" id="password" name="password" required autocomplete="new-password" class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200/60 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/10 pr-10">
                        <button type="button" onclick="togglePasswordVisibility('password')" class="absolute right-3 top-3 text-slate-400 hover:text-slate-600 transition-colors">
                            <i id="password-toggle-icon" data-lucide="eye" class="w-4 h-4"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'Confirm Password' : 'Konfirmasi Kata Sandi' }}</label>
                    <div class="relative">
                        <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200/60 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/10 pr-10">
                        <button type="button" onclick="togglePasswordVisibility('password_confirmation')" class="absolute right-3 top-3 text-slate-400 hover:text-slate-600 transition-colors">
                            <i id="password-confirm-toggle-icon" data-lucide="eye" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('users.index') }}" class="text-sm font-bold text-slate-500 hover:text-slate-800">{{ app()->getLocale() == 'en' ? 'Cancel' : 'Batal' }}</a>
                <button type="submit" class="btn-premium px-10">{{ app()->getLocale() == 'en' ? 'Register User' : 'Daftarkan Pengguna' }}</button>
            </div>
        </form>
    </div>

    <script>
        function generatePassword() {
            const length = 16;
            const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+~`|}{[]:;?><,./-=";
            let password = "";
            for (let i = 0, n = charset.length; i < length; ++i) {
                password += charset.charAt(Math.floor(Math.random() * n));
            }
            
            const pwInput = document.getElementById('password');
            const confirmInput = document.getElementById('password_confirmation');
            
            pwInput.value = password;
            confirmInput.value = password;
            
            pwInput.type = 'text';
            confirmInput.type = 'text';
            
            updateToggleIcons();
        }

        function togglePasswordVisibility(id) {
            const input = document.getElementById(id);
            if (input.type === 'password') {
                input.type = 'text';
            } else {
                input.type = 'password';
            }
            updateToggleIcons();
        }

        function updateToggleIcons() {
            const pwInput = document.getElementById('password');
            const confirmInput = document.getElementById('password_confirmation');
            
            const pwIcon = document.getElementById('password-toggle-icon');
            const confirmIcon = document.getElementById('password-confirm-toggle-icon');
            
            if (pwInput.type === 'text') {
                pwIcon.setAttribute('data-lucide', 'eye-off');
            } else {
                pwIcon.setAttribute('data-lucide', 'eye');
            }
            
            if (confirmInput.type === 'text') {
                confirmIcon.setAttribute('data-lucide', 'eye-off');
            } else {
                confirmIcon.setAttribute('data-lucide', 'eye');
            }
            
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }
    </script>
</x-app-layout>
