<?php

// use Illuminate\Support\Facades\Request;

use App\Http\Utilities\Utility;
use App\Models\Branch;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

if (!function_exists('set_active')) {
    function set_active($routes)
    {
        if (is_array($routes)) {
            foreach ($routes as $route) {
                if (Route::currentRouteName() == $route) {
                    return 'mm-active';
                }
            }
        } else {
            if (Route::currentRouteName() == $routes) {
                return 'mm-active';
            }
        }
        return '';
    }
}

// if (!function_exists('default_branch')) {
//     function default_branch()
//     {
//         if(Auth::id()==Utility::SUPER_ADMIN_ID) {
//             if (session()->has('default_branch')) {
//                 $id = session()->get('default_branch');
//                 $default_branch = Branch::where('id',decrypt($id))->first();
//             }else {
//                 $default_branch = Branch::where('status',Utility::ITEM_ACTIVE)->where('id',Utility::DEFAULT_BRANCH_ID)->first();
//                 if(!$default_branch) { $default_branch = Branch::orderBy('id','asc')->first(); }
//             }
//         }else {
//             $default_branch = Branch::where('id',Auth::user()->branch_id)->first();
//         }
//         return $default_branch;
//     }
// }

if (!function_exists('distric_list')) {
    function distric_list(Request $request)
    {
        $districts = DB::table('districts')->orderBy('name','asc')->select('id','name')->where('state_id',$request->s_id)->get();
        $data[]= '<option value="">Select District</option>';
        foreach($districts as $district) {
            $selected = $district->id == $request->d_id ? 'selected' : '';
            $data[] = '<option value="' . $district->id . '"' . $selected . ' >'. $district->name . '</option>';
        }
        return $data;
    }
}





