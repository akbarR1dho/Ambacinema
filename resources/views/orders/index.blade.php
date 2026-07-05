@extends('layouts.app')

@section('title', 'My Tickets - Ambacinema')
@section('meta_description', 'View all your movie ticket bookings and order history at Ambacinema.')
@section('meta_keywords', 'movie tickets, order history, cinema bookings, ambacinema tickets')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 border-b border-slate-200 pb-4 gap-4">
        <h2 class="text-3xl font-bold text-slate-900 uppercase tracking-wider border-l-4 border-blue-600 pl-3">{{ __('My Tickets') }}</h2>
        
        <form action="{{ route('orders.index') }}" method="GET" class="w-full sm:w-auto" id="filterForm">
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <label for="filter-date" class="text-sm font-medium text-slate-500 whitespace-nowrap hidden sm:block">{{ __('Time Range') }}:</label>
                <select name="date_filter" id="filter-date" class="w-full sm:w-auto px-4 py-2 bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm" onchange="document.getElementById('filterForm').submit()">
                    <option value="all" {{ request('date_filter', 'today') == 'all' ? 'selected' : '' }}>{{ __('All Time') }}</option>
                    <option value="today" {{ request('date_filter', 'today') == 'today' ? 'selected' : '' }}>{{ __('Today') }}</option>
                    <option value="weekly" {{ request('date_filter', 'today') == 'weekly' ? 'selected' : '' }}>{{ __('This Week') }}</option>
                    <option value="monthly" {{ request('date_filter', 'today') == 'monthly' ? 'selected' : '' }}>{{ __('This Month') }}</option>
                    <option value="annual" {{ request('date_filter', 'today') == 'annual' ? 'selected' : '' }}>{{ __('This Year') }}</option>
                </select>
            </div>
        </form>
    </div>

    @if($orders->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="orders-container">
            @include('orders._cards')
        </div>

        <!-- Loading Indicator -->
        <div id="loading-indicator" class="hidden text-center py-6 mt-4">
            <svg class="animate-spin h-8 w-8 text-blue-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-sm text-slate-500 mt-2">{{ __('Loading more tickets') }}...</p>
        </div>
    @else
        <div class="bg-white border border-slate-200 rounded-2xl p-12 text-center shadow-sm">
            <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
            <h3 class="text-2xl font-bold text-slate-900 mb-2">{{ __('No Tickets Found') }}</h3>
            <p class="text-slate-500 mb-6">{{ __("You haven't booked any movies yet.") }}</p>
            <a href="{{ route('home') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-full transition-colors uppercase tracking-wider text-sm shadow-md">{{ __('Browse Movies') }}</a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let isLoading = false;
        let nextCursor = '{!! $orders->nextCursor() ? $orders->nextCursor()->encode() : '' !!}';
        
        window.addEventListener('scroll', function() {
            if (!nextCursor || isLoading) return;
            
            if ((window.innerHeight + window.scrollY) >= document.documentElement.scrollHeight - 200) {
                loadMore();
            }
        });
        
        function loadMore() {
            isLoading = true;
            document.getElementById('loading-indicator').classList.remove('hidden');
            
            let url = new URL(window.location.href);
            url.searchParams.set('cursor', nextCursor);
            
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('loading-indicator').classList.add('hidden');
                
                if (data.html.trim() !== '') {
                    document.getElementById('orders-container').insertAdjacentHTML('beforeend', data.html);
                }
                nextCursor = data.nextCursor;
                isLoading = false;
            })
            .catch(error => {
                console.error('Error fetching more orders:', error);
                document.getElementById('loading-indicator').classList.add('hidden');
                isLoading = false;
            });
        }
    });
</script>
@endpush
