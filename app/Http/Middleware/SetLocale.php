<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            // Get locale from request parameter, session, or default to 'en'
            $locale = $request->get('lang', Session::get('locale', 'en'));
            
            // Validate locale
            $supportedLocales = ['en', 'fr', 'de', 'it'];
            if (!in_array($locale, $supportedLocales)) {
                $locale = 'en';
            }
            
            // Store in session if it's different
            if (Session::get('locale') !== $locale) {
                Session::put('locale', $locale);
            }
            
            // Set the application locale
            App::setLocale($locale);
            
            return $next($request);
        } catch (\Exception $e) {
            Log::error('SetLocale middleware error', [
                'error' => $e->getMessage(),
                'request_url' => $request->fullUrl()
            ]);
            
            // Fallback to English
            App::setLocale('en');
            return $next($request);
        }
    }
}
