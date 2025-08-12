<?php $__env->startSection('title', __('messages.menu.daily_meals') . ' - ' . config('app.name')); ?>

<?php $__env->startSection('content'); ?>
<style>
    .card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transition: all 0.2s;
    }
 .empty-state i {
        color: #adb5bd;
    }
    .empty-state p {
        font-size: 1.1rem;
    }
    .alert-info {
        background-color: #e7f3fe;
        border-left: 4px solid #0d6efd;
    }
</style>
<div class="container my-2">
    <?php if (isset($component)) { $__componentOriginal5b09c79149dfb771c232996af5f9dae4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5b09c79149dfb771c232996af5f9dae4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.flash-messages','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flash-messages'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5b09c79149dfb771c232996af5f9dae4)): ?>
<?php $attributes = $__attributesOriginal5b09c79149dfb771c232996af5f9dae4; ?>
<?php unset($__attributesOriginal5b09c79149dfb771c232996af5f9dae4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5b09c79149dfb771c232996af5f9dae4)): ?>
<?php $component = $__componentOriginal5b09c79149dfb771c232996af5f9dae4; ?>
<?php unset($__componentOriginal5b09c79149dfb771c232996af5f9dae4); ?>
<?php endif; ?>

    <?php if(isset($mealsLeft) && $mealsLeft<0): ?>
        <?php $__currentLoopData = $meal_wallets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meal_wallet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="alert alert-info d-flex justify-content-between align-items-center mt-2 mb-2">
                <div>
                    <i class="bi bi-wallet2 me-2"></i>
                    <?php echo e($meal_wallet->quantity); ?> <strong><?php echo e($meal_wallet->walletGroup->display_name); ?> </strong> left.
                </div>
                <a href="<?php echo e(route('front.meal.plan')); ?>" class="btn btn-sm btn-outline-primary">
                    <i class="fa-solid fa-plus"></i> Top Up
                </a>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
    <div class="text-center mt-4 mb-4">
        <h2 class="position-relative d-inline-block px-4 py-2">
            <?php echo e(__('messages.menu.daily_meals')); ?>

        </h2>
        <div class="mt-1" style="width: 120px; height: 2px; background: #000000; margin: auto; border-radius: 2px;"></div>
    </div>

    

    <div class="mb-5">

        <?php if(count($todayOrders) > 0): ?>
            <?php $__currentLoopData = $todayOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('partials.order_card', ['order' => $order], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            <div class="empty-state text-center text-muted my-4">
                <i class="bi bi-calendar-x display-4 mb-2"></i>
                <?php if($hasLeaveToday): ?>
                    <div class="alert alert-danger d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Day Off:</strong> You’re on leave today, so no meal will be delivered today.
                        </div>
                    </div>
                <?php else: ?>
                    <?php if(isset($mealsLeft) && $mealsLeft>0): ?>
                        <?php if($default_wallet->status): ?>
                            <div class="alert alert-success d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Waiting for Update:</strong> <?php echo e(__('messages.menu.daily_meals')); ?> is being processed and will be updated here shortly.
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-danger d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Suspended Wallet:</strong> Your Wallet is seems to be deactivated!!
                                </div>
                                <a href="<?php echo e(route('support')); ?>" class="btn btn-sm btn-outline-danger">
                                    <i class="fa-solid fa-headset"></i> Contact Support
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="alert alert-danger d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Empty Wallet:</strong> Your Meal balance is zero. Please top up to continue receiving meals..
                            </div>
                            <a href="<?php echo e(route('front.meal.plan')); ?>" class="btn btn-sm btn-outline-success">
                                <i class="fa-solid fa-headset text-success"></i> Buy a Meal Plan
                            </a>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <?php if(($mealsLeft < Utility::WALLET_LOW_BALANCE) && ($mealsLeft != 0)): ?>
            <div class="alert alert-warning d-flex justify-content-between align-items-center">
                <div>
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    <strong>Low Meal Balance (<?php echo e($mealsLeft); ?> left):</strong> Daily Meal will stop when your balance reaches zero.
                </div>
                <a href="<?php echo e(route('front.meal.plan')); ?>" class="btn btn-sm btn-outline-danger">
                    <i class="fa-solid fa-headset"></i> Buy a Meal Plan
                </a>
            </div>
        <?php endif; ?>
    </div>

</div>

<div aria-live="polite" aria-atomic="true" class="position-fixed top-0 end-0 p-3" style="z-index: 1080;">
    <div id="toastContainer"></div>
</div>

<style>
button .spinner-border {
    vertical-align: text-bottom;
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.cancel-order').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (!confirm('Are you sure you want to cancel this order?')) {
                return;
            }

            const url = this.action;
            const token = this.querySelector('input[name="_token"]').value;
            const formEl = this;
            const button = formEl.querySelector('button');

            const originalHtml = button.innerHTML;
            button.disabled = true;
            button.innerHTML = `<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Cancelling...`;

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showToast(data.message, 'success');
                    setTimeout(() => location.reload(), 1000);  // reload after toast
                } else {
                    showToast(data.message, 'error');
                    button.disabled = false;
                    button.innerHTML = originalHtml;
                }
            })
            .catch(error => {
                showToast('Something went wrong.', 'error');
                button.disabled = false;
                button.innerHTML = originalHtml;
            });
        });
    });
});
</script>

<script>
// Simple toast generator
function showToast(message, type = 'success') {
    const toastId = 'toast-' + Date.now();
    const icon = type === 'success' ? 'check-circle-fill text-success' : 'exclamation-triangle-fill text-danger';
    const bgClass = type === 'success' ? 'bg-success text-white' : 'bg-danger text-white';

    const toastHtml = `
    <div id="${toastId}" class="toast ${bgClass}" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="4000">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-${icon} me-2"></i> ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>`;

    const container = document.getElementById('toastContainer');
    container.insertAdjacentHTML('beforeend', toastHtml);

    const toastEl = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastEl);
    toast.show();

    // Remove toast from DOM after hidden
    toastEl.addEventListener('hidden.bs.toast', () => {
        toastEl.remove();
    });
}
</script>
<?php $__env->stopPush(); ?>



<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\pages\daily_meals.blade.php ENDPATH**/ ?>