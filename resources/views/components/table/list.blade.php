@props([
    'title' => null,
    'buttonRoute' => null,
    'buttonText' => '+ Add',
    'columns' => [],
    'rows' => [],
    'editRoute' => null,
    'deleteRoute' => null,
    'viewRoute' => null,
])

@php
    $tableId = 'table_' . uniqid();
@endphp

<div class="max-w-7xl mx-auto p-6 bg-white rounded-2xl shadow-md space-y-6">


    {{-- Header + Filter --}}
    @if($title || $buttonRoute)
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4 gap-4">

        {{-- Title --}}
        @if($title)
            <h2 class="text-xl font-semibold text-gray-800">{{ $title }}</h2>
        @endif

        {{-- Right controls: Filter + Add Button --}}
        <div class="flex items-center gap-2 flex-wrap">

            {{-- Date Filter (Transactions Only) --}}
            @if(request()->routeIs('transactions.index'))
            <form method="GET" action="{{ route('transactions.index') }}" class="flex items-center gap-2 flex-wrap">

                {{-- From date --}}
                <div class="flex items-center gap-1">
                    <span class="text-gray-600 text-sm">From:</span>
                    <input type="date" name="from_date" id="from_date"
                        value="{{ request('from_date') }}"
                        class="rounded border-gray-300 px-2 py-1 text-sm">
                </div>

                {{-- To date --}}
                <div class="flex items-center gap-1">
                    <span class="text-gray-600 text-sm">To:</span>
                    <input type="date" name="to_date" id="to_date"
                        value="{{ request('to_date') }}"
                        class="rounded border-gray-300 px-2 py-1 text-sm">
                </div>

                {{-- Search button --}}
                <button type="submit"
                        class="bg-green-600 text-white text-sm px-3 py-1 rounded hover:bg-green-700 transition">
                    Search
                </button>

                {{-- Reset button --}}
                @if(request()->hasAny(['from_date','to_date']))
                    <a href="{{ route('transactions.index') }}"
                    class="bg-yellow-100 text-yellow-700 text-sm px-3 py-1 rounded hover:bg-yellow-200 transition">
                        Reset
                    </a>
                @endif
            </form>
            @endif

            {{-- Add Button --}}
            @if($buttonRoute)
                <a href="{{ $buttonRoute }}" 
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg shadow hover:bg-indigo-700 transition">
                    {{ $buttonText }}
                </a>
            @endif

        </div>
    </div>
    @endif


    {{-- Table --}}
    <div class="overflow-x-auto">
        <table id="{{ $tableId }}" class="min-w-full text-left text-gray-600 text-sm">
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3">#</th>
                    @foreach ($columns as $key => $col)
                        <th class="px-4 py-3">{{ $col }}</th>
                    @endforeach
                    @if($editRoute || $deleteRoute || $viewRoute)
                        <th class="px-4 py-3 text-right">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $index => $row)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="px-4 py-3">{{ $index + 1 }}</td>
                        @foreach ($columns as $key => $col)
                            <td class="px-4 py-3">{!! $row[$key] ?? '' !!}</td>
                        @endforeach

                        {{-- Actions --}}
                        @if($editRoute || $deleteRoute || $viewRoute)
                            <td class="px-1 py-1 flex">
                                {{-- View --}}
                                @if($viewRoute)
                                    <a href="{{ route($viewRoute, $row['id']) }}" title="View"
                                    class="p-2 rounded hover:bg-green-100 text-green-600 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                @endif

                                {{-- Edit --}}
                                @if($editRoute)
                                    <a href="{{ route($editRoute, $row['id']) }}" title="Edit"
                                    class="p-2 rounded hover:bg-blue-100 text-blue-600 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5h6m-6 4h6m-6 4h6m-6 4h6M4 5h.01M4 9h.01M4 13h.01M4 17h.01" />
                                        </svg>
                                    </a>
                                @endif

                                {{-- Delete --}}
                                @if($deleteRoute)
                                    <form action="{{ route($deleteRoute, $row['id']) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this record?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Delete"
                                                class="p-2 rounded hover:bg-red-100 text-red-600 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- DataTable JS --}}
@push('scripts')
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
$(document).ready(function() {

    // Get date range from inputs (if any)
    let fromDate = $('#from_date').val();
    let toDate = $('#to_date').val();
    let dateRangeText = '';
    if(fromDate || toDate){
        dateRangeText = `Date Range: ${fromDate || 'Start'} to ${toDate || 'End'}`;
    }

    $('#{{ $tableId }}').DataTable({
        pageLength: 10,
        searching: true,
        responsive: true,
        lengthChange: true,
        info: true,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'collection',
                text: 'Export',
                className: 'bg-indigo-600 text-white px-3 py-1 rounded shadow hover:bg-indigo-700',
                buttons: [
                    { 
                        extend: 'copy', 
                        text: 'Copy', 
                        exportOptions: { columns: ':not(:last-child)' },
                        title: dateRangeText || 'Transactions'
                    },
                    { 
                        extend: 'csv', 
                        text: 'CSV', 
                        exportOptions: { columns: ':not(:last-child)' },
                        title: dateRangeText || 'Transactions'
                    },
                    { 
                        extend: 'excel', 
                        text: 'Excel', 
                        exportOptions: { columns: ':not(:last-child)' },
                        title: dateRangeText || 'Transactions'
                    },
                    { 
                        extend: 'pdf', 
                        text: 'PDF', 
                        exportOptions: { columns: ':not(:last-child)' },
                        title: 'Transactions',
                        customize: function (doc) {
                            if(dateRangeText) {
                                doc.content.splice(0,0,{ 
                                    text: dateRangeText, 
                                    style: 'subheader', 
                                    margin: [0,0,0,12] 
                                });
                            }
                        }
                    },
                    { 
                        extend: 'print', 
                        text: 'Print', 
                        exportOptions: { columns: ':not(:last-child)' },
                        title: dateRangeText || 'Transactions'
                    },
                    { extend: 'colvis', text: 'Columns' },
                ]
            }
        ],
        columnDefs: [
            { orderable: false, targets: -1 } // Actions column not orderable
        ]
    });
});
</script>

@endpush
