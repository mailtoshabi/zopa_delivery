<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function switch($locale)
    {
        if (!in_array($locale, ['en', 'ml'])) {
            $locale = 'en';
        }

        Session::put('locale', $locale);
        App::setLocale($locale);

        return Redirect::back();
    }
}
