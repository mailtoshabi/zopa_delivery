<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="<?php echo e(URL::asset('assets/images/logo-sm.svg')); ?>" alt="" height="30">
                    </span>
                    <span class="logo-lg">
                        <img src="<?php echo e(URL::asset('assets/images/logo-sm.svg')); ?>" alt="" height="24"> <span class="logo-txt">ZOPA FOOD DROP</span>
                    </span>
                </a>

                <a href="<?php echo e(route('admin.dashboard')); ?>" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="<?php echo e(URL::asset('assets/images/logo-sm.svg')); ?>" alt="" height="30">
                    </span>
                    <span class="logo-lg">
                        <img src="<?php echo e(URL::asset('assets/images/logo-sm.svg')); ?>" alt="" height="24"> <span class="logo-txt">ZOPA FOOD DROP</span>
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 header-item" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>

            <!-- App Search-->
            

            
        </div>

        <div class="d-flex">

            

            <div class="dropdown d-none d-sm-inline-block">
                <button type="button" class="btn header-item waves-effect"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php switch(Session::get('lang')):
                    case ('ru'): ?>
                        <img src="<?php echo e(URL::asset('/assets/images/flags/russia.jpg')); ?>" alt="Header Language" height="16">
                    <?php break; ?>
                    <?php case ('it'): ?>
                        <img src="<?php echo e(URL::asset('/assets/images/flags/italy.jpg')); ?>" alt="Header Language" height="16">
                    <?php break; ?>
                    <?php case ('de'): ?>
                        <img src="<?php echo e(URL::asset('/assets/images/flags/germany.jpg')); ?>" alt="Header Language" height="16">
                    <?php break; ?>
                    <?php case ('es'): ?>
                        <img src="<?php echo e(URL::asset('/assets/images/flags/spain.jpg')); ?>" alt="Header Language" height="16">
                    <?php break; ?>
                    <?php default: ?>
                        <img src="<?php echo e(URL::asset('/assets/images/flags/us.jpg')); ?>" alt="Header Language" height="16">
                <?php endswitch; ?>
            </button>
            <div class="dropdown-menu dropdown-menu-end">

                <!-- item-->
                <a href="<?php echo e(url('index/en')); ?>" class="dropdown-item notify-item language" data-lang="eng">
                    <img src="<?php echo e(URL::asset ('/assets/images/flags/us.jpg')); ?>" alt="user-image" class="me-1" height="12"> <span class="align-middle">English</span>
                </a>
                <!-- item-->
                <a href="<?php echo e(url('index/es')); ?>" class="dropdown-item notify-item language" data-lang="sp">
                    <img src="<?php echo e(URL::asset ('/assets/images/flags/spain.jpg')); ?>" alt="user-image" class="me-1" height="12"> <span class="align-middle">Spanish</span>
                </a>

                <!-- item-->
                <a href="<?php echo e(url('index/de')); ?>" class="dropdown-item notify-item language" data-lang="gr">
                    <img src="<?php echo e(URL::asset ('/assets/images/flags/germany.jpg')); ?>" alt="user-image" class="me-1" height="12"> <span class="align-middle">German</span>
                </a>

                <!-- item-->
                <a href="<?php echo e(url('index/it')); ?>" class="dropdown-item notify-item language" data-lang="it">
                    <img src="<?php echo e(URL::asset ('/assets/images/flags/italy.jpg')); ?>" alt="user-image" class="me-1" height="12"> <span class="align-middle">Italian</span>
                </a>

                <!-- item-->
                <a href="<?php echo e(url('index/ru')); ?>" class="dropdown-item notify-item language" data-lang="ru">
                    <img src="<?php echo e(URL::asset ('/assets/images/flags/russia.jpg')); ?>" alt="user-image" class="me-1" height="12"> <span class="align-middle">Russian</span>
                </a>
            </div>
            </div>

            <div class="dropdown d-none d-sm-inline-block">
                <button type="button" class="btn header-item" id="mode-setting-btn">
                    <i data-feather="moon" class="icon-lg layout-mode-dark"></i>
                    <i data-feather="sun" class="icon-lg layout-mode-light"></i>
                </button>
            </div>

            <div class="dropdown d-none d-lg-inline-block ms-1">
                <button type="button" class="btn header-item"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i data-feather="grid" class="icon-lg"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end top_bar">
                    <div class="p-2">
                        <div class="row g-0">
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <i class="fas fa-binoculars"></i>
                                    <span>Enquiry</span>
                                </a>
                            </div>
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <i class="fas fa-clipboard"></i>
                                    <span>Estimate</span>
                                </a>
                            </div>
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                    <span>Proforma</span>
                                </a>
                            </div>
                        </div>

                        <div class="row g-0">
                            <div class="col">
                                <a class="dropdown-icon-item" href="<?php echo e(route('admin.products.create')); ?>">
                                    <i class="fas fa-boxes"></i>
                                    <span>Products</span>
                                </a>
                            </div>
                            <div class="col">
                                <a class="dropdown-icon-item" href="<?php echo e(route('admin.customers.create')); ?>">
                                    <i class="fas fa-city"></i>
                                    <span><?php echo app('translator')->get('translation.Customer'); ?></span>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            

            <div class="dropdown d-inline-block">
                <button type="submit" onclick="goLink('<?php echo e(route('admin.settings.index')); ?>')" class="btn header-item  me-2"> 
                    <i data-feather="settings" class="icon-lg"></i>
                </button>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item bg-soft-light border-start border-end" id="page-header-user-dropdown"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user" src="<?php if(Auth::user()->avatar != ''): ?><?php echo e(URL::asset(App\Models\User::DIR_STORAGE . Auth::user()->avatar)); ?><?php else: ?><?php echo e(URL::asset('images/avatar-1.jpg')); ?><?php endif; ?>" alt="Header Avatar">
                    <span class="d-none d-xl-inline-block ms-1 fw-medium"><?php echo e(Auth::user()->name); ?></span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <a class="dropdown-item" href="apps-contacts-profile"><i class="mdi mdi-face-profile font-size-16 align-middle me-1"></i> <?php echo app('translator')->get('translation.Profile'); ?></a>
                    <a class="dropdown-item" href="auth-lock-screen"><i class="mdi mdi-lock font-size-16 align-middle me-1"></i> <?php echo app('translator')->get('translation.Lock_Screen'); ?></a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item " href="javascript:void();" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="bx bx-power-off font-size-16 align-middle me-1"></i> <span key="t-logout"><?php echo app('translator')->get('translation.Logout'); ?></span></a>
                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                        <?php echo csrf_field(); ?>
                    </form>
                </div>
            </div>

        </div>
    </div>
</header>
<?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\admin\layouts\topbar.blade.php ENDPATH**/ ?>