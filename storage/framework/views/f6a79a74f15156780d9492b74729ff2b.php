<?php $__env->startSection('title', 'Purchase - ' . $meal->name . ' - Zopa Food Drop'); ?>

<?php $__env->startSection('content'); ?>
<style>
.addon-card {
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 8px;
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    box-shadow: 0 0 8px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    cursor: pointer;
    flex-wrap: wrap;
}

.addon-card:hover {
        background-color: #f8f9fa;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }

.addon-card.selected {
    border-color: #0d6efd;
    background-color: #e8f0ff;
}

.addon-top-row {
    display: flex;
    align-items: center;
    gap: 10px;
}

.addon-bottom-row {
    display: flex;
    justify-content: flex-end;
    margin-top: 10px;
    margin-left: 10px;
}

.addon-image {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 8px;
    flex-shrink: 0;
}

.addon-details {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    flex-grow: 1;
}

.addon-name {
    font-weight: normal;
    cursor: pointer;
    font-size: 14px;
}

.qty-box {
    width: 80px;
}

#payment-message {
    padding: 10px 12px;
    border-radius: 6px;
    margin-top: 8px;
    font-size: 14px;
    line-height: 1.5;
}

#payment-message.info {
    background-color: #e7f3ff;
    border: 1px solid #b3daff;
    color: #084298;
}

#payment-message.warning {
    background-color: #fff4e5;
    border: 1px solid #ffc107;
    color: #664d03;
}

@media (max-width: 576px) {
    .addon-image {
        width: 50px;
        height: 50px;
    }

    .qty-box {
        width: 70px;
    }

    .addon-details {
        flex-wrap: nowrap;
        gap: 5px;
    }
}
</style>

