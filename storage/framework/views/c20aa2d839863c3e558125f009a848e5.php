<footer class="mt-auto border-t border-slate-200/50 bg-white/50 backdrop-blur-sm z-30 relative w-full">
    <div class="max-w-[1600px] mx-auto px-6 md:px-10 lg:px-14 py-4 md:py-3">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-3 sm:gap-8">
            <!-- Left Side: Copyright & Version -->
            <div class="flex flex-wrap justify-center sm:justify-start items-center gap-3 md:gap-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-tight text-center sm:text-left">
                    &copy; <?php echo e(date('Y')); ?> <span class="text-slate-700">Rooterin System Operational.</span> <span class="hidden md:inline">All rights reserved.</span>
                </p>
                
                <div class="hidden md:block h-3 w-px bg-slate-200/60"></div>
                
                <div class="flex items-center gap-2 px-2 py-0.5 bg-white/80 border border-slate-200/50 rounded-lg shadow-sm">
                    <div class="relative flex h-1.5 w-1.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-indigo-500"></span>
                    </div>
                    <span class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em]">v1.2.4-stable</span>
                </div>
            </div>

            <!-- Right Side: Navigation Links -->
            <div class="flex items-center gap-6 sm:gap-8 md:gap-10">
                <a href="<?php echo e(route('privacy.index')); ?>" class="text-[10px] font-black text-slate-400 hover:text-indigo-600 uppercase tracking-widest transition-colors duration-200">Privacy</a>
                <a href="<?php echo e(route('terms.index')); ?>" class="text-[10px] font-black text-slate-400 hover:text-indigo-600 uppercase tracking-widest transition-colors duration-200">Terms</a>
                <a href="<?php echo e(route('help.index')); ?>" class="text-[10px] font-black text-slate-400 hover:text-indigo-600 uppercase tracking-widest transition-colors duration-200">Help Center</a>
            </div>
        </div>
    </div>
</footer>
<?php /**PATH C:\laragon\www\Rooterin-Invoice\resources\views/components/footer.blade.php ENDPATH**/ ?>