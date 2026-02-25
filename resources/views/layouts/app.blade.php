<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Yachter - Premium Yacht & Boat Services')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=playfair-display:400,500,600,700|inter:300,400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-white text-gray-900 overflow-x-hidden">
    <!-- Navigation -->
    <nav class="fixed w-full top-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-100" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-blue-800 rounded-lg flex items-center justify-center">
                            <i class="fas fa-anchor text-white text-lg"></i>
                        </div>
                        <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">
                            Yachter
                        </span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden lg:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors duration-300">{{ trans('messages.nav.home') }}</a>
                    <a href="{{ route('blog') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors duration-300">{{ trans('messages.nav.blog') }}</a>
                    <a href="{{ route('contact') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors duration-300">{{ trans('messages.nav.contact') }}</a>
                    <x-language-switcher :current-locale="app()->getLocale()" />
                    <a href="{{ route('contact') }}" class="bg-gradient-to-r from-blue-600 to-blue-800 text-white px-6 py-3 rounded-full font-semibold hover:shadow-lg hover:scale-105 transition-all duration-300">
                        Get Started
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="lg:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-700 hover:text-blue-600 focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div x-show="mobileMenuOpen" x-transition class="lg:hidden border-t border-gray-100 py-4">
                <div class="flex flex-col space-y-4">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 font-medium">{{ trans('messages.nav.home') }}</a>
                    <a href="{{ route('blog') }}" class="text-gray-700 hover:text-blue-600 font-medium">{{ trans('messages.nav.blog') }}</a>
                    <a href="{{ route('contact') }}" class="text-gray-700 hover:text-blue-600 font-medium">{{ trans('messages.nav.contact') }}</a>
                    <div class="pt-4">
                        <x-language-switcher :current-locale="app()->getLocale()" />
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-20">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-br from-gray-900 via-blue-900 to-gray-900 text-white">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-anchor text-white text-lg"></i>
                        </div>
                        <span class="text-2xl font-bold">Yachter</span>
                    </div>
                    <p class="text-gray-300 mb-6 max-w-md">
                        Experience the ultimate luxury on the water with our premium yacht and boat services. 
                        Your journey to maritime excellence starts here.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-6">{{ trans('messages.footer.quick_links') }}</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition-colors">{{ trans('messages.nav.home') }}</a></li>
                        <li><a href="{{ route('blog') }}" class="text-gray-300 hover:text-white transition-colors">{{ trans('messages.nav.blog') }}</a></li>
                        <li><a href="{{ route('contact') }}" class="text-gray-300 hover:text-white transition-colors">{{ trans('messages.nav.contact') }}</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Services</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Fleet</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h3 class="text-lg font-semibold mb-6">{{ trans('messages.footer.contact_info') }}</h3>
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-envelope text-blue-400"></i>
                            <span class="text-gray-300">mt@cat0.app</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-phone text-blue-400"></i>
                            <span class="text-gray-300">+1 (555) 123-4567</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-map-marker-alt text-blue-400"></i>
                            <span class="text-gray-300">Marina Bay, Harbor City</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-12 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-400 text-sm">
                        &copy; 2025 Yachter. {{ trans('messages.footer.copyright') }}
                    </p>
                    <div class="flex space-x-6 mt-4 md:mt-0">
                        <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Privacy Policy</a>
                        <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Terms of Service</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
