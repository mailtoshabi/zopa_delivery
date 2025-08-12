<?php $__env->startSection('title'); ?> <?php echo app('translator')->get('translation.Daily_orders'); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<link href="<?php echo e(URL::asset('/assets/libs/datatables.net-bs4/datatables.net-bs4.min.css')); ?>" rel="stylesheet">
<link href="<?php echo e(URL::asset('assets/libs/datatables.net-responsive-bs4/datatables.net-responsive-bs4.min.css')); ?>" rel="stylesheet" type="text/css" />

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('kitchen.dir_components.breadcrumb'); ?>
<?php $__env->slot('li_1'); ?> <?php echo app('translator')->get('translation.Order_Manage'); ?> <?php $__env->endSlot(); ?>
<?php $__env->slot('li_2'); ?> <?php echo app('translator')->get('translation.Orders'); ?> <?php $__env->endSlot(); ?>
<?php $__env->slot('title'); ?> <?php echo app('translator')->get('translation.Purchases'); ?> <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>
<?php if(session()->has('success')): ?>
<div class="alert alert-success alert-top-border alert-dismissible fade show" role="alert">
    <i class="mdi mdi-check-all me-3 align-middle text-success"></i><strong>Success</strong> - <?php echo e(session()->get('success')); ?>

</div>
<?php endif; ?>
<div class="row">
    <div class="col-lg-12">
    <ul class="nav nav-tabs">
        <li class="nav-item">
          <a class="nav-link <?php if($is_active==Utility::ITEM_INACTIVE): ?> active <?php endif; ?>" <?php if($is_active==Utility::ITEM_INACTIVE): ?>aria-current="page"<?php endif; ?> href="<?php echo e(route('kitchen.orders.index','status='.encrypt(Utility::ITEM_INACTIVE))); ?>">Pending to add Wallet <span class="badge rounded-pill bg-soft-danger text-danger float-end"><?php echo e($count_new); ?></span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php if($is_active==Utility::ITEM_ACTIVE): ?> active <?php endif; ?>" <?php if($is_active==Utility::ITEM_ACTIVE): ?>aria-current="page"<?php endif; ?> href="<?php echo e(route('kitchen.orders.index','status='.encrypt(Utility::ITEM_ACTIVE))); ?>">Added to Wallet</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-danger <?php if($is_active==Utility::STATUS_NOTPAID): ?> active <?php endif; ?>" <?php if($is_active==Utility::STATUS_NOTPAID): ?>aria-current="page"<?php endif; ?> href="<?php echo e(route('kitchen.orders.index','status='.encrypt(Utility::STATUS_NOTPAID))); ?>">UnPaid <span class="badge rounded-pill bg-soft-danger text-danger float-end"><?php echo e($count_not_paid); ?></span></a>
          </li>
      </ul>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card mb-0">
            <div class="card-body">
                <div class="tab-content p-3 text-muted">
                    <div class="tab-pane customerdetailsTab active" role="tabpanel">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="mb-3">
                                <h5 class="card-title"><?php echo app('translator')->get('translation.Orders'); ?> <span class="text-muted fw-normal ms-2">(<?php echo e($customer_orders->total()); ?>)</span></h5>
                                </div>
                            </div>

                            <div class="table-responsive mb-4">
                                <table class="table align-middle dt-responsive table-check nowrap" style="border-collapse: collapse; border-spacing: 0 8px; width: 100%;">
                                    <thead>
                                    <tr>
                                        <th scope="col">Invoice</th>
                                        <th scope="col">Customer</th>
                                        <th scope="col">Product</th>
                                        <th scope="col">Grand Total</th>
                                        <th scope="col">Payment</th>
                                        <th scope="col">Ordered</th>
                                        <th style="width: 80px; min-width: 80px;">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $__currentLoopData = $customer_orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer_order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <?php if(!empty($customer_order->image_filename)): ?>
                                                    <img src="<?php echo e(URL::asset('storage/customers/' . $customer_order->image_filename)); ?>" alt="" class="avatar-sm rounded-circle me-2">
                                                <?php else: ?>
                                                <div class="avatar-sm d-inline-block align-middle me-2">
                                                    <div class="avatar-title bg-soft-primary text-primary font-size-20 m-0 rounded-circle">
                                                        <i class="bx bxs-user-circle"></i>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                                <a href="#" class=""><?php echo e($customer_order->invoice_no); ?></a>
                                                </td>

                                            <td><?php echo e($customer_order->customer->name); ?></td>
                                            <td>
                                                <?php $grandTotal = 0; ?>

                                                
                                                <?php if($customer_order->meals->isNotEmpty()): ?>
                                                    <?php $__currentLoopData = $customer_order->meals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php
                                                            $subtotal = $meal->price;
                                                            // * $meal->quantity
                                                            $grandTotal += $subtotal;
                                                        ?>
                                                        <?php echo e($meal->meal->name); ?> - ₹<?php echo e(number_format($subtotal, 2)); ?><br>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>

                                                
                                                <?php if($customer_order->addons->isNotEmpty()): ?>
                                                    <?php $__currentLoopData = $customer_order->addons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php
                                                            $subtotal = $addon->price * $addon->quantity;
                                                            $grandTotal += $subtotal;
                                                        ?>
                                                        <?php echo e($addon->quantity); ?> <?php echo e($addon->addon->name); ?> - ₹<?php echo e(number_format($subtotal, 2)); ?><br>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                ₹<?php echo e(number_format($grandTotal, 2)); ?>

                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo e(($customer_order->pay_method == Utility::PAYMENT_ONLINE) ? 'primary' : 'secondary'); ?>">
                                                    <?php echo e($customer_order->is_paid ? 'online' : 'COD'); ?>

                                                </span>
                                                <span class="badge bg-<?php echo e($customer_order->is_paid ? 'success' : 'danger'); ?>">
                                                    <?php echo e($customer_order->is_paid ? 'Paid' : 'Not Paid'); ?>

                                                </span>
                                                <span class="badge bg-<?php echo e($customer_order->status ? 'success' : 'danger'); ?>">
                                                    <?php echo e($customer_order->status ? 'Activated' : 'Not Activated'); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <a href="#" class="text-body">
                                                    On <?php echo e($customer_order->created_at->format('d M Y')); ?><br>

                                                </a>
                                            </td>

                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-link font-size-16 shadow-none py-0 text-muted dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="bx bx-dots-horizontal-rounded"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <?php if($customer_order->status == Utility::ITEM_INACTIVE): ?>
                                                                <li>
                                                                <a onclick="return confirm('Are you sure to Activate & Mark as Paid?')" class="dropdown-item" href="<?php echo e(route('kitchen.orders.activate', [encrypt($customer_order->id), 'paid'])); ?>">
                                                                    <i class="fas fa-credit-card font-size-16 text-success me-1"></i> Activate With Payment
                                                                </a>
                                                                </li>

                                                                <li>
                                                                <a onclick="return confirm('Are you sure to Activate without Payment?')" class="dropdown-item" href="<?php echo e(route('kitchen.orders.activate', [encrypt($customer_order->id), 'unpaid'])); ?>">
                                                                    <i class="fas fa-toggle-on font-size-16 text-success me-1"></i> Activate Without Payment
                                                                </a>
                                                                </li>
                                                            <?php endif; ?>
                                                            <li><a class="dropdown-item" onclick="return confirm('Are you sure to make the change?')" href="<?php echo e(route('kitchen.orders.changePayment',encrypt($customer_order->id))); ?>"><?php echo $customer_order->is_paid?'<i class="fas fa-power-off font-size-16 text-danger me-1"></i> Mark Un Paid':'<i class="fas fa-circle-notch font-size-16 text-primary me-1"></i> Mark Paid'; ?></a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                                <!-- end table -->
                                <div class="pagination justify-content-center"><?php echo e($customer_orders->appends(['status' => encrypt($is_active)])->links()); ?></div>
                            </div>
                         <!-- end table responsive -->

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

<?php echo $__env->make('kitchen.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\kitchen\customer_order\index.blade.php ENDPATH**/ ?>