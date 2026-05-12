<section>
    <header class="mb-8">
        <h3 class="text-xl font-black text-slate-900 font-jakarta uppercase tracking-tight">
            {{ __('Profile Information') }}
        </h3>
        <p class="text-sm text-slate-500 font-medium mt-1">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-8" enctype="multipart/form-data" x-data="{ 
        photoPreview: null,
        updatePreview(e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                this.photoPreview = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }">
        @csrf
        @method('patch')

        <!-- Profile Photo -->
        <div class="space-y-4">
            <label class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ __('Identity Photo') }}</label>
            <div class="flex items-center gap-6">
                <div class="relative">
                    <template x-if="!photoPreview">
                        <img src="{{ $user->profile_photo_url }}" class="w-20 h-20 rounded-2xl object-cover border-2 border-slate-100 shadow-sm">
                    </template>
                    <template x-if="photoPreview">
                        <img :src="photoPreview" class="w-20 h-20 rounded-2xl object-cover border-2 border-indigo-500 shadow-md">
                    </template>
                    <label for="profile_photo" class="absolute -bottom-2 -right-2 p-2 bg-white rounded-xl shadow-lg border border-slate-100 cursor-pointer hover:bg-slate-50 transition-colors">
                        <i data-lucide="camera" class="w-4 h-4 text-indigo-600"></i>
                        <input type="file" id="profile_photo" name="profile_photo" class="hidden" @change="updatePreview">
                    </label>
                </div>
                <div class="flex-1">
                    <p class="text-[11px] text-slate-500 font-medium leading-relaxed">JPG, PNG or GIF. Max size 2MB.<br>Upload a professional photo for system identity.</p>
                </div>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Name -->
            <div class="space-y-2">
                <x-input-label for="name" :value="__('Full Name')" class="text-[11px] font-black text-slate-400 uppercase tracking-widest" />
                <x-text-input id="name" name="name" type="text" class="w-full bg-slate-50/50 border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl py-3" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <!-- Email -->
            <div class="space-y-2">
                <x-input-label for="email" :value="__('Email Address')" class="text-[11px] font-black text-slate-400 uppercase tracking-widest" />
                <x-text-input id="email" name="email" type="email" class="w-full bg-slate-50/50 border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl py-3" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div>
                        <p class="text-sm mt-2 text-gray-800">
                            {{ __('Your email address is unverified.') }}

                            <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <!-- System Locale -->
            <div class="space-y-2">
                <x-input-label for="locale" :value="__('System Localization')" class="text-[11px] font-black text-slate-400 uppercase tracking-widest" />
                <select id="locale" name="locale" class="w-full bg-slate-50/50 border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl py-3 text-sm font-bold text-slate-700">
                    <option value="en" {{ old('locale', $user->locale) === 'en' ? 'selected' : '' }}>English (US)</option>
                    <option value="id" {{ old('locale', $user->locale) === 'id' ? 'selected' : '' }}>Bahasa Indonesia (ID)</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('locale')" />
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="btn-premium px-8 py-3">
                <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                {{ __('Update Profile') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm font-bold text-emerald-600 flex items-center gap-2"
                >
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                    {{ __('Saved successfully.') }}
                </p>
            @endif
        </div>
    </form>
</section>
