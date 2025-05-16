<?php $__env->startSection('title'); ?> <?php echo e(isset($addon) ? __('Edit Addon') : __('Add Addon')); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<link href="<?php echo e(URL::asset('assets/libs/select2/select2.min.css')); ?>" rel="stylesheet">
<link href="<?php echo e(URL::asset('assets/libs/dropzone/dropzone.min.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php
    $isEdit = isset($addon);
?>
<?php $__env->startSection('content'); ?>

<?php $__env->startComponent('admin.dir_components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Meal Management <?php $__env->endSlot(); ?>
    <?php $__env->slot('li_2'); ?> Addons <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> <?php echo e($isEdit ? 'Edit Addon' : 'Add Addon'); ?> <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>



<form action="<?php echo e($isEdit ? route('admin.addons.update', encrypt($addon->id)) : route('admin.addons.store')); ?>"
      method="POST" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <?php if($isEdit): ?>
        <?php echo method_field('PUT'); ?>
    <?php endif; ?>

    <div class="row">
        
        <div class="col-md-6 mb-3">
            <label for="name" class="form-label">Addon Name</label>
            <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $addon->name ?? '')); ?>" required>
        </div>

        
        <div class="col-md-6 mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="text" name="price" class="form-control" value="<?php echo e(old('price', $addon->price ?? '')); ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" class="form-control"><?php echo e(old('description', $addon->description ?? '')); ?></textarea>
        </div>

        

        
        <div class="col-md-6 mb-3">
            <label for="order" class="form-label">Display Order</label>
            <input type="number" name="order" class="form-control" value="<?php echo e(old('order', $addon->order ?? 0)); ?>">
        </div>


        
        

        




        
        

        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Image</h4>
                <p class="card-title-desc">Upload Image of your <?php echo app('translator')->get('translation.addon'); ?>, if any</p>
            </div>
            <div class="card-body">

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="mb-3">
                                <span id="imageContainer" <?php if(isset($addon)&&empty($addon->image_filename)): ?> style="display: none" <?php endif; ?>>
                                    <?php if(isset($addon)&&!empty($addon->image_filename)): ?>
                                        <img src="<?php echo e(Storage::url(App\Models\Addon::DIR_PUBLIC. '/' . $addon->image_filename)); ?>" alt="" class="avatar-xxl rounded-circle me-2">
                                        <button type="button" class="btn-close" aria-label="Close"></button>
                                    <?php endif; ?>
                                </span>

                                <span id="fileContainer" <?php if(isset($addon)&&!empty($addon->image_filename)): ?> style="display: none" <?php endif; ?>>
                                    <input id="image" name="image" type="file" class="form-control"  placeholder="File">
                                    <?php if(isset($addon)&&!empty($addon->image_filename)): ?>
                                        <button type="button" class="btn-close" aria-label="Close"></button>
                                    <?php endif; ?>
                                </span>
                                <input name="isImageDelete" type="hidden" value="0">
                            </div>
                        </div>
                    </div>

            </div>

        </div> <!-- end card-->

        
        <div class="col-md-6 mb-3">
            <label class="form-label">Additional Images</label>
            <input type="file" name="additional_images[]" class="form-control" multiple>
            <?php if(!empty($addon->additional_images)): ?>
                <div class="mt-2 d-flex flex-wrap gap-2">
                    <?php $__currentLoopData = json_decode($addon->additional_images); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <img src="<?php echo e(asset('storage/addons/' . $image)); ?>" alt="additional" class="img-thumbnail" width="80">
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <button type="submit" class="btn btn-primary"><?php echo e($isEdit ? 'Update' : 'Create'); ?> Addon</button>
</form>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(URL::asset('assets/libs/select2/select2.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('/assets/js/app.min.js')); ?>"></script>
<script>
    $(document).ready(function() {
        $('#imageContainer').find('button').click(function() {
            $('#imageContainer').hide();
            $('#fileContainer').show();
            $('input[name="isImageDelete"]').val(1);
        });

        $('#fileContainer').find('button').click(function() {
            $('#fileContainer').hide();
            $('#imageContainer').show();
            $('input[name="isImageDelete"]').val(0);
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\admin\addons\create.blade.php ENDPATH**/ ?>