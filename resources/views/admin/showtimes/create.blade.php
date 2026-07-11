@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-slate-900 mb-2">{{ __('Add New Showtime') }}</h2>
    <a href="{{ route('admin.showtimes.index') }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium">&larr; {{ __('Back to Showtimes') }}</a>
</div>

<div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm max-w-2xl">
    <form action="{{ route('admin.showtimes.store') }}" method="POST">
        @csrf
        
        <div class="mb-4">
            <label for="movie_id" class="block text-sm font-medium text-slate-700">{{ __('Movie') }}</label>
            <div class="mt-1 block w-full">
                <x-infinite-select 
                    id="movie_id" 
                    api-url="{{ route('admin.api.movies') }}" 
                    default-label="{{ __('Select a Movie') }}" 
                    placeholder="{{ __('Search movie') }}..."
                />
            </div>
            @error('movie_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="studio_id" class="block text-sm font-medium text-slate-700">{{ __('Studio') }}</label>
            <div class="mt-1 block w-full">
                <x-infinite-select 
                    id="studio_id" 
                    api-url="{{ route('admin.api.studios') }}" 
                    default-label="{{ __('Select a Studio') }}" 
                    placeholder="{{ __('Search studio') }}..."
                />
            </div>
            @error('studio_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="mb-6">
            <label for="start_time" class="block text-sm font-medium text-slate-700">{{ __('Start Time') }}</label>
            <input type="datetime-local" name="start_time" id="start_time" value="{{ old('start_time') }}" min="{{ now('Asia/Jakarta')->addHour()->startOfHour()->format('Y-m-d\TH:i') }}" required class="mt-1 block w-full bg-white border border-slate-300 rounded-md shadow-sm py-2 px-3 text-slate-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            <p class="text-xs text-slate-500 mt-1">{{ __('Must be scheduled from the start of the next hour onwards') }}.</p>
            @error('start_time') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit" class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                {{ __('Save Showtime') }}
            </button>
        </div>
    </form>
</div>
@endsection
