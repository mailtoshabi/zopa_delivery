<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('appName', function () {
            return "<?php echo config('app.name'); ?>";
        });

        // App::setLocale(Session::get('locale', config('app.locale')));
        view()->composer('*', function () {
            if (Auth::guard('customer')->check()) {
                App::setLocale(Auth::guard('customer')->user()->language ?? 'en');
            } else {
                App::setLocale(Session::get('locale', 'en'));
            }
        });

        Schema::defaultStringLength(191);

        Paginator::useBootstrap();
    }
}
