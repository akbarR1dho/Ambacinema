@extends('layouts.admin')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <h2 class="text-2xl font-bold text-slate-900">{{ __('Orders') }}</h2>
    <div class="flex flex-col sm:flex-row flex-wrap gap-3 w-full sm:w-auto">
        <x-infinite-select 
            id="studioFilter" 
            api-url="{{ route('admin.api.studios') }}" 
            default-label="{{ __('All Studios') }}" 
            placeholder="{{ __('Search studio...') }}"
        />
        <x-infinite-select 
            id="movieFilter" 
            api-url="{{ route('admin.api.movies') }}" 
            default-label="{{ __('All Movies') }}" 
            placeholder="{{ __('Search movie...') }}"
        />
        <select id="statusFilter" class="w-full sm:w-40 bg-white border border-slate-300 rounded-lg py-2 px-3 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 h-[38px]">
            <option value="">{{ __('All Statuses') }}</option>
            <option value="pending">{{ __('Pending') }}</option>
            <option value="confirmed">{{ __('Confirmed') }}</option>
        </select>
    </div>
</div>

<div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" id="orders-table">
            <thead>
                <tr class="text-slate-500 text-sm uppercase border-b border-slate-200">
                    <th class="py-3 px-4">{{ __('No') }}</th>
                    <th class="py-3 px-4">{{ __('Customer') }}</th>
                    <th class="py-3 px-4">{{ __('Movie') }}</th>
                    <th class="py-3 px-4 ">{{ __('Studio') }}</th>
                    <th class="py-3 px-4">{{ __('Total Price') }}</th>
                    <th class="py-3 px-4">{{ __('Status') }}</th>
                    <th class="py-3 px-4 w-24">{{ __('Action') }}</th>
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
        var table = $('#orders-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.orders.index') }}",
                data: function(d) {
                    d.studio_id = $('#studioFilter').val();
                    d.movie_id = $('#movieFilter').val();
                    d.status = $('#statusFilter').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'user_name', name: 'user.name' },
                { data: 'movie_title', name: 'showtime.movie.title' },
                { data: 'studio_name', name: 'showtime.studio.name'},
                { data: 'total_price', name: 'total_price' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            language: {
                search: "",
                searchPlaceholder: "{{ __('Search orders...') }}"
            }
        });

        $('#studioFilter, #movieFilter, #statusFilter').on('change', function() {
            table.ajax.reload();
        });
    });
</script>
@endpush
