<?php
    use App\Http\Utilities\Utility;
    use App\Models\CustomerOrder;
    use App\Models\Customer;

    $count_not_paid = CustomerOrder::where('is_paid', Utility::ITEM_INACTIVE)->count();
    $count_customer_suspended = Customer::where('is_approved', Utility::ITEM_INACTIVE)->count();
?>

<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">

                <li class="<?php echo e(set_active('admin')); ?>">
                    <a href="<?php echo e(route('kitchen.dashboard')); ?>">
                        <i class="fas fa-home"></i>
                        <span data-key="t-dashboard"><?php echo app('translator')->get('translation.Dashboards'); ?></span>
                    </a>
                </li>

                
                    <li>
                        <a href="javascript:void(0);" class="has-arrow">
                            <i class="fas fa-boxes"></i>
                            <span data-key="t-email">
                                Orders
                                <?php if($count_not_paid > 0): ?>
                                    <span class="badge rounded-pill bg-soft-danger text-danger">Unpaid: <?php echo e($count_not_paid); ?></span>
                                <?php endif; ?>
                            </span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="<?php echo e(route('kitchen.daily_meals.index')); ?>">Today's Meals</a></li>
                            <li><a href="<?php echo e(route('kitchen.daily_meals.extra')); ?>">Extra Meals</a></li>
                            <li><a href="<?php echo e(route('kitchen.daily_meals.previous')); ?>">Archived Meals</a></li>
                            <li><a href="<?php echo e(route('kitchen.orders.index')); ?>">Customer Orders</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript:void(0);" class="has-arrow">
                            <i class="fas fa-wallet"></i>
                            <span data-key="t-email"><?php echo app('translator')->get('translation.Wallets'); ?></span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="<?php echo e(route('kitchen.customers.wallets')); ?>">Meals Wallet</a></li>
                            <li><a href="<?php echo e(route('kitchen.customers.addon.wallets')); ?>">Addon Wallet</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="<?php echo e(route('kitchen.feedbacks.index')); ?>">
                            <i class="fas fa-comment-dots"></i>
                            <span data-key="t-email"><?php echo app('translator')->get('translation.Feedbacks'); ?></span>
                        </a>
                    </li>

                    <li class="menu-title" data-key="t-apps"><?php echo app('translator')->get('translation.Catalogue_Manage'); ?></li>

                    <li>
                        <a href="<?php echo e(route('kitchen.meals.index')); ?>" class="">
                            <i class="fas fa-utensils"></i>
                            <span data-key="t-email"><?php echo app('translator')->get('translation.Meal_Manage'); ?></span>
                        </a>
                    </li>

                    <li>
                        <a href="<?php echo e(route('kitchen.addons.index')); ?>" class="">
                            <i class="fas fa-pizza-slice"></i>
                            <span data-key="t-email"><?php echo app('translator')->get('translation.Addon_Manage'); ?></span>
                        </a>
                    </li>
                

                
                    <li class="menu-title" data-key="t-apps"><?php echo app('translator')->get('translation.Account_Manage'); ?></li>
                    <li>
                        <a href="javascript:void(0);" class="has-arrow">
                            <i class="fas fa-users"></i>
                            <span data-key="t-email">
                                <?php echo app('translator')->get('translation.Customer_Manage'); ?>
                                <?php if($count_customer_suspended > 0): ?>
                                    <span class="badge rounded-pill bg-soft-danger text-danger">Suspended: <?php echo e($count_customer_suspended); ?></span>
                                <?php endif; ?>
                            </span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li class="<?php echo e(set_active(['kitchen.customers.edit', 'kitchen.customers.view'])); ?>">
                                <a href="<?php echo e(route('kitchen.customers.index')); ?>"><?php echo app('translator')->get('translation.List_Menu'); ?></a>
                            </li>
                            <li><a href="<?php echo e(route('kitchen.customers.create')); ?>"><?php echo app('translator')->get('translation.Add_Menu'); ?></a></li>
                        </ul>
                    </li>
                

                

                    <li class="menu-title" data-key="t-apps"><?php echo app('translator')->get('translation.Account_Settings'); ?></li>



                    <li>
                        <a href="javascript:void(0);" class="has-arrow">
                            <i class="fas fa-cog"></i>
                            <span data-key="t-contacts"><?php echo app('translator')->get('translation.Settings'); ?></span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            
                            <li><a href="<?php echo e(route('admin.settings.change.password')); ?>"><?php echo app('translator')->get('translation.Change_Password'); ?></a></li>
                        </ul>
                    </li>
                

            </ul>
        </div>
    </div>
</div>
<!-- Left Sidebar End -->
<?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\kitchen\layouts\sidebar.blade.php ENDPATH**/ ?>