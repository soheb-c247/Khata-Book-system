<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Customers
        </h2>
    </x-slot>

    @php
        $columns = [
            'name' => 'Name',
            'phone' => 'Phone',
            'opening_balance' => 'Opening Balance',
            'balance' => 'Balance',
            'address' => 'Address',
        ];

    @endphp

    <x-table.list 
        title="Customer List"
        :columns="$columns"
        :rows="$customers"
        editRoute="customers.edit"
        deleteRoute="customers.destroy"
        viewRoute="customers.show"
        buttonRoute="{{ route('customers.create') }}"
        buttonText="+ Add"
    />
</x-app-layout>
