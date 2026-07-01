@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col lg:flex-row gap-10">
        
        <!-- Movie Poster & Quick Info (Left Side) -->
        <div class="w-full lg:w-1/3 xl:w-1/4 flex flex-col items-center lg:items-start">
            <div class="w-64 lg:w-full rounded-2xl overflow-hidden shadow-2xl border border-slate-200 mb-6 relative group">
                @if($movie->poster)
                    <img src="{{ Storage::url($movie->poster) }}" alt="{{ $movie->title }}" class="w-full h-auto object-cover">
                @else
                    <div class="w-full h-96 flex items-center justify-center text-slate-400 bg-slate-100">
                        <span class="text-xl">{{ __('No Image') }}</span>
                    </div>
                @endif
                <div class="absolute inset-0 bg-black/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 backdrop-blur-sm cursor-pointer">
                    <svg class="w-16 h-16 text-white opacity-80" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg>
                </div>
            </div>
            
            <h1 class="text-3xl font-extrabold text-slate-900 mb-2 uppercase tracking-tight text-center lg:text-left leading-tight">{{ $movie->title }}</h1>
            
            <div class="flex items-center text-sm text-slate-600 mb-6 space-x-3 justify-center lg:justify-start w-full bg-white p-3 rounded-xl border border-slate-200">
                <span class="flex items-center"><svg class="w-4 h-4 mr-1 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ $movie->duration }} {{ __('mins') }}</span>
                @php
                    $ageRatingColor = match($movie->age_rating) {
                        'SU' => 'bg-green-100 text-green-700 border-green-200',
                        '13+' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                        '17+' => 'bg-red-100 text-red-700 border-red-200',
                        '21+' => 'bg-slate-100 text-slate-700 border-slate-200',
                        default => 'bg-green-100 text-green-700 border-green-200',
                    };
                @endphp
                <span class="{{ $ageRatingColor }} px-2 py-0.5 rounded text-xs font-bold border">{{ $movie->age_rating ?? 'SU' }}</span>
                <!-- <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-xs border border-blue-200 font-bold">2D</span> -->
            </div>
        </div>

        <!-- Tabs and Content (Right Side) -->
        <div class="w-full lg:w-2/3 xl:w-3/4">
            
            <!-- Tabs -->
            <div class="flex border-b border-slate-200 mb-8">
                <button id="tab-jadwal" class="px-6 py-3 text-lg font-bold text-blue-600 border-b-2 border-blue-600 transition-colors focus:outline-none">{{ __('Schedule') }}</button>
                <button id="tab-detail" class="px-6 py-3 text-lg font-bold text-slate-500 border-b-2 border-transparent hover:text-slate-700 transition-colors focus:outline-none">{{ __('Detail') }}</button>
            </div>

            <!-- Detail Content -->
            <div id="content-detail" class="hidden prose prose-slate max-w-none bg-white p-6 rounded-2xl border border-slate-200">
                <h3 class="text-xl font-bold text-slate-900 mb-3">{{ __('Synopsis') }}</h3>
                <p class="text-slate-700 leading-relaxed text-justify">{{ $movie->description }}</p>
            </div>

            <!-- Jadwal Content -->
            <div id="content-jadwal">
                <!-- Date Selector -->
                <div class="flex items-center space-x-3 overflow-x-auto p-1 mb-8 custom-scrollbar">
                    @foreach($dates as $index => $d)
                        <button 
                            class="date-btn flex flex-col items-center justify-center min-w-[75px] md:min-w-[85px] py-3 rounded-2xl border transition-all duration-200 focus:outline-none flex-shrink-0
                                {{ $d->has_showtimes ? 'border-slate-300 bg-white hover:border-blue-500 hover:bg-slate-50 cursor-pointer shadow-sm' : 'border-slate-200 bg-slate-50 opacity-50 cursor-not-allowed' }}"
                            {{ !$d->has_showtimes ? 'disabled' : '' }}
                            data-target="date-{{ $d->date }}"
                            title="{{ $d->month_year }}"
                        >
                            <span class="text-xs font-medium {{ $d->has_showtimes ? 'text-slate-500' : 'text-slate-400' }} mb-1 uppercase tracking-wide">{{ $d->day_name }}</span>
                            <span class="text-2xl font-bold {{ $d->has_showtimes ? 'text-slate-900' : 'text-slate-400' }}">{{ $d->day_number }}</span>
                        </button>
                    @endforeach
                </div>

                <!-- Showtimes Container -->
                <div class="relative">
                    @foreach($dates as $index => $d)
                        <div id="date-{{ $d->date }}" class="date-content hidden">
                            @if($d->has_showtimes)
                                <!-- Type Tabs -->
                                <div class="flex flex-wrap gap-2 mb-6">
                                    @foreach($showtimesByDate[$d->date] as $typeName => $studiosGroup)
                                        <button class="type-btn-{{ $d->date }} px-5 py-2 rounded-xl border border-slate-300 font-bold transition-all focus:outline-none text-slate-800 bg-white hover:border-blue-500 hover:text-slate-900 data-[active=true]:border-blue-500 data-[active=true]:bg-blue-50" data-target="type-{{ $d->date }}-{{ Str::slug($typeName) }}" data-type-name="{{ $typeName }}">
                                            {{ $typeName }}
                                        </button>
                                    @endforeach
                                </div>

                                <div class="space-y-4">
                                    @foreach($showtimesByDate[$d->date] as $typeName => $studiosGroup)
                                        <div id="type-{{ $d->date }}-{{ Str::slug($typeName) }}" class="type-content-{{ $d->date }} hidden space-y-4">
                                            @foreach($studiosGroup as $studioId => $studioShowtimes)
                                                <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden transition-colors hover:border-slate-300 shadow-sm">
                                                    <!-- Accordion Header -->
                                                    <button class="accordion-btn w-full px-6 py-5 flex justify-between items-center focus:outline-none bg-white hover:bg-slate-50 transition-colors">
                                                        <span class="text-lg font-bold text-slate-900 uppercase tracking-wider">{{ $studioShowtimes->first()->studio->name }}</span>
                                                        <svg class="w-5 h-5 text-slate-400 transform transition-transform duration-200 accordion-icon rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                                    </button>
                                                    <!-- Accordion Body -->
                                                    <div class="accordion-content px-6 pb-6 pt-2 border-t border-slate-200 bg-slate-50/50">
                                                        <div class="flex flex-wrap gap-4 mt-3">
                                                            @foreach($studioShowtimes as $st)
                                                                @auth
                                                                    <a href="{{ route('booking.seat', $st->id) }}" class="inline-flex items-center justify-center px-6 py-2.5 bg-white border border-slate-300 hover:border-blue-500 hover:bg-blue-50 hover:text-blue-700 text-slate-800 font-bold rounded-xl transition-all duration-200 shadow-sm text-lg">
                                                                        {{ \Carbon\Carbon::parse($st->start_time)->format('H:i') }}
                                                                    </a>
                                                                @else
                                                                    <button type="button" onclick="promptLogin(event)" class="inline-flex items-center justify-center px-6 py-2.5 bg-white border border-slate-300 hover:border-blue-500 hover:bg-blue-50 hover:text-blue-700 text-slate-800 font-bold rounded-xl transition-all duration-200 shadow-sm text-lg focus:outline-none">
                                                                        {{ \Carbon\Carbon::parse($st->start_time)->format('H:i') }}
                                                                    </button>
                                                                @endauth
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="bg-white border border-slate-200 rounded-2xl p-10 text-center mt-4">
                                    <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <p class="text-slate-500 text-lg">{{ __('No showtimes available for this date.') }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .custom-scrollbar::-webkit-scrollbar { height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #2563eb; }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab Logic
        const tabJadwal = document.getElementById('tab-jadwal');
        const tabDetail = document.getElementById('tab-detail');
        const contentJadwal = document.getElementById('content-jadwal');
        const contentDetail = document.getElementById('content-detail');

        function activateTab(tabName) {
            if (tabName === 'jadwal') {
                tabJadwal.classList.add('text-blue-600', 'border-blue-600');
                tabJadwal.classList.remove('text-slate-500', 'border-transparent');
                tabDetail.classList.remove('text-blue-600', 'border-blue-600');
                tabDetail.classList.add('text-slate-500', 'border-transparent');
                
                contentJadwal.classList.remove('hidden');
                contentDetail.classList.add('hidden');
            } else {
                tabDetail.classList.add('text-blue-600', 'border-blue-600');
                tabDetail.classList.remove('text-slate-500', 'border-transparent');
                tabJadwal.classList.remove('text-blue-600', 'border-blue-600');
                tabJadwal.classList.add('text-slate-500', 'border-transparent');
                
                contentDetail.classList.remove('hidden');
                contentJadwal.classList.add('hidden');
            }
        }

        tabJadwal.addEventListener('click', () => activateTab('jadwal'));
        tabDetail.addEventListener('click', () => activateTab('detail'));

        // Function to initialize type tabs for a specific date
        function initTypeTabsForDate(dateStr) {
            const typeBtns = document.querySelectorAll('.type-btn-' + dateStr);
            const typeContents = document.querySelectorAll('.type-content-' + dateStr);
            
            if (typeBtns.length === 0) return;

            // Reset all
            typeBtns.forEach(btn => btn.setAttribute('data-active', 'false'));
            typeContents.forEach(c => c.classList.add('hidden'));

            // Find default (Regular or first)
            let defaultBtn = Array.from(typeBtns).find(btn => btn.dataset.typeName.toLowerCase() === 'regular');
            if (!defaultBtn) defaultBtn = typeBtns[0];

            if (defaultBtn) {
                defaultBtn.setAttribute('data-active', 'true');
                document.getElementById(defaultBtn.dataset.target).classList.remove('hidden');
            }
        }

        // Setup click listeners for type tabs
        document.querySelectorAll('[class*="type-btn-"]').forEach(btn => {
            btn.addEventListener('click', function() {
                const dateStr = this.className.match(/type-btn-(\d{4}-\d{2}-\d{2})/)[1];
                const typeBtns = document.querySelectorAll('.type-btn-' + dateStr);
                const typeContents = document.querySelectorAll('.type-content-' + dateStr);
                
                typeBtns.forEach(b => b.setAttribute('data-active', 'false'));
                typeContents.forEach(c => c.classList.add('hidden'));
                
                this.setAttribute('data-active', 'true');
                document.getElementById(this.dataset.target).classList.remove('hidden');
            });
        });

        // Date Selection Logic
        const dateBtns = document.querySelectorAll('.date-btn');
        const dateContents = document.querySelectorAll('.date-content');

        // By default, select the first date that has showtimes
        let initialized = false;
        dateBtns.forEach(btn => {
            if (!initialized && !btn.disabled) {
                // Set active
                btn.classList.add('ring-2', 'ring-blue-500', 'bg-blue-50', 'border-blue-500');
                const targetId = btn.dataset.target;
                document.getElementById(targetId).classList.remove('hidden');
                
                // Init type tabs for this date
                const dateStr = targetId.replace('date-', '');
                initTypeTabsForDate(dateStr);
                
                initialized = true;
            }
            
            btn.addEventListener('click', function() {
                if(this.disabled) return;
                
                // Reset all buttons
                dateBtns.forEach(b => {
                    b.classList.remove('ring-2', 'ring-blue-500', 'bg-blue-50', 'border-blue-500');
                });
                
                // Hide all contents
                dateContents.forEach(c => c.classList.add('hidden'));
                
                // Set active
                this.classList.add('ring-2', 'ring-blue-500', 'bg-blue-50', 'border-blue-500');
                const targetId = this.dataset.target;
                document.getElementById(targetId).classList.remove('hidden');
                
                // Init type tabs for this date
                const dateStr = targetId.replace('date-', '');
                initTypeTabsForDate(dateStr);
            });
        });

        // Accordion Logic
        const accordions = document.querySelectorAll('.accordion-btn');
        accordions.forEach(acc => {
            acc.addEventListener('click', function() {
                const content = this.nextElementSibling;
                const icon = this.querySelector('.accordion-icon');
                
                content.classList.toggle('hidden');
                icon.classList.toggle('rotate-180');
            });
        });
    });

    function promptLogin(event) {
        event.preventDefault();
        Swal.fire({
            title: '<span class="text-2xl font-extrabold text-blue-500 tracking-tighter uppercase italic">Amba<span class="text-white">cinema</span></span>',
            html: '<p class="text-slate-600 mt-2">{{ __("You must log in first to select a seat.") }}</p>',
            icon: 'warning',
            iconColor: '#3b82f6',
            showCancelButton: true,
            confirmButtonText: '{{ __("Login Now") }}',
            cancelButtonText: '{{ __("Cancel") }}',
            background: '#0f172a',
            buttonsStyling: false,
            customClass: {
                popup: 'border border-slate-200 rounded-2xl shadow-2xl',
                confirmButton: 'bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-xl transition-colors w-full sm:w-auto',
                cancelButton: 'bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2.5 px-6 rounded-xl transition-colors w-full sm:w-auto mt-3 sm:mt-0 sm:ml-3 border border-slate-300',
                actions: 'w-full flex flex-col sm:flex-row justify-center mt-6'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('login') }}";
            }
        });
    }
</script>
@endpush
