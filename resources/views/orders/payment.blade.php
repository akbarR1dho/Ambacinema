@extends('layouts.app')

@section('title', 'Payment - Ambacinema')
@section('meta_description', 'Complete your payment for Ambacinema movie tickets securely via Midtrans.')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-6">
        <a href="{{ route('orders.index') }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            {{ __('Back to My Tickets') }}
        </a>
    </div>

    <div class="rounded-2xl shadow-2xl bg-white overflow-hidden p-5 sm:p-8 text-center max-w-lg mx-auto">
        <h2 class="text-xl sm:text-2xl font-black text-slate-900 uppercase tracking-tight mb-4">{{ __('Complete Payment') }}</h2>
        <p class="text-slate-500 text-sm mb-8">{{ __('You are about to pay for your movie ticket at Ambacinema.') }}</p>

        <div class="bg-slate-50 p-4 sm:p-6 rounded-xl border border-slate-200 text-left mb-8 shadow-inner">
            <h3 class="text-lg font-bold text-slate-800 mb-4 border-b border-slate-200 pb-2">{{ __('Order Summary') }}</h3>
            
            <div class="flex justify-between mb-3 gap-2">
                <span class="text-sm font-medium text-slate-500 whitespace-nowrap">{{ __('Movie:') }}</span>
                <span class="text-sm font-bold text-slate-900 text-right">{{ $order->showtime->movie->title }}</span>
            </div>
            <div class="flex justify-between mb-3 gap-2">
                <span class="text-sm font-medium text-slate-500 whitespace-nowrap">{{ __('Studio:') }}</span>
                <span class="text-sm font-bold text-slate-900 text-right">{{ $order->showtime->studio->name }}</span>
            </div>
            <div class="flex justify-between mb-3 gap-2">
                <span class="text-sm font-medium text-slate-500 whitespace-nowrap">{{ __('Show Date:') }}</span>
                <span class="text-sm font-bold text-slate-900 text-right">{{ \Carbon\Carbon::parse($order->showtime->start_time)->format('d M Y') }}</span>
            </div>
            <div class="flex justify-between mb-3 gap-2">
                <span class="text-sm font-medium text-slate-500 whitespace-nowrap">{{ __('Show Time:') }}</span>
                <span class="text-sm font-bold text-slate-900 text-right">{{ \Carbon\Carbon::parse($order->showtime->start_time)->format('H:i') }}</span>
            </div>
            <div class="flex justify-between mb-3 gap-2">
                <span class="text-sm font-medium text-slate-500 whitespace-nowrap">{{ __('Booking Time:') }}</span>
                <span class="text-sm font-bold text-slate-900 text-right">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}</span>
            </div>
            <div class="flex justify-between mb-3 gap-2">
                <span class="text-sm font-medium text-slate-500 whitespace-nowrap">{{ __('Price/Seat:') }}</span>
                <span class="text-sm font-bold text-slate-900 text-right">Rp {{ number_format($order->total_price / max($order->seats->count(), 1), 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between mb-3 gap-2">
                <span class="text-sm font-medium text-slate-500 whitespace-nowrap">{{ __('Seats:') }}</span>
                <span class="text-sm font-bold text-blue-600 text-right">{{ $order->seats->pluck('seat_number')->implode(', ') }}</span>
            </div>
            <div class="flex justify-between items-center mt-6 pt-4 border-t border-slate-200">
                <span class="text-base font-bold text-slate-900">{{ __('Total Payment') }}</span>
                <span class="text-lg sm:text-xl font-black text-blue-600 text-right">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>
        </div>

            <div class="mb-8">
                @if($order->status == 'pending')
                    <div id="payment-instructions-box">
                        <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight mb-4 text-center">{{ __('Payment Instructions') }}</h3>
                        
                        <div class="bg-red-50 border border-red-100 rounded-xl p-4 mb-6 text-center max-w-sm mx-auto shadow-sm">
                            <p class="text-xs font-bold text-red-500 mb-1 uppercase tracking-wider">{{ __('Complete payment in') }}</p>
                            <div class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5 text-red-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span class="text-2xl font-black text-red-600 font-mono tracking-widest" id="countdown-timer">--:--:--</span>
                            </div>
                        </div>

                        <div class="w-full bg-slate-50 p-4 sm:p-6 rounded-xl border border-slate-200 mb-6 text-center shadow-inner">
                            <p class="text-sm font-bold text-slate-700 mb-4 uppercase">{{ str_replace('_', ' ', $order->payment_type == 'gopay' ? 'qris' : $order->payment_type) }}</p>
                            
                            @if($order->payment_type == 'bca_va')
                                <p class="text-xs text-slate-500 mb-2">{{ __('Virtual Account Number:') }}</p>
                                <div class="flex justify-center mb-2">
                                    <div class="flex flex-col sm:flex-row bg-white py-3 px-3 sm:px-6 rounded-xl border border-slate-200 shadow-sm items-center gap-2 sm:gap-3 w-full sm:w-auto overflow-hidden">
                                        <span class="text-base sm:text-lg md:text-xl font-black text-blue-600 font-mono select-all break-all text-center" id="bca-va">{{ $order->payment_info['va_numbers'][0]['va_number'] ?? '-' }}</span>
                                        <button onclick="navigator.clipboard.writeText(document.getElementById('bca-va').innerText); this.innerHTML='<svg class=\'w-5 h-5 text-green-500\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M5 13l4 4L19 7\'></path></svg>'; setTimeout(() => this.innerHTML='<svg class=\'w-5 h-5 text-slate-400 hover:text-blue-600 transition-colors\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z\'></path></svg>', 2000);" class="p-2 bg-slate-50 hover:bg-slate-100 sm:bg-transparent rounded-lg transition-colors focus:outline-none flex-shrink-0 w-full sm:w-auto flex justify-center mt-2 sm:mt-0" title="Copy VA Number">
                                            <svg class="w-5 h-5 text-slate-400 hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                        </button>
                                    </div>
                                </div>
                                <p class="text-xs text-slate-400 mt-2">{{ __('Transfer the exact amount to the virtual account above.') }}</p>
                            @elseif($order->payment_type == 'echannel')
                                <p class="text-xs text-slate-500 mb-2">{{ __('Biller Code:') }} <span class="font-bold text-slate-900">{{ $order->payment_info['biller_code'] ?? '-' }}</span></p>
                                <p class="text-xs text-slate-500 mb-2 mt-4">{{ __('Bill Key (VA Number):') }}</p>
                                <div class="flex justify-center mb-2">
                                    <div class="flex flex-col sm:flex-row bg-white py-3 px-3 sm:px-6 rounded-xl border border-slate-200 shadow-sm items-center gap-2 sm:gap-3 w-full sm:w-auto overflow-hidden">
                                        <span class="text-base sm:text-lg md:text-xl font-black text-blue-600 font-mono select-all break-all text-center" id="mandiri-va">{{ $order->payment_info['bill_key'] ?? '-' }}</span>
                                        <button onclick="navigator.clipboard.writeText(document.getElementById('mandiri-va').innerText); this.innerHTML='<svg class=\'w-5 h-5 text-green-500\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M5 13l4 4L19 7\'></path></svg>'; setTimeout(() => this.innerHTML='<svg class=\'w-5 h-5 text-slate-400 hover:text-blue-600 transition-colors\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z\'></path></svg>', 2000);" class="p-2 bg-slate-50 hover:bg-slate-100 sm:bg-transparent rounded-lg transition-colors focus:outline-none flex-shrink-0 w-full sm:w-auto flex justify-center mt-2 sm:mt-0" title="Copy Bill Key">
                                            <svg class="w-5 h-5 text-slate-400 hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            @elseif($order->payment_type == 'gopay')
                                <p class="text-sm text-slate-600 mb-4">{{ __('Scan this QR Code to Pay') }}</p>
                                <div class="bg-white p-4 border-4 border-slate-200 rounded-2xl mb-6 mx-auto w-fit shadow-sm">
                                    <img src="{{ collect($order->payment_info['actions'] ?? [])->firstWhere('name', 'generate-qr-code')['url'] ?? '' }}" alt="GoPay QR Code" class="w-48 h-48 object-cover">
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div id="payment-status-container">
                        <p class="text-slate-500 text-sm text-center mb-6">{{ __('Waiting for payment to be completed. This page will update automatically.') }}</p>
                        <div class="flex justify-center items-center gap-2 mb-6">
                            <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-sm font-medium text-slate-600">{{ __('Checking status in real-time...') }}</span>
                        </div>
                    </div>
                @endif

                <div id="payment-success-container" class="{{ $order->status == 'confirmed' ? '' : 'hidden' }}">
                    <div class="bg-green-50 border border-green-200 rounded-xl p-6 text-center mb-6">
                        <svg class="w-16 h-16 text-green-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <h4 class="text-xl font-bold text-green-700 mb-2">{{ __('Payment Successful!') }}</h4>
                        <p class="text-green-600 text-sm mb-6">{{ __('Your ticket has been confirmed and is ready to use.') }}</p>
                        <a href="{{ route('orders.show', $order->id) }}" class="inline-flex items-center py-3 px-8 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-green-600 hover:bg-green-700">
                            {{ __('View e-Ticket') }}
                        </a>
                        <!-- <a href="{{ route('orders.show', $order->id) }}" class="inline-flex justify-center items-center py-3 px-8 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all transform hover:-translate-y-0.5 uppercase tracking-wider">
                            {{ __('View E-Ticket') }}
                        </a> -->
                    </div>
                </div>

                <!-- <div class="flex justify-center mt-2 {{ $order->status == 'confirmed' ? 'hidden' : '' }}" id="return-button-container">
                    <a href="{{ route('orders.show', $order->id) }}" class="text-slate-500 hover:text-slate-700 text-sm font-medium underline">{{ __('Return to Ticket Details') }}</a>
                </div> -->
            </div>
    </div>
</div>
@endsection

@push('scripts')
@if($order->status == 'pending')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const pollInterval = 5000; // 5 seconds
        let isPolling = false;
        
        const pollStatus = () => {
            if (isPolling) return;
            isPolling = true;
            
            fetch("{{ route('orders.poll_status', $order->id) }}", {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'confirmed') {
                    // Hide instructions and show success
                    const instructionsBox = document.getElementById('payment-instructions-box');
                    if (instructionsBox) instructionsBox.classList.add('hidden');
                    
                    const statusContainer = document.getElementById('payment-status-container');
                    if (statusContainer) statusContainer.classList.add('hidden');
                    
                    const returnBtn = document.getElementById('return-button-container');
                    if (returnBtn) returnBtn.classList.add('hidden');
                    
                    // Keep the payment box but show success message inside it
                    const successContainer = document.getElementById('payment-success-container');
                    if (successContainer) {
                        successContainer.classList.remove('hidden');
                        successContainer.classList.add('block');
                    }
                } else if (data.status === 'failed') {
                    window.location.href = "{{ route('orders.show', $order->id) }}";
                } else {
                    isPolling = false;
                    setTimeout(pollStatus, pollInterval);
                }
            })
            .catch(error => {
                console.error('Polling error:', error);
                isPolling = false;
                setTimeout(pollStatus, pollInterval);
            });
        };
        
        // Start polling
        setTimeout(pollStatus, pollInterval);

        // Countdown Timer
        const expiryTimeStr = "{!! isset($order->payment_info['expiry_time']) ? \Carbon\Carbon::parse($order->payment_info['expiry_time'])->format('Y/m/d H:i:s') : \Carbon\Carbon::parse($order->created_at)->addHours(24)->format('Y/m/d H:i:s') !!}";
        const expiryTime = new Date(expiryTimeStr).getTime();
        const countdownElement = document.getElementById('countdown-timer');
        
        const updateCountdown = () => {
            const now = new Date().getTime();
            const distance = expiryTime - now;
            
            if (distance < 0) {
                if(countdownElement) {
                    countdownElement.innerHTML = "EXPIRED";
                    countdownElement.classList.remove('text-red-600');
                    countdownElement.classList.add('text-slate-500');
                }
                return;
            }
            
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            if(countdownElement) {
                countdownElement.innerHTML = 
                    (hours < 10 ? "0" + hours : hours) + ":" + 
                    (minutes < 10 ? "0" + minutes : minutes) + ":" + 
                    (seconds < 10 ? "0" + seconds : seconds);
            }
        };
        
        if(countdownElement) {
            updateCountdown();
            setInterval(updateCountdown, 1000);
        }
    });
</script>
@endif
@endpush
