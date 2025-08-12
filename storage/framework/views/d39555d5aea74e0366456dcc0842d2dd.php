<?php $__env->startSection('title'); ?> <?php echo app('translator')->get('translation.Branch_Details'); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<link href="<?php echo e(URL::asset('assets/libs/select2/select2.min.css')); ?>" rel="stylesheet">
<link href="<?php echo e(URL::asset('assets/libs/dropzone/dropzone.min.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('admin.dir_components.breadcrumb'); ?>
<?php $__env->slot('li_1'); ?> <?php echo app('translator')->get('translation.Account_Manage'); ?> <?php $__env->endSlot(); ?>
<?php $__env->slot('li_2'); ?> <?php echo app('translator')->get('translation.Branch_Manage'); ?> <?php $__env->endSlot(); ?>
<?php $__env->slot('title'); ?> Details of <?php echo e($branch->name); ?> <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">

                    <div class="col-xl-6">
                        <div class="mt-4 mt-xl-3">
                            <a href="javascript: void(0);" class="text-primary">Created on <?php echo e($branch->created_at->format('d-m-Y')); ?></a>
                            <h4 class="mt-1 mb-3"><?php echo e($branch->name); ?></h4>

                            
                            

                            <?php if (! (empty($branch->phone))): ?>
                                <h6 class="text-primary"><i class="fa fa-phone-square font-size-16 align-middle text-primary me-1"></i><?php echo e($branch->phone); ?></h6>
                            <?php endif; ?>
                            <?php if (! (empty($branch->email))): ?>
                            <h6 class="text-success"><i class="fa fa-envelope font-size-16 align-middle text-success me-1"></i><?php echo e($branch->email); ?></h6>
                            <?php endif; ?>
                            <?php if (! (empty($branch->website))): ?>
                            <h6 class="text-danger"><i class="fa fa-globe font-size-16 align-middle text-success me-1"></i><?php echo e($branch->website); ?></h6>
                            <?php endif; ?>
                            
                            <?php if (! (empty($branch->address1))): ?><p class="text-muted mb-0"><?php echo e($branch->address1); ?></p><?php endif; ?>
                            <?php if (! (empty($branch->address2))): ?><p class="text-muted mb-0"><?php echo e($branch->address2); ?></p><?php endif; ?>
                            <?php if (! (empty($branch->address3))): ?><p class="text-muted mb-0"><?php echo e($branch->address3); ?></p><?php endif; ?>
                            <?php if (! (empty($branch->city))): ?>
                            <p class="text-muted mb-0"><?php echo e($branch->city); ?></p>
                            <?php endif; ?>
                            
                            
                            <?php if (! (empty($branch->postal_code))): ?>
                            <p class="text-muted mb-4"><?php echo e($branch->postal_code); ?></p>
                            <?php endif; ?>
                            

                            


                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="product-detai-imgs">
                            <div class="row">
                                
                                <div class="col-md-6 offset-md-1 col-sm-9 col-8">
                                    <div class="tab-content" id="v-pills-tabContent">
                                        <div class="tab-pane fade show active" id="product-1" role="tabpanel" aria-labelledby="product-1-tab">
                                            <div>
                                                <img src="https://place-hold.it/800x800?text=<?php echo e($branch->name); ?>&fontsize=40" alt="" class="img-fluid mx-auto d-block">
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="text-center">
                                        
                                        
                                        
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <!-- end Specifications -->
            </div>
        </div>
        <!-- end card -->
    </div>
</div>
<!-- end row -->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\admin\branches\view.blade.php ENDPATH**/ ?>