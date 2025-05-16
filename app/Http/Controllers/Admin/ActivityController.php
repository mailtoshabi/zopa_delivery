<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Utilities\Utility;
use Spatie\Activitylog\Models\Activity;

class ActivityController extends Controller
{
    public function index() {
        $activities = Activity::latest()->get();
        return view('admin.activities.index',compact('activities'));
    }
}
