@extends('layouts.app')

@section('title', 'Contact Us - Yachter | Premium Yacht & Boat Services')

@section('content')
<!-- Hero Section -->
<section class="relative py-24 bg-gradient-to-br from-blue-600 via-blue-500 to-blue-700 overflow-hidden">
    <!-- Background with friendly marina image -->
    <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80')] bg-cover bg-center bg-no-repeat"></div>
    <div class="absolute inset-0 bg-black/40"></div>
    <!-- Background pattern overlay -->
    <div class="absolute inset-0">
        <div class="absolute top-0 left-0 w-full h-full bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.05"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>
    </div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="max-w-4xl mx-auto">
            <div class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full text-white/90 text-sm font-semibold mb-8">
                <i class="fas fa-phone mr-2"></i>
                Get in Touch
            </div>
            
            <h1 class="text-4xl md:text-6xl font-bold mb-8 leading-tight">
                <span class="bg-gradient-to-r from-white via-blue-100 to-white bg-clip-text text-transparent">
                    {{ trans('messages.contact.hero.title') }}
                </span>
            </h1>
            
            <p class="text-xl md:text-2xl text-blue-100 mb-12 max-w-3xl mx-auto leading-relaxed">
                {{ trans('messages.contact.hero.subtitle') }}
            </p>
        </div>
    </div>
</section>

<!-- Contact Form Section -->
<section class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
            <!-- Contact Form -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="mb-8">
                    <div class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-600 rounded-full text-sm font-semibold mb-6">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Send us a Message
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ trans('messages.contact.form.title') }}</h2>
                    <p class="text-gray-600">We'll get back to you within 24 hours</p>
                </div>
                
                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-xl mb-8 flex items-center">
                        <i class="fas fa-check-circle mr-3 text-green-600"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-xl mb-8 flex items-center">
                        <i class="fas fa-exclamation-circle mr-3 text-red-600"></i>
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('contact.store') }}" method="POST" class="space-y-8">
                    @csrf
                    
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-3">
                            {{ trans('messages.contact.form.name') }} *
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               class="w-full px-6 py-4 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 focus:bg-white transition-all duration-300 @error('name') border-red-300 bg-red-50 @enderror"
                               placeholder="Enter your full name"
                               required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-3">
                            {{ trans('messages.contact.form.email') }} *
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               class="w-full px-6 py-4 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 focus:bg-white transition-all duration-300 @error('email') border-red-300 bg-red-50 @enderror"
                               placeholder="Enter your email address"
                               required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-semibold text-gray-700 mb-3">
                            {{ trans('messages.contact.form.subject') }} *
                        </label>
                        <input type="text" 
                               id="subject" 
                               name="subject" 
                               value="{{ old('subject') }}"
                               class="w-full px-6 py-4 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 focus:bg-white transition-all duration-300 @error('subject') border-red-300 bg-red-50 @enderror"
                               placeholder="What's this about?"
                               required>
                        @error('subject')
                            <p class="text-red-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-semibold text-gray-700 mb-3">
                            {{ trans('messages.contact.form.message') }} *
                        </label>
                        <textarea id="message" 
                                  name="message" 
                                  rows="6"
                                  class="w-full px-6 py-4 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 focus:bg-white transition-all duration-300 resize-none @error('message') border-red-300 bg-red-50 @enderror"
                                  placeholder="Tell us more about your inquiry..."
                                  required>{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-red-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-4 px-8 rounded-xl font-semibold text-lg hover:shadow-2xl hover:scale-105 transition-all duration-300 flex items-center justify-center group">
                        <i class="fas fa-paper-plane mr-3"></i>
                        {{ trans('messages.contact.form.send') }}
                        <i class="fas fa-arrow-right ml-3 group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </form>
            </div>

            <!-- Contact Information -->
            <div class="space-y-8">
                <div>
                    <div class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-600 rounded-full text-sm font-semibold mb-6">
                        <i class="fas fa-info-circle mr-2"></i>
                        Contact Information
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-6">{{ trans('messages.contact.info.title') }}</h3>
                    <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                        {{ trans('messages.contact.info.subtitle') }}
                    </p>
                </div>

                <div class="space-y-8">
                    <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300">
                        <div class="flex items-start space-x-6">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-envelope text-white text-2xl"></i>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-gray-900 mb-3">{{ trans('messages.contact.info.email') }}</h4>
                                <p class="text-gray-600 mb-2">mt@cat0.app</p>
                                <p class="text-gray-600">support@yachter.com</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300">
                        <div class="flex items-start space-x-6">
                            <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-phone text-white text-2xl"></i>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-gray-900 mb-3">{{ trans('messages.contact.info.phone') }}</h4>
                                <p class="text-gray-600 mb-2">+1 (555) 123-4567</p>
                                <p class="text-gray-600">+1 (555) 987-6543</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300">
                        <div class="flex items-start space-x-6">
                            <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-map-marker-alt text-white text-2xl"></i>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-gray-900 mb-3">{{ trans('messages.contact.info.address') }}</h4>
                                <p class="text-gray-600 mb-2">123 Marina Drive</p>
                                <p class="text-gray-600">Harbor City, HC 12345</p>
                            </div>
                        </div>
                    </div>
                </div>

                
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-20 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                {{ trans('messages.contact.faq.title') }}
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-300">
                {{ trans('messages.contact.faq.subtitle') }}
            </p>
        </div>

        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                    {{ trans('messages.contact.faq.response.title') }}
                </h3>
                <p class="text-gray-600 dark:text-gray-300">
                    {{ trans('messages.contact.faq.response.answer') }}
                </p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                    {{ trans('messages.contact.faq.emergency.title') }}
                </h3>
                <p class="text-gray-600 dark:text-gray-300">
                    {{ trans('messages.contact.faq.emergency.answer') }}
                </p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                    {{ trans('messages.contact.faq.info.title') }}
                </h3>
                <p class="text-gray-600 dark:text-gray-300">
                    {{ trans('messages.contact.faq.info.answer') }}
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
