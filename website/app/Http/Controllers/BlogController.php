<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        // Sample blog posts - in a real application, these would come from a database
        $posts = [
            [
                'id' => 1,
                'title' => 'Getting Started with Laravel Blade',
                'slug' => 'getting-started-with-laravel-blade',
                'excerpt' => 'Learn the fundamentals of Laravel Blade templating engine and how to create dynamic, reusable components.',
                'content' => 'Laravel Blade is a powerful templating engine that comes built-in with Laravel. It provides a clean, elegant syntax for writing templates that are both expressive and maintainable...',
                'author' => 'John Doe',
                'published_at' => '2024-01-15',
                'featured_image' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=800&h=400&fit=crop',
            ],
            [
                'id' => 2,
                'title' => 'Building Modern Web Applications',
                'slug' => 'building-modern-web-applications',
                'excerpt' => 'Discover the latest trends and best practices for creating modern, responsive web applications.',
                'content' => 'Modern web applications require a combination of powerful backend frameworks and intuitive frontend technologies...',
                'author' => 'Jane Smith',
                'published_at' => '2024-01-10',
                'featured_image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&h=400&fit=crop',
            ],
            [
                'id' => 3,
                'title' => 'Laravel Best Practices',
                'slug' => 'laravel-best-practices',
                'excerpt' => 'Essential best practices for Laravel development to write clean, maintainable code.',
                'content' => 'Following best practices in Laravel development ensures your code is maintainable, scalable, and follows industry standards...',
                'author' => 'Mike Johnson',
                'published_at' => '2024-01-05',
                'featured_image' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&h=400&fit=crop',
            ],
        ];

        return view('blog.index', compact('posts'));
    }

    public function show($slug)
    {
        // Sample blog post - in a real application, this would come from a database
        $posts = [
            'getting-started-with-laravel-blade' => [
                'id' => 1,
                'title' => 'Getting Started with Laravel Blade',
                'slug' => 'getting-started-with-laravel-blade',
                'content' => '<h2>Introduction to Laravel Blade</h2>
                <p>Laravel Blade is a powerful templating engine that comes built-in with Laravel. It provides a clean, elegant syntax for writing templates that are both expressive and maintainable.</p>
                
                <h3>Key Features</h3>
                <ul>
                    <li><strong>Template Inheritance:</strong> Create master layouts and extend them in child templates</li>
                    <li><strong>Components:</strong> Build reusable UI components</li>
                    <li><strong>Directives:</strong> Use @if, @foreach, @include and more</li>
                    <li><strong>Escaping:</strong> Automatic XSS protection</li>
                </ul>
                
                <h3>Basic Syntax</h3>
                <p>Blade templates use double curly braces for output:</p>
                <pre><code>Hello, {{ $name }}!</code></pre>
                
                <p>And @ symbols for control structures:</p>
                <pre><code>@if($user)
    Welcome, {{ $user->name }}!
@endif</code></pre>
                
                <h3>Template Inheritance</h3>
                <p>One of Blade\'s most powerful features is template inheritance. You can define a master layout and extend it in your views:</p>
                
                <pre><code>@extends(\'layouts.app\')

@section(\'content\')
    <h1>Page Title</h1>
    <p>This is the page content.</p>
@endsection</code></pre>
                
                <p>This approach makes your templates more maintainable and reduces code duplication.</p>',
                'author' => 'John Doe',
                'published_at' => '2024-01-15',
                'featured_image' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=800&h=400&fit=crop',
            ],
            'building-modern-web-applications' => [
                'id' => 2,
                'title' => 'Building Modern Web Applications',
                'slug' => 'building-modern-web-applications',
                'content' => '<h2>Modern Web Development</h2>
                <p>Modern web applications require a combination of powerful backend frameworks and intuitive frontend technologies. Laravel provides an excellent foundation for building robust, scalable applications.</p>
                
                <h3>Backend Architecture</h3>
                <p>Laravel follows the MVC (Model-View-Controller) pattern, which helps organize code and separate concerns:</p>
                <ul>
                    <li><strong>Models:</strong> Handle data logic and database interactions</li>
                    <li><strong>Views:</strong> Present data to users (Blade templates)</li>
                    <li><strong>Controllers:</strong> Handle user input and coordinate between models and views</li>
                </ul>
                
                <h3>Frontend Integration</h3>
                <p>Modern Laravel applications often integrate with:</p>
                <ul>
                    <li>Vue.js or React for interactive components</li>
                    <li>Tailwind CSS for utility-first styling</li>
                    <li>Vite for fast asset compilation</li>
                </ul>
                
                <h3>API Development</h3>
                <p>Laravel makes it easy to build RESTful APIs:</p>
                <pre><code>Route::apiResource(\'posts\', PostController::class);</code></pre>
                
                <p>This single line creates all the standard CRUD routes for your API endpoints.</p>',
                'author' => 'Jane Smith',
                'published_at' => '2024-01-10',
                'featured_image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&h=400&fit=crop',
            ],
            'laravel-best-practices' => [
                'id' => 3,
                'title' => 'Laravel Best Practices',
                'slug' => 'laravel-best-practices',
                'content' => '<h2>Laravel Development Best Practices</h2>
                <p>Following best practices in Laravel development ensures your code is maintainable, scalable, and follows industry standards.</p>
                
                <h3>Code Organization</h3>
                <ul>
                    <li><strong>Use Service Classes:</strong> Move business logic out of controllers</li>
                    <li><strong>Repository Pattern:</strong> Abstract database operations</li>
                    <li><strong>Form Requests:</strong> Validate input in dedicated classes</li>
                    <li><strong>Events & Listeners:</strong> Decouple application logic</li>
                </ul>
                
                <h3>Database Best Practices</h3>
                <ul>
                    <li>Use migrations for database schema changes</li>
                    <li>Implement proper indexing for performance</li>
                    <li>Use Eloquent relationships effectively</li>
                    <li>Consider database query optimization</li>
                </ul>
                
                <h3>Security Considerations</h3>
                <ul>
                    <li>Always validate and sanitize user input</li>
                    <li>Use CSRF protection</li>
                    <li>Implement proper authentication and authorization</li>
                    <li>Keep dependencies updated</li>
                </ul>
                
                <h3>Testing</h3>
                <p>Laravel provides excellent testing tools:</p>
                <ul>
                    <li>Unit tests for individual components</li>
                    <li>Feature tests for complete workflows</li>
                    <li>Database factories for test data</li>
                    <li>HTTP tests for API endpoints</li>
                </ul>',
                'author' => 'Mike Johnson',
                'published_at' => '2024-01-05',
                'featured_image' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&h=400&fit=crop',
            ],
        ];

        $post = $posts[$slug] ?? null;

        if (!$post) {
            abort(404);
        }

        return view('blog.show', compact('post'));
    }
}
