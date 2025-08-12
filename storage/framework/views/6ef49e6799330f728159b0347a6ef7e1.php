<?php $__env->startSection('title'); ?> <?php echo app('translator')->get('translation.Proforma_Details'); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php if (! (empty($sale->reason))): ?>
<div class="alert alert-danger alert-top-border alert-dismissible fade show" role="alert">
    <i class="mdi mdi-check-all me-3 align-middle text-danger"></i><strong>Proforma Status : <?php echo e(Utility::saleStatus()[$sale->status]['name']); ?></strong> | <strong>Notes : </strong> <span class="">- <?php echo e($sale->reason); ?></span>
</div>
<?php endif; ?>
<div class="row">
    <div class="col-12">
        <div class="card">

            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">

                        <h6 class="text-primary">Invoice To</h6>
                        <p class="mb-2"><b><?php echo e($sale->estimate->customer->name); ?></b> </p>
                        <?php if (! (empty($sale->estimate->customer->trade_name))): ?>
                            <p class="mb-2"><?php echo e($sale->estimate->customer->trade_name); ?> (Trade Name) </p>
                        <?php endif; ?>
                        <?php if (! (empty($sale->estimate->customer->address1))): ?><p class="text-muted mb-0"><?php echo e($sale->estimate->customer->address1); ?></p><?php endif; ?>
                        <?php if (! (empty($sale->estimate->customer->address2))): ?><p class="text-muted mb-0"><?php echo e($sale->estimate->customer->address2); ?></p><?php endif; ?>
                        <?php if (! (empty($sale->estimate->customer->address3))): ?><p class="text-muted mb-0"><?php echo e($sale->estimate->customer->address3); ?></p><?php endif; ?>
                        <p class="text-muted mb-0"><?php echo e($sale->estimate->customer->city); ?></p>
                        <p class="text-muted mb-0"><?php echo e($sale->estimate->customer->district->name); ?> District</p>
                        <p class="text-muted mb-0"><?php echo e($sale->estimate->customer->state->name); ?> - <?php echo e($sale->estimate->customer->postal_code); ?></p>
                        <p class="text-primary mb-0">Mob:<?php echo e($sale->estimate->customer->phone); ?></p>
                        <?php if (! (empty($sale->estimate->customer->email))): ?><p class="text-success mb-2">Email:<?php echo e($sale->estimate->customer->email); ?></p><?php endif; ?>


                        <?php if($sale->status!=Utility::STATUS_CLOSED): ?>
                        <div class="btn-group" role="group">
                            <a href="<?php echo e(route('admin.sales.edit',encrypt($sale->id))); ?>" class="btn btn-danger waves-effect waves-light w-sm">
                                <i class="fas fa-pen d-block font-size-12"></i> Edit Proforma
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-sm-6 azzet_invoice">
                        <br>
                        <p class="mb-0">Date : <?php echo e($sale->created_at->format('d-m-Y')); ?></p>
                        <p class="mb-2">Order ID : <?php echo e($sale->invoice_no); ?> </p>
                        <?php if (! (empty($sale->estimate->customer->gstin))): ?><p class="mb-2"><b><?php echo 'GSTIN/UIN: '. $sale->estimate->customer->gstin; ?></b></p><?php endif; ?>
                        State Name :  <?php echo e($sale->estimate->customer->state->name); ?>, Code : <?php echo e($sale->estimate->customer->state->gst_code); ?> <br>
                        <?php if (! (empty($sale->estimate->customer->cin))): ?><p class="mb-2"><?php echo 'CIN: '. $sale->reason; ?></p><?php endif; ?>
                        
                        <div class="btn-group mt-2" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                Proforma Status : <span id="status_id"><?php echo e(Utility::saleStatus()[$sale->status]['name']); ?></span> <i class="mdi mdi-chevron-down"></i>
                            </button>
                            <ul id="proforma_status" class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                                <?php $__currentLoopData = Utility::saleStatus(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><a data-status_id="<?php echo e(encrypt($index)); ?>" href="<?php echo e(route('admin.sales.changeStatus')); ?>" class="dropdown-item status_change"><?php echo e($status['name']); ?></a></li>
                                    
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                        <div class="btn-group mt-2" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                Payment Status : <?php echo e($sale->payment_status); ?>

                                <i class="mdi mdi-chevron-down"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                                <li><a class="dropdown-item" href="#allPaymentDetails">Details </a></li>
                            </ul>
                        </div>

                        <div class="mt-4">
                            <a data-plugin="confirm-data" data-confirmtext="Do you really want to download the Invoice?" href="<?php echo e(route('admin.sales.download.invoice',encrypt($sale->id))); ?>" class="btn btn-primary waves-effect waves-light w-sm">
                                <i class="fas fa-download d-block font-size-12"></i> Download Invoice
                            </a>
                            <a data-plugin="confirm-data" data-confirmtext="Do you really want to print the Invoice?" href="<?php echo e(route('admin.sales.view.invoice',encrypt($sale->id))); ?>" class="btn btn-secondary waves-effect waves-light w-sm">
                                <i class="fas fa-print d-block font-size-12"></i> Print Invoice
                            </a>
                            <?php if($sale->status!=Utility::STATUS_CLOSED): ?>
                            <button type="button" id="add_freight" class="btn btn-success waves-effect waves-light w-sm">
                                <i class="fas fa-bus d-block font-size-12"></i> Add Frieght
                            </button>
                            <button type="button" id="add_discount" class="btn btn-danger waves-effect waves-light w-sm">
                                <i class="fas fa-coffee d-block font-size-12"></i> Add Discount
                            </button>
                            <button type="button" id="add_round_off" class="btn btn-info waves-effect waves-light w-sm">
                                <i class="fas fa-bullseye d-block font-size-12"></i> Round Off
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>

            <div class="card-body">
                <div class="row">

                    <div class="table-responsive">
                        <div class="margin-top">
                            <table cellpadding="0" cellspacing="0"  class="w-full">

                                <tr>
                                    <td class="w-full" colspan="2">
                                        <table class="w-full">
                                            <tr class="center height-20" >
                                                <td class="has-border noright w-3 vertical-m">SI No</td>
                                                <td colspan="3" class="has-border noright w-35 vertical-m">Description of Goods</td>

                                                <td class="has-border noright vertical-m">Quantity</td>
                                                <td class="has-border noright vertical-m">Rate</td>
                                                <td class="has-border noright vertical-m">Per</td>
                                                <td class="has-border vertical-m">Amount</td>
                                            </tr>
                                            <?php $sino = 1; ?>
                                            <?php $__currentLoopData = $sale->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="center height-20" >
                                                <td class="has-border notop noright nobottom"><?php echo e($sino); ?></td>
                                                <td colspan="3" class="has-border notop noright nobottom left-align"><b><?php echo e($product->name); ?></b><br><small><?php echo e($product->description); ?></small></td>
                                                
                                                <td class="has-border notop noright nobottom"><?php echo e($product->pivot->quantity); ?> <?php echo e($product->uom->name); ?></td>
                                                <td class="has-border notop noright nobottom"><?php echo e(Utility::formatPrice($product->pivot->price)); ?></td>
                                                <td class="has-border notop noright nobottom"><?php echo e($product->uom->name); ?></td>
                                                <td class="has-border notop nobottom  right-align"><b><?php echo e(Utility::formatPrice($product->pivot->price*$product->pivot->quantity)); ?></b></td>
                                            </tr>

                                            <?php $sino++; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="center height-20" >
                                                <td class="has-border notop noright nobottom"></td>
                                                <td colspan="3" class="has-border notop noright nobottom right-align"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border nobottom right-align"><?php echo e(Utility::formatPrice($sale->sub_total)); ?></td>
                                            </tr>
                                            <tr class="center height-20" >
                                                <td class="has-border notop noright nobottom"></td>
                                                <td colspan="3" class="has-border notop noright nobottom right-align"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border nobottom right-align"></td>
                                            </tr>
                                            <?php if (! (($sale->delivery_charge==0))): ?>
                                            <tr class="center" >
                                                <td class="has-border notop noright nobottom"></td>
                                                <td colspan="3" class="has-border notop noright nobottom right-align"><b>Freight Outward</b></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop nobottom right-align"><b><?php echo e(Utility::formatPrice($sale->delivery_charge)); ?></b></td>
                                            </tr>
                                            <?php endif; ?>
                                            <?php if($sale->estimate->customer->state->id==Utility::STATE_ID_KERALA): ?>
                                            <tr class="center" >
                                                <td class="has-border notop noright nobottom"></td>
                                                <td colspan="3" class="has-border notop noright nobottom right-align"><b>CGST</b></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop nobottom right-align"><b><?php echo e(Utility::formatPrice($sale->total_igst/2)); ?></b></td>
                                            </tr>
                                            <tr class="center" >
                                                <td class="has-border notop noright nobottom"></td>
                                                <td colspan="3" class="has-border notop noright nobottom right-align"><b>SGST</b></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop nobottom right-align"><b><?php echo e(Utility::formatPrice($sale->total_igst/2)); ?></b></td>
                                            </tr>
                                            <?php else: ?>
                                            <tr class="center" >
                                                <td class="has-border notop noright nobottom"></td>
                                                <td colspan="3" class="has-border notop noright nobottom right-align"><b>IGST</b></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop nobottom right-align"><b><?php echo e(Utility::formatPrice($sale->total_igst)); ?></b></td>
                                            </tr>
                                            <?php endif; ?>
                                            <?php if (! (($sale->discount==0))): ?>
                                                <tr class="center" >
                                                    <td class="has-border notop nobottom noright"></td>
                                                    <td colspan="3" class="has-border notop nobottom noright right-align"><b>Discount</b></td>
                                                    <td class="has-border notop nobottom noright"></td>
                                                    <td class="has-border notop nobottom noright"></td>
                                                    <td class="has-border notop nobottom noright"></td>
                                                    <td class="has-border notop nobottom noright"></td>
                                                    <td class="has-border notop nobottom right-align"><b><?php echo e(Utility::formatPrice($sale->discount)); ?></b></td>
                                                </tr>
                                            <?php endif; ?>
                                            <?php if (! (($sale->round_off==0))): ?>
                                                <tr class="center" >
                                                    <td class="has-border notop nobottom noright"></td>
                                                    <td colspan="3" class="has-border notop nobottom noright right-align"><b>Round Off</b></td>
                                                    <td class="has-border notop nobottom noright"></td>
                                                    <td class="has-border notop nobottom noright"></td>
                                                    <td class="has-border notop nobottom noright"></td>
                                                    <td class="has-border notop nobottom noright"></td>
                                                    <td class="has-border notop nobottom right-align"><b><?php echo e(Utility::formatPrice($sale->round_off)); ?></b></td>
                                                </tr>
                                            <?php endif; ?>

                                            <tr class="center height-20" >
                                                <td class="has-border noright"></td>
                                                <td colspan="3" class="has-border noright right-align vertical-m">Total</td>
                                                <td class="has-border noright"></td>
                                                <td class="has-border noright vertical-m"><?php echo e($sale->sub_quantity); ?> <?php echo e($product->uom->name); ?></td>
                                                <td class="has-border noright"></td>
                                                <td class="has-border noright"></td>
                                                <td class="has-border vertical-m right-align"><b><?php echo e(Utility::formatPrice($sale->sub_total+$sale->total_igst+$sale->delivery_charge-$sale->round_off-$sale->discount)); ?></b></td>
                                            </tr>

                                            <tr class="center height-20" >
                                                <td colspan="8" class="has-border notop noright left-align"><small>Amount Chargeable (in words)</small><br>
                                                    <b><?php echo e(Utility::CURRENCY_DISPLAY . ' ' . Utility::currencyToWords(($sale->sub_total+$sale->total_igst+$sale->delivery_charge-$sale->round_off-$sale->discount))); ?></b>
                                                </td>
                                                <td class="has-border notop noleft right-align">E. & O.E</td>
                                            </tr>


                                            <?php if($sale->estimate->customer->state->id==Utility::STATE_ID_KERALA): ?>
                                                <tr class="center height-20" >
                                                    <td rowspan="2" colspan="3" class="has-border notop noright vertical-m w-quarter">HSN/SAC</td>
                                                    <td rowspan="2" class="has-border notop noright vertical-m">Taxable Value</td>
                                                    <td colspan="2" class="has-border notop norigh vertical-m">CGST</td>
                                                    <td colspan="2" class="has-border notop norigh vertical-m">SGST/UTGST</td>
                                                    <td rowspan="2" class="has-border notop vertical-m"><b>Total Tax Amount</b></td>
                                                </tr>
                                                <tr class="center height-20" >
                                                    <td class="has-border notop norigh vertical-m">Rate</td>
                                                    <td class="has-border notop norigh vertical-m">Amount</td>
                                                    <td class="has-border notop norigh vertical-m">Rate</td>
                                                    <td class="has-border notop norigh vertical-m">Amount</td>
                                                </tr>
                                                <?php $__currentLoopData = $sale->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr class="center height-20" >
                                                    <td colspan="3" class="has-border notop noright left-align w-quarter"><?php echo e($product->hsn->name); ?></td>
                                                    <td class="has-border notop noright"><?php echo e(Utility::formatPrice($product->pivot->price*$product->pivot->quantity)); ?></td>
                                                    <td class="has-border notop noright"><?php echo e($product->hsn->tax_slab->name/2); ?>%</td>
                                                    <td class="has-border notop noright"><?php echo e(Utility::formatPrice((($product->pivot->price*$product->pivot->quantity)*($product->hsn->tax_slab->name/100))/2)); ?></td>
                                                    <td class="has-border notop noright"><?php echo e($product->hsn->tax_slab->name/2); ?>%</td>
                                                    <td class="has-border notop noright"><?php echo e(Utility::formatPrice((($product->pivot->price*$product->pivot->quantity)*($product->hsn->tax_slab->name/100))/2)); ?></td>
                                                    <td class="has-border notop"><?php echo e(Utility::formatPrice(($product->pivot->price*$product->pivot->quantity)*($product->hsn->tax_slab->name/100))); ?></td>
                                                </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <tr class="center height-40" >
                                                    <td colspan="3" class="has-border notop noright right-align  vertical-m w-quarter"><b>Total</b></td>
                                                    <td class="has-border notop noright  vertical-m"><b><?php echo e(Utility::formatPrice($sale->sub_total)); ?></b></td>
                                                    <td class="has-border notop noright"></td>
                                                    <td class="has-border notop noright  vertical-m"><b><?php echo e(Utility::formatPrice($sale->total_sgst)); ?></b></td>
                                                    <td class="has-border notop noright"></td>
                                                    <td class="has-border notop  vertical-m"><b><?php echo e(Utility::formatPrice($sale->total_sgst)); ?></b></td>
                                                    <td class="has-border notop vertical-m"><b><?php echo e(Utility::formatPrice($sale->total_igst)); ?></b></td>
                                                </tr>
                                            <?php else: ?>
                                                <tr class="center height-20" >
                                                    <td rowspan="2" colspan="5" class="has-border notop noright vertical-m">HSN/SAC</td>
                                                    <td rowspan="2" class="has-border notop noright vertical-m">Taxable Value</td>
                                                    <td colspan="2" class="has-border notop norigh vertical-m">IGST</td>
                                                    <td rowspan="2" class="has-border notop vertical-m"><b>Total Tax Amount</b></td>
                                                </tr>
                                                <tr class="center height-20" >
                                                    <td class="has-border notop norigh vertical-m">Rate</td>
                                                    <td class="has-border notop noright vertical-m">Amount</td>
                                                </tr>
                                                <?php $__currentLoopData = $sale->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr class="center height-20" >
                                                    <td colspan="5" class="has-border notop noright left-align"><?php echo e($product->hsn->name); ?></td>
                                                    <td class="has-border notop noright"><?php echo e(Utility::formatPrice($product->pivot->price*$product->pivot->quantity)); ?></td>
                                                    <td class="has-border notop noright"><?php echo e($product->hsn->tax_slab->name); ?>%</td>
                                                    <td class="has-border notop noright"><?php echo e(Utility::formatPrice(($product->pivot->price*$product->pivot->quantity)*($product->hsn->tax_slab->name/100))); ?></td>
                                                    <td class="has-border notop"><?php echo e(Utility::formatPrice(($product->pivot->price*$product->pivot->quantity)*($product->hsn->tax_slab->name/100))); ?></td>
                                                </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <tr class="center height-40" >
                                                    <td colspan="5" class="has-border notop noright right-align  vertical-m"><b>Total</b></td>
                                                    <td class="has-border notop noright  vertical-m"><b><?php echo e(Utility::formatPrice($sale->sub_total)); ?></b></td>
                                                    <td class="has-border notop noright"></td>
                                                    <td class="has-border notop noright  vertical-m"><b><?php echo e(Utility::formatPrice($sale->total_igst)); ?></b></td>
                                                    <td class="has-border notop  vertical-m"><b><?php echo e(Utility::formatPrice($sale->total_igst)); ?></b></td>
                                                </tr>
                                            <?php endif; ?>

                                            <tr class="center height-20" >
                                                <td colspan="9" class="has-border notop left-align"><small>Tax Amount (in words)  : </small><?php echo e(Utility::CURRENCY_DISPLAY . ' ' . Utility::currencyToWords($sale->total_igst)); ?></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div id="allPaymentDetails" class="card-header">
                <h4 class="card-title">Payment Details</h4>
                <p class="card-title-desc">Total payment of the customer</p>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="mb-3">
                            <label for="transaction_id">Invoice Amount</label>
                            <input type="text" readonly class="form-control" value="<?php echo e(Utility::formatPrice($sale->sub_total+$sale->total_igst+$sale->delivery_charge-$sale->round_off-$sale->discount)); ?>">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="mb-3">
                            <label for="transaction_id">Total Paid</label>
                            <input type="text" readonly class="form-control" value="<?php echo e(Utility::formatPrice($sale->total_paid)); ?>">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="mb-3">
                            <label for="transaction_id">Balance to Pay</label>
                            <input type="text" readonly class="form-control" value="<?php echo e(Utility::formatPrice(($sale->sub_total+$sale->total_igst+$sale->delivery_charge-$sale->round_off-$sale->discount) - ($sale->total_paid))); ?>">
                        </div>
                    </div>
                </div>
            </div>
            <?php if($sale->status!=Utility::STATUS_CLOSED): ?>
            
            <div class="card-body">
                <form method="POST" action="<?php echo e(isset($payment_edit)? route('admin.payments.update') : route('admin.payments.store')); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="sale_id" value="<?php echo e(encrypt($sale->id)); ?>" />
                    <?php if(!empty($payment_edit)): ?>
                        <input type="hidden" name="payment_id" value="<?php echo e(encrypt($payment_edit->id)); ?>" />
                        <input type="hidden" name="_method" value="PUT" />
                    <?php endif; ?>
                    <div class="row">

                        <div class="col-sm-3">
                            <div class="mb-3">
                                <label for="amount">Amount Paid</label>
                                <div class="input-group">
                                    <div class="input-group-text">INR</div>
                                    <input type="text" class="form-control" name="amount" id="amount" placeholder="Amount Paid" value="<?php if(!empty($payment_edit)): ?> <?php echo e($payment_edit->amount); ?> <?php endif; ?>">
                                    </div>
                                </div>
                            </div>


                        <div class="col-sm-3">
                            <div class="mb-3">
                                <label class="control-label">Payment Mode</label>
                                <select id="payment_method" name="payment_method" class="form-control select2">
                                    
                                    <?php $__currentLoopData = Utility::paymentMethods(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $payment_method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($index); ?>" <?php if(!empty($payment_edit)&& ($index==$payment_edit->payment_method)): ?> selected <?php endif; ?> ><?php echo e($payment_method['name']); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['tax_slab_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="mb-3">
                                <label for="transaction_id">Transaction ID</label>
                                <input id="transaction_id" name="transaction_id" type="text" class="form-control"  placeholder="Transaction ID" value="<?php if(!empty($payment_edit)): ?> <?php echo e($payment_edit->transaction_id); ?><?php endif; ?>">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="mb-3">
                                <label for="example-date-input">Payment Date</label>
                                <input id="paid_at" name="paid_at" class="form-control" type="date" value="<?php if(empty($payment_edit)): ?><?php echo e(Carbon\Carbon::parse(now())->format('Y-m-d')); ?><?php else: ?><?php echo e(Carbon\Carbon::parse($payment_edit->paid_at)->format('Y-m-d')); ?><?php endif; ?>">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="description">Description</label>
                                <textarea id="description" name="description" type="text" class="form-control"  placeholder="Description"><?php if(!empty($payment_edit)): ?> <?php echo e($payment_edit->description); ?><?php endif; ?></textarea>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="mb-3">
                                <label class="control-label">Payment Status</label>
                                <select id="status_p" name="status" class="form-control select2">
                                    
                                    <?php $__currentLoopData = Utility::paymentStatus(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index_p => $paymentStatus): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($index_p); ?>" <?php if(!empty($payment_edit)&& ($index_p==$payment_edit->status)): ?> selected <?php endif; ?> ><?php echo e($paymentStatus['name']); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['tax_slab_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="mb-3">
                                <br><button type="submit" class="btn btn-primary waves-effect waves-light"><?php echo e(isset($payment_edit) ? 'Update' : 'Save'); ?></button>
                                <a href="<?php echo e(route('admin.sales.view',encrypt($sale->id))); ?>" class="btn btn-secondary waves-effect waves-light">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <?php endif; ?>
            <?php if($sale->payments()->exists()): ?>
                
                <div class="card-body">
                    <div class="row">
                        <div class="tab-content p-3 text-muted">
                            <div class="tab-pane customerdetailsTab active" role="tabpanel">
                                <div class="table-responsive mb-4">
                                    <table class="table align-middle dt-responsive table-check nowrap" style="border-collapse: collapse; border-spacing: 0 8px; width: 100%;">
                                        <thead>
                                        <tr>
                                            <th scope="col">Date</th>
                                            <th scope="col">Amount</th>
                                            <th scope="col">Mode</th>
                                            <th scope="col">Transaction ID</th>
                                            <th scope="col">status</th>
                                            <th scope="col">Description</th>
                                            <th style="width: 80px; min-width: 80px;">Edit</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $__currentLoopData = $sale->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                    <td>
                                                        <?php echo e($payment->paid_at->format('d M, Y')); ?>

                                                    </td>
                                                    <td>
                                                    <a href="#" class="text-body"><?php echo e(Utility::CURRENCY_DISPLAY . ' ' . Utility::formatPrice($payment->amount)); ?></a>
                                                    </td>

                                                <td><?php echo e(Utility::paymentMethods()[$payment->payment_method]['name']); ?></td>
                                                <td><?php echo e($payment->transaction_id); ?></td>
                                                <td><?php echo e(Utility::paymentStatus()[$payment->status]['name']); ?></td>
                                                <td>
                                                    <?php echo e($payment->description); ?>

                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-link font-size-16 shadow-none py-0 text-muted dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="bx bx-dots-horizontal-rounded"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li><a class="dropdown-item" href="<?php echo e(route('admin.sales.view',encrypt($sale->id). '?payment_edit_id=' . encrypt($payment->id).'#allPaymentDetails')); ?>"><i class="mdi mdi-pencil font-size-16 text-success me-1"></i> Edit</a></li>
                                                                <li><a href="#" class="dropdown-item" data-plugin="delete-data" data-target-form="#form_delete_<?php echo e($loop->iteration); ?>"><i class="mdi mdi-trash-can font-size-16 text-danger me-1"></i> Delete</a></li>
                                                                <form id="form_delete_<?php echo e($loop->iteration); ?>" method="POST" action="<?php echo e(route('admin.payments.destroy',encrypt($payment->id))); ?>">
                                                                    <?php echo csrf_field(); ?>
                                                                    <input type="hidden" name="_method" value="DELETE" />
                                                                    <input type="hidden" name="sale_id" id="sale_del_id" value="<?php echo e(encrypt($sale->id)); ?>" />
                                                                </form>
                                                            </ul>
                                                        </div>
                                                    </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                    <!-- end table -->
                                    
                                </div>
                                <!-- end table responsive -->

                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>


    </div>
</div>
<!-- end row -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<link href="<?php echo e(URL::asset('assets/css/invoice.css')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(URL::asset('assets/libs/datatables.net/datatables.net.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('assets/libs/datatables.net-bs4/datatables.net-bs4.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('assets/libs/datatables.net-responsive/datatables.net-responsive.min.js')); ?>"></script>

<script src="<?php echo e(URL::asset('assets/js/pages/datatable-pages.init.js')); ?>"></script>
<script>

    $(document).ready(function() {
        $('#proforma_status .status_change').on('click', function(e) {
            e.preventDefault();
            var targetUrl = $(this).attr('href');
            var status_id = $(this).data('status_id');
            // SweetAlert2 popup with input fields
            Swal.fire({
                title: 'Enter Note/Reason',
                html:
                    '<input type="hidden" id="sale_id_s" class="form-control" value="<?php echo e(encrypt($sale->id)); ?>">' +
                    '<input type="text" id="description_s" class="form-control" value="" placeholder="Enter Note/Reason, if have"><br>' +
                    '<input type="hidden" id="status_id_s" class="form-control" value="' + status_id + '">',
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Submit',
                preConfirm: () => {
                    const status_id_s = document.getElementById('status_id_s').value;
                    const sale_id_s = document.getElementById('sale_id_s').value;
                    const description_s = document.getElementById('description_s').value;

                    // Check if the inputs are valid
                    if (!sale_id_s) {
                        Swal.showValidationMessage('Something Went wrong!');
                        return false;
                    }
                    return { status_id_s: status_id_s, sale_id_s: sale_id_s, description_s:description_s };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Get input values from the SweetAlert2 popup
                    const status_id_s = result.value.status_id_s;
                    const sale_id_s = result.value.sale_id_s;
                    const description_s = result.value.description_s;

                    // Send the data using AJAX
                    $.ajax({
                        url: targetUrl,
                        type: 'POST',
                        data: { status_id_s: status_id_s, sale_id_s: sale_id_s, description_s:description_s },
                        success: function(response) {
                            // console.log(response);
                            Swal.fire(
                                'Success!',
                                'Your data has been submitted.',
                                'success'
                            ).then((result) => {

                                refreshPage();
                            });
                        },
                        error: function() {
                            Swal.fire(
                                'Error!',
                                'There was a problem with the submission.',
                                'error'
                            );
                        }
                    });
                }
            });
        });

        $('#add_freight').on('click', function() {
            // SweetAlert2 popup with input fields
            Swal.fire({
                title: 'Add Your Delivery Charge',
                html:
                    '<input type="text" id="delivery_charge" class="form-control" value="<?php echo e(Utility::formatPrice($sale->delivery_charge)); ?>" placeholder="Name"><br>' +
                    '<input type="hidden" id="sale_id" class="form-control" value="<?php echo e(encrypt($sale->id)); ?>">',
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Submit',
                preConfirm: () => {
                    const delivery_charge = document.getElementById('delivery_charge').value;
                    const sale_id = document.getElementById('sale_id').value;

                    // Check if the inputs are valid
                    if (!delivery_charge) {
                        Swal.showValidationMessage('Please Enter Delivery charge');
                        return false;
                    }
                    return { delivery_charge: delivery_charge, sale_id: sale_id };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Get input values from the SweetAlert2 popup
                    const delivery_charge = result.value.delivery_charge;
                    const sale_id = result.value.sale_id;

                    // Send the data using AJAX
                    $.ajax({
                        url: '<?php echo e(route("admin.sales.addFreight")); ?>',
                        type: 'POST',
                        data: { delivery_charge: delivery_charge, sale_id: sale_id },
                        success: function(response) {
                            // console.log(response);
                            Swal.fire(
                                'Success!',
                                'Your data has been submitted.',
                                'success'
                            ).then((result) => {
                                refreshPage();

                            });
                        },
                        error: function() {
                            Swal.fire(
                                'Error!',
                                'There was a problem with the submission.',
                                'error'
                            );
                        }
                    });
                }
            });
        });

        $('#add_discount').on('click', function() {
            // SweetAlert2 popup with input fields
            Swal.fire({
                title: 'Add Your Discount',
                html:
                    '<input type="text" id="discount" class="form-control" value="<?php echo e(Utility::formatPrice($sale->discount)); ?>" placeholder="Name"><br>' +
                    '<input type="hidden" id="sale_id" class="form-control" value="<?php echo e(encrypt($sale->id)); ?>">',
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Submit',
                preConfirm: () => {
                    const discount = document.getElementById('discount').value;
                    const sale_id = document.getElementById('sale_id').value;

                    // Check if the inputs are valid
                    if (!discount) {
                        Swal.showValidationMessage('Please Enter Discount Amount');
                        return false;
                    }
                    return { discount: discount, sale_id: sale_id };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Get input values from the SweetAlert2 popup
                    const discount = result.value.discount;
                    const sale_id = result.value.sale_id;

                    // Send the data using AJAX
                    $.ajax({
                        url: '<?php echo e(route("admin.sales.addDiscount")); ?>',
                        type: 'POST',
                        data: { discount: discount, sale_id: sale_id },
                        success: function(response) {
                            Swal.fire(
                                'Success!',
                                'Your data has been submitted.',
                                'success'
                            ).then((result) => {
                                refreshPage();
                            });
                        },
                        error: function() {
                            Swal.fire(
                                'Error!',
                                'There was a problem with the submission.',
                                'error'
                            );
                        }
                    });
                }
            });
        });

        $('#add_round_off').on('click', function() {
            // SweetAlert2 popup with input fields
            Swal.fire({
                title: 'Round Off',
                html:
                    '<input type="text" id="round_off" class="form-control" value="<?php echo e(Utility::formatPrice($sale->round_off)); ?>" placeholder="Name"><br>' +
                    '<input type="hidden" id="sale_id" class="form-control" value="<?php echo e(encrypt($sale->id)); ?>">',
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Submit',
                preConfirm: () => {
                    const round_off = document.getElementById('round_off').value;
                    const sale_id = document.getElementById('sale_id').value;

                    // Check if the inputs are valid
                    if (!round_off) {
                        Swal.showValidationMessage('Please Enter Discount Amount');
                        return false;
                    }
                    return { round_off: round_off, sale_id: sale_id };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Get input values from the SweetAlert2 popup
                    const round_off = result.value.round_off;
                    const sale_id = result.value.sale_id;

                    // Send the data using AJAX
                    $.ajax({
                        url: '<?php echo e(route("admin.sales.addRoundOff")); ?>',
                        type: 'POST',
                        data: { round_off: round_off, sale_id: sale_id },
                        success: function(response) {
                            Swal.fire(
                                'Success!',
                                'Your data has been submitted.',
                                'success'
                            ).then((result) => {
                                refreshPage();
                            });
                        },
                        error: function() {
                            Swal.fire(
                                'Error!',
                                'There was a problem with the submission.',
                                'error'
                            );
                        }
                    });
                }
            });
        });

        // $(document).on('click','[data-plugin="change-status"]',function(e) {
        //     e.preventDefault();
        //     if (!confirm('Do you want to change the status?')) return;
        //     var url = $(this).attr('href');
        //     $.ajax({
        //         type: "GET",
        //         url: url,
        //         success: function (data) {
        //             refreshPage();
        //         }
        //     });
	    // });

    });

</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\admin\sales\view.blade.php ENDPATH**/ ?>