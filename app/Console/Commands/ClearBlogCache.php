<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BlogService;

class ClearBlogCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blog:clear-cache {--language= : Clear cache for specific language} {--post= : Clear cache for specific post}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear blog cache for better performance management';

    /**
     * Execute the console command.
     */
    public function handle(BlogService $blogService): int
    {
        $language = $this->option('language');
        $post = $this->option('post');

        if ($post) {
            $this->info("Clearing cache for post: {$post}");
            $blogService->clearPostCache($post, $language);
            $this->info('Post cache cleared successfully!');
        } elseif ($language) {
            $this->info("Clearing cache for language: {$language}");
            $blogService->clearLanguageCache($language);
            $this->info('Language cache cleared successfully!');
        } else {
            $this->info('Clearing all blog cache...');
            $blogService->clearCache();
            $this->info('All blog cache cleared successfully!');
        }

        return Command::SUCCESS;
    }
}