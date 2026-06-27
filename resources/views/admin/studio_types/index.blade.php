@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-slate-900">Studio Types</h2>
    <a href="{{ route('admin.studio-types.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors shadow-lg">
        + Add Studio Type
    </a>
</div>

<div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
    <table class="data-table w-full">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Price Range</th>
                <th>Description</th>
                <th>Actions</th>
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
