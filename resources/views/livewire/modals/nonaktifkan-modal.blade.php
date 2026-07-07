<!-- Modal 2: Suspend/Restore Confirmation Modal -->
<div x-show="showSuspendModal" 
     x-transition:enter="transition ease-out duration-200" 
     x-transition:enter-start="opacity-0" 
     x-transition:enter-end="opacity-100" 
     x-transition:leave="transition ease-in duration-150" 
     x-transition:leave-start="opacity-100" 
     x-transition:leave-end="opacity-0" 
     class="fixed inset-0 z-[100] flex items-center justify-center p-4" 
     x-cloak 
     style="display: none;" 
     wire:key="modal-suspend"
>
    <!-- BACKDROP OVERLAY (Latar Hitam Transparan) -->
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="showSuspendModal = false"></div>

    <!-- MAIN MODAL CONTAINER (Wajib Relative & Overflow Hidden) -->
    <div x-show="showSuspendModal" 
         x-transition:enter="transition ease-out duration-200" 
         x-transition:enter-start="opacity-0 scale-95" 
         x-transition:enter-end="opacity-100 scale-100" 
         x-transition:leave="transition ease-in duration-150" 
         x-transition:leave-start="opacity-100 scale-100" 
         x-transition:leave-end="opacity-0 scale-95" 
         class="relative bg-white rounded-2xl shadow-xl w-full max-w-md max-h-[90vh] flex flex-col overflow-hidden z-10"
    >
        <!-- TOTAL OVERLAY LOADING SCREEN (Mengunci Sempurna di Dalam Box Putih) -->
        <div wire:loading wire:target="confirmSuspend, toggleSuspend" class="absolute inset-0 bg-white/90 z-50 flex flex-col items-center justify-center">
            <div class="flex flex-col items-center gap-3">
                <div class="w-10 h-10 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
                <span class="text-xs font-bold text-slate-600 tracking-wide">Sinkronisasi Data...</span>
            </div>
        </div>

        <!-- Body -->
        <div class="p-6 overflow-y-auto space-y-4 flex-1 text-center bg-white">
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

        <!-- Footer -->
        <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-center gap-3">
            <button type="button" @click="showSuspendModal = false" class="flex-1 py-2.5 text-xs font-bold text-slate-500 hover:text-slate-800 bg-white border border-slate-200 rounded-lg transition-all">{{ app()->getLocale() == 'en' ? 'Cancel' : 'Batal' }}</button>
            <button type="button" wire:click="toggleSuspend" class="flex-1 py-2.5 text-xs font-black uppercase tracking-wider text-white rounded-lg shadow-lg transition-all flex items-center justify-center gap-2 {{ $userToSuspend?->is_active ? 'bg-rose-600 hover:bg-rose-500 shadow-rose-600/10' : 'bg-emerald-600 hover:bg-emerald-500 shadow-emerald-600/10' }}">
                {{ $userToSuspend?->is_active ? (app()->getLocale() == 'en' ? 'Suspend Account' : 'Nonaktifkan Akses') : (app()->getLocale() == 'en' ? 'Restore Account' : 'Pulihkan Akses') }}
            </button>
        </div>
    </div>
</div>
