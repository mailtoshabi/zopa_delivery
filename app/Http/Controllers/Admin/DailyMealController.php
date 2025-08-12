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
use App\Models\Kitchen;
use Illuminate\Http\Request;
use App\Exports\DailyMealsExport;
use Maatwebsite\Excel\Facades\Excel;

class DailyMealController extends Controller
{
    public function index(Request $request)
    {
        $kitchens = Kitchen::where('status', Utility::ITEM_ACTIVE)->get();

        $query = DailyMeal::with('customer')
            ->whereHas('customer', function ($q) {
                $q->where('customer_type', 'individual');
            })
            ->whereDate('date', Carbon::today());

        $dailyMeals = $query->orderBy('id', 'desc')
            ->paginate(Utility::PAGINATE_COUNT);

        $addonsByMeal = DailyAddon::with('addon')
            ->whereIn('daily_meal_id', $dailyMeals->pluck('id'))
            ->get()
            ->groupBy('daily_meal_id');

        $mealtype = 1;

        return view('admin.daily_meals.index', compact('dailyMeals', 'addonsByMeal', 'mealtype','kitchens'));
    }


    public function previous(Request $request)
    {
        $query = DailyMeal::where('status', Utility::ITEM_ACTIVE);

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }else {
            $query->whereDate('date', '<', Carbon::today());
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
        $query = DailyMeal::where('is_auto',Utility::ITEM_INACTIVE);
            // ->where('status', Utility::ITEM_ACTIVE)

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }
        // else {
        //     $query->whereDate('date', '>', Carbon::today());
        // }

        $dailyMeals = $query->clone()->orderBy('date', 'asc')->paginate(Utility::PAGINATE_COUNT);

        $addonsByMeal = DailyAddon::with('addon')
            ->whereIn('daily_meal_id', $dailyMeals->pluck('id'))  // fetch only for visible meals (pagination safe)
            ->get()
            ->groupBy('daily_meal_id');

        $mealtype = 3;

