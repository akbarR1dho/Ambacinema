@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h2 class="text-2xl font-bold text-slate-900">Orders</h2>
</div>

<div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" id="orders-table">
            <thead>
                <tr class="text-slate-500 text-sm uppercase border-b border-slate-200">
                    <th class="py-3 px-4">No</th>
                    <th class="py-3 px-4">Customer</th>
                    <th class="py-3 px-4">Movie</th>
                    <th class="py-3 px-4">Total Price</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4 w-24">Action</th>
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
        $('#orders-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.orders.index') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'user_name', name: 'user.name' },
                { data: 'movie_title', name: 'showtime.movie.title' },
                { data: 'total_price', name: 'total_price' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            language: {
                search: "",
                searchPlaceholder: "Search orders..."
            }
        });
    });
</script>
@endpush
