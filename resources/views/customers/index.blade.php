<x-app-layout>
    <x-slot name="header">
        Customers
    </x-slot>

    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    @endpush

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6">
        <div class="mb-4 flex justify-end">
            <a href="{{ route('customers.create') }}" 
               class="bg-indigo-600 text-white px-4 py-2 rounded-lg shadow hover:bg-indigo-700">
                + Add Customer
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-md p-6">
            <table id="customersTable" class="min-w-full text-sm text-left text-gray-600">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Phone</th>
                        <th class="px-4 py-2">Address</th>
                        <th class="px-4 py-2 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $customer->name }}</td>
                            <td class="px-4 py-2">{{ $customer->phone }}</td>
                            <td class="px-4 py-2">{{ Str::limit($customer->address, 50, '...') }}</td>
                            <td class="px-4 py-2 text-right space-x-2">
                                <a href="{{ route('customers.edit', $customer) }}" 
                                   class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                    Edit
                                </a>

                                <form action="{{ route('customers.destroy', $customer) }}" 
                                      method="POST" class="inline-block"
                                      onsubmit="return confirm('Are you sure you want to delete this customer?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#customersTable').DataTable({
                    pageLength: 10,
                    searching: true,
                });
            });
        </script>
    @endpush
</x-app-layout>
