<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

    <head>
        <meta charset="utf-8" />
        <title> <?php echo $__env->yieldContent('title'); ?> | ZOPA - Food Drop</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <meta content="ZOPA - Food Drop" name="description" />
        <meta content="Web Mahal Web Service" name="author" />
        <!-- Standard favicon -->
        <link rel="icon" href="<?php echo e(asset('assets/favicon/favicon.ico')); ?>" type="image/x-icon">

        <!-- For modern browsers -->
        <link rel="icon" type="image/png" sizes="32x32" href="<?php echo e(asset('assets/favicon/favicon-32x32.png')); ?>">
        <link rel="icon" type="image/png" sizes="16x16" href="<?php echo e(asset('assets/favicon/favicon-16x16.png')); ?>">

        <!-- Apple Touch Icon (iPhone/iPad) -->
        <link rel="apple-touch-icon" sizes="180x180" href="<?php echo e(asset('assets/favicon/apple-touch-icon.png')); ?>">

        <!-- Android Chrome Icons -->
        <link rel="icon" type="image/png" sizes="192x192" href="<?php echo e(asset('assets/favicon/android-chrome-192x192.png')); ?>">
        <link rel="icon" type="image/png" sizes="512x512" href="<?php echo e(asset('assets/favicon/android-chrome-512x512.png')); ?>">

        <!-- Microsoft Tiles -->
        <meta name="msapplication-TileColor" content="#ec1d23">
        <meta name="msapplication-TileImage" content="<?php echo e(asset('assets/favicon/android-chrome-192x192.png')); ?>">

        <!-- Theme Color (browser UI) -->
        <meta name="theme-color" content="#ec1d23">
        <?php echo $__env->make('kitchen.layouts.head-css', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  </head>

    <?php echo $__env->yieldContent('body'); ?>

    <?php echo $__env->yieldContent('content'); ?>

    <?php echo $__env->make('kitchen.layouts.vendor-scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </body>
</html>
<?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\kitchen\layouts\master-without-nav.blade.php ENDPATH**/ ?>