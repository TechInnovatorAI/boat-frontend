<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BlogService;

class HomeController extends Controller
{
    protected BlogService $blogService;

    public function __construct(BlogService $blogService)
    {
        $this->blogService = $blogService;
    }

    public function index(Request $request)
    {
        // Get the current language
        $language = $request->get('lang', app()->getLocale());
        
        // Validate language
        if (!$this->blogService->isLanguageSupported($language)) {
            $language = 'en';
        }

        // Get latest blog posts (limit to 3 for homepage)
        $allPosts = $this->blogService->getPosts($language);
        $latestPosts = array_slice($allPosts, 0, 3);

        return view('home', compact('latestPosts', 'language'));
    }
}
