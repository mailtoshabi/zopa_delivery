<?php $__env->startSection('title'); ?> <?php echo e(isset($meal) ? __('Edit Meal') : __('Add Meal')); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<link href="<?php echo e(URL::asset('assets/libs/select2/select2.min.css')); ?>" rel="stylesheet">
<link href="<?php echo e(URL::asset('assets/libs/dropzone/dropzone.min.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php
    $isEdit = isset($meal);
?>
<?php $__env->startSection('content'); ?>

<?php $__env->startComponent('admin.dir_components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Meal Management <?php $__env->endSlot(); ?>
    <?php $__env->slot('li_2'); ?> Meals <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> <?php echo e($isEdit ? 'Edit Meal' : 'Add Meal'); ?> <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>



<form action="<?php echo e($isEdit ? route('admin.meals.update', encrypt($meal->id)) : route('admin.meals.store')); ?>"
      method="POST" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <?php if($isEdit): ?>
        <?php echo method_field('PUT'); ?>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-12 mb-3" id="category_div">
            <label for="category_id">Category</label>
            <select name="category_id" id="category_id" class="form-select" required>
                <option value="">Select Category</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id', $meal->category_id ?? '') == $category->id ? 'selected' : ''); ?>>
                        <?php echo e($category->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="col-md-3 mb-3" id="mess_category_div">
            <label for="mess_category_id">Mess Category</label>
            <select name="mess_category_id" id="mess_category_id" class="form-select" required>
                <option value="">Select Mess Category</option>
                <?php $__currentLoopData = $mess_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mess_category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($mess_category->id); ?>" <?php echo e(old('mess_category_id', $meal->mess_category_id ?? '') == $mess_category->id ? 'selected' : ''); ?>>
                        <?php echo e($mess_category->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['mess_category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="col-md-3 mb-3" id="wallet_group_div">
            <label for="wallet_group_id">Wallet Groups</label>
            <select name="wallet_group_id" id="wallet_group_id" class="form-select" required>
                <option value="">Select Group</option>
                <?php $__currentLoopData = $wallet_groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wallet_group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($wallet_group->id); ?>" <?php echo e(old('wallet_group_id', $meal->wallet_group_id ?? '') == $wallet_group->id ? 'selected' : ''); ?>>
                        <?php echo e($wallet_group->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['wallet_group_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>

    <div class="row">
        
        <div class="col-md-6 mb-3">
            <label for="name" class="form-label">Meal Name</label>
            <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $meal->name ?? '')); ?>" required>
            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        
        <div class="col-md-6 mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="text" name="price" class="form-control" value="<?php echo e(old('price', $meal->price ?? '')); ?>" required>
            <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="col-md-6 mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" name="quantity" class="form-control" value="<?php echo e(old('quantity', $meal->quantity ?? 0)); ?>" required>
            <?php $__errorArgs = ['quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        
        <div class="col-md-6 mb-3">
            <label for="order" class="form-label">Display Order</label>
            <input type="number" name="order" class="form-control" value="<?php echo e(old('order', $meal->order ?? 0)); ?>">
        </div>

        
        

        
        <div class="col-md-6 mb-3">
            <label for="ingredients" class="form-label"><?php echo app('translator')->get('translation.Ingredients'); ?></label>
            <select name="ingredient_ids[]" id="ingredients" class="form-control select2" multiple>
                <option value="">Select</option>
                <?php $__currentLoopData = $ingredients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $ingredient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($key); ?>"
                        <?php if(isset($meal) && $meal->ingredients->contains($key)): ?> selected <?php endif; ?>>
                        <?php echo e($ingredient); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label for="remarks" class="form-label"><?php echo app('translator')->get('translation.Remark_List'); ?></label>
            <select name="remark_ids[]" id="remarks" class="form-control select2" multiple>
                <option value="">Select</option>
                <?php $__currentLoopData = $remarks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $remarks): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($key); ?>"
                        <?php if(isset($meal) && $meal->remarks->contains($key)): ?> selected <?php endif; ?>>
                        <?php echo e($remarks); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        
        

        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Image</h4>
                <p class="card-title-desc">Upload Image of your <?php echo app('translator')->get('translation.meal'); ?>, if any</p>
            </div>
            <div class="card-body">

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Main Image</label>
                                <span id="imageContainer" <?php if(isset($meal)&&empty($meal->image_filename)): ?> style="display: none" <?php endif; ?>>
                                    <?php if(isset($meal)&&!empty($meal->image_filename)): ?>
                                        <img src="<?php echo e(Storage::url(App\Models\Meal::DIR_PUBLIC. '/' . $meal->image_filename)); ?>" alt="" class="avatar-xxl rounded-circle me-2">
                                        <button type="button" class="btn-close" aria-label="Close">x</button>
                                    <?php endif; ?>
                                </span>

                                <span id="fileContainer" <?php if(isset($meal)&&!empty($meal->image_filename)): ?> style="display: none" <?php endif; ?>>
                                    <div class="d-flex align-items-center gap-2">
                                    <input id="image" name="image" type="file" class="form-control"  placeholder="File">
                                    <?php if(isset($meal)&&!empty($meal->image_filename)): ?>
                                        <button type="button" class="btn-close" aria-label="Close">x</button>
                                    <?php endif; ?>
                                    </div>
                                </span>
                                <input name="isImageDelete" type="hidden" value="0">
                            </div>
                        </div>

                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Additional Images</label>
                            <input type="file" name="additional_images[]" class="form-control" multiple>
                            <?php if(!empty($meal->additional_images)): ?>
                                <div class="mt-2 d-flex flex-wrap gap-2">
                                    <?php $__currentLoopData = json_decode($meal->additional_images); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <img src="<?php echo e(asset('storage/meals/' . $image)); ?>" alt="additional" class="img-thumbnail" width="80">
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

            </div>

        </div> <!-- end card-->


    </div>

    <button type="submit" class="btn btn-primary mb-3"><?php echo e($isEdit ? 'Update' : 'Create'); ?> Meal</button>
</form>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(URL::asset('assets/libs/select2/select2.min.js')); ?>"></script>

<script>
    $(document).ready(function() {
        $('#imageContainer').find('button').click(function(e) {
            e.preventDefault();
            if (!confirm('Are you sure you want to delete?')) return;
            $('#imageContainer').hide();
            $('#fileContainer').show();
            $('input[name="isImageDelete"]').val(1);
        });

        $('#fileContainer').find('button').click(function(e) {
            e.preventDefault();
            $('#fileContainer').hide();
            $('#imageContainer').show();
            $('input[name="isImageDelete"]').val(0);
        });
    });
</script>

<script>
    $(document).ready(function() {
        // Function to toggle visibility and classes
        function toggleFields() {
            const selectedVal = $('#category_id').val();

            if (selectedVal == '1') {
                $('#mess_category_div, #wallet_group_div').show();
                $('#category_div').removeClass('col-md-12').addClass('col-md-6');
            } else {
                $('#mess_category_div, #wallet_group_div').hide();
                $('#category_div').removeClass('col-md-6').addClass('col-md-12');
            }
        }

        // Initial hide on page load
        $('#mess_category_div, #wallet_group_div').hide();

        // Bind change event
        $('#category_id').on('change', toggleFields);

        // Call on page load to reflect selected value if any
        toggleFields();
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\admin\meals\create.blade.php ENDPATH**/ ?>