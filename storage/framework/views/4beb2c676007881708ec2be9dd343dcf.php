<div class="col-md-6">
    <div class="mb-3">
    <h5 class="card-title"><?php echo app('translator')->get('translation.Customer_List'); ?> <span class="text-muted fw-normal ms-2">(<?php echo e($customers->total()); ?>)</span></h5>
    </div>
</div>

<div class="table-responsive mb-4">
    <table class="table align-middle dt-responsive table-check nowrap" style="border-collapse: collapse; border-spacing: 0 8px; width: 100%;">
        <thead>
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Kitchen</th>
            <th scope="col">Mobile</th>
            <th scope="col">Office Name</th>
            <th scope="col">City</th>
            <th scope="col">Status</th>
            <th style="width: 80px; min-width: 80px;">View</th>
        </tr>
        </thead>
        <tbody>
        <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td>
                    <?php if(!empty($customer->image_filename)): ?>
                        <img src="<?php echo e(URL::asset('storage/customers/' . $customer->image_filename)); ?>" alt="" class="avatar-sm rounded-circle me-2">
                    <?php else: ?>
                    <div class="avatar-sm d-inline-block align-middle me-2">
                        <div class="avatar-title bg-soft-primary text-primary font-size-20 m-0 rounded-circle">
                            <i class="bx bxs-user-circle"></i>
                        </div>
                    </div>
                    <?php endif; ?>
                    <a href="<?php echo e(route('admin.customers.edit',encrypt($customer->id))); ?>" class=""><?php echo e($customer->name); ?></a>
                </td>

                <td><?php echo e($customer->kitchen->display_name); ?></td>
                <td><?php echo e($customer->phone); ?></td>
                <td><?php echo e($customer->office_name); ?>

                    <?php if(!empty($customer->landmark)): ?><br> <small><?php echo e($customer->landmark); ?></small><?php endif; ?>
                </td>
                <td>
                    <?php echo e($customer->city); ?>

                    <br> <small><?php echo e($customer->district->name); ?> District</small>
                </td>
                <td>
                    <span class="badge bg-<?php echo e($customer->is_approved ? 'success' : 'danger'); ?>">
                        <?php echo e($customer->is_approved ? 'Activated' : 'Suspended'); ?>

                    </span>
                    <span class="badge bg-<?php echo e(empty($customer->office_name) ? 'danger' : ''); ?>">
                        <?php echo e(empty($customer->office_name) ? 'Address Not Completed' : ''); ?>

                    </span>
                </td>

                    <td>
                        <div class="dropdown">
                            <button class="btn btn-link font-size-16 shadow-none py-0 text-muted dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bx-dots-horizontal-rounded"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?php echo e(route('admin.customers.edit',encrypt($customer->id))); ?>"><i class="mdi mdi-pencil font-size-16 text-success me-1"></i> Edit</a></li>
                                
                                    <li><a class="dropdown-item" onclick="return confirm('Are you sure to make the change?')" href="<?php echo e(route('admin.customers.changeStatus',encrypt($customer->id))); ?>"><?php echo $customer->is_approved?'<i class="fas fa-power-off font-size-16 text-danger me-1"></i> Suspend':'<i class="fas fa-circle-notch font-size-16 text-primary me-1"></i> Activate'; ?></a></li>
                                <li><a class="dropdown-item" href="<?php echo e(route('admin.customers.show',encrypt($customer->id))); ?>"><i class="fa fa-eye font-size-16 text-success me-1"></i> Details</a></li>
                            </ul>
                        </div>
                    </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <!-- end table -->
    <div class="pagination justify-content-center"><?php echo e($customers->links()); ?></div>
</div>
<?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\admin\customers\table.blade.php ENDPATH**/ ?>