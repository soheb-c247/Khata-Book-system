<x-app-layout>
    <x-slot name="header">
    </x-slot>
        <!-- Cards Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Total Customers Card -->
            <div class="bg-white rounded-2xl shadow-md p-6 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Total Customers</h3>
                    <p class="text-3xl font-bold text-indigo-600 mt-2">{{ $totalCustomers ?? 0 }}</p>
                </div>
                <div class="bg-indigo-100 text-indigo-600 p-4 rounded-full">
                    <img src="{{ asset('imgs/default.jpg') }}" 
                        alt="Default Icon" 
                        class="h-8 w-8 object-contain rounded-full">
                </div>
            </div>
            <!-- Total Balance Card -->
            <div class="bg-white rounded-2xl shadow-md p-6 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Total Balance</h3>
                    <p class="text-3xl font-bold {{ $totalBalance >= 0 ? 'text-green-600' : 'text-red-600' }} mt-2">
                        ₹{{ number_format($totalBalance, 2) }}
                    </p>
                </div>
                <div class="{{ $totalBalance >= 0 ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} p-4 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" 
                        class="h-8 w-8" fill="none" viewBox="0 0 24 24" 
                        stroke="currentColor">
                        <circle cx="12" cy="12" r="10" stroke-width="2" stroke="currentColor" fill="none"/>
                        <text x="12" y="16" text-anchor="middle" font-size="12" font-weight="bold" 
                            fill="currentColor">₹</text>
                    </svg>
                </div>
            </div>
        </div>
            @php
                $columns = [
                    'name'   => 'Name',
                    'phone'  => 'Phone',
                    'balance'=> 'Balance',
                ];

                $rows = $outstandingCustomers->map(fn($c) => [
                    'id'     => $c->id,
                    'name'   => $c->name,
                    'phone'  => $c->phone,
                    'balance'=> '<span data-order="' . $c->balance . '" class="' . 
                                ($c->balance > 0 ? 'text-green-600 font-medium' : 'text-red-600 font-medium') . 
                                '">₹' . number_format(($c->balance), 2) . '</span>',
                ]);
            @endphp

            <x-table.list 
                title="Outstanding Customers"
                :columns="$columns"
                :rows="$rows"
            />
</x-app-layout>
