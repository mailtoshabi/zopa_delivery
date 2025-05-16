<?php

namespace App\Http\Controllers;

use App\Http\Utilities\Utility;
use App\Models\Estimate;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    // public function index(Request $request)
    // {
    //     if (view()->exists($request->path())) {
    //         return view($request->path());
    //     }
    //     return abort(404);
    // }

    public function index(Request $request)
    {
        return view('welcome');

    }

    public function root()
    {
        return view('index');
    }

    /*Language Translation*/
    public function lang($locale)
    {
        if ($locale) {
            App::setLocale($locale);
            Session::put('lang', $locale);
            Session::save();
            return redirect()->back()->with('locale', $locale);
        } else {
            return redirect()->back();
        }
    }

    // public function updateProfile(Request $request, $id)
    // {
    //     $request->validate([
    //         'name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'string', 'email'],
    //         'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
    //     ]);

    //     $user = User::find($id);
    //     $user->name = $request->get('name');
    //     $user->email = $request->get('email');

    //     if ($request->file('avatar')) {
    //         $avatar = $request->file('avatar');
    //         $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
    //         $avatarPath = public_path('/images/');
    //         $avatar->move($avatarPath, $avatarName);
    //         $user->avatar =  $avatarName;
    //     }

    //     $user->update();
    //     if ($user) {
    //         Session::flash('message', 'User Details Updated successfully!');
    //         Session::flash('alert-class', 'alert-success');
    //         return response()->json([
    //             'isSuccess' => true,
    //             'Message' => "User Details Updated successfully!"
    //         ], 200); // Status code here
    //     } else {
    //         Session::flash('message', 'Something went wrong!');
    //         Session::flash('alert-class', 'alert-danger');
    //         return response()->json([
    //             'isSuccess' => true,
    //             'Message' => "Something went wrong!"
    //         ], 200); // Status code here
    //     }
    // }

    // public function updatePassword(Request $request, $id)
    // {
    //     $request->validate([
    //         'current_password' => ['required', 'string'],
    //         'password' => ['required', 'string', 'min:6', 'confirmed'],
    //     ]);

    //     if (!(Hash::check($request->get('current_password'), Auth::user()->password))) {
    //         return response()->json([
    //             'isSuccess' => false,
    //             'Message' => "Your Current password does not matches with the password you provided. Please try again."
    //         ], 200); // Status code
    //     } else {
    //         $user = User::find($id);
    //         $user->password = Hash::make($request->get('password'));
    //         $user->update();
    //         if ($user) {
    //             Session::flash('message', 'Password updated successfully!');
    //             Session::flash('alert-class', 'alert-success');
    //             return response()->json([
    //                 'isSuccess' => true,
    //                 'Message' => "Password updated successfully!"
    //             ], 200); // Status code here
    //         } else {
    //             Session::flash('message', 'Something went wrong!');
    //             Session::flash('alert-class', 'alert-danger');
    //             return response()->json([
    //                 'isSuccess' => true,
    //                 'Message' => "Something went wrong!"
    //             ], 200); // Status code here
    //         }
    //     }
    // }
    // public function productPrice($estimate_id,$product_id) {
    //     $estimate_product = DB::table('estimate_product')->where('estimate_id',$estimate_id)->where('product_id',$product_id)->first();
    //     // $quantity = $estimate_product->quantity;
    //     $profit = $estimate_product->profit;
    //     // $estimate_product_ingredients = DB::table('component_estimate_product')->where('estimate_product_id',$estimate_product->id)->get();
    //     $sum_price_ingredients = DB::table('component_estimate_product')->where('estimate_product_id',$estimate_product->id)->sum('cost');
    //     $price = $profit + $sum_price_ingredients;
    //     return $price;
    // }

    // public function subTotal($estimate_id) {
    //     $estimate_product = DB::table('estimate_product')->where('estimate_id',$estimate_id)->sum('cost');
    //     $profit = $estimate_product->profit;
    //     $sum_price_ingredients = DB::table('component_estimate_product')->where('estimate_product_id',$estimate_product->id)->sum('cost');
    //     $price = $profit + $sum_price_ingredients;
    //     return $price;
    // }

    public function test() {
        // $var = 'Password updated successfully!';
        // return $this->productPrice(1,5);

        // return Utility::cleanString($var);
        // return set_active('test');
        // return Utility::numToWords(4);


        //whether ip is from the share internet
    //  if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
    //     $ip = $_SERVER['HTTP_CLIENT_IP'];
    // }
    // //whether ip is from the proxy
    // elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    //         $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    // }
    // //whether ip is from the remote address
    // else{
        // $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
    // }
    // return $_SERVER['REMOTE_ADDR'];
    $invoice = 'ZP-' . str_pad(10, 6, '0', STR_PAD_LEFT) . '-' . date('mY');

    }
}
