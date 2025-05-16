<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use App\Http\Controllers\Front\Auth\RegisterController;
use App\Http\Controllers\Front\Auth\LoginController;
use App\Http\Controllers\Front\HomeController as FrontHomeController;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\KitchenController;
use App\Http\Controllers\Admin\IngredientController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\AddonController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ChangePasswordController;
use App\Http\Controllers\Admin\CustomerOrderController;
use App\Http\Controllers\Admin\MealController;
use App\Http\Controllers\Admin\DailyMealController;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\RemarkController;
use App\Http\Controllers\Front\CartController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/all_cache', function() {

    Artisan::call('cache:clear');
    Artisan::call('optimize');
    Artisan::call('route:cache');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('config:cache');
    return '<h1>All cache cleared</h1>';
});

// Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/', [LoginController::class, 'showLoginForm'])->name('index');
Route::get('/test', [HomeController::class, 'test'])->name('test');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('front.register');
Route::post('/register', [RegisterController::class, 'register'])->name('front.register.submit');
Route::post('/get-districts', [FrontHomeController::class, 'getDistrictList'])->name('get.districts');
Route::get('/login', [LoginController::class, 'showLoginForm']);
Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.submit');
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});
Route::middleware(['auth:customer', 'approved.customer'])->prefix('meal')->group(function () {
    Route::get('/buy-plans', [FrontHomeController::class, 'mealPlan'])->name('front.meal.plan');
    Route::get('/buy-single', [FrontHomeController::class, 'singleMeal'])->name('front.meal.single');
    Route::get('/purchase/{meal}', [FrontHomeController::class, 'showPurchasePage'])->name('meal.purchase');
    Route::post('/purchase/{meal}', [FrontHomeController::class, 'purchaseMeal'])->name('meal.purchase.store');
    Route::get('/meal/payment-success/{order}', [FrontHomeController::class, 'showMealPaymentSuccess'])->name('meal.payment.success');

    Route::get('/buy-addons', [FrontHomeController::class, 'addons'])->name('front.addons');
    Route::post('/addons/confirm', [FrontHomeController::class, 'purchaseAddon'])->name('addons.purchase.confirm');
    Route::post('/addons/store', [FrontHomeController::class, 'storeAddonPurchase'])->name('addons.purchase.store');
    Route::get('/addons/payment-success/{order}', [FrontHomeController::class, 'showAddonPaymentSuccess'])->name('addons.payment.success');

    Route::get('/my-wallet', [FrontHomeController::class, 'myWallet'])->name('my.wallet');
    Route::get('/daily-meals', [FrontHomeController::class, 'dailyMeals'])->name('customer.daily_meals');
    Route::get('/extra-meals', [FrontHomeController::class, 'extraMeals'])->name('customer.extra_meals');
    Route::post('/daily-meals/{id}/cancel', [FrontHomeController::class, 'cancelDailyOrder'])->name('customer.daily_meals.cancel');
    Route::get('/my-purchases', [FrontHomeController::class, 'myPurchases'])->name('customer.purchases');
    Route::post('/customer/extra-meal', [FrontHomeController::class, 'requestExtraMeal'])->name('customer.request.extra-meal');
    Route::get('my-leaves', [FrontHomeController::class, 'mealLeaves'])->name('customer.leave.index');
    Route::post('my-leaves', [FrontHomeController::class, 'markLeaves'])->name('customer.mark.leaves');
    Route::delete('/my-leaves/{id}', [FrontHomeController::class, 'destroyLeave'])->name('customer.meal-leaves.destroy');
    Route::post('/addon-wallet/toggle-status', [FrontHomeController::class, 'toggleStatus'])->name('addonWallet.toggleStatus');

    Route::get('/how-to-use-pdf', [FrontHomeController::class, 'downloadHowToUse'])->name('how_to_use_pdf');

    Route::get('/feedbacks', [FrontHomeController::class, 'feedbacks'])->name('feedbacks');
    Route::post('feedback', [FrontHomeController::class, 'storeFeedback'])->name('customer.feedback.store');

    // Customer Cart
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add-meal', [CartController::class, 'addMeal'])->name('addMeal');
        Route::post('/add-addon', [CartController::class, 'addAddon'])->name('addAddon');
        Route::post('/remove-item', [CartController::class, 'removeItem'])->name('removeItem');
        Route::post('/clear', [CartController::class, 'clear'])->name('clear');

        Route::get('/cart/checkout', [CartController::class, 'checkout'])->name('checkout');
        Route::post('/cart/checkout', [CartController::class, 'store'])->name('checkout.store');
    });
});

Route::middleware(['auth:customer', 'approved.customer'])->group(function () {
    Route::get('/profile', [FrontHomeController::class, 'profile'])->name('customer.profile');
    Route::put('/profile', [FrontHomeController::class, 'updateProfile'])->name('customer.profile.update');

    Route::get('/profile/change-password', [FrontHomeController::class, 'showChangePasswordForm'])->name('customer.profile.password.change');
    Route::put('/profile/change-password', [FrontHomeController::class, 'updatePassword'])->name('customer.password.update');
});

