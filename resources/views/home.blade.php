@extends('layouts.app')

@section('title', 'Home - Yachter | Premium Yacht & Boat Services')

@section('content')
<!-- Hero Section -->
<section class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <!-- Background with friendly yacht image -->
    <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-blue-500 to-blue-700"></div>
    <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80')] bg-cover bg-center bg-no-repeat"></div>
    <div class="absolute inset-0 bg-black/30"></div>
    
    <!-- Animated background elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-400/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-300/10 rounded-full blur-3xl animate-pulse delay-1000"></div>
        
        <!-- Floating yacht elements -->
        <div class="floating-element absolute top-1/4 left-1/4 w-8 h-8 bg-white/20 rounded-full animate-float"></div>
        <div class="floating-element absolute top-1/3 right-1/3 w-6 h-6 bg-blue-300/30 rounded-full animate-float delay-500"></div>
        <div class="floating-element absolute bottom-1/4 left-1/3 w-4 h-4 bg-white/30 rounded-full animate-float delay-1000"></div>
        
        <!-- Wave animation overlay -->
        <div class="absolute inset-0 wave-animation"></div>
    </div>
    
    <!-- Content -->
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="max-w-4xl mx-auto">
            <!-- Badge -->
            <div class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full text-white/90 text-sm font-medium mb-8">
                <i class="fas fa-anchor mr-2"></i>
                Premium Yacht Services
            </div>
            
            <!-- Main heading -->
            <h1 class="text-5xl md:text-7xl lg:text-8xl font-bold mb-8 leading-tight">
                <span class="bg-gradient-to-r from-white via-blue-100 to-white bg-clip-text text-transparent">
                    {{ trans('messages.home.hero.title') }}
                </span>
            </h1>
            
            <!-- Subtitle -->
            <p class="text-xl md:text-2xl text-blue-100 mb-12 max-w-3xl mx-auto leading-relaxed">
                {{ trans('messages.home.hero.subtitle') }}
            </p>
            
            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-6 justify-center items-center mb-16">
                <a href="{{ route('contact') }}" class="group bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-4 rounded-full font-semibold text-lg hover:shadow-2xl hover:scale-105 transition-all duration-300 flex items-center">
                    <i class="fas fa-phone mr-3"></i>
                    {{ trans('messages.home.hero.get_in_touch') }}
                    <i class="fas fa-arrow-right ml-3 group-hover:translate-x-1 transition-transform"></i>
                </a>
                <a href="{{ route('blog') }}" class="group bg-white/10 backdrop-blur-sm text-white px-8 py-4 rounded-full font-semibold text-lg hover:bg-white/20 transition-all duration-300 flex items-center">
                    <i class="fas fa-book mr-3"></i>
                    {{ trans('messages.home.hero.explore_blog') }}
                </a>
            </div>
            
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-2xl mx-auto">
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-bold text-white mb-2">500+</div>
                    <div class="text-blue-200">Happy Clients</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-bold text-white mb-2">50+</div>
                    <div class="text-blue-200">Luxury Yachts</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-bold text-white mb-2">24/7</div>
                    <div class="text-blue-200">Support</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scroll indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <i class="fas fa-chevron-down text-white/60 text-2xl"></i>
    </div>
</section>

