@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-slate-900 mb-2">Order Details: #{{ $order->id }}</h2>
    <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium">&larr; Back to Orders</a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
        <h3 class="text-lg font-semibold text-slate-900 mb-4 border-b border-slate-200 pb-2">Customer & Movie Info</h3>
        <p class="text-slate-600 mb-2"><span class="font-medium text-slate-800">Customer Name:</span> {{ $order->user->name }}</p>
        <p class="text-slate-600 mb-2"><span class="font-medium text-slate-800">Email:</span> {{ $order->user->email }}</p>
        <div class="mt-4 pt-4 border-t border-slate-200">
            <p class="text-slate-600 mb-2"><span class="font-medium text-slate-800">Movie Title:</span> {{ $order->showtime->movie->title }}</p>
            <p class="text-slate-600 mb-2"><span class="font-medium text-slate-800">Studio:</span> {{ $order->showtime->studio->name }}</p>
            <p class="text-slate-600 mb-2"><span class="font-medium text-slate-800">Showtime:</span> {{ \Carbon\Carbon::parse($order->showtime->start_time)->format('d M Y, H:i') }}</p>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
        <h3 class="text-lg font-semibold text-slate-900 mb-4 border-b border-slate-200 pb-2">Booking Summary</h3>
        <p class="text-slate-600 mb-2"><span class="font-medium text-slate-800">Status:</span> 
            @if($order->status == 'confirmed')
                <span class="text-green-600 font-semibold uppercase">Confirmed</span>
            @else
                <span class="text-yellow-600 font-semibold uppercase">{{ $order->status }}</span>
            @endif
        </p>
        <p class="text-slate-600 mb-2"><span class="font-medium text-slate-800">Seats Booked:</span></p>
        <div class="flex flex-wrap gap-2 mb-4">
            @foreach($order->seats as $seat)
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded text-sm font-medium border border-blue-200">{{ $seat->seat_number }}</span>
            @endforeach
        </div>
        <div class="mt-4 pt-4 border-t border-slate-200">
            <p class="text-2xl font-bold text-slate-900">Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
        </div>
    </div>
</div>
@endsection
