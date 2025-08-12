<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Utilities\Utility;
use Illuminate\Support\Facades\Crypt;
use App\Models\Kitchen;
use Illuminate\Http\Request;


class MapController extends Controller
{
    /**
     * Display all the meals on the meal plan page.
     */


     public function getNearbyKitchens(Request $request)
    {
        $lat = $request->latitude;
        $lng = $request->longitude;

        // $lat = 11.1395625;
        // $lng = 75.9943594;

        $haversine = '(6371 * acos(
            cos(radians(?)) *
            cos(radians(latitude)) *
            cos(radians(longitude) - radians(?)) +
            sin(radians(?)) *
            sin(radians(latitude))
        ))';

        $bindings = [$lat, $lng, $lat];

        $kitchens = Kitchen::select('*')
            ->selectRaw("$haversine AS distance", $bindings)
            ->whereRaw("$haversine <= delivery_distance", $bindings)
            ->orderBy('distance', 'asc')
            ->get();
        $kitchens->makeVisible('id');

        // Map through and encrypt the id
        $kitchens = $kitchens->map(function ($kitchen) {
            $kitchen->encrypted_id = Crypt::encrypt($kitchen->id);
            return $kitchen;
        });
        return response()->json($kitchens);
    }

}
