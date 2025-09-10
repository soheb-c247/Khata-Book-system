<x-app-layout>
    <x-slot name="header">
    </x-slot>
    @php
        $columns = [
            'name' => 'Name',
            'type' => 'Type',
            'amount' => 'Amount',
            'date' => 'Date',
        ];

        $rows = $transactions->map(fn($t) => [
            'name'      => ucfirst($t->customer->name),
            'type'      => ucfirst($t->type),
            'amount'    => $t->amount,
            'date'      => \Carbon\Carbon::parse($t->date)->format('d M, Y'),
            'id'        => $t->encrypted_id,
        ]);
    @endphp

    <x-table.list 
        title="Transactions"
        :columns="$columns" 
        :rows="$rows" 
        editRoute="transactions.edit"
        deleteRoute="transactions.destroy"
        buttonRoute="{{ route('transactions.create') }}" 
        buttonText="+ Add"
    />
</x-app-layout>
