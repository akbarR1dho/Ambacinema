@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
<style>
    .swiper-button-next, .swiper-button-prev { color: #2563eb; }
    .swiper-pagination-bullet-active { background: #2563eb; }
    .swiper-container-horizontal>.swiper-pagination-bullets, .swiper-pagination-custom, .swiper-pagination-fraction { bottom: 0px; }
    .movie-swiper { padding-bottom: 40px; }
</style>
@endpush

@section('content')
<!-- Hero Section (Carousel/Banner placeholder) -->
<div class="relative bg-blue-900 overflow-hidden mb-12">
    <div class="absolute inset-0 bg-gradient-to-r from-blue-950 via-blue-900/80 to-transparent z-10"></div>
    <div class="h-[400px] w-full object-cover opacity-40 bg-[url('https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80')] bg-cover bg-center"></div>
    <div class="absolute inset-0 z-20 flex flex-col justify-center max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-4 tracking-tight">EXPERIENCE <br><span class="text-blue-400">CINEMA</span> LIKE NEVER BEFORE</h1>
        <p class="text-blue-100 text-lg md:text-xl max-w-2xl mb-8">Book your tickets now for the latest blockbuster movies playing in Ambacinema theaters.</p>
        <div>
            <a href="#now-playing" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-full transition-colors inline-block uppercase tracking-wider text-sm shadow-lg shadow-blue-900/30">Now Playing</a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" id="now-playing">
    <div class="flex items-center justify-between mb-8 border-b border-slate-200 pb-4">
        <h2 class="text-2xl font-bold text-slate-900 uppercase tracking-wider border-l-4 border-blue-600 pl-3">Now Playing</h2>
    </div>

    @if($movies->count() > 4)
        <!-- Swiper Carousel Layout -->
        <div class="swiper movie-swiper">
            <div class="swiper-wrapper">
                @foreach($movies as $movie)
                    <div class="swiper-slide">
                        <div class="group relative rounded-xl overflow-hidden bg-white border border-slate-200 hover:border-blue-500 transition-all duration-300 shadow-sm hover:shadow-xl hover:shadow-blue-900/10">
                            <div class="aspect-[2/3] w-full overflow-hidden bg-slate-100 relative">
                                @if($movie->poster)
                                    <img src="{{ Storage::url($movie->poster) }}" alt="{{ $movie->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-400 bg-slate-100">
                                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent opacity-90"></div>
                                <div class="absolute bottom-0 left-0 p-4 w-full">
                                    <h3 class="text-lg font-bold text-white truncate drop-shadow-md">{{ $movie->title }}</h3>
                                    <p class="text-sm text-slate-200 mt-1 flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $movie->duration }} mins
                                    </p>
                                </div>
                                
                                <!-- Hover Overlay -->
                                <div class="absolute inset-0 bg-slate-900/80 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 backdrop-blur-sm">
                                    <a href="{{ route('movie.show', $movie->id) }}" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-full transform -translate-y-4 group-hover:translate-y-0 transition-all duration-300 shadow-lg shadow-blue-900/50 uppercase text-sm tracking-wider">Buy Ticket</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>
            <!-- Add Navigation -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    @elseif($movies->count() > 0)
        <!-- Grid Layout -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($movies as $movie)
                <div class="group relative rounded-xl overflow-hidden bg-white border border-slate-200 hover:border-blue-500 transition-all duration-300 shadow-sm hover:shadow-xl hover:shadow-blue-900/10">
                    <div class="aspect-[2/3] w-full overflow-hidden bg-slate-100 relative">
                        @if($movie->poster)
                            <img src="{{ Storage::url($movie->poster) }}" alt="{{ $movie->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-400 bg-slate-100">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent opacity-90"></div>
                        <div class="absolute bottom-0 left-0 p-4 w-full">
                            <h3 class="text-lg font-bold text-white truncate drop-shadow-md">{{ $movie->title }}</h3>
                            <p class="text-sm text-slate-200 mt-1 flex items-center">
                                <svg class="w-4 h-4 mr-1 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $movie->duration }} mins
                            </p>
                        </div>
                        
                        <!-- Hover Overlay -->
                        <div class="absolute inset-0 bg-slate-900/80 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 backdrop-blur-sm">
                            <a href="{{ route('movie.show', $movie->id) }}" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-full transform -translate-y-4 group-hover:translate-y-0 transition-all duration-300 shadow-lg shadow-blue-900/50 uppercase text-sm tracking-wider">Buy Ticket</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-20 bg-white rounded-xl border border-slate-200">
            <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path></svg>
            <h3 class="text-xl font-medium text-slate-600">No movies playing right now</h3>
            <p class="text-slate-500 mt-2">Check back later for exciting new releases!</p>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (document.querySelector('.movie-swiper')) {
            new Swiper('.movie-swiper', {
                slidesPerView: 2,
                spaceBetween: 24,
                loop: false,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    640: { slidesPerView: 3, spaceBetween: 24 },
                    1024: { slidesPerView: 4, spaceBetween: 24 },
                    1280: { slidesPerView: 5, spaceBetween: 24 },
                }
            });
        }
    });
</script>
@endpush
