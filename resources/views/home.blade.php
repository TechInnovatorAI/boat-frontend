@extends('layouts.app')

@section('title', 'Home - Boat Website')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">{{ trans('messages.home.hero.title') }}</h1>
            <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto">
                {{ trans('messages.home.hero.subtitle') }}
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('blog') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-300">
                    {{ trans('messages.home.hero.explore_blog') }}
                </a>
                <a href="{{ route('contact') }}" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition duration-300">
                    {{ trans('messages.home.hero.get_in_touch') }}
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-20 bg-white dark:bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                {{ trans('messages.home.features.title') }}
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                {{ trans('messages.home.features.subtitle') }}
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center p-6 rounded-lg bg-gray-50 dark:bg-gray-700">
                <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">{{ trans('messages.home.features.expert.title') }}</h3>
                <p class="text-gray-600 dark:text-gray-300">
                    {{ trans('messages.home.features.expert.description') }}
                </p>
            </div>
            
            <div class="text-center p-6 rounded-lg bg-gray-50 dark:bg-gray-700">
                <div class="w-16 h-16 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">{{ trans('messages.home.features.quality.title') }}</h3>
                <p class="text-gray-600 dark:text-gray-300">
                    {{ trans('messages.home.features.quality.description') }}
                </p>
            </div>
            
            <div class="text-center p-6 rounded-lg bg-gray-50 dark:bg-gray-700">
                <div class="w-16 h-16 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">{{ trans('messages.home.features.community.title') }}</h3>
                <p class="text-gray-600 dark:text-gray-300">
                    {{ trans('messages.home.features.community.description') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Latest Blog Posts -->
<section class="py-20 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                {{ trans('messages.home.blog.title') }}
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                {{ trans('messages.home.blog.subtitle') }}
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($latestPosts as $post)
            <article class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
                <img src="{{ $post['featured_image'] }}" alt="{{ $post['title'] }}" class="w-full h-48 object-cover">
                <div class="p-6">
                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-2">
                        <span>{{ $post['author'] }}</span>
                        <span class="mx-2">•</span>
                        <time datetime="{{ $post['published_at'] }}">{{ date('M j, Y', strtotime($post['published_at'])) }}</time>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2">
                        {{ $post['title'] }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-4 line-clamp-3">
                        {{ $post['excerpt'] }}
                    </p>
                    <a href="{{ route('blog.show', ['slug' => $post['slug'], 'lang' => $language]) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-semibold">
                        {{ trans('messages.home.blog.read_more') }} →
                    </a>
                </div>
            </article>
            @empty
            <div class="col-span-full text-center py-12">
                <div class="text-gray-400 dark:text-gray-500 mb-4">
                    <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                    {{ trans('messages.home.blog.no_posts') }}
                </h3>
                <p class="text-gray-600 dark:text-gray-300">
                    {{ trans('messages.home.blog.no_posts_desc') }}
                </p>
            </div>
            @endforelse
        </div>
        
        <div class="text-center mt-12">
            <a href="{{ route('blog', ['lang' => $language]) }}" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-300">
                {{ trans('messages.home.blog.view_all') }}
            </a>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-blue-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">{{ trans('messages.home.cta.title') }}</h2>
        <p class="text-xl mb-8 max-w-2xl mx-auto">
            {{ trans('messages.home.cta.subtitle') }}
        </p>
        <a href="{{ route('contact') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-300">
            {{ trans('messages.home.cta.contact') }}
        </a>
    </div>
</section>
@endsection
