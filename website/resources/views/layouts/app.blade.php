<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Boat Website')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    <!-- Navigation -->
    <nav class="bg-white dark:bg-gray-800 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-xl font-bold text-gray-800 dark:text-white">
                        Boat Website
                    </a>
                </div>
                <div class="flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium">{{ trans('messages.nav.home') }}</a>
                    <a href="{{ route('blog') }}" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium">{{ trans('messages.nav.blog') }}</a>
                    <a href="{{ route('contact') }}" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium">{{ trans('messages.nav.contact') }}</a>
                    <x-language-switcher :current-locale="app()->getLocale()" />
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 dark:bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">{{ trans('messages.footer.title') }}</h3>
                    <p class="text-gray-300">{{ trans('messages.footer.description') }}</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">{{ trans('messages.footer.quick_links') }}</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-white">{{ trans('messages.nav.home') }}</a></li>
                        <li><a href="{{ route('blog') }}" class="text-gray-300 hover:text-white">{{ trans('messages.nav.blog') }}</a></li>
                        <li><a href="{{ route('contact') }}" class="text-gray-300 hover:text-white">{{ trans('messages.nav.contact') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">{{ trans('messages.footer.contact_info') }}</h3>
                    <p class="text-gray-300">Email: info@boatwebsite.com</p>
                    <p class="text-gray-300">Phone: (555) 123-4567</p>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                <p class="text-gray-300">&copy; 2025 Boat Website. {{ trans('messages.footer.copyright') }}</p>
            </div>
        </div>
    </footer>
</body>
</html>
