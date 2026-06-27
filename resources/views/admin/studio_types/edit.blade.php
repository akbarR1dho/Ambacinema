@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-slate-900 mb-2">Edit Studio Type</h2>
    <a href="{{ route('admin.studio-types.index') }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium">&larr; Back to Studio Types</a>
</div>

<div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm max-w-2xl">
    <form action="{{ route('admin.studio-types.update', $studioType->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-slate-700">Type Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $studioType->name) }}" required class="mt-1 block w-full bg-white border border-slate-300 rounded-md shadow-sm py-2 px-3 text-slate-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <label for="price_weekday" class="block text-sm font-medium text-slate-700">Weekday Price (Mon-Thu)</label>
                <input type="number" name="price_weekday" id="price_weekday" value="{{ old('price_weekday', $studioType->price_weekday) }}" required min="0" class="mt-1 block w-full bg-white border border-slate-300 rounded-md shadow-sm py-2 px-3 text-slate-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                @error('price_weekday') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="price_friday" class="block text-sm font-medium text-slate-700">Friday Price</label>
                <input type="number" name="price_friday" id="price_friday" value="{{ old('price_friday', $studioType->price_friday) }}" required min="0" class="mt-1 block w-full bg-white border border-slate-300 rounded-md shadow-sm py-2 px-3 text-slate-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                @error('price_friday') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="price_weekend" class="block text-sm font-medium text-slate-700">Weekend Price (Sat-Sun)</label>
                <input type="number" name="price_weekend" id="price_weekend" value="{{ old('price_weekend', $studioType->price_weekend) }}" required min="0" class="mt-1 block w-full bg-white border border-slate-300 rounded-md shadow-sm py-2 px-3 text-slate-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                @error('price_weekend') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-slate-700">Description</label>
            <textarea name="description" id="description" rows="3" class="mt-1 block w-full bg-white border border-slate-300 rounded-md shadow-sm py-2 px-3 text-slate-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('description', $studioType->description) }}</textarea>
            @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                Update Studio Type
            </button>
        </div>
    </form>
</div>
@endsection
