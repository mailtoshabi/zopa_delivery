<?php $__env->startSection('title', 'My Wallet - Zopa Food Drop'); ?>
<?php $__env->startSection('content'); ?>
<div class="container my-2">
    <div class="text-center mb-4">
        <h2 class="position-relative d-inline-block px-4 py-2">
            My Wallet
        </h2>
        <div class="mt-1" style="width: 120px; height: 2px; background: #000000; margin: auto; border-radius: 2px;"></div>
    </div>

    <div class="row">

        
        <div class="col-sm-12 mb-3">
            <div class="card shadow">
                <div class="card-header bg-light">
                    <h4 class="mb-3">Meal Wallet</h4>
                </div>
                <div class="card-body">
                    <small class="text-muted d-block mt-1 mb-3">
                        If you have meal balance? Your Daily Meal gets auto-delivered — no need to set a reminder or do anything.<br>
                        Just mark leave if you want to skip a day. Sundays are off!
                    </small>
                    <div class="row d-flex align-items-center" style="width: 100%;">
                        <div class="col-sm-12 col-md-6 mb-3 mb-md-0">
                            <div class="d-flex align-items-center">
                                
                                <img src="<?php echo e(asset('front/images/meals.png')); ?>"
                                    alt="Meals"
                                    class="rounded me-3 shadow-sm"
                                    style="width: 50px; height: 50px; object-fit: cover;">

                                
                                <span class="me-2">Meals</span>
                                <span class="badge bg-success rounded-pill"><?php echo e($mealsLeft); ?> left</span>
                            </div>
                        </div>

                        
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-sm-12 mb-3">
            <div class="card shadow">
                <div class="card-header bg-light">
                    <h4 class="mb-3">Addons Wallet</h4>
                </div>
                <?php if($addon_wallet->count() > 0): ?>
                    <div class="card-body">
                        <small class="text-muted d-block mt-1 mb-2">
                            If you have addon balance and it’s on, we’ll include it with your Daily Meal.
                            Don’t want it? Just switch it off!
                        </small>

                        <ul class="list-group">
                            <?php $__currentLoopData = $addon_wallet; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <?php if($item->addon->image_filename): ?>
                                        
                                        <img src="<?php echo e(Storage::url('addons/' . $item->addon->image_filename)); ?>"
                                            alt="<?php echo e($item->addon->name); ?>"
                                            class="rounded me-3 shadow-sm"
                                            style="width: 50px; height: 50px; object-fit: cover;">
                                        <?php endif; ?>

                                        
                                        <span><?php echo e($item->addon->name); ?></span>
                                        <?php if($item->addon->description): ?>
                                            <small>&nbsp;<i class="fa-solid fa-circle-info text-zopa"
                                            data-bs-toggle="tooltip"
                                            data-bs-html="true"
                                            data-bs-placement="top"
                                            title="<?php echo nl2br(e($item->addon->description)); ?>"></i></small>
                                        <?php endif; ?>
                                    </div>

                                    
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-success rounded-pill me-3"><?php echo e($item->quantity); ?> left</span>

                                        
                                        <div class="form-check form-switch">
                                            <input class="form-check-input addon-status-toggle"
                                                type="checkbox"
                                                data-id="<?php echo e($item->id); ?>"
                                                <?php echo e($item->is_on ? 'checked' : ''); ?>>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php else: ?>
                    <div class="card-body text-center">
                        <p class="text-muted d-block mt-3">
                            Addons Wallet is empty.
                        </p>
                        <a href="<?php echo e(route('front.addons')); ?>" class="btn btn-zopa makeButtonDisable">
                            <i class="fa-solid fa-plus-circle"></i>&nbsp;&nbsp;Buy Addons</a>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>


        
        <div class="col-sm-12 mb-3">
            <div class="card shadow">
                <div class="card-header bg-light">
                    <h4 class="mb-3">Monthly Leaves Limit</h4>
                </div>
                <div class="card-body text-center">
                    <a href="<?php echo e(route('customer.leave.index')); ?>" class="btn btn-zopa makeButtonDisable">
                        <i class="fa-solid fa-calendar-xmark"></i> Mark Leaves
                    </a>
                    <p class="pt-2"><small>
                        You currently have <strong><?php echo e($activeLeaveCount); ?></strong> of <strong><?php echo e($maxActiveLeaves); ?></strong> active leaves.
                        Contact <a href="<?php echo e(route('support')); ?>">Support</a> for long-term leaves.
                    </small>
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggles = document.querySelectorAll('.addon-status-toggle');

        toggles.forEach(function(toggle) {
            toggle.addEventListener('change', function() {
                const walletId = this.dataset.id;
                const isOn = this.checked ? 1 : 0;

                fetch('<?php echo e(route('addonWallet.toggleStatus')); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    },
                    body: JSON.stringify({ wallet_id: walletId, is_on: isOn })
                })
                .then(res => {
                    if (!res.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        // No need to change text (you don’t actually have status text here anyway)
                    } else {
                        alert('Failed to update toggle.');
                        toggle.checked = !isOn;
                    }
                })
                .catch(() => {
                    alert('Something went wrong. Try again.');
                    toggle.checked = !isOn;
                });
            });
        });
    });
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\pages\my_wallet.blade.php ENDPATH**/ ?>