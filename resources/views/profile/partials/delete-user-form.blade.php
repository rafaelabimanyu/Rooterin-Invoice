<section class="space-y-6">
    <div class="flex items-start gap-4">
        <div class="flex-1">
            <h3 class="text-sm font-black text-rose-900 uppercase tracking-widest mb-2">
                {{ __('Delete Account') }}
            </h3>
            <p class="text-sm text-rose-600 font-medium">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please download any data or information that you wish to retain.') }}
            </p>
        </div>
    </div>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-8 py-3 rounded-xl font-bold uppercase tracking-widest text-xs"
    >{{ __('Terminate Account') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-10">
            @csrf
            @method('delete')

            <h2 class="text-2xl font-black text-slate-900 font-jakarta tracking-tight">
                {{ __('Are you absolutely sure?') }}
            </h2>

            <p class="mt-4 text-sm text-slate-500 font-medium leading-relaxed">
                {{ __('This action is permanent and cannot be undone. All your invoices, receipts, and history will be wiped from the system. Please enter your password to confirm identity.') }}
            </p>

            <div class="mt-8 space-y-2">
                <x-input-label for="password" value="{{ __('Identity Verification') }}" class="text-[10px] font-black text-slate-400 uppercase tracking-widest" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="w-full bg-slate-50 border-slate-200 focus:border-rose-500 focus:ring-rose-500 rounded-xl py-3"
                    placeholder="{{ __('Current Password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-10 flex justify-end gap-4">
                <button type="button" x-on:click="$dispatch('close')" class="px-6 py-3 text-sm font-bold text-slate-500 hover:text-slate-700 transition-colors">
                    {{ __('Cancel') }}
                </button>

                <x-danger-button class="px-8 py-3 rounded-xl font-bold uppercase tracking-widest text-xs">
                    {{ __('Confirm Termination') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
