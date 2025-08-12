<?php
    $customer = Auth::guard('customer')->user();

    $walletCount = 0;
    $mealCount = 0;
    $addonCount = 0;
    $totalCartCount = 0;
    $profileImage = '';

    if ($customer) {
        $walletCount = $customer->mealWallet->quantity ?? 0;

        $mealCart = session('meal_cart', []);
        $addonCart = session('addon_cart', []);
        $mealCount = collect($mealCart)->sum('quantity');
        $addonCount = collect($addonCart)->sum('quantity');
        $totalCartCount = $mealCount + $addonCount;

        $profileImage = $customer->image_filename
            ? Storage::url(App\Models\Customer::DIR_PUBLIC . '/' . $customer->image_filename)
            : 'https://ui-avatars.com/api/?name='.$customer->name.'&background=ec1d23&color=fff';
    }
    if (Auth::guard('customer')->check()) {
        $mess_categories = App\Models\MessCategory::withActiveMealsForKitchen();
    }else {
        $mess_categories = App\Models\MessCategory::where('status',Utility::ITEM_ACTIVE)->orderBy('display_order','asc')->get();
    }


?>

<!-- Navigation Bar -->
<nav class="navbar navbar-light bg-light">
    <div class="container d-flex justify-content-between align-items-center">
        <a class="navbar-brand" href="<?php echo e(route('index')); ?>">
            <img src="<?php echo e(asset('front/images/logo.png')); ?>" alt="<?php echo config('app.name'); ?>" class="logo">
        </a>
        <span class="menu-toggle" onclick="toggleMenu()">
            <i class="fa-solid fa-bars"></i>
        </span>
        <div class="desktop-menu">
            <ul class="navbar-nav d-flex flex-row gap-5">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo e(route('index')); ?>"><i class="fa-solid fa-home"></i> <?php echo e(__('messages.menu.home')); ?></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?php echo e(route('customer.daily_meals')); ?>">
                        <i class="fa-solid fa-utensils"></i>&nbsp;&nbsp;<?php echo e(__('messages.menu.daily_meals')); ?>


                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="ordersDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-concierge-bell"></i>&nbsp;&nbsp;<?php echo e(__('messages.menu.zopa_mess')); ?></a>
                    <ul class="dropdown-menu" aria-labelledby="ordersDropdown">
                        <?php if($customer): ?><li><a class="dropdown-item" href="<?php echo e(route('my.wallet')); ?>"><i class="fa-solid fa-wallet"></i>&nbsp;&nbsp;<?php echo e(__('messages.menu.wallet')); ?></a></li><?php endif; ?>
                        <?php $__currentLoopData = $mess_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><a class="dropdown-item" href="<?php echo e(route('front.meal',$category->slug)); ?>"><i class="fa-solid fa-receipt"></i>&nbsp;&nbsp;<?php echo e($category->name); ?></a></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                        <li><a class="dropdown-item" href="<?php echo e(route('front.show.addons')); ?>"><i class="fa-solid fa-plus-circle"></i>&nbsp;&nbsp;<?php echo e(__('messages.menu.buy_addons')); ?></a></li>
                        <?php if($customer && ($customer->type === Utility::CUSTOMER_TYPE_IND)): ?><li><a class="dropdown-item extra-meal-btn" href="javascript:void(0);"><i class="fa-solid fa-plus"></i>&nbsp;&nbsp;<?php echo e(__('messages.menu.request_extra')); ?></a></li><?php endif; ?>
                    </ul>
                </li>
                
                <?php if($customer): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="settingsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-user"></i> <?php echo e(__('messages.menu.my_account')); ?>

                        </a>
                        <ul class="dropdown-menu" aria-labelledby="settingsDropdown">
                            
                            
                            

                            <li class="dropdown-header fw-bold text-zopa pb-0">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="<?php echo e($profileImage); ?>" alt="Profile" class="rounded-circle" width="30" height="30">
                                    <div>
                                        <?php echo e($customer->name); ?><br>
                                        <small class="text-muted"><?php echo e($customer->phone); ?></small>
                                    </div>
                                </div>
                            </li>

                            <li class="dropdown-header fw-bold text-dark pt-0">
                                <small><a href="<?php echo e(route('my.wallet')); ?>" class="text-dark"><?php echo e(__('messages.menu.meal_wallet')); ?>:
                                    
                                            <?php echo e($walletCount); ?>

                                    
                                </a>
                                </small></li>
                            <li><hr class="dropdown-divider mt-0 mb-0"></li>
                            <li><a class="dropdown-item" href="<?php echo e(route('customer.leave.index')); ?>"><i class="fa-solid fa-calendar-xmark"></i>&nbsp;&nbsp;<?php echo e(__('messages.menu.leaves')); ?></a></li>
                            <li><a class="dropdown-item" href="<?php echo e(route('customer.purchases')); ?>"><i class="fa-solid fa-receipt"></i>&nbsp;&nbsp;<?php echo e(__('messages.menu.purchases')); ?></a></li>
                            <?php if($customer->type === Utility::CUSTOMER_TYPE_INST): ?>
                                <li><a class="dropdown-item" href="<?php echo e(route('customer.quantity-overrides.index')); ?>"><i class="fa-solid fa-sort-numeric-up-alt"></i>&nbsp;&nbsp;<?php echo e(__('messages.menu.my_quantity') ?? 'My Quantity'); ?></a></li>
                            <?php else: ?>
                                <li><a class="dropdown-item" href="<?php echo e(route('customer.extra_meals')); ?>"><i class="fa-solid fa-plus"></i>&nbsp;&nbsp;<?php echo e(__('messages.menu.extra_meals')); ?></a></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item" href="<?php echo e(route('customer.profile')); ?>"><i class="fa-solid fa-user-pen"></i>&nbsp;&nbsp;<?php echo e(__('messages.menu.profile')); ?></a></li>
                            <li>
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fa-solid fa-right-from-bracket"></i>&nbsp;&nbsp;<?php echo e(__('messages.menu.logout')); ?>

                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
                <?php if(!$customer): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('customer.login')); ?>"><i class="fa-solid fa-user"></i> Signup/Login</a>
                    </li>
                <?php endif; ?>
                
            </ul>
        </div>
    </div>
