<?php
    use App\Http\Utilities\Utility;
    use App\Models\CustomerOrder;
    use App\Models\Customer;
    $count_not_paid = CustomerOrder::where('is_paid',Utility::ITEM_INACTIVE)->count();
    $count_customer_suspended = Customer::where('is_approved',Utility::ITEM_INACTIVE)->count();
?>
<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                

                <li class="<?php echo e(set_active('admin')); ?>">
                    <a href="<?php echo e(route('admin.dashboard')); ?>">
                        <i class="fas fa-home"></i>
                        
                        <span data-key="t-dashboard"><?php echo app('translator')->get('translation.Dashboards'); ?></span>
                    </a>
                </li>

                <?php if (\Illuminate\Support\Facades\Blade::check('hasrole', 'Administrator')): ?>

                <?php endif; ?>
                
                <?php if($user->hasRole(['Administrator', 'Manager'])): ?>
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            
                            <i class="fas fa-boxes"></i>
                            <span data-key="t-email">Orders <?php if($count_not_paid>0): ?><span class="badge rounded-pill bg-soft-danger text-danger">Unpaid: <?php echo e($count_not_paid); ?></span><?php endif; ?> </span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            
                            <li class=""><a href="<?php echo e(route('admin.daily_meals.index')); ?>" data-key="t-read-email">Today's Meals</a></li>
                            <li class=""><a href="<?php echo e(route('admin.daily_meals.extra')); ?>" data-key="t-read-email">Extra Meals</a></li>
                            <li class=""><a href="<?php echo e(route('admin.daily_meals.previous')); ?>" data-key="t-read-email">Archived Meals</a></li>
                            <li class=""><a href="<?php echo e(route('admin.orders.index')); ?>" data-key="t-read-email">Customer Orders </a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            
                            <i class="fas fa-boxes"></i>
                            <span data-key="t-email"><?php echo app('translator')->get('translation.Wallets'); ?> </span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="<?php echo e(route('admin.customers.wallets')); ?>" data-key="t-inbox">Meals Wallet</a></li>
                            <li><a href="<?php echo e(route('admin.customers.addon.wallets')); ?>" data-key="t-inbox">Addon Wallet</a></li>
                        </ul>
                    </li>

                    <li class="">
                        <a href="<?php echo e(route('admin.feedbacks.index')); ?>">
                            <i class="fas fa-coins"></i>
                            <span data-key="t-email"><?php echo app('translator')->get('translation.Feedbacks'); ?></span>
                        </a>
                    </li>

                    <li class="menu-title" data-key="t-apps"><?php echo app('translator')->get('translation.Catalogue_Manage'); ?></li>

                    <li class="<?php echo e(set_active(['admin.categories.edit','admin.categories.create','admin.categories.products'])); ?>">
                        <a href="<?php echo e(route('admin.categories.index')); ?>">
                            <i class="fas fa-coins"></i>
                            <span data-key="t-email"><?php echo app('translator')->get('translation.Category_Manage'); ?></span>
                        </a>
                    </li>

                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i class="fas fa-boxes"></i>
                            <span data-key="t-email"><?php echo app('translator')->get('translation.Meal_Manage'); ?></span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="<?php echo e(route('admin.meals.create')); ?>" data-key="t-inbox"><?php echo app('translator')->get('translation.Add_Menu'); ?></a></li>
                            <li class="<?php echo e(set_active('admin.meals.edit')); ?>"><a href="<?php echo e(route('admin.meals.index')); ?>" data-key="t-read-email"><?php echo app('translator')->get('translation.List_Menu'); ?></a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i class="fas fa-boxes"></i>
                            <span data-key="t-email"><?php echo app('translator')->get('translation.Addon_Manage'); ?></span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="<?php echo e(route('admin.addons.create')); ?>" data-key="t-inbox"><?php echo app('translator')->get('translation.Add_Menu'); ?></a></li>
                            <li class="<?php echo e(set_active('admin.addons.edit')); ?>"><a href="<?php echo e(route('admin.addons.index')); ?>" data-key="t-read-email"><?php echo app('translator')->get('translation.List_Menu'); ?></a></li>
                        </ul>
                    </li>

                <?php endif; ?>
                <?php if($user->hasRole(['Administrator', 'Manager'])): ?>
                    <li class="menu-title" data-key="t-apps"><?php echo app('translator')->get('translation.Account_Manage'); ?></li>
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i class="fas fa-city"></i>
                            <span data-key="t-email"><?php echo app('translator')->get('translation.Customer_Manage'); ?><?php if($count_customer_suspended>0): ?> <span class="badge rounded-pill bg-soft-danger text-danger">Suspended: <?php echo e($count_customer_suspended); ?></span><?php endif; ?></span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li class="<?php echo e(set_active(['admin.customers.edit','admin.customers.view'])); ?>"><a href="<?php echo e(route('admin.customers.index')); ?>" data-key="t-read-email"><?php echo app('translator')->get('translation.List_Menu'); ?></a></li>
                            <li><a href="<?php echo e(route('admin.customers.create')); ?>" data-key="t-inbox"><?php echo app('translator')->get('translation.Add_Menu'); ?></a></li>
                        </ul>
                    </li>
                <?php endif; ?>
                <?php if($user->hasRole('Administrator')): ?>


                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i class="fas fa-user-friends"></i>
                            <span data-key="t-contacts"><?php echo app('translator')->get('translation.User_Management'); ?></span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li class="<?php echo e(set_active('admin.users.edit')); ?>"><a href="<?php echo e(route('admin.users.index')); ?>" data-key="t-user-grid"><?php echo app('translator')->get('translation.List_Menu'); ?></a></li>
                            <li><a href="<?php echo e(route('admin.users.create')); ?>" data-key="t-user-grid"><?php echo app('translator')->get('translation.Add_Menu'); ?></a></li>
                        </ul>
                    </li>


                <li class="menu-title" data-key="t-apps"><?php echo app('translator')->get('translation.Account_Settings'); ?></li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i class="fas fa-warehouse"></i>
                        <span data-key="t-email"><?php echo app('translator')->get('translation.Kitchen_Manage'); ?></span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li class="<?php echo e(set_active('admin.kitchens.edit')); ?>"><a href="<?php echo e(route('admin.kitchens.index')); ?>" data-key="t-read-email"><?php echo app('translator')->get('translation.List_Menu'); ?></a></li>
                        <li><a href="<?php echo e(route('admin.kitchens.create')); ?>" data-key="t-inbox"><?php echo app('translator')->get('translation.Add_Menu'); ?></a></li>
                    </ul>
                </li>

                

                <li class="<?php echo e(set_active(['admin.ingredients.create','admin.ingredients.edit'])); ?>">
                    <a href="<?php echo e(route('admin.ingredients.index')); ?>">
                        <i class="fas fa-vials"></i>
                        <span data-key="t-email"><?php echo app('translator')->get('translation.Ingredient_List'); ?></span>
                    </a>
                </li>
                <li class="<?php echo e(set_active(['admin.remarks.create','admin.remarks.edit'])); ?>">
                    <a href="<?php echo e(route('admin.remarks.index')); ?>">
                        <i class="fas fa-vials"></i>
                        <span data-key="t-email"><?php echo app('translator')->get('translation.Remark_List'); ?></span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i class="fas fa-cog"></i>
                        <span data-key="t-contacts"><?php echo app('translator')->get('translation.Settings'); ?></span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="<?php echo e(route('admin.settings.index')); ?>" data-key="t-read-email"><?php echo app('translator')->get('translation.General_Settings'); ?></a></li>
                        <li><a href="<?php echo e(route('admin.settings.change.password')); ?>" data-key="t-user-grid"><?php echo app('translator')->get('translation.Change_Password'); ?></a></li>
                    </ul>
                </li>
                <?php endif; ?>

            </ul>

            
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
<?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\admin\layouts\sidebar_Old.blade.php ENDPATH**/ ?>