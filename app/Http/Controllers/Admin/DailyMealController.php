<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyMeal;
use App\Models\MealWallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Http\Utilities\Utility;
use App\Models\AddonWallet;
use App\Models\DailyAddon;
use Illuminate\Http\Request;

class DailyMealController extends Controller
{
    public function index(Request $request)
    {
        $query = DailyMeal::with('customer')
            ->whereDate('date', Carbon::today());

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        $dailyMeals = $query->orderBy('id', 'desc')
            ->paginate(Utility::PAGINATE_COUNT);

        $addonsByMeal = DailyAddon::with('addon')
            ->whereIn('daily_meal_id', $dailyMeals->pluck('id'))
            ->get()
            ->groupBy('daily_meal_id');

        $mealtype = 1;

        return view('admin.daily_meals.index', compact('dailyMeals', 'addonsByMeal', 'mealtype'));
    }


    public function previous(Request $request)
    {
        $query = DailyMeal::whereDate('date', '<', Carbon::today());

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        $dailyMeals = $query->orderBy('id', 'desc')
            ->paginate(Utility::PAGINATE_COUNT);

        $addonsByMeal = DailyAddon::with('addon')
            ->whereIn('daily_meal_id', $dailyMeals->pluck('id'))
            ->get()
            ->groupBy('daily_meal_id');

        $mealtype = 2;

        return view('admin.daily_meals.index', compact('dailyMeals', 'addonsByMeal', 'mealtype'));
    }

    public function extra_meals(Request $request)
    {
        $query = DailyMeal::whereDate('date', '>', Carbon::today());
            // ->where('status', Utility::ITEM_ACTIVE)

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        // $dailyMeals = $query->orderBy('date', 'asc')->paginate(Utility::PAGINATE_COUNT);
        $dailyMeals = $query->clone()->orderBy('date', 'asc')->paginate(Utility::PAGINATE_COUNT);

        $addonsByMeal = DailyAddon::with('addon')
            ->whereIn('daily_meal_id', $dailyMeals->pluck('id'))  // fetch only for visible meals (pagination safe)
            ->get()
            ->groupBy('daily_meal_id');

        $mealtype = 3;

        return view('admin.daily_meals.index', compact('dailyMeals', 'mealtype', 'addonsByMeal'));
    }


    public function generate()
    {
        DB::beginTransaction();

        try {
            $today = Carbon::today();

            $mealWallets = MealWallet::where('quantity', '>', 0)
                ->where('status', 1)
                ->get();

            foreach ($mealWallets as $wallet) {
                $customerId = $wallet->customer_id;

                // Skip if customer already has a generated meal for today
                $alreadyGenerated = DailyMeal::where('customer_id', $customerId)
                    ->where('is_auto', 1)
                    ->whereDate('date', $today)
                    ->exists();

                if ($alreadyGenerated) {
                    continue;
                }

                // Skip if the customer is on leave today
                $isOnLeave = DB::table('meal_leaves')
                    ->where('customer_id', $customerId)
                    ->whereDate('leave_at', $today)
                    ->exists();

                if ($isOnLeave) {
                    continue;
                }

                // Create daily meal and capture instance
                $dailyMeal = DailyMeal::create([
                    'customer_id' => $customerId,
                    'quantity' => 1,
                    'status' => Utility::ITEM_ACTIVE,
                    'date' => $today,
                    'is_auto' => 1,
                ]);

                $wallet->decrement('quantity');

                // Add Addons from wallet if available
                $addonWallets = AddonWallet::where('customer_id', $customerId)
                    ->where('quantity', '>', 0)
                    ->where('status', 1)
                    ->get();

                foreach ($addonWallets as $addonWallet) {
                    // Skip if already added to this meal (precaution)
                    $addonAlreadyExists = DailyAddon::where('daily_meal_id', $dailyMeal->id)
                        ->where('addon_id', $addonWallet->addon_id)
                        ->exists();

                    if ($addonAlreadyExists) {
                        continue;
                    }

                    // Create daily addon
                    DailyAddon::create([
                        'daily_meal_id' => $dailyMeal->id,
                        'addon_id' => $addonWallet->addon_id,
                        'quantity' => 1,
                        'is_auto' => 1,
                    ]);

                    $addonWallet->decrement('quantity');
                }
            }

            DB::commit();
            return redirect()->route('admin.daily_meals.index')->with(['success' => 'Daily meals and addons generated successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function MarkDelivery($id, Request $request)
    {
        $meal = DailyMeal::findOrFail(decrypt($id));
        $is_delivered = $meal->is_delivered ? 0 : 1;

        // If marking as undelivered, require a reason
        if (!$is_delivered) {
            $request->validate([
                'reason' => 'required|string|max:500',
            ]);
            $meal->update([
                'is_delivered' => $is_delivered,
                'reason' => $request->reason,
            ]);
        } else {
            $meal->update([
                'is_delivered' => $is_delivered,
                'reason' => null,
            ]);
        }

        return redirect()->route('admin.daily_meals.index')->with(['success' => 'Delivery Status changed Successfully']);
    }


    public function markAllDelivered()
    {
        try {
            $today = Carbon::today();

            $updated = DailyMeal::whereDate('date', $today)
                ->where('is_delivered', 0)
                ->where('status', Utility::ITEM_ACTIVE)
                ->update(['is_delivered' => 1]);

            return redirect()->back()->with('success', "$updated meals marked as delivered.");
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Failed to mark meals as delivered.');
        }
    }

    public function undoAllDelivered()
    {
        try {
            $today = Carbon::today();

            $updated = DailyMeal::whereDate('date', $today)
                ->where('is_delivered', 1)
                ->where('status', Utility::ITEM_ACTIVE)
                ->update(['is_delivered' => 0]);

            return redirect()->back()->with('success', "$updated meals marked as not delivered.");
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Failed to undo delivery status.');
        }
    }


}

