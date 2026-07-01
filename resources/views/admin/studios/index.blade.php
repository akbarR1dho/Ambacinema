@extends('layouts.admin')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 sm:gap-0">
    <h2 class="text-2xl font-bold text-slate-900">{{ __('Studios') }}</h2>
    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
        <x-infinite-select 
            id="typeFilter" 
            api-url="{{ route('admin.api.studio-types') }}" 
            default-label="{{ __('All Types') }}" 
            placeholder="{{ __('Search type...') }}"
        />
        <a href="{{ route('admin.studios.create') }}" class="w-full sm:w-auto text-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors whitespace-nowrap">
            + {{ __('Add New Studio') }}
        </a>
    </div>
</div>

<div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" id="studios-table">
            <thead>
                <tr class="text-slate-500 text-sm uppercase border-b border-slate-200">
                    <th class="py-3 px-4">{{ __('No') }}</th>
                    <th class="py-3 px-4">{{ __('Name') }}</th>
                    <th class="py-3 px-4">{{ __('Type') }}</th>
                    <th class="py-3 px-4">{{ __('Total Seats') }}</th>
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
        var table = $('#studios-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.studios.index') }}",
                data: function(d) {
                    d.type_filter = $('#typeFilter').val();
                }
            },
            order: [[1, 'asc']],
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'type', name: 'type', orderable: false, searchable: false },
                { data: 'total_seats', name: 'total_seats' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            language: {
                search: "",
                searchPlaceholder: "{{ __('Search studios...') }}"
            }
        });

        $('#typeFilter').on('change', function() {
            table.ajax.reload();
        });
    });
</script>
@endpush
