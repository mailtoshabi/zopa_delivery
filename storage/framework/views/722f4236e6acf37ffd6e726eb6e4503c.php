<?php $__env->startSection('title', 'You are offline ' . config('app.name')); ?>

<?php $__env->startSection('content'); ?>
<div class="flex flex-col items-center justify-center h-screen bg-gray-100 text-center px-6">
    <img src="<?php echo e(asset('icons/icon-192x192.png')); ?>" alt="<?php echo config('app.name'); ?> Logo" class="w-24 h-24 mb-4">

    <h1 class="text-2xl font-semibold text-gray-800 mb-2">⚠️ You're offline</h1>
    <p class="text-gray-600 mb-6">No internet connection detected.<br>Please check your network and try again.</p>

    <a href="<?php echo e(url('/')); ?>" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg shadow hover:bg-emerald-700 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10l9-7 9 7M4 10v10a1 1 0 001 1h3m10-11v10a1 1 0 001 1h3m-10-4h4" />
        </svg>
        Go back Home
    </a>

    <p class="text-xs text-gray-400 mt-8"><?php echo config('app.name'); ?> © <?php echo e(now()->year); ?></p>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\pages\offline.blade.php ENDPATH**/ ?>