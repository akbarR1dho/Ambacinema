@extends('layouts.admin')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 sm:gap-0">
    <h2 class="text-2xl font-bold text-slate-900">Movies</h2>
    <a href="{{ route('admin.movies.create') }}" class="w-full sm:w-auto text-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
        + Add New Movie
    </a>
</div>

<div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" id="movies-table">
            <thead>
                <tr class="text-slate-500 text-sm uppercase border-b border-slate-200">
                    <th class="py-3 px-4">No</th>
                    <th class="py-3 px-4">Poster</th>
                    <th class="py-3 px-4">Title</th>
                    <th class="py-3 px-4">Duration (min)</th>
                    <th class="py-3 px-4 w-32">Action</th>
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
        $('#movies-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.movies.index') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'poster', name: 'poster', orderable: false, searchable: false },
                { data: 'title', name: 'title' },
                { data: 'duration', name: 'duration' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            language: {
                search: "",
                searchPlaceholder: "Search movies..."
            }
        });
    });
</script>
@endpush