<div class="container my-2">
    <div class="text-center mb-4">
        <h2 class="position-relative d-inline-block px-4 py-2">Purchase - <?php echo e($meal->name); ?></h2>
        <div class="mt-1" style="width: 120px; height: 2px; background: #000000; margin: auto; border-radius: 2px;"></div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12 mb-3">
            <div class="card shadow">
                <div class="card-body">
                    <h4 class="mb-3 text-center">
                        <?php echo e($meal->name); ?>

                        <span class="d-block mt-1 text-muted fs-5">
                            <i class="fa-solid fa-indian-rupee-sign"></i> <?php echo e(number_format($meal->price, 2)); ?>

                        </span>
                    </h4>

                    
                    <div class="text-center mb-3">
                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#mealDetails" aria-expanded="false" aria-controls="mealDetails">
                            Show Details
                        </button>
                    </div>

                    <div class="collapse mb-3" id="mealDetails">
                        <h5>Included Items:</h5>
                        <ul class="list-group mb-3">
                            <?php $__currentLoopData = $meal->ingredients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ingredient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="list-group-item"><?php echo e($ingredient->name); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>

                        <?php if($meal->remarks->isNotEmpty()): ?>
                            <h5>Remarks:</h5>
                            <ul class="list-group mb-3">
                                <?php $__currentLoopData = $meal->remarks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $remark): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="list-group-item"><?php echo e($remark->name); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                    <hr>
                    <form method="POST" action="<?php echo e(route('meal.purchase.store', encrypt($meal->id))); ?>">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="meal_id" value="<?php echo e($meal->id); ?>">

                        
                        <?php if($addons->isNotEmpty()): ?>
                        <p class="mb-3"><strong>Select Addons (Optional)</strong></p>

                        <div class="row">
                            <?php $__currentLoopData = $addons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-6">
                                <div class="addon-card" data-addon-id="<?php echo e($addon->id); ?>">
                                    <div class="addon-top-row">
                                        <?php if($addon->image_filename): ?>
                                        <img src="<?php echo e(Storage::url('addons/' . $addon->image_filename)); ?>" class="addon-image" alt="<?php echo e($addon->name); ?>">
                                        <?php endif; ?>
                                        <div class="addon-details">
                                            <input type="checkbox" class="form-check-input addon-checkbox" name="addons[<?php echo e($addon->id); ?>][selected]" id="addon-<?php echo e($addon->id); ?>" value="<?php echo e($addon->id); ?>">

                                            <label for="addon-<?php echo e($addon->id); ?>" class="addon-name mb-0">
                                                <?php echo e($addon->name); ?>

                                            </label>

                                            <span class="text-muted">₹<?php echo e(number_format($addon->price, 2)); ?></span>

                                            <?php if($addon->description): ?>
                                                <i class="fa-solid fa-circle-info text-zopa"
                                                   data-bs-toggle="tooltip"
                                                   data-bs-html="true"
                                                   data-bs-placement="top"
                                                   title="<?php echo nl2br(e($addon->description)); ?>"></i>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="addon-bottom-row">
                                        <input type="number"
                                               name="addons[<?php echo e($addon->id); ?>][quantity]"
                                               class="form-control qty-box addon-qty"
                                               placeholder="Qty"
                                               min="1"
                                               value="<?php echo e($meal->quantity); ?>"
                                               disabled>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php endif; ?>
                        
                        <div class="text-end mb-3">
                            <h5><strong>Grand Total: ₹<span id="grand-total"><?php echo e(number_format($meal->price, 2)); ?></span></strong></h5>
                        </div>

                        <hr>
                        
                        <div class="mb-3 mt-4">
                            <label for="pay_method" class="form-label">Select Payment Method</label>
                            <select name="pay_method" id="pay_method" class="form-select <?php $__errorArgs = ['pay_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="">-- Choose Payment Option --</option>
                                <option value="<?php echo e(Utility::PAYMENT_ONLINE); ?>">Online Payment</option>
                                <option value="<?php echo e(Utility::PAYMENT_COD); ?>">Cash on Delivery</option>
                            </select>
                            <?php $__errorArgs = ['pay_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div id="payment-message" class="form-text mt-2 text-muted mb-2"></div>
                        <button type="submit" class="btn btn-zopa w-100">
                            Confirm and Pay
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Addon selection toggle
    document.querySelectorAll('.addon-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const card = this.closest('.addon-card');
            const qtyInput = card.querySelector('.addon-qty');

            if (this.checked) {
                card.classList.add('selected');
                qtyInput.disabled = false;
                qtyInput.focus();
            } else {
                card.classList.remove('selected');
                qtyInput.disabled = true;
            }
        });
    });

    // Payment method message toggle
    const payMethodSelect = document.getElementById('pay_method');
    const paymentMessageDiv = document.getElementById('payment-message');

    const onlineMessage = "Your wallet will be credited instantly with meals and addons after a successful online payment.";
    const codMessage = "Your wallet will be credited manually by an admin only after payment confirmation. Choose online payment for instant credit.";

    payMethodSelect.addEventListener('change', function() {
        const value = this.value;
        paymentMessageDiv.classList.remove('info'); // clear before applying

        if (value === '<?php echo e(Utility::PAYMENT_ONLINE); ?>') {
            paymentMessageDiv.textContent = onlineMessage;
            paymentMessageDiv.classList.add('info');
        } else if (value === '<?php echo e(Utility::PAYMENT_COD); ?>') {
            paymentMessageDiv.textContent = codMessage;
            paymentMessageDiv.classList.add('info');
        } else {
            paymentMessageDiv.textContent = '';
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        $(document).ready(function() {
            $('form').on('submit', function() {
                $(this).find('button[type=submit]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Progress...');
            });
        });
    </script>

    <script>
        function updateGrandTotal() {
            let mealPrice = parseFloat(<?php echo e($meal->price); ?>);
            let addonTotal = 0;

            document.querySelectorAll('.addon-checkbox:checked').forEach(checkbox => {
                const card = checkbox.closest('.addon-card');
                const qtyInput = card.querySelector('.addon-qty');
                const quantity = parseInt(qtyInput.value) || 1;
                const priceText = card.querySelector('.addon-details span').textContent.replace('₹', '').trim();
                const price = parseFloat(priceText);

                addonTotal += price * quantity;
            });

            const grandTotal = mealPrice + addonTotal;
            document.getElementById('grand-total').textContent = grandTotal.toFixed(2);
        }

        // Attach event listeners to checkboxes and quantity inputs
        document.querySelectorAll('.addon-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', updateGrandTotal);
        });

        document.querySelectorAll('.addon-qty').forEach(input => {
            input.addEventListener('input', updateGrandTotal);
        });

        // Trigger update once on page load
        updateGrandTotal();
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\pages\meal_purchase.blade.php ENDPATH**/ ?>