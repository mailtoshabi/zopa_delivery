<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8" />
    <title> <?php echo $__env->yieldContent('title'); ?> | ZOPA - Food Drop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <?php echo $__env->make('admin.layouts.head-css', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</head>

<?php $__env->startSection('body'); ?>
    <?php echo $__env->make('admin.layouts.body', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->yieldSection(); ?>

    <!-- Begin page -->
    <div id="layout-wrapper">
 <body data-layout="horizontal">

        <?php echo $__env->make('admin.layouts.horizontal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <!-- Start content -->
                <div class="container-fluid">
                    <?php echo $__env->yieldContent('content'); ?>
                </div> <!-- content -->
            </div>
            <?php echo $__env->make('admin.layouts.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
        <!-- ============================================================== -->
        <!-- End Right content here -->
        <!-- ============================================================== -->
    </div>
    <!-- END wrapper -->

    <!-- Right Sidebar -->
    <?php echo $__env->make('admin.layouts.right-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!-- END Right Sidebar -->

    <?php echo $__env->make('admin.layouts.vendor-scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\admin\layouts\master-layouts.blade.php ENDPATH**/ ?>