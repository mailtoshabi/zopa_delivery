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
                'phone' => ['Invalid or inactive account.'],
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

    public function showOtpLoginForm()
    {
        $firebaseConfig = config('services.firebase');
        return view('pages.login_otp',compact('firebaseConfig'));
    }

    public function verifyOtpLogin(Request $request)
    {
        $request->validate([
            'phone' => 'required|digits:10',
        ]);

        $customer = Customer::where('phone', $request->phone)->first();

        if (!$customer || !$customer->status || !$customer->is_approved) {
            return response()->json(['error' => 'Invalid or inactive account'], 403);
        }

        Auth::guard('customer')->login($customer);
        $redirectUrl = $this->redirectTo();
        return response()->json(['redirect_url' => $redirectUrl]);
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('customer.login');
    }
}
