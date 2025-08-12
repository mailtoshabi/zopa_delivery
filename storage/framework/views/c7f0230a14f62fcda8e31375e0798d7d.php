<?php $__env->startSection('title'); ?> <?php echo app('translator')->get('translation.Addons'); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<link href="<?php echo e(URL::asset('/assets/libs/datatables.net-bs4/datatables.net-bs4.min.css')); ?>" rel="stylesheet">
<link href="<?php echo e(URL::asset('assets/libs/datatables.net-responsive-bs4/datatables.net-responsive-bs4.min.css')); ?>" rel="stylesheet" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('kitchen.dir_components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> <?php echo app('translator')->get('translation.Meal_Manage'); ?> <?php $__env->endSlot(); ?>
    <?php $__env->slot('li_2'); ?> <?php echo app('translator')->get('translation.Addons'); ?> <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> <?php echo app('translator')->get('translation.Addon_List'); ?> <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<?php if(session()->has('success')): ?>
<div class="alert alert-success alert-top-border alert-dismissible fade show" role="alert">
    <i class="mdi mdi-check-all me-3 align-middle text-success"></i>
    <strong>Success</strong> - <?php echo e(session()->get('success')); ?>

</div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card mb-0">
            <div class="card-body">
                <div class="tab-content p-3 text-muted">
                    <div class="tab-pane active" role="tabpanel">
                        <div class="row align-items-center mb-3">
                            <div class="col-md-6">
                                <h5 class="card-title"><?php echo app('translator')->get('translation.Addon_List'); ?> <span class="text-muted fw-normal ms-2">(<?php echo e($addons->total()); ?>)</span></h5>
                            </div>
                        </div>

                        <div class="table-responsive mb-4">
                            <table class="table align-middle dt-responsive table-check nowrap" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th style="width: 80px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $addons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <?php if($addon->image_filename): ?>
                                                
                                                <img src="<?php echo e(Storage::url('addons/' . $addon->image_filename)); ?>" alt="" class="avatar-sm rounded-circle me-2">
                                            <?php else: ?>
                                            <div class="avatar-sm d-inline-block align-middle me-2">
                                                <div class="avatar-title bg-soft-primary text-primary font-size-20 m-0 rounded-circle">
                                                    <i class="bx bxs-user-circle"></i>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                            <a href="">
                                                <?php echo e($addon->name); ?>

                                            </a>
                                        </td>
                                        <td>₹<?php echo e($addon->kitchens->first()?->pivot?->price ?? $addon->price); ?></td>
                                        <td>
                                            <?php echo e($addon->description); ?>

                                        </td>

                                        <td>
                                            <?php
                                                $pivotStatus = $addon->kitchens->first()?->pivot?->status;
                                                $statusToShow = $pivotStatus ?? $addon->status;
                                            ?>

                                            <?php if($statusToShow): ?>
                                                <span class="badge bg-success">Active</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Inactive</span>
                                            <?php endif; ?>
                                        </td>

                                        <td><?php echo e($addon->created_at->format('d M Y')); ?></td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-link font-size-16 shadow-none py-0 text-muted dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bx bx-dots-horizontal-rounded"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item"
                                                        href="javascript:void(0);"
                                                        onclick="openManageAddonModal(
                                                            <?php echo e($addon->id); ?>,
                                                            '<?php echo e($addon->kitchens->first()?->pivot?->price ?? $addon->price); ?>',
                                                            <?php echo e($addon->kitchens->first()?->pivot?->status ?? $addon->status); ?>

                                                        )">
                                                            <i class="mdi mdi-pencil font-size-16 text-primary me-1"></i> Manage
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <div class="pagination justify-content-center mt-3">
                                <?php echo e($addons->links()); ?>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Manage Addon Modal -->
<div class="modal fade" id="manageAddonModal" tabindex="-1" aria-labelledby="manageAddonModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="manageAddonForm" method="POST" action="<?php echo e(route('kitchen.addons.update')); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="addon_id" id="addon_modal_id">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="manageAddonModalLabel">Manage Addon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="addon_modal_price" class="form-label">Price</label>
                        <input type="text" name="price" id="addon_modal_price" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="addon_modal_status" class="form-label">Status</label>
                        <select name="status" id="addon_modal_status" class="form-control">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(URL::asset('assets/libs/datatables.net/datatables.net.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('assets/libs/datatables.net-bs4/datatables.net-bs4.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('assets/libs/datatables.net-responsive/datatables.net-responsive.min.js')); ?>"></script>

<script src="<?php echo e(URL::asset('assets/js/pages/datatable-pages.init.js')); ?>"></script>

<script>
function openManageAddonModal(addonId, price, status) {
    document.getElementById('addon_modal_id').value = addonId;
    document.getElementById('addon_modal_price').value = price;
    document.getElementById('addon_modal_status').value = status;
    new bootstrap.Modal(document.getElementById('manageAddonModal')).show();
}
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('kitchen.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\kitchen\addons\index.blade.php ENDPATH**/ ?>