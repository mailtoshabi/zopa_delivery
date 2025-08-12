<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Helpers\FileHelper;
use App\Http\Utilities\Utility;
use App\Models\Customer;
use App\Models\DailyQuantityOverride;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class QuantityOverrideController extends Controller
{
    protected $cutoffHour;
    protected $cutoffMinute;

    public function __construct()
    {
        $cutoff = Utility::getCutoffHourAndMinute();
        $this->cutoffHour = $cutoff['hour'];
        $this->cutoffMinute = $cutoff['minute'];
    }

    public function index()
    {
        $overrides = DailyQuantityOverride::where('customer_id', Auth::id())
            ->orderByDesc('date')
            ->get();

        return view('pages.my_quantity', compact('overrides'));
    }

    public function store(Request $request)
    {
        $date = Carbon::createFromFormat('d-m-Y', $request->date)->startOfDay();
        $now = now();
        $cutoffTime = now()->setTime($this->cutoffHour, $this->cutoffMinute);

        $customer_id = auth('customer')->id();
        $customer = Customer::findOrFail($customer_id);

        if ($date->isToday() && $now->gt($cutoffTime)) {
            return $request->ajax()
                ? response()->json(['success' => false, 'message' => 'Cannot change quantity today after cutoff time.'])
                : back()->with('error', 'Cannot change quantity today after cutoff time.');
        }

        if ($date->isPast()) {
            return $request->ajax()
                ? response()->json(['success' => false, 'message' => 'Cannot change quantity for past dates.'])
                : back()->with('error', 'Cannot change quantity for past dates.');
        }

        if ($date->isSunday()) {
            return $request->ajax()
                ? response()->json(['success' => false, 'message' => 'Sundays are not allowed.'])
                : back()->with('error', 'Sundays are not allowed.');
        }

        $defaultQty = $customer->daily_quantity ?? 1;
        if ($request->quantity == $defaultQty) {
            return $request->ajax()
                ? response()->json(['success' => false, 'message' => 'Same as default quantity. No need to change it.'])
                : back()->with('error', 'Same as default quantity. No need to change it.');
        }

        // ✅ Save override
        $customer->dailyQuantityOverrides()->updateOrCreate(
            ['date' => $date],
            ['quantity' => $request->quantity]
        );

        return $request->ajax()
            ? response()->json(['success' => true])
            : back()->with('success', 'Quantity changed successfully.');
    }

    public function destroy($id)
    {
        $override = DailyQuantityOverride::where('id', $id)
            ->where('customer_id', Auth::id())
            ->firstOrFail();

        $cutoff = Utility::getCutoffHourAndMinute();
        $cutoffTime = Carbon::today()->setTime($cutoff['hour'], $cutoff['minute']);

        $isPastCutoff = (
            Carbon::parse($override->date)->lt(Carbon::today()) ||
            (Carbon::parse($override->date)->equalTo(Carbon::today()) && now()->gt($cutoffTime))
        );

        if ($isPastCutoff) {
            return redirect()->back()->with('error', 'Cannot delete quantity override after cutoff.');
        }

        $override->delete();

        return redirect()->back()->with('success', 'Override removed.');
    }
}

