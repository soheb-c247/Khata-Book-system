<x-app-layout>
    <x-slot name="header">
        Dashboard
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6">
        <!-- Cards Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Total Customers Card -->
            <div class="bg-white rounded-2xl shadow-md p-6 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Total Customers</h3>
                    <p class="text-3xl font-bold text-indigo-600 mt-2">{{ $totalCustomers ?? 0 }}</p>
                </div>
                <div class="bg-indigo-100 text-indigo-600 p-4 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         class="h-8 w-8" fill="none" viewBox="0 0 24 24" 
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                              stroke-width="2" d="M17 20h5V4H2v16h5m10 0a2 2 0 11-4 0m4 0a2 2 0 01-4 0" />
                    </svg>
                </div>
            </div>

            <!-- Total Balance Card -->
            <div class="bg-white rounded-2xl shadow-md p-6 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Total Balance</h3>
                    <p class="text-3xl font-bold text-green-600 mt-2">₹{{ $totalBalance ?? 0 }}</p>
                </div>
                <div class="bg-green-100 text-green-600 p-4 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         class="h-8 w-8" fill="none" viewBox="0 0 24 24" 
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                              stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2-1.343-2-3-2zm0 12c6.627 0 12-4.373 12-10S18.627 0 12 0 0 4.373 0 10s5.373 10 12 10z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Outstanding Customers</h3>
            <div class="overflow-x-auto">
                <table id="outstandingTable" class="min-w-full text-sm text-left text-gray-600">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Phone</th>
                            <th class="px-4 py-2 text-right">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($outstandingCustomers as $customer)
                            @php
                                $balanceClass = 'text-gray-600'; // default for 0
                                if ($customer->balance > 0) {
                                    $balanceClass = 'text-red-600 font-medium'; // positive outstanding
                                } elseif ($customer->balance < 0) {
                                    $balanceClass = 'text-green-600 font-medium'; // negative outstanding
                                }
                            @endphp

                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $customer->name }}</td>
                                <td class="px-4 py-2">{{ $customer->phone }}</td>
                             <td class="px-4 py-2 text-right font-medium 
                                {{ $customer->balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                                ₹{{ number_format(abs($customer->balance), 2) }}
                            </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-2 text-center text-gray-500">
                                    No customers found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

        @push('styles')
            <!-- DataTables + Tailwind CSS -->
            <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
            <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.tailwindcss.min.css">
        @endpush

        @push('scripts')
        
            <!-- jQuery + DataTables -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
            <script>

                $(document).ready(function () {
                    $('#outstandingTable').DataTable({
                        pageLength: 10,
                        ordering: true,
                        searching: true,
                        lengthMenu: [10, 25, 50],
                        language: {
                            search: "_INPUT_",
                            searchPlaceholder: "Search customers..."
                        }
                     });
                });
            </script>
        @endpush
</x-app-layout>