Route::get('/about-us', [FrontHomeController::class, 'about_us'])->name('about_us');
Route::get('/payment-terms', [FrontHomeController::class, 'payment_terms'])->name('payment_terms');
Route::get('/privacy-policy', [FrontHomeController::class, 'privacy_policy'])->name('privacy_policy');
Route::get('/support', [FrontHomeController::class, 'support'])->name('support');
Route::get('/meals/faq', [FrontHomeController::class, 'faq'])->name('faq');
Route::get('/meals/how-to-use', [FrontHomeController::class, 'how_to_use'])->name('how_to_use');
Route::get('/meals/site-map', [FrontHomeController::class, 'site_map'])->name('site_map');
Route::view('/offline', 'pages.offline')->name('offline');


Auth::routes(['login' => false, 'register'=>false]);
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminLoginController::class,'showLoginForm'])->name('admin.show.login');
    Route::post('/login', [AdminLoginController::class,'login'])->name('login');
});

Route::get('/admin', [AdminHomeController::class,'index'])->middleware('auth')->name('admin');
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AdminHomeController::class,'index'])->name('dashboard');
    Route::post('/districts', [AdminHomeController::class,'distric_list'])->name('list.districts');

    Route::group(['prefix'=>'categories', 'as'=>'categories.', 'middleware' => ['role:Administrator|Manager']], function() {
        Route::get('/',[CategoryController::class,'index'])->name('index');
        Route::get('/create',[CategoryController::class,'create'])->name('create');
        Route::post('/store',[CategoryController::class,'store'])->name('store');
        Route::get('/edit/{id}',[CategoryController::class,'edit'])->name('edit');
        Route::put('/update',[CategoryController::class,'update'])->name('update');
        Route::delete('/destroy/{id}',[CategoryController::class,'destroy'])->name('destroy');
        Route::get('/change-status/{id}',[CategoryController::class,'changeStatus'])->name('changeStatus');
        Route::get('/products/{id}',[CategoryController::class,'products'])->name('products');
    });

    Route::middleware(['role:Administrator|Manager'])->group(function () {
        Route::resource('kitchens', KitchenController::class);
        Route::get('kitchens/status/{id}', [KitchenController::class, 'changeStatus'])->name('kitchens.changeStatus');
    });

    Route::middleware(['role:Administrator|Manager'])->group(function () {
        Route::resource('customers', CustomerController::class);
        Route::get('customers/status/{id}', [CustomerController::class, 'changeStatus'])->name('customers.changeStatus');
        Route::get('customers/approve/{id}',[CustomerController::class,'approve'])->name('approve');
    });

    Route::prefix('customers/feedbacks')->name('customers.feedbacks.')->group(function () {
        Route::get('/', [FeedbackController::class, 'index'])->name('index');
        Route::get('/toggle/{id}', [FeedbackController::class, 'togglePublic'])->name('togglePublic');
        Route::post('/{feedback}/reply-ajax', [FeedbackController::class, 'replyAjax'])->name('reply.ajax');
    });

    Route::middleware(['role:Administrator|Manager'])->group(function () {
        Route::resource('meals', MealController::class);
        Route::get('meals/status/{id}', [MealController::class, 'changeStatus'])->name('meals.changeStatus');
    });

    Route::middleware(['role:Administrator|Manager'])->group(function () {
        Route::resource('addons', AddonController::class);
        Route::get('addons/status/{id}', [AddonController::class, 'changeStatus'])->name('addons.changeStatus');
    });

    Route::middleware(['role:Administrator|Manager'])->group(function () {
        Route::resource('ingredients', IngredientController::class);
        Route::get('ingredients/status/{id}', [IngredientController::class, 'changeStatus'])->name('ingredients.changeStatus');
    });

    Route::middleware(['role:Administrator|Manager'])->group(function () {
        Route::resource('remarks', RemarkController::class);
        Route::get('remarks/status/{id}', [RemarkController::class, 'changeStatus'])->name('remarks.changeStatus');
    });

    Route::middleware(['role:Administrator|Manager'])->group(function () {
        Route::get('daily-meals-orders', [DailyMealController::class, 'index'])->name('daily_meals.index');
        Route::get('extra-meals-orders', [DailyMealController::class, 'extra_meals'])->name('daily_meals.extra');
        Route::get('previous-meals-orders', [DailyMealController::class, 'previous'])->name('daily_meals.previous');
        Route::post('/daily-meals-orders/generate', [DailyMealController::class, 'generate'])->name('daily_meals.generate');
        Route::post('daily_meals/delivery/{id}', [DailyMealController::class, 'MarkDelivery'])->name('daily_meals.changeDelivery');
        Route::post('/daily-meals-orders/markall-delivered', [DailyMealController::class, 'markAllDelivered'])->name('daily_meals.mark.all.delivered');
        Route::post('/daily-meals/undo-delivered', [DailyMealController::class, 'undoAllDelivered'])->name('daily_meals.undo.delivered');
    });

    Route::group(['prefix'=>'purchases', 'as'=>'orders.', 'middleware' => ['role:Administrator']], function() {
            Route::get('/',[CustomerOrderController::class,'index'])->name('index');

            Route::get('/change-payment/{id}',[CustomerOrderController::class,'changePayment'])->name('changePayment');
            Route::get('/activate/{id}',[CustomerOrderController::class,'activate'])->name('activate');
    });



    // Route::group(['prefix'=>'ingredients', 'as'=>'ingredients.', 'middleware' => ['role:Administrator']], function() {
    //     Route::get('/',[IngredientController::class,'index'])->name('index');
    //     Route::get('/create',[IngredientController::class,'create'])->name('create');
    //     Route::post('/store',[IngredientController::class,'store'])->name('store');
    //     Route::get('/edit/{id}',[IngredientController::class,'edit'])->name('edit');
    //     Route::put('/update',[IngredientController::class,'update'])->name('update');
    //     Route::delete('/destroy/{id}',[IngredientController::class,'destroy'])->name('destroy');
    //     Route::get('/change-status/{id}',[IngredientController::class,'changeStatus'])->name('changeStatus');
    // });

    Route::group(['prefix'=>'products', 'as'=>'products.', 'middleware' => ['role:Administrator|Manager']], function() {
        Route::get('/',[ProductController::class,'index'])->name('index');
        Route::get('/create',[ProductController::class,'create'])->name('create');
        Route::post('/store',[ProductController::class,'store'])->name('store');
        Route::get('/edit/{id}',[ProductController::class,'edit'])->name('edit');
        Route::put('/update',[ProductController::class,'update'])->name('update');
        Route::delete('/destroy/{id}',[ProductController::class,'destroy'])->name('destroy');
        Route::get('/change-status/{id}',[ProductController::class,'changeStatus'])->name('changeStatus');
        Route::get('/approve/{id}',[ProductController::class,'approve'])->name('approve');
        Route::post('/get-cost',[ProductController::class,'getCost'])->name('get_cost');
    });

    Route::group(['prefix'=>'payments', 'as'=>'payments.', 'middleware' => ['role:Administrator|Manager|HR']], function() {
        Route::post('/store',[PaymentController::class,'store'])->name('store');
        Route::put('/update',[PaymentController::class,'update'])->name('update');
        Route::delete('/destroy/{id}',[PaymentController::class,'destroy'])->name('destroy');
    });

    Route::group(['prefix'=>'branches', 'as'=>'branches.', 'middleware' => ['role:Administrator']], function() {
        Route::get('/',[BranchController::class,'index'])->name('index');
        Route::get('/create',[BranchController::class,'create'])->name('create');
        Route::post('/store',[BranchController::class,'store'])->name('store');
        Route::get('/edit/{id}',[BranchController::class,'edit'])->name('edit');
        Route::get('/show/{id}',[BranchController::class,'show'])->name('view');
        Route::put('/update',[BranchController::class,'update'])->name('update');
        Route::delete('/destroy/{id}',[BranchController::class,'destroy'])->name('destroy');
        Route::get('/change-status/{id}',[BranchController::class,'changeStatus'])->name('changeStatus');
        Route::get('/make-default/{id}',[BranchController::class,'makeDefault'])->name('makeDefault');
        Route::post('/make-global-default',[BranchController::class,'makeDefaultGlobal'])->name('makeDefaultGlobal');
        Route::post('/districts', [BranchController::class,'distric_list'])->name('list.districts');
    });


    Route::group(['prefix'=>'users', 'as'=>'users.', 'middleware' => ['role:Administrator']], function() {
        Route::get('/',[UserController::class,'index'])->name('index');
        Route::get('/create',[UserController::class,'create'])->name('create');
        Route::post('/store',[UserController::class,'store'])->name('store');
        Route::get('/edit/{id}',[UserController::class,'edit'])->name('edit');
        Route::put('/update',[UserController::class,'update'])->name('update');
        Route::delete('/destroy/{id}',[UserController::class,'destroy'])->name('destroy');
        Route::get('/change-status/{id}',[UserController::class,'changeStatus'])->name('changeStatus');
    });

    Route::group(['prefix'=>'settings', 'as'=>'settings.'], function() {
        Route::get('/',[SettingsController::class,'index'])->name('index');
        Route::put('/general',[SettingsController::class,'update'])->name('update.general');
        Route::get('/password/change',[ChangePasswordController::class,'edit'])->name('change.password');
        Route::put('/password/update',[ChangePasswordController::class,'update'])->name('update.password');
    });

    // Route::resource('/roles',RoleController::class)->middleware('role:Administrator');

    Route::group(['prefix'=>'activities', 'as'=>'activities.'], function() {
        Route::get('/',[ActivityController::class,'index'])->name('index');
    });
});
// Admin Dashboard Routes --End--


//Language Translation
Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);

// Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');

//Update User Details
// Route::post('/update-profile/{id}', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');
// Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');

// Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index.home');

//Route::get('/test', [App\Http\Controllers\HomeController::class, 'test'])->name('test');


