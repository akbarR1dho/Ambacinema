@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-slate-900 mb-2">Add New Studio</h2>
    <a href="{{ route('admin.studios.index') }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium">&larr; Back to Studios</a>
</div>

<div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm max-w-2xl">
    <form action="{{ route('admin.studios.store') }}" method="POST">
        @csrf
        
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-slate-700">Studio Name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required class="mt-1 block w-full bg-white border border-slate-300 rounded-md shadow-sm py-2 px-3 text-slate-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="studio_type_id" class="block text-sm font-medium text-slate-700">Studio Type</label>
            <select name="studio_type_id" id="studio_type_id" required class="mt-1 block w-full bg-white border border-slate-300 rounded-md shadow-sm py-2 px-3 text-slate-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select Type</option>
                @foreach($studioTypes as $type)
                    <option value="{{ $type->id }}" {{ old('studio_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                @endforeach
            </select>
            @error('studio_type_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mb-6">
            <label for="total_seats" class="block text-sm font-medium text-slate-700">Total Seats</label>
            <input type="number" name="total_seats" id="total_seats" value="{{ old('total_seats') }}" required min="1" max="500" class="mt-1 block w-full bg-white border border-slate-300 rounded-md shadow-sm py-2 px-3 text-slate-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            <p class="text-xs text-slate-500 mt-1">Seats will be generated automatically up to this amount.</p>
            @error('total_seats') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                Save Studio
            </button>
        </div>
    </form>
</div>
@endsection
