<?php $__env->startSection('title', 'Pay Online - ' . config('app.name')); ?>

<?php $__env->startSection('content'); ?>
<div class="container my-4">
    <div class="text-center mb-4">
        <h2 class="position-relative d-inline-block px-4 py-2">Pay Online</h2>
        <div class="mt-1" style="width: 120px; height: 2px; background: #000000; margin: auto; border-radius: 2px;"></div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body text-center">
                    <h3 class="text-success mb-4">Invoice No: <?php echo e($order->notes->invoice_no); ?></h3>
                    
                    

                    <button id="rzp-button" class="btn btn-success btn-lg">Pay ₹<?php echo e(number_format($grandTotal, 2)); ?> </button>

                    <p class="mt-3 text-muted">You will be redirected to Razorpay secure payment gateway.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Razorpay JS -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    const options = {
        "key": "<?php echo e($razorpayKey); ?>",
        "amount": "<?php echo e($grandTotal * 100); ?>", // in paise
        "currency": "INR",
        "name": "<?php echo e(config('app.name')); ?>",
        "description": "Meal & Addon Purchase",
        "image": "<?php echo e(asset('front/images/logo_red.png')); ?>",
        "order_id": "<?php echo e($order->id); ?>", // Razorpay order ID from backend
        "handler": function (response) {
            const form = document.createElement("form");
            form.method = "POST";
            form.action = "<?php echo e(route('meal.payment.verify')); ?>";

            const csrf = document.createElement("input");
            csrf.name = "_token";
            csrf.value = "<?php echo e(csrf_token()); ?>";
            form.appendChild(csrf);

            const paymentId = document.createElement("input");
            paymentId.name = "razorpay_payment_id";
            paymentId.value = response.razorpay_payment_id;
            form.appendChild(paymentId);

            const orderId = document.createElement("input");
            orderId.name = "razorpay_order_id";
            orderId.value = response.razorpay_order_id;
            form.appendChild(orderId);

            const signature = document.createElement("input");
            signature.name = "razorpay_signature";
            signature.value = response.razorpay_signature;
            form.appendChild(signature);

            document.body.appendChild(form);
            form.submit();
        },
        "prefill": {
            "name": "<?php echo e($customer->name); ?>",
            "email": "<?php echo e($customer->email ?? 'support@zopa.com'); ?>",
            "contact": "<?php echo e($customer->phone); ?>"
        },
        "theme": {
            "color": "#0d6efd"
        }
    };

    const rzp = new Razorpay(options);
    document.getElementById('rzp-button').onclick = function (e) {
        e.preventDefault();

        const button = this;
        button.disabled = true;
        button.innerText = 'Processing...'; // Optional: show feedback

        rzp.open();

        // Re-enable the button if payment popup is closed by the user
        rzp.on('payment.failed', function () {
            button.disabled = false;
            button.innerText = 'Pay Now';
        });
    };
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\pages\razorpay_payment.blade.php ENDPATH**/ ?>