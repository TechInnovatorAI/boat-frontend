@extends('layouts.app')

@section('title', 'Blog - Boat Website')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ trans('messages.blog.hero.title') }}</h1>
            <p class="text-xl max-w-2xl mx-auto">
                {{ trans('messages.blog.hero.subtitle') }}
            </p>
        </div>
    </div>
</section>

<!-- Language Indicator -->
<section class="py-8 bg-gray-50 dark:bg-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="flex justify-center items-center space-x-2 text-sm text-gray-600 dark:text-gray-300">
                <span>{{ trans('messages.blog.current_language') }}:</span>
                <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full font-medium">
                    {{ $language === 'en' ? 'English' : ($language === 'fr' ? 'Français' : ($language === 'de' ? 'Deutsch' : 'Italiano')) }}
                </span>
                <span>•</span>
                <span>{{ count($posts) }} {{ trans('messages.blog.posts_available') }}</span>
            </div>
        </div>
    </div>
</section>

<!-- Blog Posts -->
<section class="py-20 bg-white dark:bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($posts as $post)
            <article class="bg-white dark:bg-gray-700 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
                <img src="{{ $post['featured_image'] }}" alt="{{ $post['title'] }}" class="w-full h-48 object-cover">
                <div class="p-6">
                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-2">
                        <span>{{ $post['author'] }}</span>
                        <span class="mx-2">•</span>
                        <time datetime="{{ $post['published_at'] }}">{{ date('M j, Y', strtotime($post['published_at'])) }}</time>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3 line-clamp-2">
                        {{ $post['title'] }}
                    </h2>
                    <p class="text-gray-600 dark:text-gray-300 mb-4 line-clamp-3">
                        {{ $post['excerpt'] }}
                    </p>
                    <a href="{{ route('blog.show', ['slug' => $post['slug'], 'lang' => $language]) }}" 
                       class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-semibold">
                        {{ trans('messages.blog.read_more') }}
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </article>
            @endforeach
        </div>

        <!-- Newsletter Signup -->
        <div class="mt-16 bg-gray-50 dark:bg-gray-700 rounded-lg p-8 text-center">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                {{ trans('messages.blog.newsletter.title') }}
            </h3>
            <p class="text-gray-600 dark:text-gray-300 mb-6 max-w-2xl mx-auto">
                {{ trans('messages.blog.newsletter.subtitle') }}
            </p>
            <div class="max-w-md mx-auto flex gap-4">
                <input type="email" 
                       placeholder="{{ trans('messages.blog.newsletter.placeholder') }}" 
                       class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-600 dark:text-white">
                <button class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-300">
                    {{ trans('messages.blog.newsletter.subscribe') }}
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-20 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                {{ trans('messages.blog.categories.title') }}
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-300">
                {{ trans('messages.blog.categories.subtitle') }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 text-center hover:shadow-lg transition duration-300">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ trans('messages.blog.categories.laravel') }}</h3>
                <p class="text-gray-600 dark:text-gray-300 text-sm">{{ trans('messages.blog.categories.laravel.desc') }}</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 text-center hover:shadow-lg transition duration-300">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ trans('messages.blog.categories.webdev') }}</h3>
                <p class="text-gray-600 dark:text-gray-300 text-sm">{{ trans('messages.blog.categories.webdev.desc') }}</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 text-center hover:shadow-lg transition duration-300">
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ trans('messages.blog.categories.performance') }}</h3>
                <p class="text-gray-600 dark:text-gray-300 text-sm">{{ trans('messages.blog.categories.performance.desc') }}</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 text-center hover:shadow-lg transition duration-300">
                <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ trans('messages.blog.categories.best_practices') }}</h3>
                <p class="text-gray-600 dark:text-gray-300 text-sm">{{ trans('messages.blog.categories.best_practices.desc') }}</p>
            </div>
        </div>
    </div>
</section>
@endsection
