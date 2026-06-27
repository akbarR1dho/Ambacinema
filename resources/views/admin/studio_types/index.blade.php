@extends('layouts.admin')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 sm:gap-0">
    <h2 class="text-2xl font-bold text-slate-900">Studio Types</h2>
    <a href="{{ route('admin.studio-types.create') }}" class="w-full sm:w-auto text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors shadow-lg">
        + Add Studio Type
    </a>
</div>

<div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
    <table class="data-table w-full">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th class="min-tablet">Price Range</th>
                <th class="min-desktop">Description</th>
                <th class="min-tablet">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.studio-types.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'name', name: 'name'},
                {data: 'price', name: 'price'},
                {data: 'description', name: 'description'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
    });
</script>
@endpush
