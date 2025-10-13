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
        $supportedLocales = ['en', 'fr', 'de', 'it'];
        
        if (!in_array($locale, $supportedLocales)) {
            abort(404);
        }

        // Set the locale
        App::setLocale($locale);
        
        // Store in session
        Session::put('locale', $locale);
        
        // Get the previous URL to maintain the current page context
        $previousUrl = $request->header('referer');
        
        if ($previousUrl) {
            // Parse the URL to extract the current path and query parameters
            $parsedUrl = parse_url($previousUrl);
            $path = $parsedUrl['path'] ?? '/';
            $query = $parsedUrl['query'] ?? '';
            
            // Parse existing query parameters
            parse_str($query, $queryParams);
            
            // Update or add the language parameter
            $queryParams['lang'] = $locale;
            
            // Rebuild the URL with the new language parameter
            $newQuery = http_build_query($queryParams);
            $newUrl = $path . ($newQuery ? '?' . $newQuery : '');
            
            return redirect($newUrl);
        }
        
        // Fallback: redirect to home with language parameter
        return redirect()->route('home', ['lang' => $locale]);
    }
}
