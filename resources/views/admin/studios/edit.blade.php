@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-slate-900 mb-2">{{ __('Edit Studio:') }} {{ $studio->name }}</h2>
    <a href="{{ route('admin.studios.index') }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium">&larr; {{ __('Back to Studios') }}</a>
</div>

<div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm max-w-2xl">
    <form action="{{ route('admin.studios.update', $studio->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-slate-700">{{ __('Studio Name') }}</label>
            <input type="text" name="name" id="name" value="{{ old('name', $studio->name) }}" required class="mt-1 block w-full bg-white border border-slate-300 rounded-md shadow-sm py-2 px-3 text-slate-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="studio_type_id" class="block text-sm font-medium text-slate-700">{{ __('Studio Type') }}</label>
            <select name="studio_type_id" id="studio_type_id" required class="mt-1 block w-full bg-white border border-slate-300 rounded-md shadow-sm py-2 px-3 text-slate-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                @foreach($studioTypes as $type)
                    <option value="{{ $type->id }}" {{ old('studio_type_id', $studio->studio_type_id) == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                @endforeach
            </select>
            @error('studio_type_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mb-6">
            <label for="total_seats" class="block text-sm font-medium text-slate-700">{{ __('Total Seats') }}</label>
            <input type="number" name="total_seats" id="total_seats" value="{{ $studio->total_seats }}" disabled class="mt-1 block w-full bg-slate-100 border border-slate-300 rounded-md shadow-sm py-2 px-3 text-slate-500 cursor-not-allowed">
            <p class="text-xs text-red-500 mt-1">{{ __('Seat count cannot be modified after creation.') }}</p>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                {{ __('Update Studio') }}
            </button>
        </div>
    </form>
</div>
@endsection
