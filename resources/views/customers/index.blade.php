<x-app-layout>
    <x-slot name="header">
    </x-slot>

    @php
        $columns = [
            'name' => 'Name',
            'phone' => 'Phone',
            'opening_balance' => 'Opening Balance',
            'balance' => 'Balance',
        ];

    @endphp

    <x-table.list 
        title="Customers"
        :columns="$columns"
        :rows="$customers"
        editRoute="customers.edit"
        deleteRoute="customers.destroy"
        viewRoute="customers.show"
        buttonRoute="{{ route('customers.create') }}"
        buttonText="+ Add"
    />
</x-app-layout>
