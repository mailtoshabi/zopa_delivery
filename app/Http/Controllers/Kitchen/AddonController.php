<?php

namespace App\Http\Controllers\Kitchen;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddonStoreRequest;
use App\Http\Requests\AddonUpdateRequest;
use App\Http\Utilities\Utility;
use App\Helpers\FileHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Addon;
use App\Models\Ingredient;
use App\Models\Remark;

class AddonController extends Controller
{
    public function index()
    {
        // $addons = Addon::orderBy('id', 'desc')->paginate(Utility::PAGINATE_COUNT);


        $kitchen = auth('kitchen')->user(); // Or ->kitchen if using User model

        $addons = Addon::with([
            'kitchens' => function($q) use ($kitchen) {
                $q->where('kitchen_id', $kitchen->id);
            }
        ])->paginate(Utility::PAGINATE_COUNT);
        return view('kitchen.addons.index', compact('addons'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'addon_id' => 'required|exists:addons,id',
            'price'    => 'required|numeric',
            'status'   => 'required|boolean',
        ]);

        $kitchen = auth('kitchen')->user();

        if (!$kitchen) {
            return back()->with('error', 'No kitchen found for this account.');
        }

        // Check global addon status
        $addon = Addon::find($request->addon_id);
        if ($addon->status == 0) {
            return back()->with('error', 'This addon is inactive and cannot be updated.');
        }

        // Insert or update kitchen-specific record
        $kitchen->addons()->syncWithoutDetaching([
            $request->addon_id => [
                'price'  => $request->price,
                'status' => $request->status,
            ]
        ]);

        return back()->with('success', 'Addon updated successfully.');
    }
}
