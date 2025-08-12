<?php

namespace App\Http\Controllers\Kitchen;

use App\Http\Controllers\Controller;
use App\Http\Utilities\Utility;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Meal;
use App\Models\Ingredient;

class MealController extends Controller
{
    public function index()
    {
        // $meals = Meal::orderBy('id', 'desc')->paginate(Utility::PAGINATE_COUNT);
        $kitchen = auth('kitchen')->user(); // Or ->kitchen if using User model

        $meals = Meal::with(['kitchens' => function($q) use ($kitchen) {
                $q->where('kitchen_id', $kitchen->id);
            }, 'mess_category', 'walletGroup', 'ingredients', 'remarks'])
            ->where('status',Utility::ITEM_ACTIVE)->orderBy('id', 'desc')->paginate(Utility::PAGINATE_COUNT);
        return view('kitchen.meals.index', compact('meals'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'meal_id' => 'required|exists:meals,id',
            'price'   => 'required|numeric',
            'status'  => 'required|boolean',
        ]);

        // Get logged-in kitchen
        $kitchen = auth('kitchen')->user(); // Or ->kitchen if using User model

        if (!$kitchen) {
            return back()->with('error', 'No kitchen found for this account.');
        }

        // Check if meal is globally inactive
        $meal = Meal::find($request->meal_id);
        if ($meal->status == 0) {
            return back()->with('error', 'This meal is inactive and cannot be updated.');
        }

        // Insert or update the pivot data
        $kitchen->meals()->syncWithoutDetaching([
            $request->meal_id => [
                'price'  => $request->price,
                'status' => $request->status,
            ]
        ]);

        return back()->with('success', 'Meal updated successfully.');
    }


    public function changeStatus($id)
    {
        $meal = Meal::findOrFail(decrypt($id));
        $status = $meal->status ? 0 : 1;
        $meal->update(['status' => $status]);

        return redirect()->route('admin.meals.index')->with(['success' => 'Status changed Successfully']);
    }

}
