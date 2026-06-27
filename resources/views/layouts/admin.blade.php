<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Ambacinema</title>
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
            align-items: center;
            justify-content: flex-end;
            gap: 0.375rem;
            margin-top: 1rem;
            float: right;
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
            margin: 0 !important;
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
            background: transparent !important;
            border-color: transparent !important;
        }
        
        /* Clearfix for datatables */
        .dataTables_wrapper:after {
            content: "";
            display: block;
            clear: both;
        }

        /* Responsive Extensions Override */
        table.dataTable.dtr-inline.collapsed>tbody>tr>td.dtr-control:before, 
        table.dataTable.dtr-inline.collapsed>tbody>tr>th.dtr-control:before {
            background-color: #2563eb !important;
            box-shadow: none !important;
            border: 2px solid #fff !important;
            line-height: 16px !important;
        }
        table.dataTable.dtr-inline.collapsed>tbody>tr.parent>td.dtr-control:before, 
        table.dataTable.dtr-inline.collapsed>tbody>tr.parent>th.dtr-control:before {
            background-color: #94a3b8 !important;
        }
        
        /* Mobile View adjustments */
        @media (max-width: 768px) {
            .dataTables_wrapper .dataTables_length,
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
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased overflow-hidden flex h-screen">
    
    <!-- Mobile Sidebar Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-slate-900/50 z-40 hidden lg:hidden"></div>

    <!-- Sidebar -->
    <aside id="admin-sidebar" class="w-64 bg-slate-900 border-r border-slate-800 flex flex-col fixed inset-y-0 left-0 z-50 transform -translate-x-full lg:translate-x-0 lg:static transition-transform duration-300 ease-in-out shadow-xl">
        <div class="h-16 flex items-center justify-center border-b border-slate-800">
            <a href="{{ route('admin.dashboard') }}" class="flex-shrink-0 flex items-center">
                <span class="text-2xl font-extrabold text-blue-500 tracking-tighter uppercase italic">Amba<span class="text-slate-100">cinema</span></span>
                <span class="ml-2 px-1.5 py-0.5 bg-blue-900/40 text-blue-400 text-[10px] font-bold uppercase rounded border border-blue-800/50">Admin</span>
            </a>
        </div>
        <nav class="flex-1 overflow-y-auto py-4 space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-3 text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600/10 text-blue-400 border-r-4 border-blue-500' : 'text-slate-400 hover:bg-slate-800 hover:text-white border-r-4 border-transparent' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Dashboard
            </a>
            
            <a href="{{ route('admin.studios.index') }}" class="flex items-center px-6 py-3 text-sm font-medium transition-colors {{ request()->routeIs('admin.studios.*') ? 'bg-blue-600/10 text-blue-400 border-r-4 border-blue-500' : 'text-slate-400 hover:bg-slate-800 hover:text-white border-r-4 border-transparent' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                Studios
            </a>
            
            <a href="{{ route('admin.studio-types.index') }}" class="flex items-center px-6 py-3 text-sm font-medium transition-colors {{ request()->routeIs('admin.studio-types.*') ? 'bg-blue-600/10 text-blue-400 border-r-4 border-blue-500' : 'text-slate-400 hover:bg-slate-800 hover:text-white border-r-4 border-transparent' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                Studio Types
            </a>
            
            <a href="{{ route('admin.movies.index') }}" class="flex items-center px-6 py-3 text-sm font-medium transition-colors {{ request()->routeIs('admin.movies.*') ? 'bg-blue-600/10 text-blue-400 border-r-4 border-blue-500' : 'text-slate-400 hover:bg-slate-800 hover:text-white border-r-4 border-transparent' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path></svg>
                Movies
            </a>
            
            <a href="{{ route('admin.showtimes.index') }}" class="flex items-center px-6 py-3 text-sm font-medium transition-colors {{ request()->routeIs('admin.showtimes.*') ? 'bg-blue-600/10 text-blue-400 border-r-4 border-blue-500' : 'text-slate-400 hover:bg-slate-800 hover:text-white border-r-4 border-transparent' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Showtimes
            </a>
            
            <a href="{{ route('admin.orders.index') }}" class="flex items-center px-6 py-3 text-sm font-medium transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-blue-600/10 text-blue-400 border-r-4 border-blue-500' : 'text-slate-400 hover:bg-slate-800 hover:text-white border-r-4 border-transparent' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                Orders
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Header -->
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 z-30 relative shadow-sm">
            <div class="flex items-center">
                <!-- Hamburger Button (Visible on all screens) -->
                <button id="sidebar-toggle-btn" class="text-slate-500 hover:text-blue-600 focus:outline-none p-2 rounded-md hover:bg-slate-100 transition-colors mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>
            <div class="flex-1 flex justify-end">
                <div class="relative group cursor-pointer">
                    <div class="flex items-center space-x-3">
                        <div class="text-right hidden sm:block">
                            <span class="block text-sm font-medium text-slate-700">Welcome, {{ Auth::user()->name }}</span>
                            <span class="block text-xs text-slate-500 capitalize">{{ Auth::user()->role }}</span>
                        </div>
                        <div class="h-9 w-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold ring-2 ring-white shadow-sm">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                    
                    <!-- Admin Profile Dropdown -->
                    <div class="absolute right-0 pt-3 w-48 hidden group-hover:block z-50">
                        <div class="bg-white rounded-md shadow-lg py-1 ring-1 ring-slate-900 ring-opacity-5 border border-slate-200">
                            <a href="{{ route('home') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">Go to User Site</a>
                            <div class="border-t border-slate-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-medium transition-colors">Sign out</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-6">
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
            // Global DataTables Default Settings
            $.extend( true, $.fn.dataTable.defaults, {
                responsive: true
            } );

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
        });
    </script>
    @stack('scripts')
</body>
</html>
