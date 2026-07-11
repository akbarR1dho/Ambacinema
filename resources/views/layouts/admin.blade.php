<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Ambacinema</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- DataTables CSS via CDN -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <style>
        /* Custom scrollbar for cinema theme */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f8fafc; }
        ::-webkit-scrollbar-thumb { background: #2563eb; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #1d4ed8; }
        
        /* Webkit Date/Time Picker Fix for Light Theme */
        input[type="date"]::-webkit-calendar-picker-indicator,
        input[type="time"]::-webkit-calendar-picker-indicator,
        input[type="datetime-local"]::-webkit-calendar-picker-indicator {
            cursor: pointer;
        }

        /* Modern DataTables Tailwind Styling */
        .dataTables_wrapper { padding: 0.5rem 0; color: #64748b; font-size: 0.875rem; font-family: inherit; }
        
        /* Top Controls: Search and Length */
        .dataTables_wrapper .dataTables_length { color: #475569; margin-bottom: 1.25rem; float: left; }
        .dataTables_wrapper .dataTables_filter { color: #475569; margin-bottom: 1.25rem; float: right; }
        
        .dataTables_wrapper .dataTables_filter input {
            background-color: #fff;
            color: #0f172a;
            border: 1px solid #cbd5e1;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            margin-left: 0.75rem;
            outline: none;
            transition: all 0.2s ease-in-out;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
        }
        .dataTables_wrapper .dataTables_length select {
            background-color: #fff;
            color: #0f172a;
            border: 1px solid #cbd5e1;
            border-radius: 0.5rem;
            padding: 0.375rem 2rem 0.375rem 1rem;
            outline: none;
            margin: 0 0.5rem;
            cursor: pointer;
        }

        /* Table Core */
        table.dataTable { 
            border-collapse: collapse !important; 
            width: 100% !important; 
            margin: 1rem 0 !important; 
            border-radius: 0.5rem;
            overflow: hidden;
        }
        table.dataTable thead th, table.dataTable thead td {
            padding: 1rem 1.25rem !important;
            border-bottom: 1px solid #e2e8f0 !important;
            font-weight: 600 !important;
            color: #475569 !important;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            background-color: #f8fafc !important;
        }
        
        table.dataTable tbody tr { 
            background-color: #fff !important; 
            transition: background-color 0.15s ease-in-out;
        }
        table.dataTable tbody tr:hover { 
            background-color: #f1f5f9 !important;
        }
        table.dataTable tbody td {
            padding: 1rem 1.25rem !important;
            border-bottom: 1px solid #e2e8f0 !important;
            color: #334155 !important;
            vertical-align: middle;
        }
        table.dataTable.no-footer { border-bottom: none !important; }

        /* Pagination & Info */
        .dataTables_wrapper .dataTables_info {
            color: #64748b !important;
            padding-top: 1.25rem !important;
            float: left;
            clear: none;
        }
        .dataTables_wrapper .dataTables_paginate {
            display: flex;
            flex-wrap: nowrap;
            align-items: center;
            justify-content: flex-end;
            gap: 0.375rem;
            margin-top: 1rem;
            float: right;
            overflow-x: auto;
        }
        .dataTables_wrapper .dataTables_paginate > span:not(.ellipsis) {
            display: flex;
            flex-wrap: nowrap;
            align-items: center;
            gap: 0.375rem;
        }
        .dataTables_wrapper .dataTables_paginate .ellipsis {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 0.25rem;
            color: #64748b;
            font-weight: bold;
            line-height: 1;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.5rem 0.875rem !important;
            border-radius: 0.5rem !important;
            border: 1px solid #cbd5e1 !important;
            background: #fff !important;
            color: #475569 !important;
            font-weight: 500;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s;
            margin: 0 0.125rem !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.disabled) {
            background: #f1f5f9 !important;
            color: #0f172a !important;
            border-color: #cbd5e1 !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: #2563eb !important;
            color: #fff !important;
            border-color: #2563eb !important;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.3);
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            background: #f8fafc !important;
        }
        
        /* Clearfix for datatables */
        .dataTables_wrapper:after {
            content: "";
            display: block;
            clear: both;
        }

        /* Responsive Extensions Override */
        table.dataTable.dtr-inline.collapsed>tbody>tr>td.dtr-control,
        table.dataTable.dtr-inline.collapsed>tbody>tr>th.dtr-control {
            position: relative;
            padding-left: 30px !important;
        }
        table.dataTable.dtr-inline.collapsed>tbody>tr>td.dtr-control:before, 
        table.dataTable.dtr-inline.collapsed>tbody>tr>th.dtr-control:before {
            content: '+' !important;
            background-color: #2563eb !important;
            background-image: none !important;
            color: white !important;
            border: 2px solid #fff !important;
            box-shadow: 0 0 3px rgba(0,0,0,0.2) !important;
            box-sizing: border-box;
            position: absolute;
            top: 50%;
            left: 5px;
            height: 18px;
            width: 18px;
            margin-top: -9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: bold;
            border-radius: 50%;
            line-height: 1 !important;
        }
        table.dataTable.dtr-inline.collapsed>tbody>tr.parent>td.dtr-control:before, 
        table.dataTable.dtr-inline.collapsed>tbody>tr.parent>th.dtr-control:before {
            content: '-' !important;
            background-color: #ef4444 !important;
        }

        /* Beautiful Child Row Styling */
        table.dataTable>tbody>tr.child ul.dtr-details {
            display: flex;
            flex-direction: column;
            width: 100%;
            padding: 0;
            margin: 0;
        }
        table.dataTable>tbody>tr.child ul.dtr-details>li {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.75rem 0;
        }
        table.dataTable>tbody>tr.child ul.dtr-details>li:last-child {
            border-bottom: none;
        }
        table.dataTable>tbody>tr.child ul.dtr-details>li .dtr-title {
            font-weight: 600;
            color: #64748b;
            min-width: 120px;
        }
        table.dataTable>tbody>tr.child ul.dtr-details>li .dtr-data {
            color: #0f172a;
            text-align: right;
            font-weight: 500;
        }
        
        /* Mobile View adjustments */
        @media (max-width: 768px) {
            .dataTables_wrapper .dataTables_length {
                float: none !important;
                text-align: center !important;
                margin-bottom: 1rem !important;
            }
            .dataTables_wrapper .dataTables_filter {
                float: none !important;
                text-align: left !important;
                margin-bottom: 1rem !important;
            }
            .dataTables_wrapper .dataTables_filter input {
                width: 100% !important;
                margin-left: 0 !important;
                margin-top: 0.5rem !important;
            }
            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_paginate {
                float: none !important;
                text-align: center !important;
            }
            .dataTables_wrapper .dataTables_paginate {
                justify-content: center !important;
                margin-top: 1rem !important;
            }
            .dataTables_wrapper .dataTables_paginate .paginate_button {
                padding: 0.375rem 0.5rem !important;
                font-size: 0.75rem !important;
            }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased overflow-hidden flex h-[100dvh]">
    
    <!-- Mobile Sidebar Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-slate-900/50 z-40 hidden lg:hidden"></div>

    <!-- Sidebar -->
    <aside id="admin-sidebar" class="w-64 bg-slate-900 border-r border-slate-800 flex flex-col fixed inset-y-0 left-0 z-50 transform -translate-x-full lg:translate-x-0 lg:static transition-transform duration-300 ease-in-out shadow-xl">
        <div class="h-16 flex items-center justify-start px-6 border-b border-slate-800">
            <a href="{{ route('admin.dashboard') }}" class="flex-shrink-0 flex items-center">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-6 mr-2">
                <span class="text-base font-extrabold text-blue-600 tracking-tighter uppercase italic">Amba<span class="text-white">cinema</span></span>
                <span class="ml-1.5 px-1.5 py-0.5 bg-blue-900/40 text-blue-400 text-[9px] font-bold uppercase rounded border border-blue-800/50">Admin</span>
            </a>
        </div>
        <nav class="flex-1 overflow-y-auto py-4 space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-3 text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600/10 text-blue-400 border-r-4 border-blue-500' : 'text-slate-400 hover:bg-slate-800 hover:text-white border-r-4 border-transparent' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                {{ __('Dashboard') }}
            </a>
            
            <a href="{{ route('admin.studios.index') }}" class="flex items-center px-6 py-3 text-sm font-medium transition-colors {{ request()->routeIs('admin.studios.*') ? 'bg-blue-600/10 text-blue-400 border-r-4 border-blue-500' : 'text-slate-400 hover:bg-slate-800 hover:text-white border-r-4 border-transparent' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                {{ __('Studios') }}
            </a>
            
            <a href="{{ route('admin.studio-types.index') }}" class="flex items-center px-6 py-3 text-sm font-medium transition-colors {{ request()->routeIs('admin.studio-types.*') ? 'bg-blue-600/10 text-blue-400 border-r-4 border-blue-500' : 'text-slate-400 hover:bg-slate-800 hover:text-white border-r-4 border-transparent' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                {{ __('Studio Types') }}
            </a>
            
            <a href="{{ route('admin.movies.index') }}" class="flex items-center px-6 py-3 text-sm font-medium transition-colors {{ request()->routeIs('admin.movies.*') ? 'bg-blue-600/10 text-blue-400 border-r-4 border-blue-500' : 'text-slate-400 hover:bg-slate-800 hover:text-white border-r-4 border-transparent' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path></svg>
                {{ __('Movies') }}
            </a>
            
            <a href="{{ route('admin.showtimes.index') }}" class="flex items-center px-6 py-3 text-sm font-medium transition-colors {{ request()->routeIs('admin.showtimes.*') ? 'bg-blue-600/10 text-blue-400 border-r-4 border-blue-500' : 'text-slate-400 hover:bg-slate-800 hover:text-white border-r-4 border-transparent' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                {{ __('Showtimes') }}
            </a>
            
            <a href="{{ route('admin.orders.index') }}" class="flex items-center px-6 py-3 text-sm font-medium transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-blue-600/10 text-blue-400 border-r-4 border-blue-500' : 'text-slate-400 hover:bg-slate-800 hover:text-white border-r-4 border-transparent' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                {{ __('Orders') }}
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Header -->
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 z-30 relative shadow-sm">
            <div class="flex items-center">
                <!-- Hamburger Button (Visible on all screens) -->
                <button id="sidebar-toggle-btn" class="cursor-pointer text-slate-500 hover:text-blue-600 focus:outline-none p-2 rounded-md hover:bg-slate-100 transition-colors mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>
            <div class="flex-1 flex justify-end">
                <!-- Language Switcher Admin -->
                <div class="relative mr-4 self-center" id="lang-dropdown-wrapper">
                     <button id="lang-menu-btn" class="cursor-pointer flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 focus:outline-none bg-slate-100 px-2 py-1 rounded-md border border-slate-200">
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

                <div class="relative self-center" id="profile-dropdown-wrapper">
                    <button class="cursor-pointer flex items-center space-x-3" id="profile-dropdown-btn">
                        <!-- <div class="text-right hidden sm:block">
                            <span class="block text-sm font-medium text-slate-700">{{ __('Welcome') }}, {{ Auth::user()->name }}</span>
                            <span class="block text-xs text-slate-500 capitalize">{{ Auth::user()->role }}</span>
                        </div> -->
                        <div class="h-9 w-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold ring-2 ring-white shadow-sm hover:ring-blue-300 transition-all">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <!-- <svg class="w-4 h-4 text-slate-400 hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg> -->
                    </button>
                    
                    <!-- Admin Profile Dropdown -->
                    <div class="absolute right-0 mt-3 w-48 hidden z-50" id="profile-dropdown-menu">
                        <div class="bg-white rounded-md shadow-lg py-1 ring-1 ring-slate-900 ring-opacity-5 border border-slate-200">
                            <a href="{{ route('home') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">{{ __('Go to User Site') }}</a>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">{{ __('My Profile') }}</a>
                            <div class="border-t border-slate-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                @csrf
                                <button type="button" onclick="confirmLogout(event)" class="cursor-pointer block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-medium transition-colors">{{ __('Sign out') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-4 sm:p-6 pb-20 sm:pb-10">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-6" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script>
        $(document).ready(function() {
            // Custom DataTables Pager (3 numbers with ellipsis)
            $.fn.DataTable.ext.pager.three_numbers_with_ellipsis = function(page, pages) {
                var numbers = [];
                var start = page - 1;
                if (start < 0) { start = 0; }
                var end = start + 3;
                if (end > pages) {
                    end = pages;
                    start = pages - 3;
                    if (start < 0) { start = 0; }
                }
                for (var i = start; i < end; i++) {
                    numbers.push(i);
                }
                
                var result = [];
                if (start > 0) {
                    result.push('ellipsis');
                }
                result = result.concat(numbers);
                if (end < pages) {
                    result.push('ellipsis');
                }
                
                return ['first', 'previous', result, 'next', 'last'];
            };

            // Global DataTables Default Settings
            $.extend( true, $.fn.dataTable.defaults, {
                responsive: true,
                pagingType: 'three_numbers_with_ellipsis',
                language: {
                    paginate: {
                        first: "&laquo;",
                        previous: "&lsaquo;",
                        next: "&rsaquo;",
                        last: "&raquo;"
                    }
                }
            } );

            // // SIMULASI: Ubah teks angka menjadi ratusan (misal 1 -> 101) khusus untuk tes visual
            // $(document).on('draw.dt', function() {
            //     $('.dataTables_paginate .paginate_button').each(function() {
            //         var txt = $(this).text();
            //         // Jika teksnya murni angka
            //         if (/^\d+$/.test(txt)) {
            //             $(this).text(parseInt(txt) + 880); // Tambahkan 100
            //         }
            //     });
            // });

            // Sidebar Toggle Logic
            const sidebar = $('#admin-sidebar');
            const overlay = $('#sidebar-overlay');
            const toggleBtn = $('#sidebar-toggle-btn');

            function toggleSidebar() {
                // On mobile/tablet (lg breakpoint is 1024px)
                if (window.innerWidth < 1024) {
                    sidebar.toggleClass('-translate-x-full');
                    overlay.toggleClass('hidden');
                } else {
                    // On desktop
                    sidebar.toggleClass('lg:-translate-x-full lg:hidden');
                }
            }

            toggleBtn.on('click', toggleSidebar);
            overlay.on('click', toggleSidebar);

            // Profile Dropdown Logic (Mobile Friendly)
            const profileBtn = $('#profile-dropdown-btn');
            const profileMenu = $('#profile-dropdown-menu');

            profileBtn.on('click', function(e) {
                e.stopPropagation();
                profileMenu.toggleClass('hidden');
            });

            // Close dropdown when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#profile-dropdown-wrapper').length) {
                    profileMenu.addClass('hidden');
                }
                if (!$(e.target).closest('#lang-dropdown-wrapper').length) {
                    $('#lang-menu').addClass('hidden');
                }
            });

            // Lang menu toggle
            $('#lang-menu-btn').on('click', function(e) {
                e.stopPropagation();
                $('#lang-menu').toggleClass('hidden');
            });
        });
    </script>
    <!-- SweetAlert2 for Logout -->
    <script>
        function confirmLogout(event) {
            event.preventDefault();
            Swal.fire({
                title: '<div class="flex items-center justify-center"><img src="{{ asset("images/logo.png") }}" alt="Logo" class="h-10 mr-4"><span class="text-2xl font-extrabold text-blue-500 tracking-tighter uppercase italic">Amba<span class="text-white">cinema</span></span></div>',
                html: '<p class="text-slate-400 mt-2">{{ __("Are you sure you want to log out of the Admin Panel?") }}</p>',
                icon: 'question',
                iconColor: '#3b82f6',
                showCancelButton: true,
                confirmButtonText: '{{ __("Yes, Log Out") }}',
                cancelButtonText: '{{ __("Cancel") }}',
                background: '#0f172a',
                buttonsStyling: false,
                customClass: {
                    popup: 'border border-slate-800 rounded-2xl shadow-2xl',
                    confirmButton: 'cursor-pointer bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-xl transition-colors w-full sm:w-auto',
                    cancelButton: 'cursor-pointer bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold py-2.5 px-6 rounded-xl transition-colors w-full sm:w-auto mt-3 sm:mt-0 sm:ml-3',
                    actions: 'w-full flex flex-col sm:flex-row justify-center mt-6'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.closest('form').submit();
                }
            });
        }
        function showErrorAlert(message) {
            Swal.fire({
                title: '<div class="flex items-center justify-center"><img src="{{ asset("images/logo.png") }}" alt="Logo" class="h-10 mr-4"><span class="text-2xl font-extrabold text-blue-500 tracking-tighter uppercase italic">Amba<span class="text-white">cinema</span></span></div>',
                html: `<p class="text-slate-400 mt-2">${message}</p>`,
                icon: 'error',
                iconColor: '#ef4444',
                confirmButtonText: '{{ __("OK") }}',
                background: '#0f172a',
                buttonsStyling: false,
                customClass: {
                    popup: 'border border-slate-800 rounded-2xl shadow-2xl',
                    confirmButton: 'bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 px-6 rounded-xl transition-colors w-full sm:w-auto',
                    actions: 'w-full flex justify-center mt-6'
                }
            });
        }
        function confirmDelete(event, message = '{{ __("Are you sure you want to delete this item?") }}') {
            event.preventDefault();
            Swal.fire({
                title: '<div class="flex items-center justify-center"><img src="{{ asset("images/logo.png") }}" alt="Logo" class="h-10 mr-4"><span class="text-2xl font-extrabold text-blue-500 tracking-tighter uppercase italic">Amba<span class="text-white">cinema</span></span></div>',
                html: `<p class="text-slate-400 mt-2">${message}<br><span class="text-red-400 text-xs mt-1 block">{{ __("This action cannot be undone") }}.</span></p>`,
                icon: 'warning',
                iconColor: '#ef4444',
                showCancelButton: true,
                confirmButtonText: '{{ __("Yes, Delete") }}',
                cancelButtonText: '{{ __("Cancel") }}',
                background: '#0f172a',
                buttonsStyling: false,
                customClass: {
                    popup: 'border border-slate-800 rounded-2xl shadow-2xl',
                    confirmButton: 'cursor-pointer bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 px-6 rounded-xl transition-colors w-full sm:w-auto',
                    cancelButton: 'cursor-pointer bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold py-2.5 px-6 rounded-xl transition-colors w-full sm:w-auto mt-3 sm:mt-0 sm:ml-3',
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
