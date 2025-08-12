<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {

            // Admin routes
            if ($request->routeIs('admin') || $request->routeIs('admin.*') || $request->is('super/admin/*')) {
                return route('admin.show.login'); // Admin login route
            }

            // Kitchen routes
            if ($request->routeIs('kitchen') || $request->routeIs('kitchen.*') || $request->is('kitchen/admin/*')) {
                return route('kitchen.login'); // Kitchen login route
            }

            // Frontend (customer) routes — if using auth:customer middleware
            if ($request->route()?->middleware() && in_array('auth:customer', (array) $request->route()->middleware())) {
                return route('unauthenticated.page'); // Custom guest page
            }

            // Fallback — send to customer login
            return route('customer.login');
        }
    }
}
