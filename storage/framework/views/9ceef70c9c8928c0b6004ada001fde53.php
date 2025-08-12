<!-- Modal -->
<div class="modal fade" id="defaultMealModal" tabindex="-1" aria-labelledby="defaultMealModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="defaultMealModalLabel"><i class="fa-solid fa-utensils"></i> Set Default Wallet</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="defaultMealContent">
        
      </div>
    </div>
  </div>
</div>
<?php if($meal_wallets->isNotEmpty()): ?>
    <div id="mealWalletList" style="display: none;">
        <?php $__currentLoopData = $meal_wallets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meal_wallet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($meal_wallet->quantity>0 && $meal_wallet->status): ?>
                <div class="row d-flex align-items-center mb-3" style="width: 100%;">
                    <div class="col-sm-12 col-md-6 mb-3 mb-md-0">
                        <div class="d-flex align-items-center">
                            
                            <img src="<?php echo e(asset('front/images/meals.png')); ?>"
                                alt="Meals"
                                class="rounded me-3 shadow-sm"
                                style="width: 50px; height: 50px; object-fit: cover;">
                            <span class="me-2 <?php echo e($meal_wallet->status ? 'text-dark' : 'text-muted'); ?>"><?php echo e($meal_wallet->walletGroup->display_name); ?></span>
                            <?php if($meal_wallet->is_on): ?>
                                <small><span class="badge bg-secondary rounded-pill ms-2"><i class="fa-solid fa-star"></i> Default</span></small>
                            <?php else: ?>
                                <small>
                                    <a href="javascript:void(0);"
                                    class="text-primary make-default-btn"
                                    data-id="<?php echo e($meal_wallet->id); ?>">
                                    Make Default
                                    </a>
                                </small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function loadDefaultMealContent() {
        var content = document.getElementById('mealWalletList').innerHTML;
        document.getElementById('defaultMealContent').innerHTML = content;

        // Rebind the click after cloning
        bindMakeDefaultButtons();
    }

    function bindMakeDefaultButtons() {
        $('.make-default-btn').off('click').on('click', function () {
            let walletId = $(this).data('id');

            $.ajax({
                url: "<?php echo e(route('front.wallet.make-default')); ?>",
                type: 'POST',
                data: {
                    _token: "<?php echo e(csrf_token()); ?>",
                    wallet_id: walletId
                },
                success: function (response) {
                    if (response.success) {
                        // Optionally refresh the modal content
                        loadDefaultMealContent();

                        // Or you could reload the page
                        location.reload();
                    } else {
                        alert('Something went wrong!');
                        location.reload();
                    }
                },
                error: function () {
                    alert('Request failed!');
                }
            });
        });
    }

    // Initial bind
    // $(document).ready(function () {
    //     bindMakeDefaultButtons();
    // });
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\partials\make_default_modal.blade.php ENDPATH**/ ?>