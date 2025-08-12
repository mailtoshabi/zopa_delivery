<?php $__env->startSection('title', 'My Daily Meal Quantities - ' . config('app.name')); ?>

<?php $__env->startSection('content'); ?>
<div class="container my-2">
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="text-center mb-4">
        <h2 class="position-relative d-inline-block px-4 py-2">
            <?php echo e(__('messages.page.my_quantities')); ?>

        </h2>
        <div class="mt-1" style="width: 160px; height: 2px; background: #000000; margin: auto; border-radius: 2px;"></div>
    </div>

    <div class="alert alert-info">
        Your Default daily quantity is: <strong><?php echo e(auth()->user()->daily_quantity ?? 'Not set'); ?></strong><br>
        Use this page to change the quantity for specific coming dates.
    </div>

    <form action="<?php echo e(route('customer.quantity-overrides.store')); ?>" method="POST" id="quantity-form">
        <?php echo csrf_field(); ?>
        <div class="mb-3">
            <label for="override_date" class="form-label">Select Date</label>
            <div class="input-group">
                <input type="text" id="override_date" name="date" class="form-control" placeholder="dd-mm-yyyy" autocomplete="off" required>
                <button class="btn btn-outline-secondary" type="button" id="dateBtn">
                    <i class="fa fa-calendar"></i>
                </button>
            </div>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Meal Quantity</label>
            <input type="number" min="1" name="quantity" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-zopa">Save Override</button>
    </form>

    <hr class="my-4">

    <h5 class="mb-3">Daily Meal Quantities</h5>
    <ul class="list-group">
        <?php $__empty_1 = true; $__currentLoopData = $overrides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $override): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $overrideDate = \Carbon\Carbon::parse($override->date)->startOfDay();
                $today = \Carbon\Carbon::today();
                $now = now();

                $cutoff = Utility::getCutoffHourAndMinute();
                $cutoffTime = \Carbon\Carbon::today()->setTime($cutoff['hour'], $cutoff['minute']);

                $isExpired = false;
                if ($overrideDate->lt($today)) {
                    $isExpired = true;
                } elseif ($overrideDate->equalTo($today) && $now->gt($cutoffTime)) {
                    $isExpired = true;
                }

                $badge = $isExpired
                    ? '<span class="badge bg-secondary ms-2">Expired</span>'
                    : '<span class="badge bg-success ms-2">Active</span>';
            ?>

            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <?php echo e($overrideDate->format('d M Y (l)')); ?> – Quantity: <strong><?php echo e($override->quantity); ?></strong>
                    <?php echo $badge; ?>

                </div>
                <?php if(!$isExpired): ?>
                    <form action="<?php echo e(route('customer.quantity-overrides.destroy', $override->id)); ?>" method="POST" onsubmit="return confirm('Remove this quantity override?')">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                    </form>
                <?php endif; ?>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <li class="list-group-item">No quantity overrides added yet.</li>
        <?php endif; ?>
    </ul>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('style'); ?>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/ui-lightness/jquery-ui.css">
    <style>
        .ui-datepicker {
            font-size: 14px;
            background-color: #f9f9f9;
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 10px;
            z-index: 1056 !important;
        }

        .ui-datepicker td a {
            padding: 6px;
            text-align: center;
            display: inline-block;
            background-color: #f8f9fa;
            border-radius: 4px;
            color: #333;
            transition: all 0.2s;
        }

        .ui-datepicker td a:hover {
            background-color: #f63b41;
            color: white;
        }

        .ui-state-highlight, .ui-widget-content .ui-state-highlight, .ui-widget-header .ui-state-highlight {
            border: 1px solid #ec1d23;
            background: #ec1d23 50% top repeat-x;
            color: #ffffff !important;
        }

        .ui-datepicker .ui-datepicker-header {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 6px 0;
            font-weight: bold;
            border-radius: 6px 6px 0 0;
        }

        .ui-datepicker .ui-datepicker-prev,
        .ui-datepicker .ui-datepicker-next {
            cursor: pointer;
            color: white !important;
        }

        .ui-widget-header {
            border: 1px solid #e78f08;
            background: #ec1d23 50% 50% repeat-x !important;
        }

        .ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default, .ui-button, html .ui-button.ui-state-disabled:hover, html .ui-button.ui-state-disabled:active {
            color: #f67579;
        }

    </style>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('scripts'); ?>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>
$(function() {
    $("#override_date").datepicker({
        dateFormat: 'dd-mm-yy', // format: day-month-year
        minDate: 0,             // disables past dates
        beforeShowDay: function(date) {
            const day = date.getDay();
            // 0 = Sunday
            return [day !== 0, "", day === 0 ? "Sundays not allowed" : ""];
        }
    });
});
</script>
<script>
$('#dateBtn').on('click', function() {
    $('#override_date').datepicker('show');
});
</script>
<script>
let allowSubmit = false;

$(document).on('submit', '#quantity-form', function(e) {
    if (allowSubmit) return; // Let it submit normally now

    e.preventDefault();

    const form = $(this);
    const formData = form.serialize();
    const submitButton = form.find('button[type=submit]');
    const originalBtnText = submitButton.html();

    submitButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Saving...');

    $.ajax({
        url: form.attr('action'),
        method: "POST",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        success: function(response) {
            if (response.success) {
                allowSubmit = true;
                form.submit(); // ✅ Will bypass e.preventDefault next time
            } else {
                alert(response.message || 'Validation failed.');
                submitButton.prop('disabled', false).html(originalBtnText);
            }
        },
        error: function(xhr) {
            const message = xhr.responseJSON?.message || 'Validation failed.';
            alert(message);
            submitButton.prop('disabled', false).html(originalBtnText);
        }
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\pages\my_quantity.blade.php ENDPATH**/ ?>