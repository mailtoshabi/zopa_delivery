<?php

namespace App\Http\Controllers\Front\Auth;

use App\Http\Controllers\Controller;
use App\Http\Utilities\Utility;
use App\Models\Customer;
use App\Models\Kitchen;
use App\Models\MealWallet;
use App\Providers\RouteServiceProvider;
use App\Services\TwilioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */

     protected function redirectTo()
    {
        return route(RouteServiceProvider::HOME);
    }

    public function showRegistrationForm()
{
    $kitchens = Kitchen::select('id', 'name')->get();
    $states = DB::table('states')->orderBy('name', 'asc')->select('id', 'name')->get();
    $firebaseConfig = config('services.firebase');

    return view('pages.register', compact('kitchens', 'states', 'firebaseConfig'));
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
            'has_whatsapp' => 'nullable|boolean',
            // 'office_name'  => 'required',
            // 'whatsapp'     => [
            //     'required',
            //     'unique:customers,whatsapp',
            //     'regex:/^[6-9]\d{9}$/',
            // ],
            // 'kitchen_id'   => 'required',
            // 'city'         => 'required',
            // 'postal_code'  => 'required',
        ];

        $messages = [
            'phone.regex' => 'The phone number must be a valid 10-digit Indian mobile number.',
            // 'whatsapp.regex' => 'The WhatsApp number must be a valid 10-digit Indian mobile number.',
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

        // $otp = rand(100000, 999999);
        // $expiresAt = now()->addMinutes(Utility::OTP_EXPIRY_MINUTE);

        $whatsApp = $request->has('has_whatsapp') ? $request->phone : '';

        $customer = Customer::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            // 'office_name' => $request->office_name,
            // 'city' => $request->city,
            // 'landmark' => $request->landmark,
            // 'designation' => $request->designation,
            'whatsapp' => $whatsApp,
            'district_id' => Utility::DISTRICT_ID_MPM,
            'state_id' => Utility::STATE_ID_KERALA,
            'postal_code' => $request->postal_code,
            // 'kitchen_id' => decrypt($request->kitchen_id),
            'kitchen_id' => Utility::KITCHEN_KDY,
            'status' => Utility::ITEM_ACTIVE,
            'is_approved' => 1,
            'firebase_uid' => $request->firebase_uid,
            // 'otp_code' => $otp,
            // 'otp_expires_at' => $expiresAt,
        ]);

        MealWallet::create([
            'customer_id' => $customer->id,
            'quantity' => 0,
            'status' => Utility::ITEM_ACTIVE,
        ]);

        // Log in the customer
        // Auth::login($customer);

        // Check if approved and active
        if ($customer->is_approved && $customer->status == Utility::ITEM_ACTIVE) {
            // Login the customer
            Auth::guard('customer')->login($customer);
            $redirectUrl = route('front.registration.success');
            $successMessage = 'Registration successful! Welcome to your dashboard.';
        } else {

            // Store phone or customer ID in session for OTP verification
            // session(['otp_customer_id' => $customer->id]);
            // Auth::logout();

            // Send OTP
            // $twilio = new TwilioService();
            // $twilio->sendSms(Utility::COUNTRY_CODE.$customer->phone, "Your Zopa OTP is {$otp}");

            // $redirectUrl = route('verify.otp.form');
            // $redirectUrl = route('front.registration.success');
            // $successMessage = 'Registration successful. Our Support Team will contact you soon and activate your account!';
            return 'Something Went Wrong';
        }

        if ($request->expectsJson()) {
            return response()->json([
                // 'message' => $successMessage,
                'redirect_url' => $redirectUrl
            ]);
        }

        return redirect($redirectUrl)->with('success', $successMessage);
    }

    public function showOtpForm()
    {
        $customerId = session('otp_customer_id');

        if (!$customerId) {
            return redirect()->route('front.register')->with('error', 'Session expired. Please register again.');
        }

        if (Auth::guard('customer')->check()) {
            return redirect()->intended($this->redirectTo());
        }

        $customer = Customer::find($customerId);

        return view('pages.verify_otp', compact('customer'));
    }

    public function checkCustomerExists(Request $request)
    {
        $phone = $request->input('phone');

        if (!$phone) {
            return response()->json(['exists' => false, 'error' => 'Phone number is required'], 400);
        }

        $customer = Customer::where('phone', $phone)->first();

        if ($customer) {
            return response()->json(['exists' => true]);
        }

        return response()->json(['exists' => false]);
    }


    // // verifyOtp for Twilio
    // public function verifyOtp(Request $request)
    // {
    //     $customerId = session('otp_customer_id');

    //     if (!$customerId) {
    //         return response()->json([
    //             'errors' => ['otp' => ['Session expired. Please register again.']],
    //         ], 422);
    //     }

    //     $customer = Customer::find($customerId);

    //     if (
    //         !$customer ||
    //         $customer->otp_code !== $request->otp ||
    //         now()->gt($customer->otp_expires_at)
    //     ) {
    //         return response()->json([
    //             'errors' => ['otp' => ['Invalid or expired OTP']],
    //         ], 422);
    //     }

    //     // Mark as verified
    //     $customer->is_approved = Utility::ITEM_ACTIVE;
    //     $customer->otp_code = null;
    //     $customer->otp_expires_at = null;
    //     $customer->save();

    //     // Login the customer
    //     Auth::guard('customer')->login($customer);

    //     // Clear session key
    //     session()->forget('otp_customer_id');

    //     // ✅ Return JSON for AJAX redirect
    //     return response()->json([
    //         'message' => 'OTP verified successfully.',
    //         'redirect_url' => route('front.registration.success'),
    //     ]);
    // }

}
