<section>
    <header class="mb-8">
        <h3 class="text-xl font-black text-slate-900 font-jakarta uppercase tracking-tight">
            {{ __('Update Password') }}
        </h3>
        <p class="text-sm text-slate-500 font-medium mt-1">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-8" x-data="{ 
        password: '',
        strength: 0,
        getStrengthColor() {
            if (this.strength < 2) return 'bg-rose-500';
            if (this.strength < 4) return 'bg-amber-500';
            return 'bg-emerald-500';
        },
        getStrengthLabel() {
            if (this.password.length === 0) return '';
            if (this.strength < 2) return 'Weak';
            if (this.strength < 4) return 'Medium';
            return 'Strong';
        },
        calculateStrength() {
            let s = 0;
            if (this.password.length > 8) s++;
            if (/[A-Z]/.test(this.password)) s++;
            if (/[0-9]/.test(this.password)) s++;
            if (/[^A-Za-z0-9]/.test(this.password)) s++;
            if (this.password.length > 12) s++;
            this.strength = s;
        }
    }">
        @csrf
        @method('put')

        <div class="space-y-6">
            <!-- Current Password -->
            <div class="space-y-2">
                <x-input-label for="update_password_current_password" :value="__('Current Password')" class="text-[11px] font-black text-slate-400 uppercase tracking-widest" />
                <x-text-input id="update_password_current_password" name="current_password" type="password" class="w-full bg-slate-50/50 border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl py-3" autocomplete="current-password" />
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>

            <!-- New Password -->
            <div class="space-y-2">
                <x-input-label for="update_password_password" :value="__('New Password')" class="text-[11px] font-black text-slate-400 uppercase tracking-widest" />
                <x-text-input id="update_password_password" name="password" type="password" class="w-full bg-slate-50/50 border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl py-3" autocomplete="new-password" x-model="password" @input="calculateStrength" />
                
                <!-- Strength Meter -->
                <div class="mt-3 space-y-2" x-show="password.length > 0">
                    <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-widest">
                        <span class="text-slate-400">Security Strength</span>
                        <span :class="getStrengthColor().replace('bg-', 'text-')" x-text="getStrengthLabel()"></span>
                    </div>
                    <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full transition-all duration-500" :class="getStrengthColor()" :style="`width: ${strength * 20}%` shadow: 0 0 10px currentColor"></div>
                    </div>
                </div>
                
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="space-y-2">
                <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" class="text-[11px] font-black text-slate-400 uppercase tracking-widest" />
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="w-full bg-slate-50/50 border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl py-3" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="btn-premium px-8 py-3">
                <i data-lucide="shield-lock" class="w-4 h-4 mr-2"></i>
                {{ __('Update Security') }}
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
                    {{ __('Password synchronized.') }}
                </p>
            @endif
        </div>
    </form>
</section>
