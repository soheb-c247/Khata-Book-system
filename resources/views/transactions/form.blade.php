<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($transaction) ? 'Edit Transaction' : 'Add Transaction' }}
        </h2>
    </x-slot>

    <div class="w-full py-4 flex justify-center">
        <div class="w-full max-w-3xl bg-white rounded-xl shadow p-4">

            <!-- Title inside the card -->
            <h2 class="text-lg font-semibold mb-3 border-b pb-2">
                {{ isset($transaction) ? 'Update Transaction Details' : 'Enter Transaction Details' }}
            </h2>

            <form id="transaction-form"  method="POST"
                  action="{{ isset($transaction) 
                                ? route('transactions.update', $transaction->encrypted_id) 
                                : route('transactions.store') }}">
                @csrf
                @if(isset($transaction))
                    @method('PUT')
                @endif

                <!-- Grid layout -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <!-- Customer -->
                    <div class="p-1">
                        <label for="customer_id" class="block text-xs font-medium text-gray-700">Customer</label>
                        <select name="customer_id" id="customer_id"
                            class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm p-1"
                            {{ isset($transaction) ? 'disabled' : '' }}>
                            <option value="">-- Select Customer --</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}"
                                    @if(isset($transaction) && $transaction->customer_id == $c->id) selected @endif
                                    @if(isset($customer) && $customer->id == $c->id) selected @endif>
                                    {{ $c->name }} ({{ $c->phone }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-red-500 text-xs mt-1 error-message" id="error-customer_id">
                            @error('customer_id') {{ $message }} @enderror
                        </p>
                    </div>

                    @if(isset($transaction))
                        <input type="hidden" name="customer_id" value="{{ $transaction->customer_id }}">
                    @endif
                    <!-- Type -->
                    <div class="p-1">
                        <label for="type" class="block text-xs font-medium text-gray-700">Type</label>
                        <select name="type" id="type"
                            class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm p-1">
                            <option value="credit" {{ (isset($transaction) && $transaction->type == 'credit') ? 'selected' : '' }}>Credit</option>
                            <option value="debit"  {{ (isset($transaction) && $transaction->type == 'debit') ? 'selected' : '' }}>Debit</option>
                        </select>
                        <p class="text-red-500 text-xs mt-1 error-message" id="error-type">
                            @error('type') {{ $message }} @enderror
                        </p>
                    </div>

                    <!-- Amount -->
                    <div class="p-1">
                        <label for="amount" class="block text-xs font-medium text-gray-700">Amount</label>
                        <input type="number" min="1" step="0.01" name="amount" id="amount"
                            value="{{ old('amount', $transaction->amount ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm p-1"
                            >
                            <p class="text-red-500 text-xs mt-1 error-message" id="error-amount">
                                @error('amount') {{ $message }} @enderror
                            </p>
                    </div>

                    <!-- Date -->
                    <div class="p-1">
                        <label for="date" class="block text-xs font-medium text-gray-700">Date</label>
                        <input type="date" name="date" id="date"
                            value="{{ old('date', isset($transaction) ? $transaction->date->format('Y-m-d') : now()->format('Y-m-d')) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm p-1">
                        <p class="text-red-500 text-xs mt-1 error-message" id="error-date">
                            @error('date') {{ $message }} @enderror
                        </p>
                    </div>

                </div>

                <!-- Notes (full width row) -->
                <div class="p-1 mt-4">
                    <label for="notes" class="block text-xs font-medium text-gray-700">Note : <small>optional</small></label>
                    <textarea name="notes" id="notes" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm p-1">{{ old('notes', $transaction->notes ?? '') }}</textarea>
                        
                        <p class="text-red-500 text-xs mt-1 error-message" id="error-notes">
                                @error('notes') {{ $message }} @enderror
                        </p>
                </div>

                <!-- Submit -->
                <div class="flex justify-end space-x-2 mt-4">
                    <a href="{{ route('transactions.index') }}" 
                       class="px-3 py-1 rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200 text-sm">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-3 py-1 rounded-md bg-indigo-600 text-white hover:bg-indigo-700 text-sm">
                        {{ isset($transaction) ? 'Update' : 'Save' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
