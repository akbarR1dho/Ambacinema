@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-slate-900 mb-2">{{ __('Order Details:') }} #{{ $order->id }}</h2>
    <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium">&larr; {{ __('Back to Orders') }}</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
    <!-- Customer & Movie Info Card -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50">
            <h3 class="text-lg font-bold text-slate-900">{{ __('Customer & Movie Info') }}</h3>
        </div>
        <div class="p-6">
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-6">
                <div>
                    <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">{{ __('Customer Name') }}</dt>
                    <dd class="text-base font-bold text-slate-900">{{ $order->user->name }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">{{ __('Email') }}</dt>
                    <dd class="text-base font-bold text-slate-900 break-all">{{ $order->user->email }}</dd>
                </div>
                <div class="sm:col-span-2 border-t border-slate-100 pt-6">
                    <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">{{ __('Movie Title') }}</dt>
                    <dd class="text-lg font-bold text-slate-900">{{ $order->showtime->movie->title }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">{{ __('Studio') }}</dt>
                    <dd class="text-base font-bold text-slate-900">{{ $order->showtime->studio->name }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">{{ __('Showtime') }}</dt>
                    <dd class="text-base font-bold text-slate-900">{{ $order->showtime->start_time_local->format('d M Y, H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Booking Summary Card -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50">
            <h3 class="text-lg font-bold text-slate-900">{{ __('Booking Summary') }}</h3>
        </div>
        <div class="p-6 flex-grow">
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-6">
                <div class="sm:col-span-2">
                    <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">{{ __('Status') }}</dt>
                    <dd>
                        @if($order->status == 'confirmed')
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold bg-green-100 text-green-700 uppercase tracking-wide border border-green-200">{{ __('Confirmed') }}</span>
                        @elseif($order->status == 'pending')
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold bg-yellow-100 text-yellow-700 uppercase tracking-wide border border-yellow-200">{{ __('Pending') }}</span>
                        @else
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold bg-red-100 text-red-700 uppercase tracking-wide border border-red-200">{{ __('Failed') }}</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">{{ __('Booking Time') }}</dt>
                    <dd class="text-sm font-bold text-slate-800">{{ $order->pending_at_local ? $order->pending_at_local->format('d M Y, H:i') : '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">{{ __('Confirmation Time') }}</dt>
                    <dd class="text-sm font-bold text-slate-800">{{ $order->confirmed_at_local ? $order->confirmed_at_local->format('d M Y, H:i') : '-' }}</dd>
                </div>
                @if($order->failed_at)
                    <div>
                        <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">{{ __('Failed Time') }}</dt>
                        <dd class="text-sm font-bold text-slate-800">{{ $order->failed_at_local->format('d M Y, H:i') }}</dd>
                    </div>
                @endif
                <div class="sm:col-span-2 border-t border-slate-100 pt-6">
                    <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">{{ __('Seats Booked') }}</dt>
                    <dd>
                        <!-- <div class="flex flex-wrap gap-2">
                            @foreach($order->seats as $seat)
                                <span class="bg-slate-50 text-slate-900 border border-slate-200 px-3 py-1.5 rounded-lg text-sm font-bold">{{ $seat->seat_number }}</span>
                            @endforeach
                        </div> -->
                        <div>
                        <div id="seats-container" class="flex flex-wrap gap-2 relative transition-all duration-300 max-h-[100px] overflow-hidden">
                            @foreach($order->seats as $seat)
                                <span class="bg-slate-50 text-slate-900 border border-slate-200 px-3 py-1.5 rounded-lg text-sm font-bold">{{ $seat->seat_number }}</span>
                            @endforeach
                            <div id="seats-gradient" class="hidden absolute bottom-0 left-0 right-0 h-10 bg-gradient-to-t from-white to-transparent pointer-events-none transition-opacity duration-300"></div>
                        </div>

                        <button type="button" id="seats-toggle-btn" onclick="toggleSeats()" class="hidden mt-3 text-xs font-bold text-slate-700 hover:text-blue-600 transition-colors items-center gap-1 focus:outline-none">
                            <span id="seats-toggle-text">{{ __('View All') }}</span>
                            <svg id="seats-icon-down" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            <svg id="seats-icon-up" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                        </button>

                        <script>
                            (function() {
                                const container = document.getElementById('seats-container');
                                const gradient = document.getElementById('seats-gradient');
                                const toggleBtn = document.getElementById('seats-toggle-btn');
                                
                                function checkOverflow() {
                                    if (container.classList.contains('max-h-[100px]')) {
                                        if (container.scrollHeight > 100) {
                                            gradient.classList.remove('hidden');
                                            toggleBtn.classList.remove('hidden');
                                            toggleBtn.classList.add('flex');
                                        } else {
                                            gradient.classList.add('hidden');
                                            toggleBtn.classList.add('hidden');
                                            toggleBtn.classList.remove('flex');
                                        }
                                    }
                                }
                                
                                // Check shortly after render
                                setTimeout(checkOverflow, 50);
                                
                                // Check on window resize
                                window.addEventListener('resize', () => {
                                    if (container.classList.contains('max-h-[100px]')) {
                                        checkOverflow();
                                    } else {
                                        // If expanded and window widened enough to not overflow
                                        if (container.scrollHeight <= 100) {
                                            toggleSeats();
                                            checkOverflow();
                                        }
                                    }
                                });
                            })();

                            function toggleSeats() {
                                const container = document.getElementById('seats-container');
                                const gradient = document.getElementById('seats-gradient');
                                const text = document.getElementById('seats-toggle-text');
                                const iconDown = document.getElementById('seats-icon-down');
                                const iconUp = document.getElementById('seats-icon-up');
                                
                                const isExpanded = !container.classList.contains('max-h-[100px]');
                                
                                if (isExpanded) {
                                    // Collapse
                                    container.classList.add('max-h-[100px]');
                                    if(gradient) gradient.classList.remove('opacity-0');
                                    text.innerText = '{{ __('View All') }}';
                                    iconDown.classList.remove('hidden');
                                    iconUp.classList.add('hidden');
                                } else {
                                    // Expand
                                    container.classList.remove('max-h-[100px]');
                                    if(gradient) gradient.classList.add('opacity-0');
                                    text.innerText = '{{ __('Less') }}';
                                    iconDown.classList.add('hidden');
                                    iconUp.classList.remove('hidden');
                                }
                            }
                        </script>
                    </div>
                    </dd>
                </div>
            </dl>
        </div>
        <div class="px-6 py-5 bg-slate-50 border-t border-slate-200">
            <div class="flex justify-between items-center">
                <span class="text-lg font-bold text-slate-900 uppercase tracking-wide">{{ __('Total') }}</span>
                <span class="text-2xl font-black text-blue-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
