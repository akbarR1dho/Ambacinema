@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-6">
        <a href="{{ route('orders.index') }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to My Tickets
        </a>
    </div>

    <!-- E-Ticket Card -->
    <div class="rounded-2xl shadow-2xl flex flex-col md:flex-row relative bg-white overflow-hidden">
        <!-- Left Side (Movie Details) -->
        <div class="w-full md:w-2/3 bg-slate-50 border-b md:border-b-0 md:border-r border-dashed border-slate-300 p-6 md:p-8 relative">
            <!-- Cutouts Mobile -->
            <div class="absolute -bottom-4 -left-4 w-8 h-8 bg-slate-50 rounded-full md:hidden shadow-inner border border-slate-300"></div>
            <div class="absolute -bottom-4 -right-4 w-8 h-8 bg-slate-50 rounded-full md:hidden shadow-inner border border-slate-300"></div>
            <!-- Cutouts Desktop -->
            <div class="absolute -top-4 -right-4 w-8 h-8 bg-slate-50 rounded-full hidden md:block shadow-inner border border-slate-300"></div>
            <div class="absolute -bottom-4 -right-4 w-8 h-8 bg-slate-50 rounded-full hidden md:block shadow-inner border border-slate-300"></div>
            
            <div class="flex flex-col-reverse sm:flex-row justify-between items-start mb-6 gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-black text-slate-900 uppercase tracking-tight leading-tight">{{ $order->showtime->movie->title }}</h1>
                    <span class="inline-block mt-2 px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full border border-blue-200 uppercase">Confirmed</span>
                </div>
                <div class="text-left sm:text-right">
                    <span class="text-2xl md:text-3xl font-extrabold text-blue-600 tracking-tighter uppercase italic">Amba<span class="text-slate-900">cinema</span></span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 md:gap-6 mb-8 mt-6 md:mt-10">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Date</p>
                    <p class="text-base md:text-lg font-bold text-slate-900">{{ \Carbon\Carbon::parse($order->showtime->start_time)->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Time</p>
                    <p class="text-base md:text-lg font-bold text-slate-900">{{ \Carbon\Carbon::parse($order->showtime->start_time)->format('H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Studio</p>
                    <p class="text-base md:text-lg font-bold text-slate-900">{{ $order->showtime->studio->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Seats</p>
                    <p class="text-base md:text-lg font-bold text-blue-600">{{ $order->seats->pluck('seat_number')->implode(', ') }}</p>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-200 flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Booked By</p>
                    <p class="text-slate-900 font-medium">{{ $order->user->name }}</p>
                </div>
                <div class="text-left sm:text-right">
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Total Payment</p>
                    <p class="text-xl font-bold text-slate-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Right Side (QR Code) -->
        <div class="w-full md:w-1/3 p-6 md:p-8 flex flex-col items-center justify-center relative">
            <h3 class="text-black font-bold text-xl uppercase tracking-widest mb-6">Scan Entry</h3>
            
            <div class="bg-white p-2 border-4 border-black rounded-xl mb-6">
                @if($order->qr_code)
                    <img src="{{ Storage::url($order->qr_code) }}" alt="QR Code" class="w-48 h-48">
                @else
                    <div class="w-48 h-48 bg-gray-200 flex items-center justify-center text-gray-500">No QR Code</div>
                @endif
            </div>

            <p class="text-gray-500 text-xs text-center">Please present this QR code at the studio entrance.</p>
            <p class="text-black font-bold text-sm mt-4 uppercase">Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>
    </div>
</div>
@endsection
