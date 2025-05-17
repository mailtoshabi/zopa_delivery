<?php $__env->startSection('title'); ?> <?php echo app('translator')->get('translation.Daily_Meals'); ?> <?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<link href="<?php echo e(URL::asset('/assets/libs/datatables.net-bs4/datatables.net-bs4.min.css')); ?>" rel="stylesheet">
<link href="<?php echo e(URL::asset('assets/libs/datatables.net-responsive-bs4/datatables.net-responsive-bs4.min.css')); ?>" rel="stylesheet" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('admin.dir_components.breadcrumb'); ?>
<?php $__env->slot('li_1'); ?> <?php echo app('translator')->get('translation.Order_Manage'); ?> <?php $__env->endSlot(); ?>
<?php $__env->slot('li_2'); ?> <?php echo app('translator')->get('translation.Orders'); ?> <?php $__env->endSlot(); ?>
<?php $__env->slot('title'); ?>
    <?php if($mealtype ==1): ?>
        <?php echo app('translator')->get('translation.Daily_orders'); ?>
    <?php elseif($mealtype ==2): ?>
        <?php echo app('translator')->get('translation.Previous_orders'); ?>
    <?php else: ?>
        <?php echo app('translator')->get('translation.Extra_orders'); ?>
    <?php endif; ?>
<?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<?php if(session()->has('success')): ?>
<div class="alert alert-success alert-top-border alert-dismissible fade show" role="alert">
    <i class="mdi mdi-check-all me-3 align-middle text-success"></i><strong>Success</strong> - <?php echo e(session()->get('success')); ?>

</div>
<?php endif; ?>

<?php if($errors->any()): ?>
<div class="alert alert-danger alert-top-border alert-dismissible fade show" role="alert">
    <i class="mdi mdi-alert-circle-outline me-3 align-middle text-danger"></i><strong>Error</strong> - <?php echo e($errors->first()); ?>

