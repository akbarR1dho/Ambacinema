@extends('layouts.app')

@section('title', 'Ticket Details - Ambacinema')
@section('meta_description', 'View the details of your movie ticket booking at Ambacinema. Access your e-ticket and QR code for studio entry.')
@section('meta_keywords', 'e-ticket, movie ticket, cinema entry, qr code ticket, ambacinema')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-6">
        <a href="{{ route('orders.index') }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            {{ __('Back to My Tickets') }}
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
            
            <div class="flex flex-col justify-between items-start mb-6 gap-5">
                <div class="text-left sm:text-right flex items-center justify-start sm:justify-end">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 md:h-10 inline-block mr-3 md:mr-4">
                    <span class="text-xl md:text-2xl font-extrabold text-blue-600 tracking-tighter uppercase italic">Amba<span class="text-slate-900">cinema</span></span>
                </div> 
                <div>
                    <h1 class="text-2xl md:text-3xl font-black text-slate-900 uppercase tracking-tight leading-tight">{{ $order->showtime->movie->title }}</h1>
                    @if($order->status == 'pending')
                        <span class="inline-block mt-2 px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-bold rounded-full border border-yellow-200 uppercase">{{ __('Pending') }}</span>
                    @elseif($order->status == 'confirmed')
                        <span class="inline-block mt-2 px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full border border-green-200 uppercase">{{ __('Confirmed') }}</span>
                    @else
                        <span class="inline-block mt-2 px-3 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-full border border-red-200 uppercase">{{ $order->status }}</span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 md:gap-6 mb-6 mt-6 md:mt-10">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">{{ __('Date') }}</p>
                    <p class="text-base md:text-lg font-bold text-slate-900">{{ \Carbon\Carbon::parse($order->showtime->start_time)->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">{{ __('Time') }}</p>
                    <p class="text-base md:text-lg font-bold text-slate-900">{{ \Carbon\Carbon::parse($order->showtime->start_time)->format('H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">{{ __('Studio') }}</p>
                    <p class="text-base md:text-lg font-bold text-slate-900">{{ $order->showtime->studio->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">{{ __('Seats') }}</p>
                    <p class="text-base md:text-lg font-bold text-blue-600">{{ $order->seats->pluck('seat_number')->implode(', ') }}</p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4 md:gap-6 mb-8 border-t border-slate-200 pt-6">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">{{ __('Booking Time') }}</p>
                    <p class="text-sm font-medium text-slate-700">{{ $order->pending_at ? $order->pending_at->format('d M Y, H:i') : '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">{{ __('Confirmation Time') }}</p>
                    <p class="text-sm font-medium text-slate-700">{{ $order->confirmed_at ? $order->confirmed_at->format('d M Y, H:i') : '-' }}</p>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-200 flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">{{ __('Booked By') }}</p>
                    <p class="text-slate-900 font-medium">{{ $order->user->name }}</p>
                </div>
                <div class="text-left sm:text-right">
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">{{ __('Total Payment') }}</p>
                    <p class="text-xl font-bold text-slate-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Right Side (QR Code or Payment) -->
        <div class="w-full md:w-1/3 p-6 md:p-8 flex flex-col items-center justify-center relative">
            @if($order->status == 'pending')
                <div class="flex flex-col items-center text-center">
                    <svg class="w-16 h-16 text-yellow-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <h3 class="text-yellow-600 font-bold text-xl uppercase tracking-widest mb-2">{{ __('Payment Required') }}</h3>
                    <p class="text-slate-500 text-sm mb-2">{{ __('You have not completed the payment for this ticket.') }}</p>
                    <p class="text-slate-600 font-medium text-sm">{{ __('Please return to the "My Tickets" page and click the "Pay" button to complete your transaction.') }}</p>
                </div>
            @elseif($order->status == 'confirmed')
                <h3 class="text-black font-bold text-xl uppercase tracking-widest mb-6">{{ __('Scan Entry') }}</h3>
                
                <div class="bg-white p-2 border-4 border-black rounded-xl mb-6">
                    @if($order->qr_code)
                        <img src="{{ Storage::url($order->qr_code) }}" alt="QR Code" class="w-48 h-48">
                    @else
                        <div class="w-48 h-48 bg-gray-200 flex items-center justify-center text-gray-500">{{ __('No QR Code') }}</div>
                    @endif
                </div>

                <p class="text-gray-500 text-xs text-center">{{ __('Please present this QR code at the studio entrance.') }}</p>
            @else
                <h3 class="text-red-600 font-bold text-xl uppercase tracking-widest mb-6">{{ __('Order Failed') }}</h3>
                <p class="text-gray-500 text-xs text-center">{{ __('This order was not successful.') }}</p>
            @endif
            
            <p class="text-black font-bold text-center text-sm mt-4 uppercase">{{ __('Order') }} #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>
    </div>
</div>
@endsection
