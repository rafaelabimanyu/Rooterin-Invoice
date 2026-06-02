<div class="space-y-6 w-full min-w-0">
    <!-- Top Filter Bar & Header -->
    <?php echo $__env->make('chronos.components.header-filter', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Main Calendar Grid -->
    <?php echo $__env->make('chronos.components.calendar-grid', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Modals & Overlay Preview Tooltip -->
    <?php echo $__env->make('chronos.components.modal-reminder', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>
<?php /**PATH C:\laragon\www\Rooterin-Invoice\resources\views/livewire/chronos-calendar.blade.php ENDPATH**/ ?>