</div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card mb-0">
            <div class="card-body">
                <div class="tab-content p-3 text-muted">
                    <div class="tab-pane active">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h5 class="card-title"><?php echo app('translator')->get('translation.Meal_List'); ?> <span class="text-muted fw-normal ms-2">(<?php echo e($dailyMeals->total()); ?>)</span></h5>
                                </div>
                            </div>
                            <?php if($mealtype ==1): ?>
                                <div class="col-md-6 text-start">
                                    <div class="d-inline-block me-2">
                                        <form action="<?php echo e(route('admin.daily_meals.mark.all.delivered')); ?>" method="POST" onsubmit="return confirm('Are you sure to mark all as delivered?')">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="mdi mdi-check-all"></i> Mark Delivered
                                            </button>
                                        </form>
                                    </div>
                                    <div class="d-inline-block me-2">
                                        <form action="<?php echo e(route('admin.daily_meals.undo.delivered')); ?>" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure to undo all delivered meals for today?')">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-outline-warning btn-sm">
                                                <i class="mdi mdi-undo"></i> Undo Delivery
                                            </button>
                                        </form>
                                    </div>
                                    <div class="d-inline-block">
                                        <form action="<?php echo e(route('admin.daily_meals.generate')); ?>" method="POST" onsubmit="return confirm('Are you sure to generate daily meals?')">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="mdi mdi-sync"></i> Generate From Wallet
                                            </button>
                                        </form>
                                    </div>
                                </div>

                            <?php endif; ?>
                        </div>

                        <div class="table-responsive mb-4">
                            <form method="GET" class="row g-3 mb-3">
                                <div class="col-auto">
                                    <input type="date" name="date" class="form-control" value="<?php echo e(request('date')); ?>">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                                    <a href="<?php echo e(route('admin.daily_meals.extra')); ?>" class="btn btn-outline-secondary btn-sm">Reset</a>
                                </div>
                            </form>
                            <table class="table align-middle dt-responsive nowrap" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Phone</th>
                                        <th>Quantity</th>
                                        <th>Addons</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $dailyMeals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($meal->customer->name ?? 'N/A'); ?></td>
                                            <td><?php echo e($meal->customer->phone ?? '-'); ?></td>
                                            <td><?php echo e($meal->quantity); ?></td>

                                            
                                            <td>
                                                <?php
                                                    $addons = $addonsByMeal[$meal->id] ?? collect();
                                                ?>

                                                <?php if($addons->isNotEmpty()): ?>
                                                    <ul class="mb-0 ps-3">
                                                        <?php $__currentLoopData = $addons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <li>
                                                                <?php echo e($addon->addon->name ?? '-'); ?> (<?php echo e($addon->quantity); ?>)
                                                            </li>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </ul>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>

                                            <td>
                                                <span class="badge bg-<?php echo e($meal->is_auto ? 'secondary' : 'primary'); ?>">
                                                    <?php echo e($meal->is_auto ? 'Auto Generated' : 'Requested'); ?>

                                                </span>
                                                <span class="badge bg-<?php echo e($meal->status ? 'success' : 'danger'); ?>">
                                                    <?php echo e($meal->status ? 'Active' : 'Cancelled'); ?>

                                                </span>
                                                <?php if($meal->date->isToday()): ?>
                                                    <span class="badge bg-<?php echo e($meal->is_delivered ? 'primary' : 'warning'); ?>">
                                                        <?php echo e($meal->is_delivered ? 'Delivered' : 'Sheduled'); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <?php if($meal->status && $meal->date->lte(\Carbon\Carbon::today())): ?>
                                                        <span class="badge bg-<?php echo e($meal->is_delivered ? 'warning' : 'danger'); ?>"
                                                            <?php if(!$meal->is_delivered): ?>
                                                            data-bs-toggle="tooltip"
                                                            <?php endif; ?>
                                                            data-bs-placement="top" title="<?php echo e($meal->reason ?? 'No reason provided'); ?>">
                                                            <?php echo e($meal->is_delivered ? 'Delivered' : 'Not Delivered'); ?>

                                                        </span>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </td>

                                            <td><?php echo e($meal->date->format('d M Y')); ?></td>

                                            <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-link font-size-16 shadow-none py-0 text-muted dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="bx bx-dots-horizontal-rounded"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <!-- Undelivered Reason Button -->
                                                            <?php if($meal->status && $meal->date->lte(\Carbon\Carbon::today())): ?>
                                                                <?php if($meal->is_delivered): ?>
                                                                    <!-- Trigger Modal -->
                                                                    <li>
                                                                        <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#undeliverModal<?php echo e($meal->id); ?>">
                                                                            <i class="fas fa-power-off font-size-16 text-danger me-1"></i> Mark Un Delivered
                                                                        </button>
                                                                    </li>
                                                                <?php else: ?>
                                                                    <!-- Deliver directly via form -->
                                                                    <li>
                                                                        <form action="<?php echo e(route('admin.daily_meals.changeDelivery', encrypt($meal->id))); ?>" method="POST" onsubmit="return confirm('Mark as Delivered?')">
                                                                            <?php echo csrf_field(); ?>
                                                                            <button type="submit" class="dropdown-item">
                                                                                <i class="fas fa-circle-notch font-size-16 text-primary me-1"></i> Mark Delivered
                                                                            </button>
                                                                        </form>
                                                                    </li>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        </ul>
                                                    </div>
                                                </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="6" class="text-center">No daily meals found.</td>
                                        </tr>
                                    <?php endif; ?>
                                    </tbody>
                            </table>

                            <div class="pagination justify-content-center mt-3">
                                <?php echo e($dailyMeals->appends(request()->query())->links()); ?>

                            </div>
                        </div> <!-- end table-responsive -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__currentLoopData = $dailyMeals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if($meal->is_delivered): ?>
    <!-- Modal -->
    <div class="modal fade" id="undeliverModal<?php echo e($meal->id); ?>" tabindex="-1" aria-labelledby="undeliverModalLabel<?php echo e($meal->id); ?>" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="<?php echo e(route('admin.daily_meals.changeDelivery', encrypt($meal->id))); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="undeliverModalLabel<?php echo e($meal->id); ?>">Reason for Undelivered</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <textarea name="reason" class="form-control" rows="3" required placeholder="Enter reason for undelivering this meal"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Mark Un Delivered</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(URL::asset('assets/libs/datatables.net/datatables.net.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('assets/libs/datatables.net-bs4/datatables.net-bs4.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('assets/libs/datatables.net-responsive/datatables.net-responsive.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('/assets/js/app.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('assets/js/pages/datatable-pages.init.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views/admin/daily_meals/index.blade.php ENDPATH**/ ?>