<!-- Features Section -->
<section class="relative py-24 bg-gray-50 overflow-hidden">
    <!-- Background pattern -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23f8fafc" fill-opacity="0.3"%3E%3Ccircle cx="30" cy="30" r="1"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-20">
            <div class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-600 rounded-full text-sm font-semibold mb-6">
                <i class="fas fa-star mr-2"></i>
                Why Choose Yachter
            </div>
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                {{ trans('messages.home.features.title') }}
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                {{ trans('messages.home.features.subtitle') }}
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-anchor text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ trans('messages.home.features.expert.title') }}</h3>
                <p class="text-gray-600 leading-relaxed">
                    {{ trans('messages.home.features.expert.description') }}
                </p>
            </div>
            
            <!-- Feature 2 -->
            <div class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-shield-alt text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ trans('messages.home.features.quality.title') }}</h3>
                <p class="text-gray-600 leading-relaxed">
                    {{ trans('messages.home.features.quality.description') }}
                </p>
            </div>
            
            <!-- Feature 3 -->
            <div class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-users text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ trans('messages.home.features.community.title') }}</h3>
                <p class="text-gray-600 leading-relaxed">
                    {{ trans('messages.home.features.community.description') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Latest Blog Posts -->
<section class="relative py-24 bg-white overflow-hidden">
    <!-- Background with friendly ocean theme -->
    <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80')] bg-cover bg-center bg-no-repeat opacity-8"></div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-20">
            <div class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-600 rounded-full text-sm font-semibold mb-6">
                <i class="fas fa-newspaper mr-2"></i>
                Latest News
            </div>
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                {{ trans('messages.home.blog.title') }}
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                {{ trans('messages.home.blog.subtitle') }}
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($latestPosts as $post)
            <article class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                <div class="relative overflow-hidden">
                    <img src="{{ $post['featured_image'] }}" alt="{{ $post['title'] }}" class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    <div class="absolute top-4 left-4">
                        <span class="bg-white/90 backdrop-blur-sm text-gray-900 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ date('M j', strtotime($post['published_at'])) }}
                        </span>
                    </div>
                </div>
                <div class="p-8">
                    <div class="flex items-center text-sm text-gray-500 mb-4">
                        <i class="fas fa-user mr-2"></i>
                        <span>{{ $post['author'] }}</span>
                        <span class="mx-2">•</span>
                        <i class="fas fa-clock mr-2"></i>
                        <time datetime="{{ $post['published_at'] }}">{{ date('M j, Y', strtotime($post['published_at'])) }}</time>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 line-clamp-2 group-hover:text-blue-600 transition-colors">
                        {{ $post['title'] }}
                    </h3>
                    <p class="text-gray-600 mb-6 line-clamp-3 leading-relaxed">
                        {{ $post['excerpt'] }}
                    </p>
                    <a href="{{ route('blog.show', ['slug' => $post['slug'], 'lang' => $language]) }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold group">
                        {{ trans('messages.home.blog.read_more') }}
                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </article>
            @empty
            <div class="col-span-full text-center py-16">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-newspaper text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">
                    {{ trans('messages.home.blog.no_posts') }}
                </h3>
                <p class="text-gray-600 text-lg">
                    {{ trans('messages.home.blog.no_posts_desc') }}
                </p>
            </div>
            @endforelse
        </div>
        
        <div class="text-center mt-16">
            <a href="{{ route('blog', ['lang' => $language]) }}" class="group bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-4 rounded-full font-semibold text-lg hover:shadow-2xl hover:scale-105 transition-all duration-300 inline-flex items-center">
                {{ trans('messages.home.blog.view_all') }}
                <i class="fas fa-arrow-right ml-3 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="relative py-24 bg-gradient-to-br from-blue-600 via-blue-500 to-blue-700 overflow-hidden parallax">
    <!-- Background with friendly sunset yacht image -->
    <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80')] bg-cover bg-center bg-no-repeat"></div>
    <div class="absolute inset-0 bg-gradient-to-br from-blue-600/80 via-blue-500/80 to-blue-700/80"></div>
    
    <!-- Background elements -->
    <div class="absolute inset-0">
        <div class="absolute top-0 left-0 w-full h-full bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.05"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>
    </div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="max-w-4xl mx-auto">
            <div class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full text-white/90 text-sm font-semibold mb-8">
                <i class="fas fa-phone mr-2"></i>
                Ready to Set Sail?
            </div>
            
            <h2 class="text-4xl md:text-6xl font-bold mb-8 leading-tight">
                <span class="bg-gradient-to-r from-white via-blue-100 to-white bg-clip-text text-transparent">
                    {{ trans('messages.home.cta.title') }}
                </span>
            </h2>
            
            <p class="text-xl md:text-2xl text-blue-100 mb-12 max-w-3xl mx-auto leading-relaxed">
                {{ trans('messages.home.cta.subtitle') }}
            </p>
            
            <div class="flex flex-col sm:flex-row gap-6 justify-center items-center">
                <a href="{{ route('contact') }}" class="group bg-white text-blue-600 px-8 py-4 rounded-full font-semibold text-lg hover:shadow-2xl hover:scale-105 transition-all duration-300 flex items-center">
                    <i class="fas fa-phone mr-3"></i>
                    {{ trans('messages.home.cta.contact') }}
                    <i class="fas fa-arrow-right ml-3 group-hover:translate-x-1 transition-transform"></i>
                </a>
                <a href="{{ route('blog') }}" class="group bg-white/10 backdrop-blur-sm text-white px-8 py-4 rounded-full font-semibold text-lg hover:bg-white/20 transition-all duration-300 flex items-center">
                    <i class="fas fa-book mr-3"></i>
                    Explore Our Blog
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
