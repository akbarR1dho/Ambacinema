@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-slate-900 mb-2">{{ __('Add New Studio') }}</h2>
    <a href="{{ route('admin.studios.index') }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium">&larr; {{ __('Back to Studios') }}</a>
</div>

<div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm max-w-2xl">
    <form action="{{ route('admin.studios.store') }}" method="POST">
        @csrf
        
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-slate-700">{{ __('Studio Name') }}</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required class="mt-1 block w-full bg-white border border-slate-300 rounded-md shadow-sm py-2 px-3 text-slate-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="studio_type_id" class="block text-sm font-medium text-slate-700">{{ __('Studio Type') }}</label>
            <div class="mt-1 block w-full">
                <x-infinite-select 
                    id="studio_type_id" 
                    api-url="{{ route('admin.api.studio-types') }}" 
                    default-label="{{ __('Select Type') }}" 
                    placeholder="{{ __('Search type') }}..."
                />
            </div>
            @error('studio_type_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="mb-6">
            <label for="total_seats" class="block text-sm font-medium text-slate-700">{{ __('Total Seats') }}</label>
            <input type="number" name="total_seats" id="total_seats" value="{{ old('total_seats') }}" required min="1" max="500" class="mt-1 block w-full bg-white border border-slate-300 rounded-md shadow-sm py-2 px-3 text-slate-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            <p class="text-xs text-slate-500 mt-1">{{ __('Seats will be generated automatically up to this amount') }}.</p>
            @error('total_seats') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit" class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                {{ __('Save Studio') }}
            </button>
        </div>
    </form>
</div>
@endsection
