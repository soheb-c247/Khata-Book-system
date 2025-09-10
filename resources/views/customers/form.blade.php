<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="max-w-7xl mx-auto p-6 bg-white rounded-2xl shadow-md space-y-6">


            <!-- Title -->
            <h2 class="text-lg font-semibold mb-3 border-b pb-2">
                {{ isset($customer) ? 'Edit Customer' : 'Add Customer' }}
            </h2>

            <form id="customer-form" method="POST" 
                  action="{{ isset($customer) ? route('customers.update', $customer->encrypted_id) : route('customers.store') }}">
                @csrf
                @if(isset($customer))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <!-- Name -->
                    <div class="p-1">
                        <label for="name" class="block text-xs font-medium text-gray-700">Name<span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name"
                            value="{{ old('name', $customer->name ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm p-1"
                            >
                        <p class="text-red-500 text-xs mt-1 error-message" id="error-name">
                            @error('name') {{ $message }} @enderror
                        </p>
                    </div>


                    <!-- Phone -->
                    <div class="p-1">
                        <label for="phone" class="block text-xs font-medium text-gray-700">Phone<span class="text-red-500">*</span></label>
                        <input type="text" name="phone" id="phone"
                               value="{{ old('phone', $customer->phone ?? '') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm p-1"
                               >
                        <p class="text-red-500 text-xs mt-1 error-message" id="error-phone">
                            @error('phone') {{ $message }} @enderror
                        </p>
                    </div>

                </div>

                <!-- Address -->
                <div class="p-1 mt-4">
                    <label for="address" class="block text-xs font-medium text-gray-700">Address<span class="text-red-500">*</span></label>
                    <textarea name="address" id="address" rows="2" maxlength="200"
                              class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm p-1"
                              >{{ old('address', $customer->address ?? '') }}</textarea>
                    <p class="text-red-500 text-xs mt-1 error-message" id="error-address">
                        @error('address') {{ $message }} @enderror
                    </p>
                    <p class="text-gray-400 text-xs mt-1" id="address-count">0 / 200 characters</p>
                </div>

                <!-- Opening Balance -->
                @if(!isset($customer))
                    <div class="p-1 mt-4 md:w-1/2" id="opening-balance-container">
                        <label for="opening_balance" class="block text-xs font-medium text-gray-700">Opening Balance</label>
                        <input type="text" name="opening_balance" id="opening_balance"
                            value="{{ old('opening_balance', 0) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm p-1">
                        <p class="text-red-500 text-xs mt-1 error-message" id="error-opening_balance">
                            @error('opening_balance') {{ $message }} @enderror
                        </p>
                    </div>
                @endif

                <!-- Submit -->
                <div class="flex justify-end space-x-2 mt-4">
                    <a href="{{ route('customers.index') }}" 
                       class="px-3 py-1 rounded bg-gray-100 text-gray-700 hover:bg-gray-200 text-sm transition">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 text-white text-sm px-3 py-1 rounded hover:bg-blue-700 transition">
                        {{ isset($customer) ? 'Update' : 'Save' }}
                    </button>
                </div>
            </form>
    </div>
</x-app-layout>
