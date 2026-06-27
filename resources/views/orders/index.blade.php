@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center justify-between mb-8 border-b border-slate-200 pb-4">
        <h2 class="text-3xl font-bold text-slate-900 uppercase tracking-wider border-l-4 border-blue-600 pl-3">My Tickets</h2>
    </div>

    @if($orders->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($orders as $order)
                <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden hover:border-blue-500 transition-colors shadow-sm hover:shadow-xl hover:shadow-blue-900/10 group">
                    <div class="relative h-48 w-full">
                        @if($order->showtime->movie->poster)
                            <img src="{{ Storage::url($order->showtime->movie->poster) }}" alt="Poster" class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition-opacity">
                        @else
                            <div class="w-full h-full bg-slate-100"></div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent opacity-90"></div>
                        <div class="absolute bottom-4 left-4 right-4">
                            <h3 class="text-xl font-bold text-white truncate drop-shadow-md">{{ $order->showtime->movie->title }}</h3>
                            <p class="text-sm text-blue-300 font-medium drop-shadow-md">{{ \Carbon\Carbon::parse($order->showtime->start_time)->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <p class="text-xs text-slate-500 uppercase tracking-wider">Studio</p>
                                <p class="text-slate-900 font-medium">{{ $order->showtime->studio->name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-slate-500 uppercase tracking-wider">Status</p>
                                <span class="text-green-600 font-bold uppercase text-sm">{{ $order->status }}</span>
                            </div>
                        </div>
                        <div class="mb-6">
                            <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Seats</p>
                            <p class="text-slate-700 font-medium truncate">{{ $order->seats->pluck('seat_number')->implode(', ') }}</p>
                        </div>
                        <a href="{{ route('orders.show', $order->id) }}" class="block w-full text-center bg-white hover:bg-blue-600 text-blue-600 hover:text-white font-semibold py-2 rounded-lg transition-colors border border-slate-300 hover:border-transparent shadow-sm">
                            View e-Ticket
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white border border-slate-200 rounded-2xl p-12 text-center shadow-sm">
            <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
            <h3 class="text-2xl font-bold text-slate-900 mb-2">No Tickets Found</h3>
            <p class="text-slate-500 mb-6">You haven't booked any movies yet.</p>
            <a href="{{ route('home') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-full transition-colors uppercase tracking-wider text-sm shadow-md">Browse Movies</a>
        </div>
    @endif
</div>
@endsection
