<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Transactions</h2>
    </x-slot>
    @php
        $columns = [
            'date' => 'Date',
            'customer' => 'Customer',
            'type' => 'Type',
            'amount' => 'Amount',
            'notes' => 'Notes',
        ];

        $rows = $transactions->map(fn($t) => [
            'date' => \Carbon\Carbon::parse($t->date)->format('d M, Y'),
            'customer' => $t->customer->name,
            'type' => ucfirst($t->type),
            'amount' => $t->amount,
            'notes' => $t->notes,
            'id' => $t->encrypted_id,
        ]);
    @endphp

    <x-table.list 
        title="Transaction List"
        :columns="$columns" 
        :rows="$rows" 
        editRoute="transactions.edit"
        deleteRoute="transactions.destroy"
        buttonRoute="{{ route('transactions.create') }}" 
        buttonText="+ Add"
    />
</x-app-layout>
