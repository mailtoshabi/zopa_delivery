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
?>

<!-- Navigation Bar -->
<nav class="navbar navbar-light bg-light">
    <div class="container d-flex justify-content-between align-items-center">
        <a class="navbar-brand" href="<?php echo e(route('index')); ?>">
            <img src="<?php echo e(asset('front/images/logo.png')); ?>" alt="Zopa Food Drop" class="logo">
        </a>
        <span class="menu-toggle" onclick="toggleMenu()">
            <i class="fa-solid fa-bars"></i>
        </span>
        <div class="desktop-menu">
            <ul class="navbar-nav d-flex flex-row gap-5">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo e(route('customer.daily_meals')); ?>">
                        <i class="fa-solid fa-utensils"></i>&nbsp;&nbsp;Daily Meals
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="ordersDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-concierge-bell"></i>&nbsp;&nbsp;Zopa Meals</a>
                    <ul class="dropdown-menu" aria-labelledby="ordersDropdown">
                        <?php if($customer): ?><li><a class="dropdown-item" href="<?php echo e(route('my.wallet')); ?>"><i class="fa-solid fa-wallet"></i>&nbsp;&nbsp;Wallet</a></li><?php endif; ?>
                        <li><a class="dropdown-item" href="<?php echo e(route('front.meal.plan')); ?>"><i class="fa-solid fa-receipt"></i>&nbsp;&nbsp;Buy A Plan</a></li>
                        <li><a class="dropdown-item" href="<?php echo e(route('front.meal.single')); ?>"><i class="fa-solid fa-shopping-basket"></i>&nbsp;&nbsp;Buy Single</a></li>
                        <li><a class="dropdown-item" href="<?php echo e(route('front.show.addons')); ?>"><i class="fa-solid fa-plus-circle"></i>&nbsp;&nbsp;Buy Addons</a></li>
                        <?php if($customer): ?><li><a class="dropdown-item extra-meal-btn" href="javascript:void(0);"><i class="fa-solid fa-plus"></i>&nbsp;&nbsp;Request Extra Meal</a></li><?php endif; ?>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo e(route('feedbacks')); ?>"><i class="fa-solid fa-comments"></i> Feedbacks</a>
                </li>
                <?php if($customer): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="settingsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-user"></i> My Account
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
                                <small><a href="<?php echo e(route('my.wallet')); ?>" class="text-dark">Meal Wallet:
                                    
                                            <?php echo e($walletCount); ?>

                                    
                                </a>
                                </small></li>
                            <li><hr class="dropdown-divider mt-0 mb-0"></li>
                            <li><a class="dropdown-item" href="<?php echo e(route('customer.leave.index')); ?>"><i class="fa-solid fa-calendar-xmark"></i>&nbsp;&nbsp;Leaves</a></li>
                            <li><a class="dropdown-item" href="<?php echo e(route('customer.purchases')); ?>"><i class="fa-solid fa-receipt"></i>&nbsp;&nbsp;Purchases</a></li>
                            <li><a class="dropdown-item" href="<?php echo e(route('customer.extra_meals')); ?>"><i class="fa-solid fa-plus"></i>&nbsp;&nbsp;Extra Meals</a></li>
                            <li><a class="dropdown-item" href="<?php echo e(route('customer.profile')); ?>"><i class="fa-solid fa-user-pen"></i>&nbsp;&nbsp;Profile</a></li>
                            <li>
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fa-solid fa-right-from-bracket"></i>&nbsp;&nbsp;Logout
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
        
        <?php if($customer): ?>
        <li><a href="<?php echo e(route('customer.daily_meals')); ?>"><i class="fa-solid fa-utensils"></i> Daily Meals</a></li>
        <?php endif; ?>
        <li>
            <a href="#" onclick="toggleSubmenu(event, 'zopaMealsSubmenu')">
                <i class="fa-solid fa-concierge-bell"></i> Zopa Meals
                <i class="fa-solid fa-chevron-down float-end"></i> 
            </a>
            <ul class="submenu" id="zopaMealsSubmenu">
                <?php if($customer): ?>
                <li><a href="<?php echo e(route('my.wallet')); ?>"><i class="fa-solid fa-wallet"></i> Wallet</a></li>
                <?php endif; ?>
                <li><a href="<?php echo e(route('front.meal.plan')); ?>"><i class="fa-solid fa-receipt"></i> Buy A Plan</a></li>
                <li><a href="<?php echo e(route('front.meal.single')); ?>"><i class="fa-solid fa-shopping-basket"></i> Buy Single</a></li>
                <li><a href="<?php echo e(route('front.show.addons')); ?>"><i class="fa-solid fa-plus-circle"></i> Buy Addons</a></li>
            </ul>
        </li>
        <li><a href="<?php echo e(route('feedbacks')); ?>"><i class="fa-solid fa-comments"></i> Feedbacks</a></li>
        <?php if($customer): ?>
        <li>
            <a href="#" onclick="toggleSubmenu(event, 'zopaMealsSettings')">
                <i class="fa-solid fa-user"></i> Account
                <i class="fa-solid fa-chevron-down float-end"></i> 
            </a>
            <ul class="submenu" id="zopaMealsSettings">
                <li><a href="<?php echo e(route('customer.leave.index')); ?>"><i class="fa-solid fa-calendar-xmark"></i> Leaves</a></li>
                <li><a href="<?php echo e(route('customer.purchases')); ?>"><i class="fa-solid fa-receipt"></i> Purchases</a></li>
                <li><a href="<?php echo e(route('customer.extra_meals')); ?>"><i class="fa-solid fa-plus"></i> Extra Meals</a></li>
                <li><a href="<?php echo e(route('customer.profile')); ?>"><i class="fa-solid fa-user-pen"></i> Profile</a></li>
            </ul>
        </li>
        <li>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </li>
        <?php endif; ?>
    </ul>
</div>

<form id="logout-form" action="<?php echo e(route('customer.logout')); ?>" method="POST" style="display: none;">
    <?php echo csrf_field(); ?>
</form>
<?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\includes\nav.blade.php ENDPATH**/ ?>