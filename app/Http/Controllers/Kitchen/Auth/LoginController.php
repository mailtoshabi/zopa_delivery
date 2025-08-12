<?php

namespace App\Http\Controllers\Kitchen\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
    {
        protected function redirectTo()
        {
            return route('kitchen.dashboard'); // or '/kitchen/dashboard'
        }
        public function showLoginForm()
        {
            return view('kitchen.auth.login');
        }

        public function login(Request $request)
        {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $credentials = [
                'email' => $request->email,
                'password' => $request->password,
                'is_approved' => 1, // Add condition for approved kitchens
            ];

            if (Auth::guard('kitchen')->attempt($credentials, $request->remember)) {
                return redirect()->intended(route('kitchen.dashboard'));
            }

            return back()->withErrors([
                'email' => 'Invalid login credentials or account not approved.',
            ]);
        }

        public function logout(Request $request)
        {
            Auth::guard('kitchen')->logout();
            return redirect()->route('kitchen.login');
        }
    }
