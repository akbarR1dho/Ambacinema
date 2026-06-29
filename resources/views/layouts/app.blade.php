<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Primary Meta Tags -->
    <title>@yield('title', 'Ambacinema - Premium Movie Booking')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <meta name="title" content="@yield('title', 'Ambacinema - Premium Movie Booking')">
    <meta name="description" content="@yield('meta_description', 'Experience the magic of cinema with cutting-edge technology and premium comfort. Book your tickets easily and enjoy the show!')">
    <meta name="keywords" content="@yield('meta_keywords', 'cinema, movies, tickets, booking, ambacinema')">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'Ambacinema - Premium Movie Booking')">
    <meta property="og:description" content="@yield('meta_description', 'Experience the magic of cinema with cutting-edge technology and premium comfort. Book your tickets easily and enjoy the show!')">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', 'Ambacinema - Premium Movie Booking')">
    <meta property="twitter:description" content="@yield('meta_description', 'Experience the magic of cinema with cutting-edge technology and premium comfort. Book your tickets easily and enjoy the show!')">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Cinema Theme Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f8fafc; }
        ::-webkit-scrollbar-thumb { background: #2563eb; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #1d4ed8; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased min-h-screen flex flex-col">
    <!-- Navbar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 sm:h-10 mr-3 sm:mr-4">
                        <span class="hidden md:block text-xl sm:text-2xl font-extrabold text-blue-600 tracking-tighter uppercase italic">Amba<span class="text-slate-900">cinema</span></span>
                    </a>
                    <!-- <div class="hidden md:ml-10 md:flex md:space-x-8">
                        <a href="{{ route('home') }}" class="text-slate-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors uppercase tracking-wider">Now Playing</a>
                        <a href="#" class="text-slate-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors uppercase tracking-wider">Theaters</a>
                    </div> -->
                </div>
                <div class="flex items-center">
                    <!-- Language Switcher -->
                    <div class="relative mr-3 sm:mr-5">
                        <button id="lang-menu-btn" class="flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 focus:outline-none bg-slate-100 px-2 py-1 rounded-md border border-slate-200">
                            @if(App::getLocale() === 'id')
                                <svg class="w-4 h-4 mr-1.5 rounded-sm object-cover border border-slate-200" viewBox="0 0 640 480" xmlns="http://www.w3.org/2000/svg"><path fill="#e00000" d="M0 0h640v240H0z"/><path fill="#fff" d="M0 240h640v240H0z"/></svg>
                            @else
                                <svg class="w-4 h-4 mr-1.5 rounded-sm object-cover border border-slate-200" viewBox="0 0 640 480" xmlns="http://www.w3.org/2000/svg"><path fill="#012169" d="M0 0h640v480H0z"/><path fill="#FFF" d="m75 0 244 181L562 0h78v62L400 241l240 178v61h-80L320 301 81 480H0v-60l239-178L0 64V0z"/><path fill="#C8102E" d="m424 281 216 159v40L369 281zm-184 20 6 35L22 480H0zM640 0v3L391 191l2-44L590 0zM0 0l239 176h-60L0 42z"/><path fill="#FFF" d="M241 0v480h160V0zM0 160v160h640V160z"/><path fill="#C8102E" d="M0 193v96h640v-96zM273 0v480h96V0z"/></svg>
                            @endif
                            <span class="uppercase font-bold">{{ App::getLocale() }}</span>
                            <svg class="ml-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </button>
                        <div id="lang-menu" class="absolute right-0 mt-2 w-28 hidden z-50">
                            <div class="bg-white rounded-md shadow-lg py-1 ring-1 ring-slate-900 ring-opacity-5 border border-slate-200">
                                <a href="{{ route('lang.switch', 'id') }}" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition-colors {{ App::getLocale() === 'id' ? 'font-bold text-blue-600' : '' }}">
                                    <svg class="w-4 h-4 mr-2 rounded-sm object-cover border border-slate-200" viewBox="0 0 640 480" xmlns="http://www.w3.org/2000/svg"><path fill="#e00000" d="M0 0h640v240H0z"/><path fill="#fff" d="M0 240h640v240H0z"/></svg> ID
                                </a>
                                <a href="{{ route('lang.switch', 'en') }}" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition-colors {{ App::getLocale() === 'en' ? 'font-bold text-blue-600' : '' }}">
                                    <svg class="w-4 h-4 mr-2 rounded-sm object-cover border border-slate-200" viewBox="0 0 640 480" xmlns="http://www.w3.org/2000/svg"><path fill="#012169" d="M0 0h640v480H0z"/><path fill="#FFF" d="m75 0 244 181L562 0h78v62L400 241l240 178v61h-80L320 301 81 480H0v-60l239-178L0 64V0z"/><path fill="#C8102E" d="m424 281 216 159v40L369 281zm-184 20 6 35L22 480H0zM640 0v3L391 191l2-44L590 0zM0 0l239 176h-60L0 42z"/><path fill="#FFF" d="M241 0v480h160V0zM0 160v160h640V160z"/><path fill="#C8102E" d="M0 193v96h640v-96zM273 0v480h96V0z"/></svg> EN
                                </a>
                            </div>
                        </div>
                    </div>

                    @auth
                        <div class="relative ml-1 sm:ml-3">
                            <button id="user-menu-btn" class="flex items-center space-x-3">
                                <div class="h-9 w-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold ring-2 ring-white shadow-sm hover:ring-blue-300 transition-all">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <!-- <svg class="w-4 h-4 text-slate-400 hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg> -->
                            </button>
                            <div id="user-menu" class="absolute right-0 mt-2 w-48 hidden z-50">
                                <div class="bg-white rounded-md shadow-lg py-1 ring-1 ring-slate-900 ring-opacity-5 border border-slate-200">
                                    @if(Auth::user()->role === 'admin')
                                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">{{ __('Admin Panel') }}</a>
                                    @else
                                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">{{ __('Dashboard') }}</a>
                                    @endif
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition-colors font-medium">{{ __('My Profile') }}</a>
                                    <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">{{ __('My Tickets') }}</a>
                                    <div class="border-t border-slate-100 my-1"></div>
                                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                        @csrf
                                        <button type="button" onclick="confirmLogout(event)" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors font-medium">{{ __('Sign out') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-slate-600 hover:text-blue-600 px-2 sm:px-3 py-2 text-sm font-medium transition-colors whitespace-nowrap">{{ __('Log in') }}</a>
                        <a href="{{ route('register') }}" class="ml-2 sm:ml-4 bg-blue-600 hover:bg-blue-700 text-white px-3 sm:px-4 py-1.5 sm:py-2 rounded-md text-sm font-medium transition-colors whitespace-nowrap">{{ __('Sign up') }}</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            </div>
        @endif
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 border-t border-slate-800 pt-10 md:pt-16 pb-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 md:gap-12 mb-10 md:mb-12">
                <div class="md:col-span-2 text-center md:text-left flex flex-col items-center md:items-start">
                    <div class="flex items-center mb-4">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 sm:h-12 mr-4 sm:mr-5">
                        <span class="text-2xl sm:text-3xl font-extrabold text-blue-500 tracking-tighter uppercase italic">Amba<span class="text-white">cinema</span></span>
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed max-w-sm mt-2">
                        {{ __('Experience the magic of cinema with cutting-edge technology and premium comfort. Book your tickets easily and enjoy the show!') }}
                    </p>
                </div>
                <div class="md:col-span-2 grid grid-cols-2 gap-8 md:gap-12">
                    <div>
                        <h4 class="text-white font-bold mb-4 uppercase tracking-wider text-sm">{{ __('Quick Links') }}</h4>
                        <ul class="space-y-3">
                            <li><a href="#" class="text-slate-400 hover:text-blue-400 transition-colors text-sm flex items-center"><span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-2 md:opacity-0 md:hover:opacity-100 transition-opacity hidden md:block"></span>{{ __('Now Playing') }}</a></li>
                            <li><a href="#" class="text-slate-400 hover:text-blue-400 transition-colors text-sm flex items-center"><span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-2 md:opacity-0 md:hover:opacity-100 transition-opacity hidden md:block"></span>{{ __('Theaters') }}</a></li>
                            <li><a href="#" class="text-slate-400 hover:text-blue-400 transition-colors text-sm flex items-center"><span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-2 md:opacity-0 md:hover:opacity-100 transition-opacity hidden md:block"></span>{{ __('Promotions') }}</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white font-bold mb-4 uppercase tracking-wider text-sm">{{ __('Support') }}</h4>
                        <ul class="space-y-3">
                            <li><a href="#" class="text-slate-400 hover:text-blue-400 transition-colors text-sm flex items-center"><span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-2 md:opacity-0 md:hover:opacity-100 transition-opacity hidden md:block"></span>{{ __('Help Center') }}</a></li>
                            <li><a href="#" class="text-slate-400 hover:text-blue-400 transition-colors text-sm flex items-center"><span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-2 md:opacity-0 md:hover:opacity-100 transition-opacity hidden md:block"></span>{{ __('Terms of Use') }}</a></li>
                            <li><a href="#" class="text-slate-400 hover:text-blue-400 transition-colors text-sm flex items-center"><span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-2 md:opacity-0 md:hover:opacity-100 transition-opacity hidden md:block"></span>{{ __('Privacy Policy') }}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-slate-500 text-sm mb-4 md:mb-0">© {{ date('Y') }} Ambacinema. All rights reserved.</p>
                <div class="flex space-x-4">
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-blue-600 hover:text-white transition-all transform hover:scale-110">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-blue-600 hover:text-white transition-all transform hover:scale-110">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>
    <script>
        document.addEventListener('click', function(event) {
            // User Menu
            const dropdownBtn = document.getElementById('user-menu-btn');
            const dropdownMenu = document.getElementById('user-menu');
            if (dropdownBtn && dropdownMenu) {
                const isClickInside = dropdownBtn.contains(event.target) || dropdownMenu.contains(event.target);
                if (!isClickInside) {
                    dropdownMenu.classList.add('hidden');
                } else if (dropdownBtn.contains(event.target)) {
                    dropdownMenu.classList.toggle('hidden');
                }
            }
            
            // Lang Menu
            const langBtn = document.getElementById('lang-menu-btn');
            const langMenu = document.getElementById('lang-menu');
            if (langBtn && langMenu) {
                const isClickInsideLang = langBtn.contains(event.target) || langMenu.contains(event.target);
                if (!isClickInsideLang) {
                    langMenu.classList.add('hidden');
                } else if (langBtn.contains(event.target)) {
                    langMenu.classList.toggle('hidden');
                }
            }
        });
    </script>
    <!-- SweetAlert2 for Logout -->
    <script>
        function confirmLogout(event) {
            event.preventDefault();
            Swal.fire({
                title: '<div class="flex items-center justify-center"><img src="{{ asset("images/logo.png") }}" alt="Logo" class="h-10 mr-4"><span class="text-2xl font-extrabold text-blue-500 tracking-tighter uppercase italic">Amba<span class="text-white">cinema</span></span></div>',
                html: '<p class="text-slate-400 mt-2">{{ __("Are you sure you want to log out?") }}</p>',
                icon: 'question',
                iconColor: '#3b82f6',
                showCancelButton: true,
                confirmButtonText: '{{ __("Yes, Log Out") }}',
                cancelButtonText: '{{ __("Cancel") }}',
                background: '#0f172a',
                buttonsStyling: false,
                customClass: {
                    popup: 'border border-slate-800 rounded-2xl shadow-2xl',
                    confirmButton: 'bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-xl transition-colors w-full sm:w-auto',
                    cancelButton: 'bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold py-2.5 px-6 rounded-xl transition-colors w-full sm:w-auto mt-3 sm:mt-0 sm:ml-3',
                    actions: 'w-full flex flex-col sm:flex-row justify-center mt-6'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.closest('form').submit();
                }
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
