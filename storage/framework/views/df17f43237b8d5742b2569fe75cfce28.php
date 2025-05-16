<?php $__env->startSection('title', 'My Daily Orders - Zopa Food Drop'); ?>

<?php $__env->startSection('content'); ?>
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
    <div class="text-center mb-4">
        <h2 class="position-relative d-inline-block px-4 py-2">
            My Daily Orders
        </h2>
        <div class="mt-1" style="width: 120px; height: 2px; background: #000000; margin: auto; border-radius: 2px;"></div>
    </div>

    

    <div class="text-end mb-3">
        <a class="btn btn-danger text-white extra-meal-btn" href="javascript:void(0);">
            <i class="bi bi-plus-circle"></i> Order Extra Meal
        </a>
    </div>

    <ul class="nav nav-tabs" id="orderTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="today-tab" data-bs-toggle="tab" data-tab="today" type="button" role="tab">Today's</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="coming-tab" data-bs-toggle="tab" data-tab="coming" type="button" role="tab">Coming</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="previous-tab" data-bs-toggle="tab" data-tab="previous" type="button" role="tab">Previous</button>
        </li>
    </ul>

    <div class="tab-content mt-3" id="ordersContent">
        <div id="loading" class="text-center py-5 d-none">
            <div class="spinner-border text-primary" role="status"></div>
        </div>
        <div id="orders-list">
            
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>
<script>
    let currentTab = 'today'; // ✅ Declare it globally here

    function loadTab(tab) {
        $('#loading').removeClass('d-none');
        $('#orders-list').html('');

        $.ajax({
            url: '<?php echo e(route("customer.daily_meals")); ?>',
            method: 'GET',
            data: { tab: tab },
            success: function(response) {
                $('#orders-list').html(response);
                initTooltips(); // Re-initialize tooltips on new content
            },
            error: function () {
                $('#orders-list').html('<div class="alert alert-danger">Failed to load orders. Please try again.</div>');
            },
            complete: function() {
                setTimeout(() => {
                    $('#loading').addClass('d-none');
                }, 300);
            }
        });
    }

    function initTooltips() {
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
            new bootstrap.Tooltip(el);
        });
    }

    function showToast(message, type = 'success') {
        let toast = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        $('.flash-messages').prepend(toast);
    }

    $(document).ready(function () {
        loadTab('today'); // Default tab
        initTooltips();

        $('#orderTabs button').on('click', function () {
            $('#orderTabs button').removeClass('active');
            $(this).addClass('active');

            currentTab = $(this).data('tab'); // ✅ Uses global variable now
            loadTab(currentTab);
        });
    });

    $(document).on('click', '#load-more', function () {
        const button = $(this);
        const nextPage = button.data('next-page');
        button.attr('disabled', true).text('Loading...');

        $.ajax({
            url: '<?php echo e(route("customer.daily_meals")); ?>',
            method: 'GET',
            data: {
                tab: currentTab,
                page: nextPage,
                load_more: true
            },
            success: function (response) {
                button.closest('div').remove(); // Remove old button
                $('#previous-orders-list').append(response);
                initTooltips();
            },
            error: function () {
                showToast('Failed to load more orders.', 'danger');
            }
        });
    });
</script>
<script>
    $(document).on('submit', '.cancel-order', function (e) {
        e.preventDefault();

        if (confirm('Are you sure you want to cancel this order?')) {
            let form = $(this);
            form.find('button[type=submit]').attr('disabled', true);

            $.post(form.attr('action'), form.serialize())
                .done(function () {
                    loadTab('today');
                    showToast('Order cancelled and refunded to wallet.', 'success');
                })
                .fail(function (xhr) {
                    let message = 'Failed to cancel order. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    showToast(message, 'danger');
                });
        }
    });
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\pages\daily_meals_OLD.blade.php ENDPATH**/ ?>