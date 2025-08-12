<?php $__env->startSection('title'); ?> <?php echo app('translator')->get('translation.Meals'); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<link href="<?php echo e(URL::asset('/assets/libs/datatables.net-bs4/datatables.net-bs4.min.css')); ?>" rel="stylesheet">
<link href="<?php echo e(URL::asset('assets/libs/datatables.net-responsive-bs4/datatables.net-responsive-bs4.min.css')); ?>" rel="stylesheet" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('admin.dir_components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> <?php echo app('translator')->get('translation.Meal_Manage'); ?> <?php $__env->endSlot(); ?>
    <?php $__env->slot('li_2'); ?> <?php echo app('translator')->get('translation.Meals'); ?> <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> <?php echo app('translator')->get('translation.Meal_List'); ?> <?php $__env->endSlot(); ?>
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
                                <h5 class="card-title"><?php echo app('translator')->get('translation.Meal_List'); ?> <span class="text-muted fw-normal ms-2">(<?php echo e($meals->total()); ?>)</span></h5>
                            </div>
                            <div class="col-md-6 text-end">
                                <a href="<?php echo e(route('admin.meals.create')); ?>" class="btn btn-primary">
                                    <i class="mdi mdi-plus"></i> Add A Meal
                                </a>
                            </div>
                        </div>

                        <div class="table-responsive mb-4">
                            <table class="table align-middle dt-responsive table-check nowrap" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Image</th>
                                        <th>Ingredients</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th style="width: 80px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $meals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo e(route('admin.meals.edit', encrypt($meal->id))); ?>">
                                                <?php echo e($meal->name); ?><br>
                                                <?php if(isset($meal->category)): ?>
                                                    <small>Category: <?php echo e($meal->category->name); ?></small><br>
                                                <?php endif; ?>
                                                <?php if(isset($meal->mess_category)): ?>
                                                    <small>Mess Category: <?php echo e($meal->mess_category->name); ?></small>
                                                <?php endif; ?>
                                                <?php if(isset($meal->walletGroup)): ?>
                                                    <small>Wallet Group: <?php echo e($meal->walletGroup->name); ?></small>
                                                <?php endif; ?>
                                            </a>
                                        </td>
                                        <td>₹<?php echo e($meal->price); ?></td>
                                        <td>
                                            <?php if($meal->image_filename): ?>
                                                
                                                <img src="<?php echo e(Storage::url('meals/' . $meal->image_filename)); ?>" alt="" class="avatar-sm rounded-circle me-2">
                                            <?php else: ?>
                                            <div class="avatar-sm d-inline-block align-middle me-2">
                                                <div class="avatar-title bg-soft-primary text-primary font-size-20 m-0 rounded-circle">
                                                    <i class="bx bxs-user-circle"></i>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php $__currentLoopData = $meal->ingredients ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ingredient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="badge bg-light text-dark"><?php echo e($ingredient->name); ?></span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <br>
                                            <?php $__currentLoopData = $meal->remarks ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $remark): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="badge bg-dark text-light"><?php echo e($remark->name); ?></span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                        <td>
                                            <?php echo $meal->status ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>'; ?>

                                        </td>
                                        <td><?php echo e($meal->created_at->format('d M Y')); ?></td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-link font-size-16 shadow-none py-0 text-muted dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bx bx-dots-horizontal-rounded"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="<?php echo e(route('admin.meals.edit', encrypt($meal->id))); ?>"><i class="mdi mdi-pencil font-size-16 text-success me-1"></i> Edit</a></li>
                                                    <li><a href="#" class="dropdown-item" data-plugin="delete-data" data-target-form="#form_delete_<?php echo e($loop->iteration); ?>"><i class="mdi mdi-trash-can font-size-16 text-danger me-1"></i> Delete</a></li>
                                                    <form id="form_delete_<?php echo e($loop->iteration); ?>" method="POST" action="<?php echo e(route('admin.meals.destroy', encrypt($meal->id))); ?>">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                    </form>
                                                    <li>
                                                        <?php
                                                            $stat_msg = $meal->status ? 'Unpublish':'Publish';
                                                            // return confirm('Are you sure to '. $stat_msg . '?')
                                                        ?>
                                                        <a onclick="return confirm('Are you sure to <?php echo e($stat_msg); ?> the Food?')" class="dropdown-item" href="<?php echo e(route('admin.meals.changeStatus', encrypt($meal->id))); ?>">
                                                            <?php echo $meal->status ? '<i class="fas fa-power-off font-size-16 text-danger me-1"></i> Unpublish' : '<i class="fas fa-circle-notch font-size-16 text-primary me-1"></i> Publish'; ?>

                                                        </a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="<?php echo e(route('admin.meals.show', encrypt($meal->id))); ?>"><i class="fa fa-eye font-size-16 text-success me-1"></i> Details</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <div class="pagination justify-content-center mt-3">
                                <?php echo e($meals->links()); ?>

                            </div>
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

<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\admin\meals\index.blade.php ENDPATH**/ ?>