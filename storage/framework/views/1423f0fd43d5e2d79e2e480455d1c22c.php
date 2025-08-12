<?php $__env->startSection('title', 'My Wallet - ' . config('app.name')); ?>
<?php $__env->startSection('content'); ?>
<div class="container my-2">
    <div class="text-center mb-4">
        <h2 class="position-relative d-inline-block px-4 py-2">
            My Wallets
        </h2>
        <div class="mt-1" style="width: 120px; height: 2px; background: #000000; margin: auto; border-radius: 2px;"></div>
    </div>

    <div class="row">

        
        <div class="col-sm-12 mb-3">
            <div class="card shadow">
                <div class="card-header bg-light">
                    <h4 class="mb-3">Meal Wallet</h4>
                </div>

                <small class="text-muted d-block mt-1 mb-3 mx-3">
                    If you have meal balance, Daily Meal gets auto-delivered — no need to set a reminder or do anything.
                    Just mark leave if you want to skip a day. Sundays are already off!
                </small>
                <?php if($meal_wallets->isNotEmpty()): ?>
                    <div class="card-body">
                        <?php $__currentLoopData = $meal_wallets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meal_wallet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="row d-flex align-items-center" style="width: 100%;">
                                <div class="col-sm-12 mb-3">
                                    <div class="d-flex align-items-center">
                                        
                                        <div class="position-relative me-3" style="width: 50px; height: 50px;">
                                            <img src="<?php echo e(asset('front/images/meals.png')); ?>"
                                                alt="Meals"
                                                class="rounded me-3 shadow-sm <?php echo e($meal_wallet->status == 0?'changetogrey':''); ?>"
                                                style="width: 50px; height: 50px; object-fit: cover;">
                                            <?php if($meal_wallet->is_on && ($meal_wallets->count()>1)): ?>
                                                <div class="position-absolute top-0 end-0" style="transform: translate(30%, -30%);">
                                                    <span class="badge bg-secondary rounded-circle px-2 py-1"
                                                        data-bs-toggle="tooltip"
                                                        title="Default">
                                                        <i class="fa-solid fa-star"></i>
                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        
                                        <span class="me-2 <?php echo e($meal_wallet->status ? 'text-dark' : 'text-muted'); ?>">
                                            <?php echo e($meal_wallet->walletGroup->display_name); ?>

                                        </span>
                                        <span class="badge <?php echo e($meal_wallet->status ? 'bg-success' : 'bg-secondary'); ?> rounded-pill"><?php echo e($meal_wallet ? $meal_wallet->quantity : 0); ?> left</span>&nbsp;
                                        

                                        <?php if($meal_wallet->status == 0): ?>
                                            <span class="badge bg-danger rounded-pill">Suspended</span><span>&nbsp;&nbsp;<a href="<?php echo e(route('support')); ?>" >Contact Support</a></span>
                                        <?php else: ?>
                                             <?php if($meal_wallet->quantity < Utility::WALLET_LOW_BALANCE): ?>
                                                <span class="badge bg-danger rounded-pill">Low Balance</span><span>&nbsp;&nbsp;<a href="<?php echo e(route('front.meal.plan')); ?>" >Buy</a></span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>

                                    
                                </div>

                                
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php if($meal_wallets->count()>1): ?>
                    <div class="text-end mt-2 mx-3 mb-3">
                        <a href="javascript:void(0);" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#defaultMealModal" onclick="loadDefaultMealContent()">
                            <i class="fa-solid fa-star"></i> Change Default
                        </a>
                    </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="card-body text-center">
                        <p class="text-muted mt-3">
                            You haven't purchased any Meals yet. Get started by purchasing our meals plans.
                        </p>

                        <a href="<?php echo e(route('front.meal.plan')); ?>" class="btn btn-zopa makeButtonDisable">
                            <i class="fa-solid fa-plus-circle"></i>&nbsp;&nbsp;Buy Meal Plans
                        </a>
                    </div>
                <?php endif; ?>

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

                        <?php if($addon_wallet->where('status', 0)->count() > 0): ?>
                            <div class="alert alert-warning d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Note:</strong> Suspended addons will not be delivered.
                                    <br class="d-sm-none">
                                    To activate, <a href="<?php echo e(route('support')); ?>">contact support</a>.
                                </div>
                            </div>
                        <?php endif; ?>

                        <ul class="list-group">
                            <?php $__currentLoopData = $addon_wallet; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <?php
                                $addonProcessingCount = \App\Models\DailyAddon::where('addon_id', $item->addon_id)
                                    ->whereHas('dailyMeal', function ($query) use ($item) {
                                        $query->where('customer_id', auth('customer')->id())
                                            ->whereDate('created_at', \Carbon\Carbon::today())
                                            ->where('status', 1)
                                            ->where('is_delivered', 0);
                                    })->sum('quantity');
                            ?>
                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                    <div class=" align-items-center">
                                        <?php if($item->addon->image_filename): ?>
                                        
                                        <img src="<?php echo e(Storage::url('addons/' . $item->addon->image_filename)); ?>"
                                            alt="<?php echo e($item->addon->name); ?>"
                                            class="rounded me-3 shadow-sm"
                                            style="width: 50px; height: 50px; object-fit: cover;">
                                        <?php endif; ?>

                                        
                                        <span class="<?php echo e($item->status ? 'text-dark' : 'text-muted'); ?>"><strong><?php echo e($item->addon->name); ?></strong></span>
                                        <?php if($item->addon->description): ?>
                                            <small>&nbsp;<i class="fa-solid fa-circle-info text-zopa"
                                            data-bs-toggle="tooltip"
                                            data-bs-html="true"
                                            data-bs-placement="top"
                                            title="<?php echo nl2br(e($item->addon->description)); ?>"></i></small>
                                        <?php endif; ?>

                                        <?php if($item->status == 0): ?>
                                            <span class="badge bg-danger text-white ms-2">Suspended</span>
                                        <?php else: ?>
                                            <span class="addon-status-badge ms-2" data-id="<?php echo e($item->id); ?>">
                                                <span class="badge <?php echo e($item->is_on ? 'bg-success' : 'bg-danger'); ?> text-white addon-status-text">
                                                    <?php echo e($item->is_on ? 'ON' : 'OFF'); ?>

                                                </span>
                                                <span class="spinner-border spinner-border-sm text-primary d-none addon-status-spinner" role="status" aria-hidden="true"></span>
                                            </span>


                                            <?php if($item->quantity < Utility::WALLET_LOW_BALANCE): ?>
                                                <span class="badge bg-danger rounded-pill">Low Balance</span><span>&nbsp;&nbsp;<a href="<?php echo e(route('front.show.addons')); ?>" >Buy Addons</a></span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>

                                    
                                    <div class="d-flex align-items-center">
                                        <span class="badge <?php echo e($item->status ? 'bg-success' : 'bg-secondary'); ?> rounded-pill me-3"><?php echo e($item->quantity); ?> left</span>
                                        <?php if($addonProcessingCount > 0): ?>
                                            <span class="badge bg-info rounded-pill ms-2"><?php echo e($addonProcessingCount); ?> processing</span>
                                        <?php endif; ?>
                                        
                                        <?php if($item->status == 1): ?>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input addon-status-toggle"
                                                    type="checkbox"
                                                    data-id="<?php echo e($item->id); ?>"
                                                    <?php echo e($item->is_on ? 'checked' : ''); ?>>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php else: ?>
                    <div class="card-body text-center">
                        <p class="text-muted mt-3">
                            You haven't purchased any addons yet. Get started by choosing from our delicious extras!
                        </p>

                        <a href="<?php echo e(route('front.show.addons')); ?>" class="btn btn-zopa makeButtonDisable">
                            <i class="fa-solid fa-plus-circle"></i>&nbsp;&nbsp;Buy Addons
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>


        
        <div class="col-sm-12 mb-3">
            <div class="card shadow">
                <div class="card-header bg-light">
                    <h4 class="mb-3">Monthly Leaves</h4>
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