        return view('admin.daily_meals.index', compact('dailyMeals', 'mealtype', 'addonsByMeal'));
    }


    public function generate(Request $request)
    {
        DB::beginTransaction();

        try {
            $today = Carbon::today();
            $kitchenId = $request->input('kitchen_id'); // Accept kitchen_id from the request

            // Eager-load customer and filter where type = individual
            $mealWallets = MealWallet::with('customer')
                ->where('quantity', '>', 0)
                ->where('status', Utility::ITEM_ACTIVE)
                ->where('is_on', Utility::ITEM_ACTIVE)
                ->get()
                ->filter(function ($wallet) use ($kitchenId) {
                    return $wallet->customer
                        && $wallet->customer->customer_type === 'individual'
                        && (!$kitchenId || $wallet->customer->kitchen_id == $kitchenId);
                });

            foreach ($mealWallets as $wallet) {
                $customerId = $wallet->customer_id;

                // Skip if already generated
                $alreadyGenerated = DailyMeal::where('customer_id', $customerId)
                    ->where('is_auto', Utility::ITEM_ACTIVE)
                    ->whereDate('date', $today)
                    ->exists();

                if ($alreadyGenerated) {
                    continue;
                }

                // Skip if customer is on leave today
                $isOnLeave = DB::table('meal_leaves')
                    ->where('customer_id', $customerId)
                    ->whereDate('leave_at', $today)
                    ->exists();

                if ($isOnLeave) {
                    continue;
                }

                // Create daily meal
                $dailyMeal = DailyMeal::create([
                    'customer_id' => $customerId,
                    'quantity' => 1,
                    'wallet_group_id' => $wallet->wallet_group_id,
                    'status' => Utility::ITEM_ACTIVE,
                    'date' => $today,
                    'is_auto' => Utility::ITEM_ACTIVE,
                ]);

                $wallet->decrement('quantity');

                // Check if wallet is now zero, then switch to default wallet
                if ($wallet->fresh()->quantity <= 0) {
                    // Deactivate current wallet
                    $wallet->update(['is_on' => 0]);

                    // Activate default wallet (wallet_group_id = 1)
                    MealWallet::where('customer_id', $customerId)
                        ->where('wallet_group_id', Utility::WALLET_GROUP_MEAL)
                        ->update(['is_on' => Utility::ITEM_ACTIVE]);
                }

                // Handle addons
                if ($wallet->wallet_group_id == Utility::WALLET_GROUP_MEAL) {
                    $addonWallets = AddonWallet::where('customer_id', $customerId)
                        ->where('quantity', '>', 0)
                        ->where('status', Utility::ITEM_ACTIVE)
                        ->get();

                    foreach ($addonWallets as $addonWallet) {
                        $addonAlreadyExists = DailyAddon::where('daily_meal_id', $dailyMeal->id)
                            ->where('addon_id', $addonWallet->addon_id)
                            ->exists();

                        if ($addonAlreadyExists) {
                            continue;
                        }

                        DailyAddon::create([
                            'daily_meal_id' => $dailyMeal->id,
                            'addon_id' => $addonWallet->addon_id,
                            'quantity' => 1,
                            'is_auto' => Utility::ITEM_ACTIVE,
                        ]);

                        $addonWallet->decrement('quantity');
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.daily_meals.index')
                ->with(['success' => 'Daily meals and addons generated successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function exportDailyMeals(Request $request)
    {
        $date = $request->get('date', now()->toDateString());
        $kitchenId = decrypt($request->get('kitchen_id'));

        // return $kitchenId;

        // Default name if no kitchen selected
        $kitchenName = 'all_kitchens';

        if ($kitchenId) {
            $kitchen = Kitchen::find($kitchenId);
            if ($kitchen) {
                // Replace spaces with underscores for file name
                $kitchenName = str_replace(' ', '_', strtolower($kitchen->display_name));
            }
        }

        $fileName = "daily_meals_{$kitchenName}_{$date}.xlsx";

        return Excel::download(
            new DailyMealsExport($date, $kitchenId, true),
            $fileName
        );
    }

    public function generateInstitutionalMeals()
    {
        DB::beginTransaction();

        try {
            $today = Carbon::today();

            // Eager-load customer and filter only institutions
            $mealWallets = MealWallet::with('customer')
                ->where('quantity', '>', 0)
                ->where('status', Utility::ITEM_ACTIVE)
                ->get()
                ->filter(function ($wallet) {
                    return $wallet->customer
                        && $wallet->customer->type === 'institution'
                        && $wallet->customer->daily_quantity > 0;
                });

            foreach ($mealWallets as $wallet) {
                $customer = $wallet->customer;
                $customerId = $customer->id;
                $quantity = $customer->daily_quantity;

                // Skip if already generated for today
                $alreadyGenerated = DailyMeal::where('customer_id', $customerId)
                    ->where('is_auto', Utility::ITEM_ACTIVE)
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

                // Skip if wallet doesn't have enough meals
                if ($wallet->quantity < $quantity) {
                    continue;
                }

                // Create daily meal record
                $dailyMeal = DailyMeal::create([
                    'customer_id' => $customerId,
                    'quantity' => $quantity,
                    'status' => Utility::ITEM_ACTIVE,
                    'date' => $today,
                    'is_auto' => Utility::ITEM_ACTIVE,
                ]);

                // Deduct correct quantity
                $wallet->decrement('quantity', $quantity);

                // Handle addons if any
                $addonWallets = AddonWallet::where('customer_id', $customerId)
                    ->where('quantity', '>=', $quantity)
                    ->where('status', Utility::ITEM_ACTIVE)
                    ->get();

                foreach ($addonWallets as $addonWallet) {
                    $addonAlreadyExists = DailyAddon::where('daily_meal_id', $dailyMeal->id)
                        ->where('addon_id', $addonWallet->addon_id)
                        ->exists();

                    if ($addonAlreadyExists) {
                        continue;
                    }

                    DailyAddon::create([
                        'daily_meal_id' => $dailyMeal->id,
                        'addon_id' => $addonWallet->addon_id,
                        'quantity' => $quantity,
                        'is_auto' => Utility::ITEM_ACTIVE,
                    ]);

                    $addonWallet->decrement('quantity', $quantity);
                }
            }

            DB::commit();
            return redirect()->route('admin.daily_meals.index')
                ->with('success', 'Institutional daily meals and addons generated successfully.');
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

        // Update delivery status for all related dailyAddons
        foreach ($meal->dailyAddons as $addon) {
            $addon->update(['is_delivered' => $is_delivered]);
        }

        return redirect()->route('admin.daily_meals.index')->with(['success' => 'Delivery Status changed Successfully']);
    }


    public function markAllDelivered()
    {
        try {
            $today = Carbon::today();

            // Get all undelivered and active meals for today
            $meals = DailyMeal::whereDate('date', $today)
                ->where('is_delivered', 0)
                ->where('status', Utility::ITEM_ACTIVE)
                ->get();

            $count = 0;

            foreach ($meals as $meal) {
                $meal->update(['is_delivered' => 1]);

                // Mark related addons as delivered
                foreach ($meal->dailyAddons as $addon) {
                    $addon->update(['is_delivered' => 1]);
                }

                $count++;
            }

            return redirect()->back()->with('success', "$count meals marked as delivered.");
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Failed to mark meals as delivered.');
        }
    }

    public function undoAllDelivered()
    {
        try {
            $today = Carbon::today();

            // Get all delivered and active meals for today
            $meals = DailyMeal::whereDate('date', $today)
                ->where('is_delivered', 1)
                ->where('status', Utility::ITEM_ACTIVE)
                ->get();

            $count = 0;

            foreach ($meals as $meal) {
                $meal->update(['is_delivered' => 0]);

                // Mark related addons as not delivered
                foreach ($meal->dailyAddons as $addon) {
                    $addon->update(['is_delivered' => 0]);
                }

                $count++;
            }

            return redirect()->back()->with('success', "$count meals marked as not delivered.");
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Failed to undo delivery status.');
        }
    }


}

