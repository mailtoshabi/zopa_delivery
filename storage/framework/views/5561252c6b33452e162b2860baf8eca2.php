<div class="flash-messages">
<script>
    <?php if(session('success')): ?>
        showToast('<?php echo e(session('success')); ?>', 'success');
    <?php endif; ?>

    <?php if(session('error')): ?>
        showToast('<?php echo e(session('error')); ?>', 'danger');
    <?php endif; ?>
</script>
</div>
<?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\components\flash-messages.blade.php ENDPATH**/ ?>