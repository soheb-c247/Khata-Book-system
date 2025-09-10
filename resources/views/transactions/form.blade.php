<x-app-layout>
    <x-slot name="header">
    </x-slot>
    
    <div class="max-w-7xl mx-auto p-6 bg-white rounded-2xl shadow-md space-y-6">
        <!-- Title inside the card -->
        <h2 class="text-lg font-semibold mb-3 border-b pb-2">
            {{ isset($transaction) ? 'Edit Transaction' : 'Add Transaction' }}
        </h2>

        <form id="transaction-form" method="POST" action="{{ isset($transaction) ? route('transactions.update', $transaction->encrypted_id) : route('transactions.store') }} " enctype="multipart/form-data">
            @csrf
            @if(isset($transaction))
                @method('PUT')
            @endif

            <!-- Grid layout -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- Customer -->
                <div class="p-1">
                    <label for="customer_id" class="block text-xs font-medium text-gray-700">Customer<span class="text-red-500">*</span></label>
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
                    <label for="type" class="block text-xs font-medium text-gray-700">Type<span class="text-red-500">*</span></label>
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
                    <label for="amount" class="block text-xs font-medium text-gray-700">Amount<span class="text-red-500">*</span></label>
                    <input type="text" name="amount" id="amount"
                        value="{{ old('amount', $transaction->amount ?? '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm p-1"
                        >
                        <p class="text-red-500 text-xs mt-1 error-message" id="error-amount">
                            @error('amount') {{ $message }} @enderror
                        </p>
                </div>

                <!-- Date -->
                <div class="p-1">
                    <label for="date" class="block text-xs font-medium text-gray-700">Date<span class="text-red-500">*</span></label>
                    <input type="date" name="date" id="date"
                        value="{{ old('date', isset($transaction) ? $transaction->date->format('Y-m-d') : now()->format('Y-m-d')) }}"     min="2020-01-01" max="2030-12-31"
                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm p-1">
                    <p class="text-red-500 text-xs mt-1 error-message" id="error-date">
                        @error('date') {{ $message }} @enderror
                    </p>
                </div>
                <div class="p-1">
                    <label for="notes" class="block text-xs font-medium text-gray-700">Note</label>
                    <textarea name="notes" id="notes" rows="5"
                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm p-1">{{ old('notes', $transaction->notes ?? '') }}</textarea>
                        
                        <p class="text-red-500 text-xs mt-1 error-message" id="error-notes">
                                @error('notes') {{ $message }} @enderror
                        </p>
                </div>
                <!-- File Upload -->
                <div class="p-1">
                    <label for="file" class="block text-xs font-medium text-gray-700 mb-1">
                        Upload Image / PDF <span class="text-gray-400 text-xs">(Max size: 2 MB)</span>
                    </label>

                    <div class="relative w-full">
                        <!-- Text Input showing selected or existing file name -->
                        <input type="text" id="file-name" 
                            readonly
                            class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm p-2 pr-10 cursor-pointer"
                            placeholder="Choose a file..."
                            value="{{ isset($transaction) && $transaction->file_path ? basename($transaction->file_path) : '' }}">

                        <!-- Preview button/icon -->
                        <button type="button" id="file-preview-btn" 
                            class="absolute inset-y-0 right-2 flex items-center justify-center text-gray-500 hover:text-gray-800"
                            data-file="{{ isset($transaction) && $transaction->file_path ? $transaction->full_url : '' }}"
                            title="Preview file">
                            <i class="fa-solid fa-eye"></i>
                        </button>

                        <!-- Hidden file input -->
                        <input type="file" name="file" id="file" accept=".jpg,.jpeg,.png,.pdf" class="hidden">
                    </div>

                    <p class="text-red-500 text-xs mt-1 error-message" id="error-file">
                        @error('file') {{ $message }} @enderror
                    </p>
                </div>

            </div>

            <!-- Submit -->
            <div class="flex justify-end space-x-2 mt-4">
                <a href="{{ url()->previous() }}" 
                    class="px-3 py-1 rounded bg-gray-100 text-gray-700 hover:bg-gray-200 text-sm transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-blue-600 text-white text-sm px-3 py-1 rounded hover:bg-blue-700 transition">
                    {{ isset($transaction) ? 'Update' : 'Save' }}
                </button>
            </div>
        </form>
    </div>
    @push('scripts')
    <script>
        const fileInput = document.getElementById('file');
        const fileNameInput = document.getElementById('file-name');
        const previewBtn = document.getElementById('file-preview-btn');

        // Click on text input triggers file input
        fileNameInput.addEventListener('click', () => fileInput.click());

        // Update file name when file selected & validate
        fileInput.addEventListener('change', function () {
            const file = this.files[0];
            fileNameInput.value = file.name;
            previewBtn.dataset.file = URL.createObjectURL(file);
        });

        // Click on preview button
        previewBtn.addEventListener('click', function () {
            const fileUrl = this.dataset.file;
            if (fileUrl) {
                window.open(fileUrl, '_blank');
            } else {
                toastr.info('No file selected to preview.');
            }
        });
    </script>
    @endpush
</x-app-layout>
