<div id="statementModal" 
     class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4">

    <div class="bg-white w-full max-w-4xl rounded-2xl shadow-2xl overflow-hidden flex flex-col">
        
        <!-- Header -->
        <div class="flex justify-between items-center px-6 py-4 border-b bg-gray-50">
            <h2 class="text-lg md:text-xl font-semibold text-gray-800">Get Customer Statement</h2>
            <button onclick="closeStatementModal()" 
                    class="text-gray-500 hover:text-gray-700 text-2xl leading-none">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-6 space-y-6 overflow-y-auto max-h-[70vh]">
            <form id="statementForm" action="{{ route('customers.statement', $customer->encrypted_id) }}" class="space-y-6">
                @csrf
                <input type="hidden" name="customer_id" value="{{ $customer->id }}">

                <!-- Date Range -->
                <div>
                    <span class="font-semibold text-gray-700">Select Date Range:</span>
                    <div class="mt-3 grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <label class="flex items-center gap-2 bg-gray-100 rounded-lg px-3 py-2 cursor-pointer hover:bg-gray-200">
                            <input type="radio" name="predefined_range" value="current_month" checked> Current Month
                        </label>
                        <label class="flex items-center gap-2 bg-gray-100 rounded-lg px-3 py-2 cursor-pointer hover:bg-gray-200">
                            <input type="radio" name="predefined_range" value="last_3_months"> Last 3 Months
                        </label>
                        <label class="flex items-center gap-2 bg-gray-100 rounded-lg px-3 py-2 cursor-pointer hover:bg-gray-200">
                            <input type="radio" name="predefined_range" value="custom"> Custom Range
                        </label>
                    </div>
                </div>

                <!-- Custom Dates -->
                <div id="customDateFields" class="hidden grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">From:</label>
                        <input type="date" name="from_date" 
                               class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-indigo-300 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">To:</label>
                        <input type="date" name="to_date" 
                               class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-indigo-300 focus:outline-none">
                    </div>
                </div>

                <!-- Output Type -->
                <div>
                    <span class="font-semibold text-gray-700">Select Output:</span>
                    <div class="mt-3 flex flex-wrap gap-3">
                        <label class="flex items-center gap-2 bg-gray-100 rounded-lg px-3 py-2 cursor-pointer hover:bg-gray-200">
                            <input type="radio" name="file_type" value="pdf" checked> PDF
                        </label>
                    </div>
                </div>

                <!-- Action Option -->
                <div>
                    <span class="font-semibold text-gray-700">Action:</span>
                    <div class="mt-3 flex flex-wrap gap-3">
                        <label class="flex items-center gap-2 bg-gray-100 rounded-lg px-3 py-2 cursor-pointer hover:bg-gray-200">
                            <input type="radio" name="action" value="download" checked> Download
                        </label>
                        <!-- <label class="flex items-center gap-2 bg-gray-100 rounded-lg px-3 py-2 cursor-pointer hover:bg-gray-200">
                            <input type="radio" name="action" value="email"> Email
                        </label> -->
                    </div>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-3 px-6 py-4 border-t bg-gray-50">
            <button onclick="closeStatementModal()" 
                    class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition">
                Cancel
            </button>
            <button type="submit" form="statementForm" id="generateBtn"
                    class="px-5 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition">
                Generate
            </button>
        </div>
    </div>
</div>
