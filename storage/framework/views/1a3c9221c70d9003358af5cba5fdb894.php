<?php $__env->startSection('title'); ?> <?php echo app('translator')->get('translation.Customers'); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<link href="<?php echo e(URL::asset('/assets/libs/datatables.net-bs4/datatables.net-bs4.min.css')); ?>" rel="stylesheet">
<link href="<?php echo e(URL::asset('assets/libs/datatables.net-responsive-bs4/datatables.net-responsive-bs4.min.css')); ?>" rel="stylesheet" type="text/css" />

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('admin.dir_components.breadcrumb'); ?>
<?php $__env->slot('li_1'); ?> <?php echo app('translator')->get('translation.Account_Manage'); ?> <?php $__env->endSlot(); ?>
<?php $__env->slot('li_2'); ?> <?php echo app('translator')->get('translation.Customer_Manage'); ?> <?php $__env->endSlot(); ?>
<?php $__env->slot('title'); ?> <?php echo app('translator')->get('translation.Customer_List'); ?> <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>
<?php if(session()->has('success')): ?>
<div class="alert alert-success alert-top-border alert-dismissible fade show" role="alert">
    <i class="mdi mdi-check-all me-3 align-middle text-success"></i><strong>Success</strong> - <?php echo e(session()->get('success')); ?>

</div>
<?php endif; ?>
<!-- Bootstrap Tabs -->
    <ul class="nav nav-tabs" id="customerTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="individual-tab" data-bs-toggle="tab" href="#individuals" role="tab">
                Individual
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="institution-tab" data-bs-toggle="tab" href="#institutions" role="tab">
                Institution
            </a>
        </li>
    </ul>
<div class="row">
    <div class="col-lg-12">
        <div class="card mb-0">
            <div class="card-body">
                <div class="tab-content p-3 text-muted">
                    <div class="tab-pane customerdetailsTab active" role="tabpanel">
                        <div class="row align-items-center">

                            <div class="tab-content mt-3" id="customerTabsContent">
                                <div class="tab-pane fade show active" id="individuals" role="tabpanel">
                                    <?php echo $__env->make('admin.customers.table', ['customers' => $individuals], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                </div>
                                <div class="tab-pane fade" id="institutions" role="tabpanel">
                                    <?php echo $__env->make('admin.customers.table', ['customers' => $institutions], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                </div>
                            </div>


                         <!-- end table responsive -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script src="<?php echo e(URL::asset('assets/libs/datatables.net/datatables.net.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('assets/libs/datatables.net-bs4/datatables.net-bs4.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('assets/libs/datatables.net-responsive/datatables.net-responsive.min.js')); ?>"></script>

<script src="<?php echo e(URL::asset('assets/js/pages/datatable-pages.init.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\admin\customers\index.blade.php ENDPATH**/ ?>