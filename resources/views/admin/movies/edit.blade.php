@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-slate-900 mb-2">{{ __('Edit Movie:') }} {{ $movie->title }}</h2>
    <a href="{{ route('admin.movies.index') }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium">&larr; {{ __('Back to Movies') }}</a>
</div>

<div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm max-w-3xl">
    <form action="{{ route('admin.movies.update', $movie->id) }}" method="POST" id="movieForm">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-slate-700">{{ __('Title') }}</label>
            <input type="text" name="title" id="title" value="{{ old('title', $movie->title) }}" required class="mt-1 block w-full bg-white border border-slate-300 rounded-md shadow-sm py-2 px-3 text-slate-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-slate-700">{{ __('Description') }}</label>
            <textarea name="description" id="description" rows="4" required class="mt-1 block w-full bg-white border border-slate-300 rounded-md shadow-sm py-2 px-3 text-slate-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('description', $movie->description) }}</textarea>
            @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="duration" class="block text-sm font-medium text-slate-700">{{ __('Duration (Minutes)') }}</label>
            <input type="number" name="duration" id="duration" value="{{ old('duration', $movie->duration) }}" min="1" required class="mt-1 block w-full bg-white border border-slate-300 rounded-md shadow-sm py-2 px-3 text-slate-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            @error('duration') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="age_rating" class="block text-sm font-medium text-slate-700">{{ __('Age Rating') }}</label>
            <select name="age_rating" id="age_rating" required class="cursor-pointer mt-1 block w-full bg-white border border-slate-300 rounded-md shadow-sm py-2 px-3 text-slate-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <option value="SU" {{ old('age_rating', $movie->age_rating) == 'SU' ? 'selected' : '' }}>SU</option>
                <option value="13+" {{ old('age_rating', $movie->age_rating) == '13+' ? 'selected' : '' }}>13+</option>
                <option value="17+" {{ old('age_rating', $movie->age_rating) == '17+' ? 'selected' : '' }}>17+</option>
                <option value="21+" {{ old('age_rating', $movie->age_rating) == '21+' ? 'selected' : '' }}>21+</option>
            </select>
            @error('age_rating') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mb-6">
            <label for="poster_file" class="block text-sm font-medium text-slate-700">{{ __('Poster Image') }}</label>
            @if($movie->poster)
                <div class="mb-2">
                    <img src="{{ Storage::url($movie->poster) }}" alt="Poster" class="h-32 rounded shadow border border-slate-200">
                </div>
            @endif
            <input type="file" id="poster_file" accept=".jpg,.jpeg,.png,.webp" class="mt-1 block w-full text-slate-700 bg-white border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 cursor-pointer file:cursor-pointer file:mr-4 file:py-2.5 file:px-4 file:rounded-l-lg file:border-0 file:border-r file:border-slate-300 file:text-sm file:font-semibold file:bg-slate-50 file:text-slate-700 hover:file:bg-slate-100 transition-colors">
            <input type="hidden" name="poster_path" id="poster_path">
            <p class="text-xs text-slate-500 mt-1">{{ __('Leave blank to keep current poster') }}.</p>
            @error('poster_path') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit" class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                {{ __('Update Movie') }}
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('movieForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const fileInput = document.getElementById('poster_file');
    const form = this;
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;

    if (!fileInput.files.length) {
        form.submit();
        return;
    }

    const file = fileInput.files[0];
    const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
    const maxSize = 2 * 1024 * 1024; // 2MB

    if (!allowedTypes.includes(file.type)) {
        showErrorAlert('Only JPG, PNG, and WEBP files are allowed.');
        return;
    }

    if (file.size > maxSize) {
        showErrorAlert('File size must be less than 2MB.');
        return;
    }

    submitBtn.disabled = true;
    submitBtn.classList.add('opacity-75', 'cursor-not-allowed');

    try {
        const response = await fetch('{{ route('admin.api.upload.presigned') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                file_name: file.name,
                content_type: file.type,
                file_size: file.size
            })
        });

        if (!response.ok) {
            const errData = await response.json();
            throw new Error(errData.message || 'Failed to get upload URL');
        }

        const data = await response.json();

        const uploadResponse = await fetch(data.url, {
            method: 'PUT',
            headers: {
                'Content-Type': file.type
            },
            body: file
        });

        if (!uploadResponse.ok) {
            throw new Error('Failed to upload file to S3');
        }

        document.getElementById('poster_path').value = data.path;
        form.submit();

    } catch (error) {
        console.error(error);
        showErrorAlert(error.message || 'An error occurred during file upload. Please try again.');
        submitBtn.innerHTML = originalBtnText;
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
    }
});
</script>
@endpush
