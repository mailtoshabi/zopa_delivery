<?php $__env->startSection('title'); ?>  <?php if(isset($mess_category)): ?> <?php echo app('translator')->get('translation.Edit_MessCategory'); ?> <?php else: ?> <?php echo app('translator')->get('translation.Add_MessCategory'); ?> <?php endif; ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<link href="<?php echo e(URL::asset('assets/libs/select2/select2.min.css')); ?>" rel="stylesheet">
<link href="<?php echo e(URL::asset('assets/libs/dropzone/dropzone.min.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('admin.dir_components.breadcrumb'); ?>
<?php $__env->slot('li_1'); ?> <?php echo app('translator')->get('translation.Catalogue_Manage'); ?> <?php $__env->endSlot(); ?>
<?php $__env->slot('li_2'); ?> <?php echo app('translator')->get('translation.MessCategory_Manage'); ?> <?php $__env->endSlot(); ?>
<?php $__env->slot('title'); ?> <?php if(isset($mess_category)): ?> <?php echo app('translator')->get('translation.Edit_MessCategory'); ?> <?php else: ?> <?php echo app('translator')->get('translation.Add_MessCategory'); ?> <?php endif; ?> <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>
<div class="row">
    <form method="POST" action="<?php echo e(isset($mess_category)? route('admin.mess_categories.update') : route('admin.mess_categories.store')); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php if(isset($mess_category)): ?>
            <input type="hidden" name="mess_category_id" value="<?php echo e(encrypt($mess_category->id)); ?>" />
            <input type="hidden" name="_method" value="PUT" />
        <?php endif; ?>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Mess Category Details</h4>
                    <p class="card-title-desc"><?php echo e(isset($mess_category)? 'Edit' : "Enter"); ?> the Details of your Mess Category</p>
                </div>
                <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input id="name" name="name" type="text" class="form-control"  placeholder="Mess Category Name" value="<?php echo e(isset($mess_category)?$mess_category->name:old('name')); ?>">
                                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="display_order">Display Order</label>
                                    <input type="number" name="display_order" class="form-control" placeholder="Display Order" value="<?php echo e(isset($mess_category)?$mess_category->display_order:old('display_order')); ?>" >
                                    <?php $__errorArgs = ['display_order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <label class="control-label">Image</label>
                                    <span id="imageContainer" <?php if(isset($mess_category)&&empty($mess_category->image)): ?> style="display: none" <?php endif; ?>>
                                        <?php if(isset($mess_category)&&!empty($mess_category->image)): ?>
                                            <img src="<?php echo e(URL::asset(App\Models\MessCategory::DIR_STORAGE . $mess_category->image)); ?>" alt="" class="avatar-xxl rounded-circle me-2">
                                            <button type="button" class="btn-close" aria-label="Close">x</button>
                                        <?php endif; ?>
                                    </span>

                                    <span id="fileContainer" <?php if(isset($mess_category)&&!empty($mess_category->image)): ?> style="display: none" <?php endif; ?>>
                                        <div class="d-flex align-items-center gap-2">
                                        <input id="image" name="image" type="file" class="form-control"  placeholder="File">
                                        <?php if(isset($mess_category)&&!empty($mess_category->image)): ?>
                                            <button type="button" class="btn-close" aria-label="Close">x</button>
                                        <?php endif; ?>
                                        </div>
                                    </span>
                                    <input name="isImageDelete" type="hidden" value="0">
                                </div>
                            </div>

                        </div>
                </div>
            </div>

            

            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary waves-effect waves-light"><?php echo e(isset($mess_category) ? 'Update' : 'Save'); ?></button>
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

<script>
    $(document).ready(function() {
        $('#imageContainer').find('button').click(function(e) {
            e.preventDefault();
            if (!confirm('Are you sure you want to delete?')) return;
            $('#imageContainer').hide();
            $('#fileContainer').show();
            $('input[name="isImageDelete"]').val(1);
        })

        $('#fileContainer').find('button').click(function(e) {
            e.preventDefault();
            $('#fileContainer').hide();
            $('#imageContainer').show();
            $('input[name="isImageDelete"]').val(0);
        })
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\admin\mess_categories\add.blade.php ENDPATH**/ ?>