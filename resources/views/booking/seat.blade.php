@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <a href="{{ route('movie.show', $showtime->movie_id) }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            {{ __('Back to Showtimes') }}
        </a>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Seat Map -->
        <div class="w-full lg:w-2/3 bg-white border border-slate-200 rounded-2xl p-8 shadow-2xl">
            <div class="text-center mb-10">
                <h2 class="text-2xl font-bold text-slate-900 uppercase tracking-wider mb-2">{{ __('Select Your Seats') }}</h2>
                <p class="text-slate-500">{{ $showtime->studio->name }}</p>
            </div>

            <form action="{{ route('checkout.process') }}" method="POST" id="bookingForm">
                @csrf
                <input type="hidden" name="showtime_id" value="{{ $showtime->id }}">
                
                <div class="w-full overflow-x-auto pb-8">
                    <div class="flex flex-col min-w-max px-4 md:px-8">
                        <!-- Cinema Screen -->
                        <div class="w-4/5 md:w-3/4 mx-auto mb-16 relative">
                            <div class="h-2 w-full bg-gradient-to-r from-blue-900 via-blue-500 to-blue-900 rounded-full shadow-[0_0_20px_rgba(59,130,246,0.5)]"></div>
                            <div class="w-full h-12 bg-gradient-to-b from-blue-500/20 to-transparent transform perspective-1000 rotateX-45 blur-sm mt-2"></div>
                            <p class="text-center text-slate-400 text-xs font-semibold tracking-[0.3em] mt-4 uppercase">{{ __('Cinema Screen') }}</p>
                        </div>

                        @php
                            $groupedSeats = $allSeats->groupBy(function($seat) {
                                return substr($seat->seat_number, 0, 1);
                            });
                        @endphp
                        
                        @foreach($groupedSeats as $rowLetter => $seatsInRow)
                            <div class="flex justify-center gap-2 mb-4">
                                @foreach($seatsInRow as $seat)
                                    @php
                                        $isBooked = in_array($seat->id, $bookedSeatIds);
                                        $seatNum = (int) substr($seat->seat_number, 1);
                                        // 3 separators for a 10-seat row: after 2, 5, and 8
                                        $hasAisle = (in_array($seatNum, [2, 5, 8]) && count($seatsInRow) > 5);
                                    @endphp
                                    <div class="relative group {{ $hasAisle ? 'mr-6 md:mr-10' : '' }}">
                                        <input type="checkbox" name="seats[]" value="{{ $seat->id }}" id="seat-{{ $seat->id }}" class="peer hidden" {{ $isBooked ? 'disabled' : '' }}>
                                        <label for="seat-{{ $seat->id }}" class="
                                            flex items-center justify-center w-10 h-10 md:w-12 md:h-12 rounded-t-lg border-b-4 text-xs font-bold transition-all duration-200 cursor-pointer
                                            {{ $isBooked 
                                                ? 'bg-slate-200 border-slate-300 text-slate-400 cursor-not-allowed' 
                                                : 'bg-white border-slate-300 text-slate-700 hover:bg-slate-50 peer-checked:border-blue-800 peer-checked:shadow-[0_0_15px_rgba(37,99,235,0.5)]' 
                                            }}
                                        ">
                                            {{ $seat->seat_number }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Legend -->
                <div class="flex items-center justify-center space-x-8 mt-12 pt-6 border-t border-slate-200">
                    <div class="flex items-center">
                        <div class="w-6 h-6 rounded-t bg-white border-b-2 border-slate-300 mr-2 shadow-sm"></div>
                        <span class="text-sm text-slate-500">{{ __('Available') }}</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-6 h-6 rounded-t bg-white border-b-2 border-blue-800 mr-2 shadow-[0_0_10px_rgba(37,99,235,0.3)]"></div>
                        <span class="text-sm text-slate-500">{{ __('Selected') }}</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-6 h-6 rounded-t bg-slate-200 border-b-2 border-slate-300 mr-2"></div>
                        <span class="text-sm text-slate-500">{{ __('Booked') }}</span>
                    </div>
                </div>
            </form>
        </div>

        <!-- Booking Summary Sidebar -->
        <div class="w-full lg:w-1/3">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-2xl sticky top-24">
                <h3 class="text-xl font-bold text-slate-900 mb-6 uppercase tracking-wider border-b border-slate-200 pb-4">{{ __('Booking Summary') }}</h3>
                
                <div class="mb-6 flex gap-4">
                    @if($showtime->movie->poster)
                        <img src="{{ Storage::url($showtime->movie->poster) }}" alt="Poster" class="w-20 h-28 object-cover rounded-md shadow-md">
                    @endif
                    <div>
                        <h4 class="font-bold text-lg text-slate-900 leading-tight">{{ $showtime->movie->title }}</h4>
                        <p class="text-sm text-slate-500 mt-1">{{ \Carbon\Carbon::parse($showtime->start_time)->translatedFormat('D, d M Y') }}</p>
                        <p class="text-sm text-slate-500">{{ \Carbon\Carbon::parse($showtime->start_time)->translatedFormat('H:i') }} | {{ $showtime->studio->name }}</p>
                    </div>
                </div>

                <div class="border-t border-slate-200 pt-4 mb-6">
                    <div class="flex justify-between items-start gap-4 text-sm mb-2">
                        <span class="text-slate-500 shrink-0">{{ __('Selected Seats') }} (<span id="seatCount">0</span>)</span>
                        <span class="text-slate-900 font-medium text-right leading-relaxed" id="selectedSeatsList">-</span>
                    </div>
                    <div class="flex justify-between text-sm mb-4">
                        <span class="text-slate-500">{{ __('Price per Seat') }}</span>
                        <span class="text-slate-900 font-medium">Rp {{ number_format($price, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold border-t border-slate-200 pt-4">
                        <span class="text-slate-900">{{ __('Total Payment') }}</span>
                        <span class="text-blue-600">Rp <span id="totalPrice">0</span></span>
                    </div>
                </div>
                <div class="border-t border-slate-200 pt-4 mb-6">
                    <h4 class="font-bold text-slate-800 mb-3">{{ __('Payment Method') }}</h4>
                    <div class="space-y-2 mb-4">
                        <label class="relative flex cursor-pointer rounded-lg text-slate-800 bg-white p-3 border border-slate-300 shadow-sm focus:outline-none hover:border-blue-500 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                            <input type="radio" name="payment_type" value="bca_va" class="sr-only peer payment-radio" form="bookingForm">
                            <span class="flex flex-1">
                                <span class="flex flex-col text-left">
                                    <span class="block text-sm font-medium">{{ __('BCA Virtual Account') }}</span>
                                </span>
                            </span>
                            <svg class="h-5 w-5 text-blue-600 opacity-0 peer-checked:opacity-100 transition-opacity" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="pointer-events-none absolute -inset-px rounded-lg border-2 border-transparent peer-checked:border-blue-500" aria-hidden="true"></span>
                        </label>
                        <label class="relative flex cursor-pointer rounded-lg text-slate-800 bg-white p-3 border border-slate-300 shadow-sm focus:outline-none hover:border-blue-500 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                            <input type="radio" name="payment_type" value="echannel" class="sr-only peer payment-radio" form="bookingForm">
                            <span class="flex flex-1">
                                <span class="flex flex-col text-left">
                                    <span class="block text-sm font-medium">{{ __('Mandiri Bill (VA)') }}</span>
                                </span>
                            </span>
                            <svg class="h-5 w-5 text-blue-600 opacity-0 peer-checked:opacity-100 transition-opacity" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="pointer-events-none absolute -inset-px rounded-lg border-2 border-transparent peer-checked:border-blue-500" aria-hidden="true"></span>
                        </label>
                        <label class="relative flex cursor-pointer rounded-lg text-slate-800 bg-white p-3 border border-slate-300 shadow-sm focus:outline-none hover:border-blue-500 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                            <input type="radio" name="payment_type" value="gopay" class="sr-only peer payment-radio" form="bookingForm">
                            <span class="flex flex-1">
                                <span class="flex flex-col text-left">
                                    <span class="block text-sm font-medium">{{ __('QRIS') }}</span>
                                </span>
                            </span>
                            <svg class="h-5 w-5 text-blue-600 opacity-0 peer-checked:opacity-100 transition-opacity" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="pointer-events-none absolute -inset-px rounded-lg border-2 border-transparent peer-checked:border-blue-500" aria-hidden="true"></span>
                        </label>
                    </div>
                </div>

                <button type="button" onclick="document.getElementById('bookingForm').submit()" id="checkoutBtn" disabled class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-slate-200 disabled:text-slate-400 disabled:cursor-not-allowed text-white font-bold py-4 px-4 rounded-xl transition-colors uppercase tracking-wider shadow-lg">
                    {{ __('Confirm & Pay') }}
                </button>
                @error('seats') <p class="text-red-500 text-xs mt-2 text-center">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('input[name="seats[]"]');
        const paymentRadios = document.querySelectorAll('.payment-radio');
        const seatCountEl = document.getElementById('seatCount');
        const selectedSeatsListEl = document.getElementById('selectedSeatsList');
        const totalPriceEl = document.getElementById('totalPrice');
        const checkoutBtn = document.getElementById('checkoutBtn');
        const pricePerSeat = {{ $price }};

        function updateSummary() {
            let selectedCount = 0;
            let selectedLabels = [];
            
            checkboxes.forEach(cb => {
                if(cb.checked) {
                    selectedCount++;
                    // Find the label text corresponding to this checkbox
                    const label = document.querySelector(`label[for="${cb.id}"]`).innerText.trim();
                    selectedLabels.push(label);
                }
            });

            seatCountEl.innerText = selectedCount;
            selectedSeatsListEl.innerText = selectedCount > 0 ? selectedLabels.join(', ') : '-';
            
            const total = selectedCount * pricePerSeat;
            totalPriceEl.innerText = total.toLocaleString('id-ID');

            const isPaymentSelected = Array.from(paymentRadios).some(r => r.checked);
            checkoutBtn.disabled = selectedCount === 0 || !isPaymentSelected;
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateSummary);
        });
        
        paymentRadios.forEach(radio => {
            radio.addEventListener('change', updateSummary);
        });
    });
</script>
@endpush
@endsection
