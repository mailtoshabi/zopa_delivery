<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('translation.Error_403'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('body'); ?>

<body>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="my-5 pt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center mb-5 pt-5">
                        <h1 class="error-title mt-5"><span>403!</span></h1>
                        <h4 class="text-uppercase mt-5">Sorry, page not found</h4>
                        <p class="font-size-15 mx-auto text-muted w-50 mt-4"><?php echo e($exception->getMessage() ?: 'It will be as simple as Occidental in fact, it will Occidental to an English person'); ?></p>
                        <div class="mt-5 text-center">
                            <a class="btn btn-primary waves-effect waves-light" href="<?php echo e(url('/')); ?>">Back to Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end container -->
    </div>
    <!-- end content -->
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\errors\403.blade.php ENDPATH**/ ?>