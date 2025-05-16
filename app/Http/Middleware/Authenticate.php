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
        // if (! $request->expectsJson()) {
        //     return route('login');
        // }

        if (! $request->expectsJson()) {
            // Admin routes (they all start with /admin)
            if ($request->routeIs('admin') || $request->routeIs('admin.*') || $request->is('admin/*')) {
                return route('admin.show.login');  // Your admin login route name
            }

            // Default fallback — customer routes (no prefix)
            return route('customer.login');  // Your customer login route name
        }
    }
}
