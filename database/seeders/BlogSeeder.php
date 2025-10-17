<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Blog::create([
            'title' => 'Blog 1',
            'content' => 'Content 1',
        ]);
        Blog::create([
            'title' => 'Blog 2',
            'content' => 'Content 2',
        ]);
        Blog::create([
            'title' => 'Blog 3',
            'content' => 'Content 3',
        ]);
    }
}
