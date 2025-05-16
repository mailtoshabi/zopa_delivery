<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureCustomerIsApproved
{
    public function handle(Request $request, Closure $next)
    {
        $customer = Auth::guard('customer')->user();

        if (!$customer || !$customer->is_approved) {
            Auth::guard('customer')->logout();
            return redirect()->route('customer.login')->with('error', 'Your account is not approved yet. Contact Support');
        }

        return $next($request);
    }
}

