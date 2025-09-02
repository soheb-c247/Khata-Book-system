<x-app-layout>
    <div class="max-w-3xl mx-auto p-6 bg-white rounded-2xl shadow-md">
        <h2 class="text-xl font-semibold mb-4">
            {{ $customer ? 'Edit Customer' : 'Add New Customer' }}
        </h2>

        <form method="POST" 
              action="{{ $customer ? route('customers.update', $customer->id) : route('customers.store') }}">
            @csrf
            @if($customer)
                @method('PUT')
            @endif

            <!-- Name -->
            <div class="mb-4">
                <label class="block text-gray-700">Name</label>
                <input type="text" name="name" 
                       class="w-full border rounded-lg px-3 py-2 mt-1"
                       value="{{ old('name', $customer->name ?? '') }}" required>
                @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <!-- Phone -->
            <div class="mb-4">
                <label class="block text-gray-700">Phone</label>
                <input type="text" name="phone" 
                       class="w-full border rounded-lg px-3 py-2 mt-1"
                       value="{{ old('phone', $customer->phone ?? '') }}" required>
                @error('phone') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <!-- Balance -->
            <div class="mb-4">
                <label class="block text-gray-700">Balance</label>
                <input type="number" step="0.01" name="balance" 
                       class="w-full border rounded-lg px-3 py-2 mt-1"
                       value="{{ old('balance', $customer->balance ?? 0) }}">
                @error('balance') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <!-- Buttons -->
            <div class="flex justify-between items-center mt-6">
                <a href="{{ route('customers.index') }}" 
                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg">
                   Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    {{ $customer ? 'Update' : 'Save' }}
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
