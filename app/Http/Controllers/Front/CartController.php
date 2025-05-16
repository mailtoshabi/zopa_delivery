<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Utilities\Utility;
use App\Models\Meal;
use App\Models\Addon;
use Illuminate\Http\Request;
use Session;

class CartController extends Controller
{
    // CartController.php

public function index()
{
    $mealCart = session('meal_cart', []);  // Example: [ mealId => [name, price, quantity] ]
    $addonCart = session('addon_cart', []); // Example: [ addonId => [name, price, quantity] ]

    $cart = [];

    // Merge meal_cart items with type = 'meal'
    foreach ($mealCart as $id => $item) {
        $cart[] = [
            'type' => 'meal',
            'id' => $id,
            'name' => $item['name'],
            'price' => $item['price'],
            'quantity' => $item['quantity'],
        ];
    }

    // Merge addon_cart items with type = 'addon'
    foreach ($addonCart as $id => $item) {
        $cart[] = [
            'type' => 'addon',
            'id' => $id,
            'name' => $item['name'],
            'price' => $item['price'],
            'quantity' => $item['quantity'],
        ];
    }

    return view('pages.cart', compact('cart'));
}

public function addMeal(Request $request)
{
    $request->validate([
        'meal_id' => 'required|exists:meals,id',
        'quantity' => 'nullable|integer|min:1'
    ]);

    $meal = Meal::findOrFail($request->meal_id);
    $quantity = $request->quantity ?? 1;

    $cart = session()->get('meal_cart', []);

    // Key pattern is consistent (type + id)
    $key = 'meal_' . $meal->id;

    if (isset($cart[$key])) {
        $cart[$key]['quantity'] += $quantity;
    } else {
        $cart[$key] = [
            'type' => 'meal',
            'id' => $meal->id,
            'name' => $meal->name,
            'price' => $meal->price,
            'quantity' => $quantity
        ];
    }

    session()->put('meal_cart', $cart);

    // return back()->with('success', 'Meal added to cart!');
    return redirect()->route('cart.index')->with('success', 'Meal added to cart!');
}


    public function addAddon(Request $request)
    {
        $addon = Addon::findOrFail($request->addon_id);
        $quantity = $request->quantity ?? 1;

        $cart = session()->get('addon_cart', []);

        $key = 'addon_'.$addon->id;

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            $cart[$key] = [
                'type' => 'addon',
                'id' => $addon->id,
                'name' => $addon->name,
                'price' => $addon->price,
                'quantity' => $quantity
            ];
        }

        session()->put('addon_cart', $cart);

        return back()->with('success', 'Addon added to cart!');
    }

    public function removeItem(Request $request)
{
    $type = $request->input('type'); // 'meal' or 'addon'
    $id = $request->input('id');

    if ($type === 'meal') {
        $cart = session('meal_cart', []);
        unset($cart[$id]);
        session(['meal_cart' => $cart]);
    } elseif ($type === 'addon') {
        $cart = session('addon_cart', []);
        unset($cart[$id]);
        session(['addon_cart' => $cart]);
    }

    return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
}


    public function clear()
    {
        session()->forget('meal_cart');
        session()->forget('addon_cart');
        return back()->with('success', 'Cart cleared!');
    }

    public function checkout()
    {
        $cart = session('cart');
        return view('pages.checkout', compact('cart')); // create a checkout.blade.php later
    }

    public function store(Request $request)
    {
        $request->validate([
            'pay_method' => 'required|in:' . implode(',', [Utility::PAYMENT_ONLINE, Utility::PAYMENT_COD]),
        ]);

        $cart = session('cart');

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Save the order logic here (you'll handle this later)
        // Example: Store cart items + payment method into database, clear cart, redirect success

        // session()->forget('cart');

        return redirect()->route('home')->with('success', 'Your cart order has been placed successfully!');
    }
}
