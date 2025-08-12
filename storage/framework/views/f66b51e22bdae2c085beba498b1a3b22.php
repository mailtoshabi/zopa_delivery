<?php $__env->startSection('title', 'Site Map - ' . config('app.name')); ?>

<?php $__env->startSection('content'); ?>
<div class="container my-4">
    <div class="text-center mb-4">
        <h2 class="position-relative d-inline-block px-4 py-2">
            Site Map
        </h2>
        <div class="mt-1" style="width: 120px; height: 2px; background: #000000; margin: auto; border-radius: 2px;"></div>
    </div>
    <div class="row">
        <div class="col-md-12 p-4 lh-lg">

            <ul class="list-group list-group-flush">

                <li class="list-group-item">
                    <h5 class="mb-1 text-secondary"><i class="fa-solid fa-house"></i> Dashboard</h5>
                    <small>Overview of your meal wallet, orders, and shortcuts.</small>
                </li>

                <li class="list-group-item">
                    <h5 class="mb-1 text-secondary"><i class="fa-solid fa-bowl-food"></i> Zopa Meals</h5>
                    <ul>
                        <li><strong>Buy a Plan:</strong> View prepaid meal plans and purchase using wallet or COD.</li>
                        <li><strong>Buy Single:</strong> Select one-time meals and checkout directly.</li>
                    </ul>
                </li>

                <li class="list-group-item">
                    <h5 class="mb-1 text-secondary"><i class="fa-solid fa-calendar-day"></i> Daily Orders</h5>
                    <ul>
                        <li><strong>Today's Order:</strong> Meals auto-assigned from your wallet or extra requested.</li>
                        <li><strong>Coming Orders:</strong> Future meal allocations.</li>
                        <li><strong>Order History:</strong> Past meal records and quantities.</li>
                    </ul>
                </li>

                <li class="list-group-item">
                    <h5 class="mb-1 text-secondary"><i class="fa-solid fa-utensils"></i> My Meals</h5>
                    <ul>
                        <li><strong>Request Extra Meal:</strong> Deduct from wallet and add meals for today.</li>
                    </ul>
                </li>

                <li class="list-group-item">
                    <h5 class="mb-1 text-secondary"><i class="fa-solid fa-calendar-xmark"></i> My Leaves</h5>
                    <ul>
                        <li><strong>Mark Leave:</strong> Choose dates you don't want meals.</li>
                        <li><strong>Leave Limit:</strong> Max <?php echo e(Utility::MAX_LEAVE_DAYS_AHEAD); ?> days ahead.</li>
                        <li><strong>Cutoff Time:</strong> Changes allowed only before <?php echo e($lastOrderTime); ?>.</li>
                    </ul>
                </li>

                <li class="list-group-item">
                    <h5 class="mb-1 text-secondary"><i class="fa-solid fa-wallet"></i> My Purchases</h5>
                    <ul>
                        <li><strong>Meal Plans & Wallet:</strong> View top-ups, payments, and usage.</li>
                    </ul>
                </li>

                <li class="list-group-item">
                    <h5 class="mb-1 text-secondary"><i class="fa-solid fa-user"></i> My Profile</h5>
                    <ul>
                        <li><strong>Personal Info:</strong> Edit name, phone, designation, and office.</li>
                        <li><strong>Location:</strong> Update state, district, and postal code.</li>
                    </ul>
                </li>

                <li class="list-group-item">
                    <h5 class="mb-1 text-secondary"><i class="fa-solid fa-gears"></i> Settings</h5>
                    <ul>
                        <li><strong>Password:</strong> Change login password (if enabled).</li>
                        <li><strong>Logout:</strong> End session securely.</li>
                    </ul>
                </li>

                <li class="list-group-item">
                    <h5 class="mb-1 text-secondary"><i class="fa-solid fa-comment-dots"></i> Feedbacks</h5>
                    <small>Send your feedback, suggestions, or concerns.</small>
                </li>

                <li class="list-group-item">
                    <h5 class="mb-1 text-secondary"><i class="fa-solid fa-book-open"></i> Help</h5>
                    <ul>
                        <li><a href="<?php echo e(route('how_to_use')); ?>">How to Use</a></li>
                        <li><a href="<?php echo e(route('site_map')); ?>">Support</a></li>
                        <li><a href="<?php echo e(route('support')); ?>">Site Map</a></li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\pages\site_map.blade.php ENDPATH**/ ?>