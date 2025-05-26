@php
    use App\Http\Utilities\Utility;
    use App\Models\CustomerOrder;
    use App\Models\Customer;

    $count_not_paid = CustomerOrder::where('is_paid', Utility::ITEM_INACTIVE)->count();
    $count_customer_suspended = Customer::where('is_approved', Utility::ITEM_INACTIVE)->count();
@endphp

<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">

                <li class="{{ set_active('admin') }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-home"></i>
                        <span data-key="t-dashboard">@lang('translation.Dashboards')</span>
                    </a>
                </li>

                @if ($user->hasRole(['Administrator', 'Manager']))
                    <li>
                        <a href="javascript:void(0);" class="has-arrow">
                            <i class="fas fa-boxes"></i>
                            <span data-key="t-email">
                                Orders
                                @if($count_not_paid > 0)
                                    <span class="badge rounded-pill bg-soft-danger text-danger">Unpaid: {{ $count_not_paid }}</span>
                                @endif
                            </span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="{{ route('admin.daily_meals.index') }}">Today's Meals</a></li>
                            <li><a href="{{ route('admin.daily_meals.extra') }}">Extra Meals</a></li>
                            <li><a href="{{ route('admin.daily_meals.previous') }}">Archived Meals</a></li>
                            <li><a href="{{ route('admin.orders.index') }}">Customer Orders</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript:void(0);" class="has-arrow">
                            <i class="fas fa-wallet"></i>
                            <span data-key="t-email">@lang('translation.Wallets')</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="{{ route('admin.customers.wallets') }}">Meals Wallet</a></li>
                            <li><a href="{{ route('admin.customers.addon.wallets') }}">Addon Wallet</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="{{ route('admin.feedbacks.index') }}">
                            <i class="fas fa-comment-dots"></i>
                            <span data-key="t-email">@lang('translation.Feedbacks')</span>
                        </a>
                    </li>

                    <li class="menu-title" data-key="t-apps">@lang('translation.Catalogue_Manage')</li>

                    <li class="{{ set_active(['admin.categories.edit', 'admin.categories.create', 'admin.categories.products']) }}">
                        <a href="{{ route('admin.categories.index') }}">
                            <i class="fas fa-coins"></i>
                            <span data-key="t-email">@lang('translation.Category_Manage')</span>
                        </a>
                    </li>

                    <li>
                        <a href="javascript:void(0);" class="has-arrow">
                            <i class="fas fa-utensils"></i>
                            <span data-key="t-email">@lang('translation.Meal_Manage')</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="{{ route('admin.meals.create') }}">@lang('translation.Add_Menu')</a></li>
                            <li class="{{ set_active('admin.meals.edit') }}">
                                <a href="{{ route('admin.meals.index') }}">@lang('translation.List_Menu')</a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript:void(0);" class="has-arrow">
                            <i class="fas fa-pizza-slice"></i>
                            <span data-key="t-email">@lang('translation.Addon_Manage')</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="{{ route('admin.addons.create') }}">@lang('translation.Add_Menu')</a></li>
                            <li class="{{ set_active('admin.addons.edit') }}">
                                <a href="{{ route('admin.addons.index') }}">@lang('translation.List_Menu')</a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if ($user->hasRole(['Administrator', 'Manager']))
                    <li class="menu-title" data-key="t-apps">@lang('translation.Account_Manage')</li>
                    <li>
                        <a href="javascript:void(0);" class="has-arrow">
                            <i class="fas fa-users"></i>
                            <span data-key="t-email">
                                @lang('translation.Customer_Manage')
                                @if($count_customer_suspended > 0)
                                    <span class="badge rounded-pill bg-soft-danger text-danger">Suspended: {{ $count_customer_suspended }}</span>
                                @endif
                            </span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li class="{{ set_active(['admin.customers.edit', 'admin.customers.view']) }}">
                                <a href="{{ route('admin.customers.index') }}">@lang('translation.List_Menu')</a>
                            </li>
                            <li><a href="{{ route('admin.customers.create') }}">@lang('translation.Add_Menu')</a></li>
                        </ul>
                    </li>
                @endif

                @if ($user->hasRole('Administrator'))
                    <li>
                        <a href="javascript:void(0);" class="has-arrow">
                            <i class="fas fa-user-shield"></i>
                            <span data-key="t-contacts">@lang('translation.User_Management')</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li class="{{ set_active('admin.users.edit') }}">
                                <a href="{{ route('admin.users.index') }}">@lang('translation.List_Menu')</a>
                            </li>
                            <li><a href="{{ route('admin.users.create') }}">@lang('translation.Add_Menu')</a></li>
                        </ul>
                    </li>

                    <li class="menu-title" data-key="t-apps">@lang('translation.Account_Settings')</li>

                    <li>
                        <a href="javascript:void(0);" class="has-arrow">
                            <i class="fas fa-warehouse"></i>
                            <span data-key="t-email">@lang('translation.Kitchen_Manage')</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li class="{{ set_active('admin.kitchens.edit') }}">
                                <a href="{{ route('admin.kitchens.index') }}">@lang('translation.List_Menu')</a>
                            </li>
                            <li><a href="{{ route('admin.kitchens.create') }}">@lang('translation.Add_Menu')</a></li>
                        </ul>
                    </li>

                    <li class="{{ set_active(['admin.ingredients.create', 'admin.ingredients.edit']) }}">
                        <a href="{{ route('admin.ingredients.index') }}">
                            <i class="fas fa-vials"></i>
                            <span data-key="t-email">@lang('translation.Ingredient_List')</span>
                        </a>
                    </li>

                    <li class="{{ set_active(['admin.remarks.create', 'admin.remarks.edit']) }}">
                        <a href="{{ route('admin.remarks.index') }}">
                            <i class="fas fa-comment-alt"></i>
                            <span data-key="t-email">@lang('translation.Remark_List')</span>
                        </a>
                    </li>

                    <li>
                        <a href="javascript:void(0);" class="has-arrow">
                            <i class="fas fa-cog"></i>
                            <span data-key="t-contacts">@lang('translation.Settings')</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="{{ route('admin.settings.index') }}">@lang('translation.General_Settings')</a></li>
                            <li><a href="{{ route('admin.settings.change.password') }}">@lang('translation.Change_Password')</a></li>
                        </ul>
                    </li>
                @endif

            </ul>
        </div>
    </div>
</div>
<!-- Left Sidebar End -->
