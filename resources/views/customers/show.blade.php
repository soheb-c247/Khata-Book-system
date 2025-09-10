<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6 mt-6">
        <!-- Customer Details Card -->
        <div class="bg-white p-4 rounded-lg shadow-md">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">{{ $customer->name }}</h2>
                    <p class="text-sm text-gray-500">Created at: {{ $customer->created_at->format('d M, Y') }}</p>
                </div>
                <a href="{{ route('customers.index') }}" 
                   class="px-3 py-1 bg-gray-200 text-gray-700 text-sm rounded hover:bg-gray-300 transition">
                   Back
                </a>
            </div>

            <div class="mt-3 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 text-sm">
                <div>
                    <span class="font-semibold text-gray-600">Phone:</span>
                    <p class="text-gray-800">{{ $customer->phone }}</p>
                </div>

                <div>
                    <span class="font-semibold text-gray-600">Opening Balance:</span>
                    <p class="text-gray-800">{{ number_format($customer->opening_balance ?? 0, 2) }}</p>
                </div>

                <div>
                    <span class="font-semibold text-gray-600">Total Credits:</span>
                    <p class="text-green-600 font-semibold">{{ number_format($totalCredits, 2) }}</p>
                </div>

                <div>
                    <span class="font-semibold text-gray-600">Total Debits:</span>
                    <p class="text-red-600 font-semibold">{{ number_format($totalDebits, 2) }}</p>
                </div>

                <div>
                    <span class="font-semibold text-gray-600">Balance:</span>
                    <p class="font-bold {{ $balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ number_format($balance, 2) }}
                    </p>
                </div>
            </div>

            <div class="flex justify-between items-center">
                <div>
                    <span class="font-semibold text-gray-600">Address :</span>
                    <p class="text-gray-800 mb-2">{{ Str::limit($customer->address, 150, '...') }}</p>
                </div>
                 <button 
                        type="button" 
                        class="bg-green-600 text-white text-sm px-3 py-1 rounded hover:bg-green-700 transition"
                        onclick="openStatementModal()">
                        Get Statement
                    </button>                
            </div>
        </div>

        <!-- Transactions Table -->
            @php
                $columns = [
                    'date' => 'Date',
                    'type' => 'Type',
                    'amount' => 'Amount',
                ];
            @endphp

            <x-table.list 
                title="Transaction"
                :columns="$columns"
                :rows="$rows"
                editRoute="transactions.edit"
                deleteRoute="transactions.destroy"
                buttonRoute="{{ route('transactions.create', ['customer_id' => $customer->encrypted_id]) }}"
                buttonText="+ Add"
            />
    </div>

    <!-- Include Statement Modal -->
    <x-customer.statement-modal :customer="$customer" />
    <script src="{{ asset('js/customer-statement.js') }}"></script>
</x-app-layout>
