<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        if (Auth::guard('customer')->check()) {
            App::setLocale(Auth::guard('customer')->user()->language ?? 'en');
        } else {
            App::setLocale(Session::get('locale', 'en'));
        }

        return $next($request);
    }
}
