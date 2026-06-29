@extends('layouts.admin')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 sm:gap-0">
    <h2 class="text-2xl font-bold text-slate-900">{{ __('Movies') }}</h2>
    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
        <select id="ageRatingFilter" class="bg-white border border-slate-300 rounded-lg py-2 px-3 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 w-full sm:w-auto">
            <option value="">{{ __('All Ratings') }}</option>
            <option value="SU">SU (Semua Umur)</option>
            <option value="13+">13+</option>
            <option value="17+">17+</option>
            <option value="21+">21+</option>
        </select>
        <a href="{{ route('admin.movies.create') }}" class="w-full sm:w-auto text-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors whitespace-nowrap">
            + {{ __('Add New Movie') }}
        </a>
    </div>
</div>

<div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" id="movies-table">
            <thead>
                <tr class="text-slate-500 text-sm uppercase border-b border-slate-200">
                    <th class="py-3 px-4">{{ __('No') }}</th>
                    <th class="py-3 px-4">{{ __('Poster') }}</th>
                    <th class="py-3 px-4">{{ __('Title') }}</th>
                    <th class="py-3 px-4">{{ __('Duration (min)') }}</th>
                    <th class="py-3 px-4">{{ __('Age Rating') }}</th>
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
        var table = $('#movies-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.movies.index') }}",
                data: function(d) {
                    d.age_rating = $('#ageRatingFilter').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'poster', name: 'poster', orderable: false, searchable: false },
                { data: 'title', name: 'title' },
                { data: 'duration', name: 'duration' },
                { data: 'age_rating', name: 'age_rating' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            language: {
                search: "",
                searchPlaceholder: "{{ __('Search movies...') }}"
            }
        });

        $('#ageRatingFilter').on('change', function() {
            table.ajax.reload();
        });
    });
</script>
@endpush
