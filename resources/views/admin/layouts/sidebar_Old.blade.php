@php
    use App\Http\Utilities\Utility;
    use App\Models\CustomerOrder;
    use App\Models\Customer;
    $count_not_paid = CustomerOrder::where('is_paid',Utility::ITEM_INACTIVE)->count();
    $count_customer_suspended = Customer::where('is_approved',Utility::ITEM_INACTIVE)->count();
@endphp
<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                {{-- <li class="menu-title" data-key="t-menu">@lang('translation.Menu')</li> --}}

                <li class="{{ set_active('admin') }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-home"></i>
                        {{-- <span class="badge rounded-pill bg-soft-success text-success float-end">9+</span> --}}
                        <span data-key="t-dashboard">@lang('translation.Dashboards')</span>
                    </a>
                </li>

                @hasrole('Administrator')

                @endhasrole
                {{-- @endif --}}
                @if ($user->hasRole(['Administrator', 'Manager']))
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            {{-- <span class="badge rounded-pill bg-soft-danger text-danger float-end"></span> --}}
                            <i class="fas fa-boxes"></i>
                            <span data-key="t-email">Orders @if($count_not_paid>0)<span class="badge rounded-pill bg-soft-danger text-danger">Unpaid: {{ $count_not_paid }}</span>@endif </span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            {{-- <li><a href="{{ route('admin.meals.create') }}" data-key="t-inbox">@lang('translation.Add_Menu')</a></li> --}}
                            <li class=""><a href="{{ route('admin.daily_meals.index') }}" data-key="t-read-email">Today's Meals</a></li>
                            <li class=""><a href="{{ route('admin.daily_meals.extra') }}" data-key="t-read-email">Extra Meals</a></li>
                            <li class=""><a href="{{ route('admin.daily_meals.previous') }}" data-key="t-read-email">Archived Meals</a></li>
                            <li class=""><a href="{{ route('admin.orders.index') }}" data-key="t-read-email">Customer Orders </a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            {{-- <span class="badge rounded-pill bg-soft-danger text-danger float-end"></span> --}}
                            <i class="fas fa-boxes"></i>
                            <span data-key="t-email">@lang('translation.Wallets') </span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="{{ route('admin.customers.wallets') }}" data-key="t-inbox">Meals Wallet</a></li>
                            <li><a href="{{ route('admin.customers.addon.wallets') }}" data-key="t-inbox">Addon Wallet</a></li>
                        </ul>
                    </li>

                    <li class="">
                        <a href="{{ route('admin.feedbacks.index') }}">
                            <i class="fas fa-coins"></i>
                            <span data-key="t-email">@lang('translation.Feedbacks')</span>
                        </a>
                    </li>

                    <li class="menu-title" data-key="t-apps">@lang('translation.Catalogue_Manage')</li>

                    <li class="{{ set_active(['admin.categories.edit','admin.categories.create','admin.categories.products']) }}">
                        <a href="{{ route('admin.categories.index') }}">
                            <i class="fas fa-coins"></i>
                            <span data-key="t-email">@lang('translation.Category_Manage')</span>
                        </a>
                    </li>

                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i class="fas fa-boxes"></i>
                            <span data-key="t-email">@lang('translation.Meal_Manage')</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="{{ route('admin.meals.create') }}" data-key="t-inbox">@lang('translation.Add_Menu')</a></li>
                            <li class="{{ set_active('admin.meals.edit') }}"><a href="{{ route('admin.meals.index') }}" data-key="t-read-email">@lang('translation.List_Menu')</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i class="fas fa-boxes"></i>
                            <span data-key="t-email">@lang('translation.Addon_Manage')</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="{{ route('admin.addons.create') }}" data-key="t-inbox">@lang('translation.Add_Menu')</a></li>
                            <li class="{{ set_active('admin.addons.edit') }}"><a href="{{ route('admin.addons.index') }}" data-key="t-read-email">@lang('translation.List_Menu')</a></li>
                        </ul>
                    </li>

                @endif
                @if ($user->hasRole(['Administrator', 'Manager']))
                    <li class="menu-title" data-key="t-apps">@lang('translation.Account_Manage')</li>
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i class="fas fa-city"></i>
                            <span data-key="t-email">@lang('translation.Customer_Manage')@if($count_customer_suspended>0) <span class="badge rounded-pill bg-soft-danger text-danger">Suspended: {{ $count_customer_suspended }}</span>@endif</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li class="{{ set_active(['admin.customers.edit','admin.customers.view']) }}"><a href="{{ route('admin.customers.index') }}" data-key="t-read-email">@lang('translation.List_Menu')</a></li>
                            <li><a href="{{ route('admin.customers.create') }}" data-key="t-inbox">@lang('translation.Add_Menu')</a></li>
                        </ul>
                    </li>
                @endif
                @if ($user->hasRole('Administrator'))


                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i class="fas fa-user-friends"></i>
                            <span data-key="t-contacts">@lang('translation.User_Management')</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li class="{{ set_active('admin.users.edit') }}"><a href="{{ route('admin.users.index') }}" data-key="t-user-grid">@lang('translation.List_Menu')</a></li>
                            <li><a href="{{ route('admin.users.create') }}" data-key="t-user-grid">@lang('translation.Add_Menu')</a></li>
                        </ul>
                    </li>


                <li class="menu-title" data-key="t-apps">@lang('translation.Account_Settings')</li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i class="fas fa-warehouse"></i>
                        <span data-key="t-email">@lang('translation.Kitchen_Manage')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li class="{{ set_active('admin.kitchens.edit') }}"><a href="{{ route('admin.kitchens.index') }}" data-key="t-read-email">@lang('translation.List_Menu')</a></li>
                        <li><a href="{{ route('admin.kitchens.create') }}" data-key="t-inbox">@lang('translation.Add_Menu')</a></li>
                    </ul>
                </li>

                {{-- <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i class="fas fa-users"></i>
                        <span data-key="t-contacts">@lang('translation.Role_Management')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li class="{{ set_active('admin.roles.edit') }}"><a href="{{ route('admin.roles.index') }}" data-key="t-read-email">@lang('translation.List_Menu')</a></li>
                        <li><a href="{{ route('admin.roles.create') }}" data-key="t-read-email">@lang('translation.Add_Menu')</a></li>
                    </ul>
                </li> --}}

                <li class="{{ set_active(['admin.ingredients.create','admin.ingredients.edit']) }}">
                    <a href="{{ route('admin.ingredients.index') }}">
                        <i class="fas fa-vials"></i>
                        <span data-key="t-email">@lang('translation.Ingredient_List')</span>
                    </a>
                </li>
                <li class="{{ set_active(['admin.remarks.create','admin.remarks.edit']) }}">
                    <a href="{{ route('admin.remarks.index') }}">
                        <i class="fas fa-vials"></i>
                        <span data-key="t-email">@lang('translation.Remark_List')</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i class="fas fa-cog"></i>
                        <span data-key="t-contacts">@lang('translation.Settings')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('admin.settings.index') }}" data-key="t-read-email">@lang('translation.General_Settings')</a></li>
                        <li><a href="{{ route('admin.settings.change.password') }}" data-key="t-user-grid">@lang('translation.Change_Password')</a></li>
                    </ul>
                </li>
                @endif

            </ul>

            {{-- <div class="card sidebar-alert shadow-none text-center mx-4 mb-0 mt-5">
                <div class="card-body">
                    <img src="assets/images/giftbox.png" alt="">
                    <div class="mt-4">
                        <h5 class="alertcard-title font-size-16">Unlimited Access</h5>
                        <p class="font-size-13">Upgrade your plan from a Free trial, to select ‘Business Plan’.</p>
                        <a href="#!" class="btn btn-primary mt-2">Upgrade Now</a>
                    </div>
                </div>
            </div> --}}
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
