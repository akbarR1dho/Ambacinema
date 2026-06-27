@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h2 class="text-2xl font-bold text-slate-900">Showtimes</h2>
    <a href="{{ route('admin.showtimes.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
        + Add New Showtime
    </a>
</div>

<div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" id="showtimes-table">
            <thead>
                <tr class="text-slate-500 text-sm uppercase border-b border-slate-200">
                    <th class="py-3 px-4">No</th>
                    <th class="py-3 px-4">Movie</th>
                    <th class="py-3 px-4">Studio</th>
                    <th class="py-3 px-4">Start Time</th>
                    <th class="py-3 px-4">End Time</th>
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
        $('#showtimes-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.showtimes.index') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'movie_title', name: 'movie.title' },
                { data: 'studio_name', name: 'studio.name' },
                { data: 'start_time', name: 'start_time' },
                { data: 'end_time', name: 'end_time' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            language: {
                search: "",
                searchPlaceholder: "Search showtimes..."
            }
        });
    });
</script>
@endpush