</nav>

<?php if($customer && (empty($customer->office_name) || empty($customer->location_name)) ): ?>
    <div class="alert alert-danger d-flex justify-content-between align-items-center mx-4 mt-4 mb-4">
        <div>
            <i class="bi bi-wallet2 me-2"></i>
            <strong>Complete Your Address</strong> to recieve the meals to your doorstep.
        </div>
        <a href="<?php echo e(route('customer.profile')); ?>?edit=true" class="btn btn-sm btn-outline-danger text-danger">
            <i class="fa-solid fa-pencil text-danger"></i> Complete your Address
        </a>
    </div>
<?php endif; ?>

<!-- Mobile Menu -->
<div class="mobile-menu" id="mobileMenu">
    <span class="close-btn" onclick="toggleMenu()">&times;</span>
    <?php if($customer): ?>
    <div class="px-4 py-3 border-bottom">
        
        <div class="fw-bold text-zopa d-flex align-items-center gap-2">
            <img src="<?php echo e($profileImage); ?>" alt="Profile" class="rounded-circle" width="30" height="30">
            <div>
                <?php echo e($customer->name); ?><br>
                <small class="text-muted"><?php echo e($customer->phone); ?></small>
            </div>
        </div>
        <div class="text-muted small"><a href="<?php echo e(route('my.wallet')); ?>" class="text-dark">My Wallet: <?php echo e($walletCount); ?></a></div>
    </div>
    <?php endif; ?>
    <ul>
        
        <li>
            <div class="d-flex gap-2 ps-2">
                <a href="<?php echo e(route('front.set.language', 'en')); ?>" class="lang-box" data-bs-toggle="tooltip" data-bs-placement="top" title="English">EN</a>
                <a href="<?php echo e(route('front.set.language', 'ml')); ?>" class="lang-box" data-bs-toggle="tooltip" data-bs-placement="top" title="Malayalam">ML</a>
            </div>
        </li>
        <?php if($customer): ?>
        <li><a href="<?php echo e(route('customer.daily_meals')); ?>"><i class="fa-solid fa-utensils"></i> Daily Meals</a></li>
        <?php endif; ?>
        <li>
            <a href="#" onclick="toggleSubmenu(event, 'zopaMealsSubmenu')">
                <i class="fa-solid fa-concierge-bell"></i> <?php echo e(__('messages.menu.zopa_mess')); ?>

                <i class="fa-solid fa-chevron-down float-end"></i> 
            </a>
            <ul class="submenu" id="zopaMealsSubmenu">
                <?php if($customer): ?>
                <li><a href="<?php echo e(route('my.wallet')); ?>"><i class="fa-solid fa-wallet"></i> <?php echo e(__('messages.menu.wallet')); ?></a></li>
                <?php endif; ?>
                <li><a href="<?php echo e(route('front.meal.plan')); ?>"><i class="fa-solid fa-receipt"></i> <?php echo e(__('messages.menu.buy_plan')); ?></a></li>
                <li><a href="<?php echo e(route('front.meal.single')); ?>"><i class="fa-solid fa-shopping-basket"></i> <?php echo e(__('messages.menu.buy_single')); ?></a></li>
                <li><a href="<?php echo e(route('front.show.addons')); ?>"><i class="fa-solid fa-plus-circle"></i> <?php echo e(__('messages.menu.buy_addons')); ?></a></li>
            </ul>
        </li>
        <li><a href="<?php echo e(route('feedbacks')); ?>"><i class="fa-solid fa-comments"></i> <?php echo e(__('messages.menu.feedbacks')); ?></a></li>
        <?php if($customer): ?>
        <li>
            <a href="#" onclick="toggleSubmenu(event, 'zopaMealsSettings')">
                <i class="fa-solid fa-user"></i> Account
                <i class="fa-solid fa-chevron-down float-end"></i> 
            </a>
            <ul class="submenu" id="zopaMealsSettings">
                <li><a href="<?php echo e(route('customer.leave.index')); ?>"><i class="fa-solid fa-calendar-xmark"></i> <?php echo e(__('messages.menu.leaves')); ?></a></li>
                <li><a href="<?php echo e(route('customer.purchases')); ?>"><i class="fa-solid fa-receipt"></i> <?php echo e(__('messages.menu.purchases')); ?></a></li>
                <?php if($customer->type === Utility::CUSTOMER_TYPE_INST): ?>
                    <li><a href="<?php echo e(route('customer.quantity-overrides.index')); ?>"><i class="fa-solid fa-sort-numeric-up-alt"></i> <?php echo e(__('messages.menu.my_quantity')); ?></a></li>
                <?php else: ?>
                    <li><a href="<?php echo e(route('customer.extra_meals')); ?>"><i class="fa-solid fa-plus"></i> <?php echo e(__('messages.menu.extra_meals')); ?></a></li>
                <?php endif; ?>
                <li><a href="<?php echo e(route('customer.profile')); ?>"><i class="fa-solid fa-user-pen"></i> <?php echo e(__('messages.menu.profile')); ?></a></li>
            </ul>
        </li>
        <li>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa-solid fa-right-from-bracket"></i> <?php echo e(__('messages.menu.logout')); ?>

            </a>
        </li>
        <?php endif; ?>
    </ul>
</div>

<form id="logout-form" action="<?php echo e(route('customer.logout')); ?>" method="POST" style="display: none;">
    <?php echo csrf_field(); ?>
</form>
<?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\includes\nav.blade.php ENDPATH**/ ?>