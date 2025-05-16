<?php $__env->startSection('title'); ?> <?php echo app('translator')->get('translation.Add_Ingredient'); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<link href="<?php echo e(URL::asset('assets/libs/select2/select2.min.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('admin.dir_components.breadcrumb'); ?>
<?php $__env->slot('li_1'); ?> <?php echo app('translator')->get('translation.Inventory_Manage'); ?> <?php $__env->endSlot(); ?>
<?php $__env->slot('li_2'); ?> <?php echo app('translator')->get('translation.Ingredient_Manage'); ?> <?php $__env->endSlot(); ?>
<?php $__env->slot('title'); ?> <?php if(isset($ingredient)): ?> <?php echo app('translator')->get('translation.Edit_Ingredient'); ?> <?php else: ?> <?php echo app('translator')->get('translation.Add_Ingredient'); ?> <?php endif; ?> <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row">
    <form method="POST" action="<?php echo e(isset($ingredient)? route('admin.ingredients.update', encrypt($ingredient->id)) : route('admin.ingredients.store')); ?>">
        <?php echo csrf_field(); ?>
        <?php if(isset($ingredient)): ?>
            <?php echo method_field('PUT'); ?>
        <?php endif; ?>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><?php echo app('translator')->get('translation.Ingredient'); ?> Details</h4>
                    <p class="card-title-desc required"><?php echo e(isset($ingredient)? 'Edit' : "Enter"); ?> the Details of the <?php echo app('translator')->get('translation.Ingredient'); ?>, fields marked with <label></label> are mandatory.</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3 required">
                                <label for="name"><?php echo app('translator')->get('translation.Name'); ?></label>
                                <input id="name" name="name" type="text" class="form-control" placeholder="<?php echo app('translator')->get('translation.Name'); ?>" value="<?php echo e(isset($ingredient)? $ingredient->name : old('name')); ?>">
                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="mb-3">
                                <label for="description"><?php echo app('translator')->get('translation.Description'); ?></label>
                                <textarea id="description" name="description" class="form-control" placeholder="<?php echo app('translator')->get('translation.Description'); ?>"><?php echo e(isset($ingredient)? $ingredient->description : old('description')); ?></textarea>
                                <?php $__errorArgs = ['description'];
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
                                <label for="status"><?php echo app('translator')->get('translation.Status'); ?></label>
                                <select name="status" id="i_status" class="form-control select2">
                                    <option value="1" <?php echo e((isset($ingredient) && $ingredient->status == 1) ? 'selected' : ''); ?>><?php echo app('translator')->get('translation.Active'); ?></option>
                                    <option value="0" <?php echo e((isset($ingredient) && $ingredient->status == 0) ? 'selected' : ''); ?>><?php echo app('translator')->get('translation.Inactive'); ?></option>
                                </select>
                                <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit -->
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(URL::asset('assets/libs/select2/select2.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('/assets/js/app.min.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\admin\ingredients\create.blade.php ENDPATH**/ ?>