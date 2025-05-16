<?php $__env->startSection('title', 'My Leaves - Zopa Food Drop'); ?>

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
            My Leaves
        </h2>
        <div class="mt-1" style="width: 120px; height: 2px; background: #000000; margin: auto; border-radius: 2px;"></div>
    </div>
    <div class="alert alert-info">
        You’ve used <strong><?php echo e($monthlyLeaveCount); ?></strong> of <strong><?php echo e($maxLeaves); ?></strong> monthly leaves.<br>
        You currently have <strong><?php echo e($activeLeaveCount); ?></strong> of <strong><?php echo e($maxActiveLeaves); ?></strong> active leaves.<br>
        Contact <a href="<?php echo e(route('support')); ?>">Support</a> for long-term leaves.
    </div>

    <form action="<?php echo e(route('customer.mark.leaves')); ?>" method="POST" id="leave-form">
        <?php echo csrf_field(); ?>
        <div class="mb-3">
            <label for="leave_date" class="form-label">Select a Date to Mark Leave</label>
            <input type="date" id="leave_date" name="date" class="form-control"
                min="<?php echo e(now()->toDateString()); ?>"
                
                required>
        </div>
        <button type="submit" class="btn btn-zopa"
            
            >
            Mark Leave
        </button>
    </form>

    <hr class="my-4">

    <h5 class="mb-3">My Leaves</h5>
    <ul class="list-group">
        <?php $__empty_1 = true; $__currentLoopData = $leaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php

                $leaveDate = \Carbon\Carbon::parse($leave->leave_at)->startOfDay();
                $today = \Carbon\Carbon::today();
                $now = now();

                $cutoff = Utility::getCutoffHourAndMinute();
                $cutoffHour = $cutoff['hour'];
                $cutoffMinute = $cutoff['minute'];

                $cutoffTime = \Carbon\Carbon::today()->setTime($cutoffHour, $cutoffMinute);

                $isExpired = false;

                if ($leaveDate->lt($today)) {
                    $isExpired = true;
                } elseif ($leaveDate->equalTo($today) && $now->gt($cutoffTime)) {
                    $isExpired = true;
                }

                if ($isExpired) {
                    $badge = '<span class="badge bg-secondary ms-2">Expired</span>';
                } else {
                    $badge = '<span class="badge bg-success ms-2">Active</span>';
                }
            ?>
            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <div class="d-flex align-items-center">
                    <?php echo e($leaveDate->format('d M Y (l)')); ?>

                    <?php echo $badge; ?>

                </div>
                <?php if(!$isExpired): ?>
                    <form action="<?php echo e(route('customer.meal-leaves.destroy', $leave->id)); ?>" method="POST" onsubmit="return confirm('Cancel this leave?')">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-sm btn-outline-danger">Cancel</button>
                    </form>
                <?php endif; ?>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <li class="list-group-item">No leaves marked yet.</li>
        <?php endif; ?>
    </ul>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.getElementById('leave-form').addEventListener('submit', function (e) {
        const dateInput = document.getElementById('leave_date');
        const selectedDate = new Date(dateInput.value);

        if (selectedDate.getDay() === 0) { // 0 = Sunday
            e.preventDefault();
            alert("Sundays are already off. You don't need to mark leave.");
        }
    });

    $(document).ready(function() {
        $('form').on('submit', function() {
            $(this).find('button[type=submit]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Marking...');
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\pages\meal_leaves.blade.php ENDPATH**/ ?>