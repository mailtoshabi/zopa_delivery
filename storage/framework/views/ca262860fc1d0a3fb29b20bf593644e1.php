<?php $__env->startSection('title'); ?> <?php echo app('translator')->get('translation.General_Settings'); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<link href="<?php echo e(URL::asset('assets/libs/select2/select2.min.css')); ?>" rel="stylesheet">
<link href="<?php echo e(URL::asset('assets/libs/dropzone/dropzone.min.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('admin.dir_components.breadcrumb'); ?>
<?php $__env->slot('li_1'); ?> <?php echo app('translator')->get('translation.Account_Settings'); ?> <?php $__env->endSlot(); ?>
<?php $__env->slot('li_2'); ?> <?php echo app('translator')->get('translation.Settings'); ?> <?php $__env->endSlot(); ?>
<?php $__env->slot('title'); ?> <?php echo app('translator')->get('translation.General_Settings'); ?> <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>
<div class="row">
    <?php if(session()->has('success')): ?> <p class="text-success"><?php echo e(session()->get('success')); ?> <?php endif; ?></p>
    <?php if(session()->has('error')): ?> <p class="text-danger"><?php echo e(session()->get('error')); ?> <?php endif; ?></p>
    <form method="POST" action="<?php echo e(route('admin.settings.update.general')); ?>">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="_method" value="PUT" />
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Basic Information</h4>
                    <p class="card-title-desc">Fill all information below</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input id="name" name="name" type="text" class="form-control"  placeholder="Name" value="<?php echo e(Utility::settings('name')); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="trade_name">Trande Name</label>
                                <input id="trade_name" name="trade_name" type="text" class="form-control"  placeholder="Trande Name" value="<?php echo e(Utility::settings('trade_name')); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="email">Email</label>
                                <input id="email" name="email" type="text" class="form-control"  placeholder="Email" value="<?php echo e(Utility::settings('email')); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="mobile">Mobile No</label>
                                <input id="mobile" name="mobile" type="text" class="form-control" placeholder="Mobile No" value="<?php echo e(Utility::settings('phone')); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="address1">Address 1</label>
                                <input id="address1" name="address1" type="text" class="form-control" placeholder="Address 1" value="<?php echo e(Utility::settings('address1')); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="address2">Address 2</label>
                                <input id="address2" name="address2" type="text" class="form-control" placeholder="Address 2" value="<?php echo e(Utility::settings('address2')); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="address3">Address 3</label>
                                <input id="address3" name="address3" type="text" class="form-control" placeholder="Address 3" value="<?php echo e(Utility::settings('address3')); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="city">City</label>
                                <input id="city" name="city" type="text" class="form-control" placeholder="City" value="<?php echo e(Utility::settings('city')); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="district_id">District ID </label>
                                <input id="district_id" name="district_id" type="text" class="form-control" placeholder="District ID" value="<?php echo e(Utility::settings('district_id')); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="state_id">State ID </label>
                                <input id="state_id" name="state_id" type="text" class="form-control" placeholder="State ID" value="<?php echo e(Utility::settings('state_id')); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="postal_code">Postal Code </label>
                                <input id="postal_code" name="postal_code" type="text" class="form-control" placeholder="Postal Code" value="<?php echo e(Utility::settings('postal_code')); ?>">
                            </div>
                        </div>

                        <div class="col-sm-6">




                            <div class="mb-3">
                                <label for="website">Website </label>
                                <input id="website" name="website" type="text" class="form-control" placeholder="Website" value="<?php echo e(Utility::settings('website')); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="gstin">GSTIN </label>
                                <input id="gstin" name="gstin" type="text" class="form-control" placeholder="GSTIN" value="<?php echo e(Utility::settings('gstin')); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="cin">CIN </label>
                                <input id="cin" name="cin" type="text" class="form-control" placeholder="CIN" value="<?php echo e(Utility::settings('CIN')); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="pan">PAN </label>
                                <input id="pan" name="pan" type="text" class="form-control" placeholder="PAN" value="<?php echo e(Utility::settings('pan')); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="bank_id">Bank ID </label>
                                <input id="bank_id" name="bank_id" type="text" class="form-control" placeholder="Bank ID" value="<?php echo e(Utility::settings('bank_id')); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="account_name">Account Name </label>
                                <input id="account_name" name="account_name" type="text" class="form-control" placeholder="Account Name" value="<?php echo e(Utility::settings('account_name')); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="account_number">Account Number </label>
                                <input id="account_number" name="account_number" type="text" class="form-control" placeholder="Account Number" value="<?php echo e(Utility::settings('account_number')); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="bank_branch">Bank Branch </label>
                                <input id="bank_branch" name="bank_branch" type="text" class="form-control" placeholder="Bank Branch" value="<?php echo e(Utility::settings('bank_branch')); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="ifsc">IFSC </label>
                                <input id="ifsc" name="ifsc" type="text" class="form-control" placeholder="IFSc" value="<?php echo e(Utility::settings('ifsc')); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="default_branch_id">Default Branch ID</label>
                                <input id="default_branch_id" name="default_branch_id" type="text" class="form-control" placeholder="Default Branch ID" value="<?php echo e(Utility::settings('default_branch_id')); ?>">
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Save Changes</button>
                        <button type="reset" class="btn btn-secondary waves-effect waves-light">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- end row -->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script src="<?php echo e(URL::asset('assets/libs/select2/select2.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('assets/libs/dropzone/dropzone.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('assets/js/pages/ecommerce-select2.init.js')); ?>"></script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\admin\settings\general.blade.php ENDPATH**/ ?>