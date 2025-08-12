<?php $__env->startSection('title', 'Buy Meal Plans - ' . config('app.name')); ?>

<?php $__env->startSection('content'); ?>
<div class="container my-2">
    <div class="text-center mb-4">
        <h2 class="position-relative d-inline-block px-4 py-2">
            Buy <?php echo e(isset($mess_category) ? $mess_category->name : 'Meal Plans'); ?>

        </h2>
        <div class="mt-1" style="width: 120px; height: 2px; background: #000000; margin: auto; border-radius: 2px;"></div>
    </div>

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

    <div class="row">
        <?php $__currentLoopData = $meals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-sm-6 mb-3">
                <div class="card shadow">
                    <div class="card-header">
                        <h4 class="card-title"><?php echo e($meal->name); ?></h4>
                    </div>
                    <div class="card-body text-center">
                        <img src="<?php echo e(asset('front/images/meals.png')); ?>" alt="<?php echo config('app.name'); ?>" class="img-fluid d-block mx-auto mb-3" style="max-height:150px;">

                        <!-- Details Button triggers modal -->
                        <button type="button" class="btn btn-outline-primary mb-3" data-bs-toggle="modal" data-bs-target="#mealDetailsModal<?php echo e($meal->id); ?>">
                            View Details
                        </button>
                    </div>

                    <div class="card-footer d-flex justify-content-center align-items-center">
                        <a href="<?php echo e(route('meal.purchase', encrypt($meal->id))); ?>"
                           class="btn btn-zopa me-2 makeButtonDisable">
                           <?php if(auth()->guard('customer')->check()): ?>
                            <b>Buy @ <i class="inr-size fa-solid fa-indian-rupee-sign"></i><?php echo e(number_format($meal->price, 2)); ?></b>
                            <?php if($meal->quantity>1): ?>
                            <small>₹<?php echo e(number_format($meal->price/$meal->quantity, 0)); ?>/each</small>
                            <?php endif; ?>
                            <?php endif; ?>
                            <?php if(auth()->guard('customer')->guest()): ?>
                            <b>Buy <?php echo e($meal->name); ?></b>
                            <?php endif; ?>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="mealDetailsModal<?php echo e($meal->id); ?>" tabindex="-1" aria-labelledby="mealDetailsModalLabel<?php echo e($meal->id); ?>" aria-hidden="true">
              <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="mealDetailsModalLabel<?php echo e($meal->id); ?>"><?php echo e($meal->name); ?> - Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <h5>Recipe Items:</h5>
                    <?php if($meal->ingredients->isNotEmpty()): ?>
                        <ul>
                            <?php $__currentLoopData = $meal->ingredients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ingredient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($ingredient->name); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    <?php else: ?>
                        
                    <?php endif; ?>

                    <h5 class="mt-3">Plan Feature:</h5>
                    <?php if($meal->remarks->isNotEmpty()): ?>
                        <ul>
                            <?php $__currentLoopData = $meal->remarks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $remark): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($remark->name); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    <?php else: ?>
                        
                    <?php endif; ?>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="<?php echo e(route('meal.purchase', encrypt($meal->id))); ?>" class="btn btn-zopa">Buy Now</a>
                  </div>
                </div>
              </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\pages\meal_plan.blade.php ENDPATH**/ ?>