@extends('layouts.app')

@section('title', $post['title'] . ' - Boat Website')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $post['title'] }}</h1>
            <div class="flex items-center justify-center text-blue-100 space-x-4">
                <span>{{ trans('messages.blog.by') }} {{ $post['author'] }}</span>
                <span>•</span>
                <time datetime="{{ $post['published_at'] }}">{{ date('F j, Y', strtotime($post['published_at'])) }}</time>
            </div>
        </div>
    </div>
</section>

<!-- Blog Post Content -->
<article class="py-20 bg-white dark:bg-gray-800">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Featured Image -->
        <div class="mb-12">
            <img src="{{ $post['featured_image'] }}" 
                 alt="{{ $post['title'] }}" 
                 class="w-full h-64 md:h-96 object-cover rounded-lg shadow-lg">
        </div>

        <!-- Post Content -->
        <div class="prose prose-lg max-w-none dark:prose-invert">
            {!! $post['content'] !!}
        </div>

        <!-- Author Bio -->
        <div class="mt-16 bg-gray-50 dark:bg-gray-700 rounded-lg p-8">
            <div class="flex items-start space-x-4">
                <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                    <span class="text-blue-600 dark:text-blue-400 font-bold text-xl">
                        {{ substr($post['author'], 0, 1) }}
                    </span>
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                        {{ trans('messages.blog.post.author.about') }} {{ $post['author'] }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        {{ $post['author'] }} {{ trans('messages.blog.post.author.bio') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</article>

<!-- Related Posts -->
<section class="py-20 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                {{ trans('messages.blog.post.related.title') }}
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-300">
                {{ trans('messages.blog.post.related.subtitle') }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($relatedPosts as $relatedPost)
            <article class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
                <img src="{{ $relatedPost['featured_image'] }}" alt="{{ $relatedPost['title'] }}" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                        {{ $relatedPost['title'] }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">
                        {{ $relatedPost['excerpt'] }}
                    </p>
                    <a href="{{ route('blog.show', ['slug' => $relatedPost['slug'], 'lang' => $language]) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-semibold">
                        {{ trans('messages.blog.read_more') }} →
                    </a>
                </div>
            </article>
            @empty
            <div class="col-span-full text-center py-8">
                <p class="text-gray-500 dark:text-gray-400">{{ trans('messages.blog.no_related_posts') }}</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Navigation -->
<section class="py-12 bg-white dark:bg-gray-800">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <a href="{{ route('blog', ['lang' => $language]) }}" class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-semibold">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                {{ trans('messages.blog.post.nav.back') }}
            </a>
            
            <div class="flex space-x-4">
                <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                    {{ trans('messages.blog.post.nav.share') }}
                </button>
                <button class="border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-6 py-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-300">
                    {{ trans('messages.blog.post.nav.bookmark') }}
                </button>
            </div>
        </div>
    </div>
</section>
@endsection
