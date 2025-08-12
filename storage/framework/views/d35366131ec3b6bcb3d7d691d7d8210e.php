<?php $__env->startSection('title'); ?> <?php echo app('translator')->get('translation.Estimate_List'); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<link href="<?php echo e(URL::asset('/assets/libs/datatables.net-bs4/datatables.net-bs4.min.css')); ?>" rel="stylesheet">
<link href="<?php echo e(URL::asset('assets/libs/datatables.net-responsive-bs4/datatables.net-responsive-bs4.min.css')); ?>" rel="stylesheet" type="text/css" />

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('admin.dir_components.breadcrumb'); ?>
<?php $__env->slot('li_1'); ?> <?php echo app('translator')->get('translation.Proforma_Manage'); ?> <?php $__env->endSlot(); ?>
<?php $__env->slot('li_2'); ?> <?php echo app('translator')->get('translation.Proforma_Invoice'); ?> <?php $__env->endSlot(); ?>
<?php $__env->slot('title'); ?> <?php echo app('translator')->get('translation.Proforma_List'); ?> <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>
<?php if(session()->has('success')): ?>
<div class="alert alert-success alert-top-border alert-dismissible fade show" role="alert">
    <i class="mdi mdi-check-all me-3 align-middle text-success"></i><strong>Success</strong> - <?php echo e(session()->get('success')); ?>

</div>
<?php endif; ?>
<?php if(session()->has('error')): ?>
<div class="alert alert-danger alert-top-border alert-dismissible fade show" role="alert">
    <i class="mdi mdi-check-all me-3 align-middle text-danger"></i><strong>Error</strong> - <?php echo e(session()->get('error')); ?>

</div>
<?php endif; ?>
<div class="row">
    <div class="col-lg-12">
    <ul class="nav nav-tabs sales_list">
        <?php $__currentLoopData = Utility::saleStatus(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index=>$payment_status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li class="nav-item">
            <a class="nav-link <?php if($status==$index): ?> active <?php endif; ?>" <?php if($status==$index): ?>aria-current="page"<?php endif; ?> href="<?php echo e(route('admin.sales.index','status='.encrypt($index))); ?>"><?php echo e($payment_status['name']); ?> </a>
        </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <li class="nav-item">
            <a class="nav-link text-danger <?php if($status==Utility::STATUS_NOTPAID): ?> active <?php endif; ?>" <?php if($status==Utility::STATUS_NOTPAID): ?>aria-current="page"<?php endif; ?> href="<?php echo e(route('admin.sales.index','status='.encrypt(Utility::STATUS_NOTPAID))); ?>"><b><i class="fas fa-exclamation-triangle"></i> Pending Payment</b></a>
        </li>
      </ul>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card mb-0">
            <div class="card-body">
                 <div class="row align-items-center">
                     <div class="col-md-6">
                         <div class="mb-3">
                             <h5 class="card-title">Proforma Invoices <span class="text-muted fw-normal ms-2">(<?php echo e($sales->count()); ?>)</span></h5>
                         </div>
                     </div>

                     <div class="col-md-6">
                         <div class="d-flex flex-wrap align-items-center justify-content-end gap-2 mb-3">
                             

                             
                         </div>

                     </div>
                 </div>
                 <!-- end row -->

                 <div class="table-responsive mb-4">
                     <table class="table align-middle dt-responsive table-check nowrap" style="border-collapse: collapse; border-spacing: 0 8px; width: 100%;">
                         <thead>
                         <tr>
                             <th scope="col" style="width: 50px;">
                                 <div class="form-check font-size-16">
                                     <input type="checkbox" class="form-check-input" id="checkAll">
                                     <label class="form-check-label" for="checkAll"></label>
                                 </div>
                             </th>
                             <th scope="col">Invoice No</th>
                             <th scope="col">Estimate</th>
                             <th scope="col">Customer</th>
                             <th scope="col">Items</th>
                             <th scope="col">Sub Total</th>
                             <th scope="col">Total Paid</th>
                             <th scope="col">Status</th>
                             <th style="width: 80px; min-width: 80px;">View</th>
                         </tr>
                         </thead>
                         <tbody>
                            <?php $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <tr>
                                    <th scope="row">
                                        <div class="form-check font-size-16">
                                            <input type="checkbox" class="form-check-input" id="contacusercheck1">
                                            <label class="form-check-label" for="contacusercheck1"></label>
                                        </div>
                                    </th>
                                    <td><a target="_blank" href="<?php echo e(route('admin.sales.view',encrypt($sale->id))); ?>"><?php echo e($sale->invoice_no); ?><br><?php echo e($sale->created_at->format('d-m-Y H:i:s')); ?></a></td>
                                    <td>
                                        <a target="_blank" href="<?php echo e(route('admin.estimates.edit',encrypt($sale->estimate->id))); ?>"><?php echo e($sale->estimate->est_no); ?></a>
                                    </td>
                                    <td>
                                        <a target="_blank" href="<?php echo e(route('admin.customers.view',encrypt($sale->estimate->customer->id))); ?>"><?php echo e($sale->estimate->customer->name); ?></a>
                                    </td>

                                    <td>
                                        <?php $data = ''; $count = 1;  ?>
                                        <?php $__currentLoopData = $sale->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                $comma= $count==1? '':', ';
                                                $data .= $comma . $product->name . ' (' . $product->pivot->quantity . ')'; $count++; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <a href="#" class="text-body"><?php echo e($data); ?></a>
                                    </td>
                                    <td><?php echo e(Utility::formatPrice($sale->sub_total+$sale->total_igst+$sale->delivery_charge-$sale->round_off-$sale->discount)); ?></td>
                                    <td><?php echo e(Utility::formatPrice($sale->total_paid)); ?></td>
                                    <td><?php echo e($sale->payment_status); ?></td>
                                    <td>
                                        <a target="_blank" title="view" href="<?php echo e(route('admin.sales.view',encrypt($sale->id))); ?>"><i class="fa fa-eye font-size-16 text-primary me-1"></i></a>
                                    </td>
                                </tr>
                             <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                         </tbody>
                     </table>
                     <!-- end table -->
                     
                        <div class="pagination justify-content-center"><?php echo e($sales->appends(['status' => encrypt($status)])->links()); ?></div>
                     
                 </div>
                 <!-- end table responsive -->
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

<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\admin\sales\index.blade.php ENDPATH**/ ?>