<?php echo $__env->make('partials.make_default_modal', ['meal_wallets' => $meal_wallets], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggles = document.querySelectorAll('.addon-status-toggle');

        toggles.forEach(function(toggle) {
            toggle.addEventListener('change', function() {
                const walletId = this.dataset.id;
                const isOn = this.checked ? 1 : 0;

                const badgeContainer = document.querySelector(`.addon-status-badge[data-id="${walletId}"]`);
                const statusText = badgeContainer.querySelector('.addon-status-text');
                const spinner = badgeContainer.querySelector('.addon-status-spinner');

                // Show spinner and hide text
                spinner.classList.remove('d-none');
                statusText.classList.add('d-none');

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
                        statusText.textContent = isOn === 1 ? 'ON' : 'OFF';
                        statusText.className = `badge ${isOn ? 'bg-success' : 'bg-danger'} text-white addon-status-text`;
                    } else {
                        alert('Failed to update toggle.');
                        toggle.checked = !isOn;
                    }
                })
                .catch(() => {
                    alert('Something went wrong. Try again.');
                    toggle.checked = !isOn;
                })
                .finally(() => {
                    spinner.classList.add('d-none');
                    statusText.classList.remove('d-none');
                });
            });
        });
    });
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\pages\my_wallet.blade.php ENDPATH**/ ?>