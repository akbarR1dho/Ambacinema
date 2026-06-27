@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-slate-900 mb-2">Add New Movie</h2>
    <a href="{{ route('admin.movies.index') }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium">&larr; Back to Movies</a>
</div>

<div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm max-w-3xl">
    <form action="{{ route('admin.movies.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-slate-700">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" required class="mt-1 block w-full bg-white border border-slate-300 rounded-md shadow-sm py-2 px-3 text-slate-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-slate-700">Description</label>
            <textarea name="description" id="description" rows="4" required class="mt-1 block w-full bg-white border border-slate-300 rounded-md shadow-sm py-2 px-3 text-slate-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('description') }}</textarea>
            @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="duration" class="block text-sm font-medium text-slate-700">Duration (Minutes)</label>
            <input type="number" name="duration" id="duration" value="{{ old('duration') }}" min="1" required class="mt-1 block w-full bg-white border border-slate-300 rounded-md shadow-sm py-2 px-3 text-slate-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            @error('duration') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mb-6">
            <label for="poster" class="block text-sm font-medium text-slate-700">Poster Image</label>
            <input type="file" name="poster" id="poster" accept="image/*" class="mt-1 block w-full bg-white border border-slate-300 rounded-md shadow-sm py-2 px-3 text-slate-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            @error('poster') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                Save Movie
            </button>
        </div>
    </form>
</div>
@endsection
