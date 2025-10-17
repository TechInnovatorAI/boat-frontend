<?php

namespace App\Http\Controllers;

use App\Services\BlogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BlogController extends Controller
{
    protected BlogService $blogService;

    public function __construct(BlogService $blogService)
    {
        $this->blogService = $blogService;
    }

    /**
     * Display a listing of blog posts
     */
    public function index(Request $request)
    {
        $request->validate([
            'lang' => 'sometimes|string|in:en,fr,de,it',
        ]);

        $language = $request->get('lang', app()->getLocale());

        // Validate language
        if (! $this->blogService->isLanguageSupported($language)) {
            $language = 'en';
        }

        try {
            $posts = $this->blogService->getPosts($language);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve blog posts', [
                'language' => $language,
                'error' => $e->getMessage(),
            ]);

            $posts = [];
        }

        return view('blog.index', compact('posts', 'language'));
    }

    /**
     * Display the specified blog post
     */
    public function show(Request $request, string $slug)
    {
        // Get language from request parameter or session
        $language = $request->get('lang', session('locale', app()->getLocale()));

        // Validate language
        if (! $this->blogService->isLanguageSupported($language)) {
            $language = 'en';
        }

        // Sanitize slug - only remove truly dangerous characters
        $slug = $this->sanitizeSlug($slug);

        try {
            $post = $this->blogService->getPost($slug, $language);

            if (! $post) {
                // Try to find the post in any language
                $allLanguages = $this->blogService->getSupportedLanguages();
                foreach ($allLanguages as $lang) {
                    $post = $this->blogService->getPost($slug, $lang);
                    if ($post) {
                        $language = $lang;
                        break;
                    }
                }

                if (! $post) {
                    abort(404, 'Blog post not found');
                }
            }

            // Get available languages for this post
            $availableLanguages = $this->blogService->getAvailableLanguages($slug);

            // Get related posts
            $relatedPosts = $this->blogService->getRelatedPosts($slug, $language, 3);

            return view('blog.show', compact('post', 'language', 'availableLanguages', 'relatedPosts'));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve blog post', [
                'slug' => $slug,
                'language' => $language,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            abort(500, 'Unable to load blog post');
        }
    }

    /**
     * Sanitize slug input
     */
    protected function sanitizeSlug(string $slug): string
    {
        // Only remove truly dangerous characters, keep hyphens and underscores
        $slug = trim($slug);
        $slug = preg_replace('/[^a-zA-Z0-9\-_]/', '', $slug);

        // Ensure it's not empty
        if (empty($slug)) {
            abort(404, 'Invalid blog post identifier');
        }

        return $slug;
    }
}
