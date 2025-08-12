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
                    <a href="{{ route('kitchen.dashboard') }}">
                        <i class="fas fa-home"></i>
                        <span data-key="t-dashboard">@lang('translation.Dashboards')</span>
                    </a>
                </li>

                {{-- @if ($user->hasRole(['Administrator', 'Manager'])) --}}
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
                            <li><a href="{{ route('kitchen.daily_meals.index') }}">Today's Meals</a></li>
                            <li><a href="{{ route('kitchen.daily_meals.extra') }}">Extra Meals</a></li>
                            <li><a href="{{ route('kitchen.daily_meals.previous') }}">Archived Meals</a></li>
                            <li><a href="{{ route('kitchen.orders.index') }}">Customer Orders</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript:void(0);" class="has-arrow">
                            <i class="fas fa-wallet"></i>
                            <span data-key="t-email">@lang('translation.Wallets')</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="{{ route('kitchen.customers.wallets') }}">Meals Wallet</a></li>
                            <li><a href="{{ route('kitchen.customers.addon.wallets') }}">Addon Wallet</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="{{ route('kitchen.feedbacks.index') }}">
                            <i class="fas fa-comment-dots"></i>
                            <span data-key="t-email">@lang('translation.Feedbacks')</span>
                        </a>
                    </li>

                    <li class="menu-title" data-key="t-apps">@lang('translation.Catalogue_Manage')</li>

                    <li>
                        <a href="{{ route('kitchen.meals.index') }}" class="">
                            <i class="fas fa-utensils"></i>
                            <span data-key="t-email">@lang('translation.Meal_Manage')</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('kitchen.addons.index') }}" class="">
                            <i class="fas fa-pizza-slice"></i>
                            <span data-key="t-email">@lang('translation.Addon_Manage')</span>
                        </a>
                    </li>
                {{-- @endif --}}

                {{-- @if ($user->hasRole(['Administrator', 'Manager'])) --}}
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
                            <li class="{{ set_active(['kitchen.customers.edit', 'kitchen.customers.view']) }}">
                                <a href="{{ route('kitchen.customers.index') }}">@lang('translation.List_Menu')</a>
                            </li>
                            <li><a href="{{ route('kitchen.customers.create') }}">@lang('translation.Add_Menu')</a></li>
                        </ul>
                    </li>
                {{-- @endif --}}

                {{-- @if ($user->hasRole('Administrator')) --}}

                    <li class="menu-title" data-key="t-apps">@lang('translation.Account_Settings')</li>



                    <li>
                        <a href="javascript:void(0);" class="has-arrow">
                            <i class="fas fa-cog"></i>
                            <span data-key="t-contacts">@lang('translation.Settings')</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            {{-- <li><a href="{{ route('admin.settings.index') }}">@lang('translation.General_Settings')</a></li> --}}
                            <li><a href="{{ route('admin.settings.change.password') }}">@lang('translation.Change_Password')</a></li>
                        </ul>
                    </li>
                {{-- @endif --}}

            </ul>
        </div>
    </div>
</div>
<!-- Left Sidebar End -->
