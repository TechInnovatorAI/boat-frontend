<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switch(Request $request, $locale)
    {
        // Validate the locale
        $supportedLocales = ['en', 'es', 'fr', 'de', 'it', 'rm'];
        
        if (!in_array($locale, $supportedLocales)) {
            abort(404);
        }

        // Set the locale
        App::setLocale($locale);
        
        // Store in session
        Session::put('locale', $locale);
        
        // Redirect back to the previous page or home
        return redirect()->back();
    }
}
