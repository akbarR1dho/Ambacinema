@extends('layouts.admin')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 sm:gap-0">
    <h2 class="text-2xl font-bold text-slate-900">{{ __('Showtimes') }}</h2>
    <div class="flex flex-col sm:flex-row flex-wrap gap-3 w-full sm:w-auto">
        <x-infinite-select 
            id="filter_movie_id" 
            name="filter_movie_id" 
            apiUrl="{{ route('admin.api.movies') }}" 
            defaultLabel="{{ __('All Movies') }}" 
            placeholder="{{ __('Search movies...') }}" 
        />
        <x-infinite-select 
            id="filter_studio_id" 
            name="filter_studio_id" 
            apiUrl="{{ route('admin.api.studios') }}" 
            defaultLabel="{{ __('All Studios') }}" 
            placeholder="{{ __('Search studios...') }}" 
        />
        <a href="{{ route('admin.showtimes.create') }}" class="w-full sm:w-auto text-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
            + {{ __('Add New Showtime') }}
        </a>
    </div>
</div>

<!-- <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm mb-6 flex flex-col sm:flex-row gap-4">
    <div class="w-full sm:w-1/3">
        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Filter by Movie') }}</label>
        <x-infinite-select 
            id="filter_movie_id" 
            name="filter_movie_id" 
            apiUrl="{{ route('admin.api.movies') }}" 
            defaultLabel="{{ __('All Movies') }}" 
            placeholder="{{ __('Search movies...') }}" 
        />
    </div>
    <div class="w-full sm:w-1/3">
        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Filter by Studio') }}</label>
        <x-infinite-select 
            id="filter_studio_id" 
            name="filter_studio_id" 
            apiUrl="{{ route('admin.api.studios') }}" 
            defaultLabel="{{ __('All Studios') }}" 
            placeholder="{{ __('Search studios...') }}" 
        />
    </div>
</div> -->

<div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" id="showtimes-table">
            <thead>
                <tr class="text-slate-500 text-sm uppercase border-b border-slate-200">
                    <th class="py-3 px-4">{{ __('No') }}</th>
                    <th class="py-3 px-4">{{ __('Movie') }}</th>
                    <th class="py-3 px-4">{{ __('Studio') }}</th>
                    <th class="py-3 px-4 min-tablet">{{ __('Start Time') }}</th>
                    <th class="py-3 px-4 min-tablet">{{ __('End Time') }}</th>
                    <th class="py-3 px-4 w-32">{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                <!-- DataTables will populate this -->
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#showtimes-table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            searchable: false,
            ajax: {
                url: "{{ route('admin.showtimes.index') }}",
                data: function (d) {
                    d.movie_id = $('#filter_movie_id').val();
                    d.studio_id = $('#filter_studio_id').val();
                },
            },
            order: [[3, 'desc']],
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false },
                { data: 'movie_title', name: 'movie.title', orderable: false },
                { data: 'studio_name', name: 'studio.name', orderable: false },
                { data: 'start_time', name: 'start_time' },
                { data: 'end_time', name: 'end_time' },
                { data: 'action', name: 'action', orderable: false }
            ],
            language: {
                search: "",
                searchPlaceholder: "{{ __('Search showtimes...') }}"
            }
        });

        $('#filter_movie_id, #filter_studio_id').on('change', function() {
            $('#showtimes-table').DataTable().ajax.reload();
        });
    });
</script>
@endpush
