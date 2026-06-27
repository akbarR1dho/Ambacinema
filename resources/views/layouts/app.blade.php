<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ambacinema</title>
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
                        <span class="text-3xl font-extrabold text-blue-600 tracking-tighter uppercase italic">Amba<span class="text-slate-900">cinema</span></span>
                    </a>
                    <!-- <div class="hidden md:ml-10 md:flex md:space-x-8">
                        <a href="{{ route('home') }}" class="text-slate-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors uppercase tracking-wider">Now Playing</a>
                        <a href="#" class="text-slate-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors uppercase tracking-wider">Theaters</a>
                    </div> -->
                </div>
                <div class="flex items-center">
                    @auth
                        <div class="relative ml-3 group">
                            <button class="flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 focus:outline-none">
                                <span>{{ Auth::user()->name }}</span>
                                <svg class="ml-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            </button>
                            <div class="absolute right-0 pt-2 w-48 hidden group-hover:block z-50">
                                <div class="bg-white rounded-md shadow-lg py-1 ring-1 ring-slate-900 ring-opacity-5 border border-slate-200">
                                    @if(Auth::user()->role === 'admin')
                                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">Admin Panel</a>
                                    @endif
                                    <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">My Tickets</a>
                                    <div class="border-t border-slate-100 my-1"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors font-medium">Sign out</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-slate-600 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors">Log in</a>
                        <a href="{{ route('register') }}" class="ml-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">Sign up</a>
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
    <footer class="bg-white border-t border-slate-200 py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center flex-col md:flex-row">
                <div class="mb-4 md:mb-0">
                    <span class="text-3xl font-extrabold text-blue-600 tracking-tighter uppercase italic">Amba<span class="text-slate-900">cinema</span></span>
                    <p class="text-slate-500 text-sm mt-1">© {{ date('Y') }} Ambacinema. All rights reserved.</p>
                </div>
                <div class="flex space-x-6">
                    <a href="#" class="text-slate-500 hover:text-blue-600 transition-colors">About Us</a>
                    <a href="#" class="text-slate-500 hover:text-blue-600 transition-colors">Terms of Use</a>
                    <a href="#" class="text-slate-500 hover:text-blue-600 transition-colors">Privacy Policy</a>
                    <a href="#" class="text-slate-500 hover:text-blue-600 transition-colors">Contact</a>
                </div>
            </div>
        </div>
    </footer>
    @stack('scripts')
</body>
</html>
