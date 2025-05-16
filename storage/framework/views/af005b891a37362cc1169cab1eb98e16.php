<?php $__env->startSection('title', 'Buy Meal Plans - Zopa Food Drop'); ?>

<?php $__env->startSection('content'); ?>
<div class="container my-2">
    <div class="text-center mb-4">
        <h2 class="position-relative d-inline-block px-4 py-2">
            Buy Meal Plans
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
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <img src="<?php echo e(asset('front/images/meals.png')); ?>" alt="Zopa Food Drop" class="img-fluid d-block mx-auto">
                        </div>
                        <ul class="list-group mt-3">
                            <?php $__currentLoopData = $meal->ingredients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ingredient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="list-group-item"><?php echo e($ingredient->name); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                    <?php if($meal->remarks->isNotEmpty()): ?>
                    <div class="card-body">
                        <ul class="list-group mt-3">
                            <?php $__currentLoopData = $meal->remarks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $remark): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="list-group-item"><?php echo e($remark->name); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    <div class="card-footer d-flex justify-content-center align-items-center">
                        <a href="<?php echo e(route('meal.purchase', encrypt($meal->id))); ?>"
                        class="btn btn-zopa me-2 makeButtonDisable">
                        <b>Buy @ <i class="inr-size fa-solid fa-indian-rupee-sign"></i><?php echo e(number_format($meal->price, 2)); ?></b>
                        </a>


                        
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\pages\meal_plan.blade.php ENDPATH**/ ?>