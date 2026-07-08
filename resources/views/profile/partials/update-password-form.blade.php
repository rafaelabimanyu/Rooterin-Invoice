<section>
    <header class="mb-8">
        <h3 class="text-xl font-black text-slate-900 font-jakarta uppercase tracking-tight">
            {{ app()->getLocale() == 'en' ? 'Update Password' : 'Perbarui Kata Sandi' }}
        </h3>
        <p class="text-sm text-slate-500 font-medium mt-1">
            {{ app()->getLocale() == 'en' ? 'Ensure your account is using a long, random password to stay secure.' : 'Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.' }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-8" x-data="{ 
        password: '',
        password_confirmation: '',
        strength: 0,
        showCurrent: false,
        showNew: false,
        showConfirm: false,
        copied: false,
        
        getStrengthColor() {
            if (this.strength < 2) return 'bg-rose-500';
            if (this.strength < 4) return 'bg-amber-500';
            return 'bg-emerald-500';
        },
        getStrengthLabel() {
            if (this.password.length === 0) return '';
            if (this.strength < 2) return '{{ app()->getLocale() == 'en' ? 'Weak' : 'Lemah' }}';
            if (this.strength < 4) return '{{ app()->getLocale() == 'en' ? 'Medium' : 'Sedang' }}';
            return '{{ app()->getLocale() == 'en' ? 'Strong' : 'Kuat' }}';
        },
        calculateStrength() {
            let s = 0;
            if (this.password.length >= 8) s++;
            if (/[A-Z]/.test(this.password)) s++;
            if (/[0-9]/.test(this.password)) s++;
            if (/[^A-Za-z0-9]/.test(this.password)) s++;
            if (this.password.length >= 12) s++;
            this.strength = s;
        },
        generatePassword() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+';
            let generated = '';
            for (let i = 0; i < 16; i++) {
                generated += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            this.password = generated;
            this.password_confirmation = generated;
            this.calculateStrength();
            this.showNew = true;
            this.showConfirm = true;
        },
        async copyToClipboard() {
            await navigator.clipboard.writeText(this.password);
            this.copied = true;
            setTimeout(() => this.copied = false, 2000);
        }
    }">
        @csrf
        @method('put')

        <div class="space-y-6">
            <!-- Current Password -->
            <div class="space-y-2">
                <x-input-label for="update_password_current_password" value="{{ app()->getLocale() == 'en' ? 'Current Password' : 'Kata Sandi Saat Ini' }}" class="text-[11px] font-black text-slate-400 uppercase tracking-widest" />
                <div class="relative">
                    <x-text-input id="update_password_current_password" name="current_password" x-bind:type="showCurrent ? 'text' : 'password'" class="w-full bg-slate-50/50 border-slate-200 focus:border-gold-500 focus:ring-gold-500 rounded-xl py-3 pr-12" autocomplete="current-password" />
                    <button type="button" @click="showCurrent = !showCurrent" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-gold-600 transition-colors">
                        <template x-if="!showCurrent"><i data-lucide="eye" class="w-4 h-4"></i></template>
                        <template x-if="showCurrent"><i data-lucide="eye-off" class="w-4 h-4"></i></template>
                    </button>
                </div>
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>

            <!-- New Password -->
            <div class="space-y-2">
                <div class="flex justify-between items-end">
                    <x-input-label for="update_password_password" value="{{ app()->getLocale() == 'en' ? 'New Password' : 'Kata Sandi Baru' }}" class="text-[11px] font-black text-slate-400 uppercase tracking-widest" />
                    <button type="button" @click="generatePassword()" class="text-[10px] font-black text-gold-600 uppercase tracking-widest hover:text-gold-800 transition-colors flex items-center gap-1.5 mb-1 font-jakarta">
                        <i data-lucide="sparkles" class="w-3 h-3"></i>
                        {{ app()->getLocale() == 'en' ? 'Generate Strong Password' : 'Buat Kata Sandi Kuat' }}
                    </button>
                </div>
                <div class="relative">
                    <x-text-input id="update_password_password" name="password" x-bind:type="showNew ? 'text' : 'password'" class="w-full bg-slate-50/50 border-slate-200 focus:border-gold-500 focus:ring-gold-500 rounded-xl py-3 pr-24" autocomplete="new-password" x-model="password" @input="calculateStrength" />
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center gap-3">
                        <button type="button" x-show="password.length > 0" @click="copyToClipboard()" class="text-slate-400 hover:text-gold-600 transition-colors" title="Copy to clipboard">
                            <template x-if="!copied"><i data-lucide="copy" class="w-4 h-4"></i></template>
                            <template x-if="copied"><i data-lucide="check" class="w-4 h-4 text-emerald-500"></i></template>
                        </button>
                        <button type="button" @click="showNew = !showNew" class="text-slate-400 hover:text-gold-600 transition-colors">
                            <template x-if="!showNew"><i data-lucide="eye" class="w-4 h-4"></i></template>
                            <template x-if="showNew"><i data-lucide="eye-off" class="w-4 h-4"></i></template>
                        </button>
                    </div>
                </div>
                
                <!-- Strength Meter -->
                <div class="mt-3 space-y-2" x-show="password.length > 0">
                    <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-widest">
                        <span class="text-slate-400">{{ app()->getLocale() == 'en' ? 'Security Strength' : 'Kekuatan Keamanan' }}</span>
                        <span :class="getStrengthColor().replace('bg-', 'text-')" x-text="getStrengthLabel()"></span>
                    </div>
                    <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full transition-all duration-500 shadow-[0_0_10px_currentColor]" :class="getStrengthColor()" :style="`width: ${strength * 20}%`"></div>
                    </div>
                </div>
                
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="space-y-2">
                <x-input-label for="update_password_password_confirmation" value="{{ app()->getLocale() == 'en' ? 'Confirm Password' : 'Konfirmasi Kata Sandi' }}" class="text-[11px] font-black text-slate-400 uppercase tracking-widest" />
                <div class="relative">
                    <x-text-input id="update_password_password_confirmation" name="password_confirmation" x-bind:type="showConfirm ? 'text' : 'password'" class="w-full bg-slate-50/50 border-slate-200 focus:border-gold-500 focus:ring-gold-500 rounded-xl py-3 pr-12" autocomplete="new-password" x-model="password_confirmation" />
                    <button type="button" @click="showConfirm = !showConfirm" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-gold-600 transition-colors">
                        <template x-if="!showConfirm"><i data-lucide="eye" class="w-4 h-4"></i></template>
                        <template x-if="showConfirm"><i data-lucide="eye-off" class="w-4 h-4"></i></template>
                    </button>
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="btn-premium px-8 py-3">
                <i data-lucide="shield-lock" class="w-4 h-4 mr-2"></i>
                {{ app()->getLocale() == 'en' ? 'Update Security' : 'Perbarui Keamanan' }}
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm font-bold text-emerald-600 flex items-center gap-2"
                >
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                    {{ app()->getLocale() == 'en' ? 'Password synchronized.' : 'Kata sandi disinkronkan.' }}
                </p>
            @endif
        </div>
    </form>
</section>
