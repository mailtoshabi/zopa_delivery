<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Utilities\Utility;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::ADMIN_HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('web')->attempt($credentials)) {
            return redirect()->intended('/super/admin');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    protected function attemptLogin(Request $request)
    {
        // Add `status` as an additional condition to the credentials array
        return $this->guard()->attempt(
            array_merge($this->credentials($request), ['status' => Utility::ITEM_ACTIVE]),
            $request->filled('remember')
        );
    }

    public function logout(Request $request)
    {
        $this->guard('web')->logout();
        return redirect()->route('admin.show.login');
    }
}
