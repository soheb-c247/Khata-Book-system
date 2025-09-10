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
                    class="bg-blue-600 text-white text-sm px-3 py-1 rounded hover:bg-blue-700 transition" >
                    {{ $buttonText }}
                </a>
            @endif

        </div>
    </div>
    @endif


    {{-- Table --}}
    <div class="overflow-x-auto">
        <table id="{{ $tableId }}" class="min-w-full text-left text-gray-600 text-sm">
            <thead class="border-b hover:bg-gray-50 transition">
                <tr>
                    <th class="px-4 py-3 text-left">#</th>
                    @foreach ($columns as $key => $col)
                         <th class="px-4 py-3"
                            style="text-align: {{ in_array(strtolower($key), ['name']) ? 'left' : 'center' }};">
                            {{ $col }}
                        </th>
                    @endforeach
                    @if($editRoute || $deleteRoute || $viewRoute)
                        <th class="px-4 py-3 " style="text-align: center;">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $index => $row)
                    <tr class="border-b hover:bg-gray-50 transition">
                        {{-- # column --}}
                       <td class="px-2 py-1 text-left">{{ $index + 1 }}</td>

                        {{-- Dynamic columns --}}
                        @foreach ($columns as $key => $col)
                            <td class="px-2 py-1"style="text-align: {{ in_array(strtolower($key), ['name']) ? 'left' : 'center' }};">
                                {!! $row[$key] ?? '' !!}
                            </td>
                        @endforeach

                        @if($editRoute || $deleteRoute || $viewRoute)
                            <td class="px-1 py-0.5 text-center">
                                <div class="flex justify-center items-center gap-1">
                                    {{-- View --}}
                                    @if($viewRoute)
                                        <a href="{{ route($viewRoute, $row['id']) }}" 
                                        class="relative group p-2 rounded hover:bg-green-100 text-green-600 transition">
                                                    <i class="fa-solid fa-eye"></i>
                                            <span class="absolute left-1/2 bottom-0 transform -translate-x-1/2 translate-y-full mt-1 w-max px-2 py-1 text-xs text-white bg-gray-700 rounded opacity-0 group-hover:opacity-100 transition">
                                                View
                                            </span>
                                        </a>
                                    @endif

                                    {{-- Edit --}}
                                    @if($editRoute)
                                        <a href="{{ route($editRoute, $row['id']) }}" 
                                        class="relative group p-2 rounded hover:bg-blue-100 text-blue-600 transition">
                                            <i class="fa-solid fa-pencil"></i>
                                            <span class="absolute left-1/2 bottom-0 transform -translate-x-1/2 translate-y-full mt-1 w-max px-2 py-1 text-xs text-white bg-gray-700 rounded opacity-0 group-hover:opacity-100 transition">
                                                Edit
                                            </span>
                                        </a>
                                    @endif

                                    {{-- Delete --}}
                                    @if($deleteRoute)
                                        <form action="{{ route($deleteRoute, $row['id']) }}" method="POST" class="delete-form relative group">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                    class="p-2 rounded hover:bg-red-100 text-red-600 transition delete-btn">
                                                <i class="fa-solid fa-trash"></i>
                                                <span class="absolute left-1/2 bottom-0 transform -translate-x-1/2 translate-y-full mt-1 w-max px-2 py-1 text-xs text-white bg-gray-700 rounded opacity-0 group-hover:opacity-100 transition">
                                                    Delete
                                                </span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {

        // Get date range from inputs (if any)
        let fromDate = $('#from_date').val();
        let toDate = $('#to_date').val();
        let dateRangeText = '';
        if(fromDate || toDate){
            dateRangeText = `Date Range: ${fromDate || 'Start'} to ${toDate || 'End'}`;
        }

        let table = $('#{{ $tableId }}').DataTable({
            pageLength: 10,
            searching: true,
            responsive: true,
            lengthChange: true,
            info: true,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'collection',
                    text: 'Export ',
                    className: 'bg-indigo-600 text-white px-3 py-1 rounded-md shadow hover:bg-indigo-700 flex items-center gap-1',
                    autoClose: true,
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
                        }
                    ]
                }
            ],
            columnDefs: [
                { orderable: false, targets: -1 }
            ]
        });
        if (table.rows().count() === 0) {
            table.buttons().container().hide(); 
        }
    });
</script>

@endpush
