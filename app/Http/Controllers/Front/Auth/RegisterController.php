<?php

namespace App\Http\Controllers\Front\Auth;

use App\Http\Controllers\Controller;
use App\Http\Utilities\Utility;
use App\Models\Customer;
use App\Models\Kitchen;
use App\Models\MealWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        $kitchens = Kitchen::select('id', 'name')->get();
        $states = DB::table('states')->orderBy('name', 'asc')->select('id', 'name')->get();
        return view('pages.register',compact('kitchens','states'));
    }

    /**
     * Handle customer registration and meal meal_wallet creation.
     */
    public function register(Request $request)
    {
        $rules = [
            'name'         => 'required',
            'phone'        => [
                'required',
                'unique:customers,phone',
                'regex:/^[6-9]\d{9}$/',
            ],
            'password'     => [
                'required',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            ],
            'office_name'  => 'required',
            'whatsapp'     => [
                'required',
                'unique:customers,whatsapp',
                'regex:/^[6-9]\d{9}$/',
            ],
            'kitchen_id'   => 'required',
            'city'         => 'required',
            'postal_code'  => 'required',
        ];

        $messages = [
            'phone.regex' => 'The phone number must be a valid 10-digit Indian mobile number.',
            'whatsapp.regex' => 'The WhatsApp number must be a valid 10-digit Indian mobile number.',
            'password.regex' => 'Password must be at least 8 characters and include at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'password.min' => 'Password must be at least 8 characters long.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $customer = Customer::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'office_name' => $request->office_name,
            'city' => $request->city,
            'landmark' => $request->landmark,
            'designation' => $request->designation,
            'whatsapp' => $request->whatsapp,
            'district_id' => Utility::DISTRICT_ID_MPM,
            'state_id' => Utility::STATE_ID_KERALA,
            'postal_code' => $request->postal_code,
            'kitchen_id' => decrypt($request->kitchen_id),
            'status' => Utility::ITEM_ACTIVE,
            'is_approved' => 0,
        ]);

        MealWallet::create([
            'customer_id' => $customer->id,
            'quantity' => 0,
            'status' => Utility::ITEM_ACTIVE,
        ]);

        // Log in the customer
        Auth::login($customer);

        // Check if approved and active
        if ($customer->is_approved && $customer->status == Utility::ITEM_ACTIVE) {
            $redirectUrl = route('front.meal.plan'); // adjust this route name to your dashboard
            $successMessage = 'Registration successful! Welcome to your dashboard.';
        } else {
            Auth::logout();
            $redirectUrl = route('customer.login');
            $successMessage = 'Registration successful. Our Support Team will contact you soon and activate your account!';
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $successMessage,
                'redirect_url' => $redirectUrl
            ]);
        }

        return redirect($redirectUrl)->with('success', $successMessage);
    }
}
