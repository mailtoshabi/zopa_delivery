<?php $__currentLoopData = $purchases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-sm-12 mb-3">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <p style="margin-bottom: .1rem;"><small><?php echo e($order->created_at->format('d M Y')); ?></small></p>
                    <p class="fw-bold">Invoice No: <?php echo e($order->invoice_no); ?></p>
                    <?php $grandTotal = 0; ?>
                    <?php if($order->meals->isNotEmpty()): ?>
                        <p class="mb-0 ">
                            <?php $__currentLoopData = $order->meals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $subtotal = $meal->price;
                                $grandTotal += $subtotal;
                            ?>
                                <?php echo e($meal->meal->name); ?>  -
                                <i class="inr-size fa-solid fa-indian-rupee-sign"></i> <?php echo e($meal->price); ?>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </p>
                    <?php endif; ?>
                    <?php if($order->addons->isNotEmpty()): ?>
                        <p class="mb-0 ">
                            <?php $__currentLoopData = $order->addons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $addon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $subtotal = $addon->price * $addon->quantity;
                                $grandTotal += $subtotal;
                            ?>
                                <?php echo e($addon->quantity); ?> <?php echo e($addon->addon->name); ?> x
                                <i class="inr-size fa-solid fa-indian-rupee-sign"></i> <?php echo e($addon->price); ?>


                                <?php if($order->addons->count() > 1 && $index < $order->addons->count() - 1): ?>
                                    <br>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </p>
                    <?php endif; ?>
                    <h5>Grand Total: ₹<?php echo e(number_format($grandTotal, 2)); ?></h5>
                    <p>
                        <?php if($order->is_paid): ?>
                        <span class="badge bg-success ms-2">Paid</span>
                        <?php else: ?>
                        <span class="badge bg-danger ms-2">Not Paid</span>
                        <?php endif; ?>
                        <?php if($order->status): ?>
                        <span class="badge bg-success ms-2">Added to wallet</span>
                        <?php else: ?>
                        <span class="badge bg-danger ms-2">Not Active</span>
                        <?php endif; ?>

                    </p>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php if($purchases->hasMorePages()): ?>
    <div class="text-center mt-3">
        <button class="btn btn-outline-primary" id="load-more" data-next-page="<?php echo e($purchases->currentPage() + 1); ?>">Load More</button>
    </div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\partials\purchases_list.blade.php ENDPATH**/ ?>