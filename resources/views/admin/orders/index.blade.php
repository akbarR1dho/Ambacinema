@extends('layouts.admin')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <h2 class="text-2xl font-bold text-slate-900">{{ __('Orders') }}</h2>
    <div class="flex flex-col sm:flex-row flex-wrap gap-3 w-full sm:w-auto">
        <x-infinite-select 
            id="studioFilter" 
            api-url="{{ route('admin.api.studios') }}" 
            default-label="{{ __('All Studios') }}" 
            placeholder="{{ __('Search studio') }}..."
        />
        <x-infinite-select 
            id="movieFilter" 
            api-url="{{ route('admin.api.movies') }}" 
            default-label="{{ __('All Movies') }}" 
            placeholder="{{ __('Search movie') }}..."
        />
        <select id="statusFilter" class="cursor-pointer w-full sm:w-40 bg-white border border-slate-300 rounded-lg py-2 px-3 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 h-[38px]">
            <option value="">{{ __('All Statuses') }}</option>
            <option value="pending">{{ __('Pending') }}</option>
            <option value="confirmed">{{ __('Confirmed') }}</option>
            <option value="failed">{{ __('Failed') }}</option>
        </select>
        <button type="button" id="exportExcelBtn" class="cursor-pointer w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center gap-2 h-[38px]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            {{ __('Export Excel') }}
        </button>
    </div>
</div>

<div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" id="orders-table">
            <thead>
                <tr class="text-slate-500 text-sm uppercase border-b border-slate-200">
                    <th class="py-3 px-4 all">{{ __('No') }}</th>
                    <th class="py-3 px-4 all">{{ __('Id Order') }}</th>
                    <th class="py-3 px-4 all">{{ __('Movie') }}</th>
                    <th class="py-3 px-4 min-tablet">{{ __('Studio') }}</th>
                    <th class="py-3 px-4 min-tablet">{{ __('Status') }}</th>
                    <th class="py-3 px-4 min-tablet">{{ __('Action') }}</th>
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
            order: [[1, 'desc']],
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'id', name: 'id', },
                { data: 'movie_title', name: 'showtime.movie.title', searchable: false, orderable: false },
                { data: 'studio_name', name: 'showtime.studio.name', searchable: false, orderable: false},
                { data: 'status', name: 'status', searchable: false, orderable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            language: {
                search: "",
                searchPlaceholder: "{{ __('Search orders') }}..."
            }
        });

        $('#studioFilter, #movieFilter, #statusFilter').on('change', function() {
            table.ajax.reload();
        });

        $('#exportExcelBtn').on('click', function() {
            var studio_id = $('#studioFilter').val() || '';
            var movie_id = $('#movieFilter').val() || '';
            var status = $('#statusFilter').val() || '';
            
            var url = "{{ route('admin.orders.export') }}?studio_id=" + studio_id + "&movie_id=" + movie_id + "&status=" + status;
            window.location.href = url;
        });
    });
</script>
@endpush
