<?php echo $__env->make('layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<main class="flex-grow-1">
    <?php echo $__env->yieldContent('content'); ?>
</main>

<?php echo $__env->make('layouts.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views/layouts/app.blade.php ENDPATH**/ ?>