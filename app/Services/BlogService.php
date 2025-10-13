<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class BlogService
{
    protected string $contentPath;
    protected array $supportedLanguages = ['en', 'fr', 'de', 'it'];
    protected int $cacheTtl = 3600; // 1 hour

    public function __construct()
    {
        $this->contentPath = resource_path('content/blog');
    }

    /**
     * Get all blog posts for a specific language
     */
    public function getPosts(string $language = 'en'): array
    {
        $language = $this->validateLanguage($language);
        $cacheKey = "blog_posts_{$language}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($language) {
            $postsPath = $this->contentPath . '/' . $language;
            
            if (!File::exists($postsPath)) {
                return [];
            }

            $files = $this->getMarkdownFiles($postsPath);
            $posts = [];

            foreach ($files as $file) {
                $post = $this->parseMarkdownFile($file->getPathname());
                if ($post) {
                    $posts[] = $post;
                }
            }

            // Sort posts by date (newest first)
            usort($posts, function ($a, $b) {
                return strtotime($b['published_at']) - strtotime($a['published_at']);
            });

            return $posts;
        });
    }

    /**
     * Get a specific blog post by slug and language
     */
    public function getPost(string $slug, string $language = 'en'): ?array
    {
        $language = $this->validateLanguage($language);
        $cacheKey = "blog_post_{$slug}_{$language}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($slug, $language) {
            $postsPath = $this->contentPath . '/' . $language;
            
            if (!File::exists($postsPath)) {
                return null;
            }

            $files = $this->getMarkdownFiles($postsPath);
            
            foreach ($files as $file) {
                $post = $this->parseMarkdownFile($file->getPathname());
                if ($post && $post['slug'] === $slug) {
                    return $post;
                }
            }

            return null;
        });
    }

    /**
     * Get available languages for a specific post
     */
    public function getAvailableLanguages(string $slug): array
    {
        $cacheKey = "blog_available_languages_{$slug}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($slug) {
            $availableLanguages = [];
            
            foreach ($this->supportedLanguages as $language) {
                $post = $this->getPost($slug, $language);
                if ($post) {
                    $availableLanguages[] = $language;
                }
            }

            return $availableLanguages;
        });
    }

    /**
     * Get related posts (same language, excluding current post)
     */
    public function getRelatedPosts(string $currentSlug, string $language = 'en', int $limit = 3): array
    {
        $posts = $this->getPosts($language);
        
        // Filter out current post and limit results
        $relatedPosts = array_filter($posts, function ($post) use ($currentSlug) {
            return $post['slug'] !== $currentSlug;
        });

        return array_slice($relatedPosts, 0, $limit);
    }

    /**
     * Get markdown files from directory
     */
    protected function getMarkdownFiles(string $directory): Collection
    {
        try {
            return collect(File::files($directory))
                ->filter(function ($file) {
                    return $file->getExtension() === 'md';
                });
        } catch (\Exception $e) {
            Log::warning("Failed to read directory: {$directory}", ['error' => $e->getMessage()]);
            return collect();
        }
    }

    /**
     * Parse markdown file and extract metadata
     */
    protected function parseMarkdownFile(string $filePath): ?array
    {
        try {
            if (!File::exists($filePath)) {
                return null;
            }

            $content = File::get($filePath);
            
            if (empty($content)) {
                return null;
            }
            
            // Extract front matter (YAML between --- markers)
            if (preg_match('/^---\s*\n(.*?)\n---\s*\n(.*)$/s', $content, $matches)) {
                $frontMatter = $matches[1];
                $markdownContent = $matches[2];
                
                $metadata = $this->parseYaml($frontMatter);
            } else {
                // No front matter, try to extract from filename
                $filename = basename($filePath, '.md');
                $metadata = $this->extractMetadataFromFilename($filename);
                $markdownContent = $content;
            }

            if (!$metadata) {
                return null;
            }

            // Convert markdown to HTML
            $htmlContent = $this->markdownToHtml($markdownContent);

            // Generate slug if not provided
            if (!isset($metadata['slug'])) {
                $metadata['slug'] = $this->generateSlug($metadata['title'] ?? $filename);
            }

            // Set default values
            $metadata['content'] = $htmlContent;
            $metadata['excerpt'] = $metadata['excerpt'] ?? $this->generateExcerpt($htmlContent);
            $metadata['featured_image'] = $metadata['featured_image'] ?? $this->getDefaultImage();
            $metadata['author'] = $metadata['author'] ?? 'Boat Website Team';
            $metadata['published_at'] = $metadata['published_at'] ?? date('Y-m-d');

            return $metadata;
        } catch (\Exception $e) {
            Log::error("Failed to parse markdown file: {$filePath}", ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Parse YAML front matter
     */
    protected function parseYaml(string $yaml): array
    {
        $lines = explode("\n", $yaml);
        $metadata = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || strpos($line, '#') === 0) {
                continue;
            }

            if (strpos($line, ':') !== false) {
                [$key, $value] = explode(':', $line, 2);
                $key = trim($key);
                $value = trim($value);
                
                // Remove quotes if present
                if ((str_starts_with($value, '"') && str_ends_with($value, '"')) ||
                    (str_starts_with($value, "'") && str_ends_with($value, "'"))) {
                    $value = substr($value, 1, -1);
                }

                // Handle array values (tags)
                if ($key === 'tags' && str_contains($value, '[')) {
                    $value = $this->parseArrayValue($value);
                }

                $metadata[$key] = $value;
            }
        }

        return $metadata;
    }

    /**
     * Parse array values from YAML
     */
    protected function parseArrayValue(string $value): array
    {
        // Simple array parsing for tags like ["tag1", "tag2"]
        if (preg_match('/\[(.*?)\]/', $value, $matches)) {
            $items = explode(',', $matches[1]);
            return array_map('trim', array_map(function($item) {
                return trim($item, '"\'');
            }, $items));
        }
        
        return [];
    }

    /**
     * Extract metadata from filename (format: YYYY-MM-DD-title.md)
     */
    protected function extractMetadataFromFilename(string $filename): array
    {
        // Pattern: YYYY-MM-DD-title.md
        if (preg_match('/^(\d{4}-\d{2}-\d{2})-(.+)$/', $filename, $matches)) {
            return [
                'title' => Str::title(str_replace('-', ' ', $matches[2])),
                'published_at' => $matches[1],
            ];
        }

        return [
            'title' => Str::title(str_replace('-', ' ', $filename)),
            'published_at' => date('Y-m-d'),
        ];
    }

    /**
     * Convert markdown to HTML (basic implementation)
     */
    protected function markdownToHtml(string $markdown): string
    {
        // Basic markdown to HTML conversion
        $html = $markdown;

        // Headers
        $html = preg_replace('/^### (.*$)/m', '<h3>$1</h3>', $html);
        $html = preg_replace('/^## (.*$)/m', '<h2>$1</h2>', $html);
        $html = preg_replace('/^# (.*$)/m', '<h1>$1</h1>', $html);

        // Bold and italic
        $html = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $html);
        $html = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $html);

        // Links
        $html = preg_replace('/\[([^\]]+)\]\(([^)]+)\)/', '<a href="$2">$1</a>', $html);

        // Images
        $html = preg_replace('/!\[([^\]]*)\]\(([^)]+)\)/', '<img src="$2" alt="$1" class="w-full rounded-lg">', $html);

        // Code blocks
        $html = preg_replace('/```([^`]*)```/s', '<pre><code>$1</code></pre>', $html);
        $html = preg_replace('/`([^`]+)`/', '<code>$1</code>', $html);

        // Lists
        $html = preg_replace('/^\* (.*$)/m', '<li>$1</li>', $html);
        $html = preg_replace('/^- (.*$)/m', '<li>$1</li>', $html);
        $html = preg_replace('/(<li>.*<\/li>)/s', '<ul>$1</ul>', $html);

        // Paragraphs
        $html = preg_replace('/^(?!<[h|u|o|p|d|s])(.+)$/m', '<p>$1</p>', $html);

        // Clean up multiple paragraphs
        $html = preg_replace('/<\/p>\s*<p>/', "\n\n", $html);

        return $html;
    }

    /**
     * Generate excerpt from HTML content
     */
    protected function generateExcerpt(string $html, int $length = 150): string
    {
        $text = strip_tags($html);
        return Str::limit($text, $length);
    }

    /**
     * Generate URL-friendly slug
     */
    protected function generateSlug(string $title): string
    {
        return Str::slug($title);
    }

    /**
     * Get default featured image
     */
    protected function getDefaultImage(): string
    {
        return 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=800&h=400&fit=crop';
    }

    /**
     * Validate language code
     */
    protected function validateLanguage(string $language): string
    {
        return in_array($language, $this->supportedLanguages) ? $language : 'en';
    }

    /**
     * Get all available languages
     */
    public function getSupportedLanguages(): array
    {
        return $this->supportedLanguages;
    }

    /**
     * Check if a language is supported
     */
    public function isLanguageSupported(string $language): bool
    {
        return in_array($language, $this->supportedLanguages);
    }

    /**
     * Clear all blog cache
     */
    public function clearCache(): void
    {
        foreach ($this->supportedLanguages as $language) {
            Cache::forget("blog_posts_{$language}");
        }
        
        // Clear individual post caches (this is a simplified approach)
        // In production, you might want to track cache keys more precisely
        Cache::flush();
    }

    /**
     * Clear cache for specific language
     */
    public function clearLanguageCache(string $language): void
    {
        $language = $this->validateLanguage($language);
        Cache::forget("blog_posts_{$language}");
    }

    /**
     * Clear cache for specific post
     */
    public function clearPostCache(string $slug, string $language = null): void
    {
        if ($language) {
            $language = $this->validateLanguage($language);
            Cache::forget("blog_post_{$slug}_{$language}");
        } else {
            // Clear for all languages
            foreach ($this->supportedLanguages as $lang) {
                Cache::forget("blog_post_{$slug}_{$lang}");
            }
        }
        
        Cache::forget("blog_available_languages_{$slug}");
    }
}
