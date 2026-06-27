@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-slate-900">Dashboard Overview</h2>
        <p class="text-sm text-slate-500">Welcome to the Ambacinema administration panel.</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat Card 1 -->
    <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Total Studios</p>
                <p class="text-3xl font-bold text-slate-900 mt-1">{{ \App\Models\Studio::count() }}</p>
            </div>
            <div class="p-3 bg-blue-100 rounded-lg text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Total Movies</p>
                <p class="text-3xl font-bold text-slate-900 mt-1">{{ \App\Models\Movie::count() }}</p>
            </div>
            <div class="p-3 bg-blue-100 rounded-lg text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Total Showtimes</p>
                <p class="text-3xl font-bold text-slate-900 mt-1">{{ \App\Models\Showtime::count() }}</p>
            </div>
            <div class="p-3 bg-blue-100 rounded-lg text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Stat Card 4 -->
    <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Total Orders</p>
                <p class="text-3xl font-bold text-slate-900 mt-1">{{ \App\Models\Order::count() }}</p>
            </div>
            <div class="p-3 bg-blue-100 rounded-lg text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            </div>
        </div>
    </div>
</div>

@endsection
