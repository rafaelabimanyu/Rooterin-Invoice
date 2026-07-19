<!-- Modal 2: Suspend/Restore Confirmation Modal -->
<div x-show="showSuspendModal" 
     class="relative z-[100]" 
     x-cloak 
     style="display: none;" 
     wire:key="modal-suspend"
>
    <!-- BACKDROP OVERLAY -->
    <div x-show="showSuspendModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] bg-slate-950/45 backdrop-blur-sm"
    ></div>

    <!-- SCROLLABLE AREA -->
    <div x-show="showSuspendModal" 
         class="fixed inset-0 z-[101] overflow-y-auto"
    >
        <!-- ALIGNMENT CONTAINER -->
        <div class="flex min-h-full justify-center py-10 px-4 sm:px-6" @click.self="showSuspendModal = false">
            <!-- MAIN MODAL CONTAINER -->
            <div x-show="showSuspendModal" 
                 x-transition:enter="transition ease-out duration-300" 
                 x-transition:enter-start="opacity-0 scale-95 -translate-y-4" 
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
                 x-transition:leave="transition ease-in duration-200" 
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0" 
                 x-transition:leave-end="opacity-0 scale-95 -translate-y-4" 
                 class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl h-fit transform overflow-hidden transition-all text-left"
            >
                <!-- Body -->
                <div class="p-6 text-center bg-white">
                    <!-- Skeleton Shimmer Loader -->
                    <div wire:loading wire:target="confirmSuspend" class="animate-pulse space-y-4 py-4">
                        <div class="w-12 h-12 bg-slate-100 rounded-2xl mx-auto"></div>
                        <div class="h-4 w-1/3 bg-slate-200 rounded mx-auto"></div>
                        <div class="space-y-2">
                            <div class="h-3 w-5/6 bg-slate-100 rounded mx-auto"></div>
                            <div class="h-3 w-4/6 bg-slate-100 rounded mx-auto"></div>
                        </div>
                    </div>

                    <!-- Actual Content -->
                    <div wire:loading.remove wire:target="confirmSuspend" class="space-y-4">
                        <div class="w-12 h-12 rounded-2xl mx-auto flex items-center justify-center border-2 {{ $userToSuspend?->is_active ? 'bg-rose-50 border-rose-200 text-rose-600' : 'bg-emerald-50 border-emerald-200 text-emerald-600' }}">
                            <i data-lucide="{{ $userToSuspend?->is_active ? 'shield-off' : 'shield' }}" class="w-6 h-6"></i>
                        </div>
                        
                        <div class="space-y-1">
                            <h3 class="text-base font-black text-slate-900 uppercase tracking-tight">
                                {{ $userToSuspend?->is_active ? (app()->getLocale() == 'en' ? 'Suspend Access?' : 'Nonaktifkan Akses?') : (app()->getLocale() == 'en' ? 'Restore Access?' : 'Pulihkan Akses?') }}
                            </h3>
                            <p class="text-xs text-slate-500 font-medium leading-relaxed">
                                @if($userToSuspend?->is_active)
                                    {{ app()->getLocale() == 'en' ? 'Are you sure you want to suspend security clearance for ' : 'Apakah Anda yakin ingin menonaktifkan izin masuk untuk ' }}
                                    <strong>{{ $userToSuspend?->name }}</strong>?
                                    {{ app()->getLocale() == 'en' ? 'They will be locked out of the J&J GROUP system immediately.' : 'Staf ini akan langsung terblokir dan tidak dapat login ke sistem J&J GROUP.' }}
                                @else
                                    {{ app()->getLocale() == 'en' ? 'Are you sure you want to restore access clearance for ' : 'Apakah Anda yakin ingin memulihkan kembali izin masuk untuk ' }}
                                    <strong>{{ $userToSuspend?->name }}</strong>?
                                    {{ app()->getLocale() == 'en' ? 'They will be allowed to log back in.' : 'Staf ini akan diizinkan kembali untuk masuk ke sistem.' }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-center gap-3">
                    <button type="button" @click="showSuspendModal = false" class="flex-1 py-2.5 text-xs font-bold text-slate-500 hover:text-slate-800 bg-white border border-slate-200 rounded-lg transition-all" wire:loading.attr="disabled" wire:target="toggleSuspend">{{ app()->getLocale() == 'en' ? 'Cancel' : 'Batal' }}</button>
                    <button type="button" wire:click="toggleSuspend" class="flex-1 py-2.5 text-xs font-black uppercase tracking-wider text-white rounded-lg shadow-lg transition-all flex items-center justify-center gap-2 {{ $userToSuspend?->is_active ? 'bg-rose-600 hover:bg-rose-500 shadow-rose-600/10' : 'bg-emerald-600 hover:bg-emerald-500 shadow-emerald-600/10' }}" wire:loading.attr="disabled" wire:target="toggleSuspend">
                        <span wire:loading.remove wire:target="toggleSuspend">
                            {{ $userToSuspend?->is_active ? (app()->getLocale() == 'en' ? 'Suspend Account' : 'Nonaktifkan Akses') : (app()->getLocale() == 'en' ? 'Restore Account' : 'Pulihkan Akses') }}
                        </span>
                        <span wire:loading wire:target="toggleSuspend" class="flex items-center gap-1.5 justify-center">
                            <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
