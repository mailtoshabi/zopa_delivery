<?php

namespace App\Http\Controllers\Front\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends Controller
{

    protected function redirectTo()
    {
        return route(RouteServiceProvider::HOME);
    }

    public function showLoginForm() {
        if (Auth::guard('customer')->check()) {
            return redirect()->intended($this->redirectTo());
        }

        return view('pages.login');
    }

    public function login(Request $request)
    {
        $maxAttempts = 5;
        $key = 'login:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            throw ValidationException::withMessages([
                'phone' => ['Too many login attempts. Please try again later.'],
            ]);
        }

        RateLimiter::hit($key, 60); // Block for 60 seconds after 5 attempts

        $credentials = $request->validate([
            'phone' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $customer = Customer::where('phone', $credentials['phone'])->first();

        if (!$customer || !$customer->status || !$customer->is_approved) {
            throw ValidationException::withMessages([
                'phone' => ['Your account is not approved or is inactive.'],
            ]);
        }

        if (Auth::guard('customer')->attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            RateLimiter::clear($key);

            $redirectUrl = $this->redirectTo();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['redirect_url' => $redirectUrl]);
            }

            return redirect()->intended($redirectUrl);
        }

        throw ValidationException::withMessages([
            'phone' => ['The provided credentials are incorrect.'],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('customer.login');
    }
}
