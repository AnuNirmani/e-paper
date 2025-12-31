<x-app-layout>
        @section('page_title', 'Customer Details')
    <div class="max-w-2xl mx-auto mt-10 bg-white rounded-xl shadow-lg p-8">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Customer Details</h2>
        <form class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 mb-0">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">First Name </label>
                <input value="{{ $customer->first_name }}" readonly class="border border-gray-300 rounded-lg px-3 py-2 w-full bg-gray-100 cursor-not-allowed">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Last Name </label>
                <input value="{{ $customer->last_name }}" readonly class="border border-gray-300 rounded-lg px-3 py-2 w-full bg-gray-100 cursor-not-allowed">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone </label>
                <input value="{{ $customer->phone }}" readonly class="border border-gray-300 rounded-lg px-3 py-2 w-full bg-gray-100 cursor-not-allowed">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp Number</label>
                <input value="{{ $customer->whatsapp_number }}" readonly class="border border-gray-300 rounded-lg px-3 py-2 w-full bg-gray-100 cursor-not-allowed">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email </label>
                <input value="{{ $customer->email }}" readonly class="border border-gray-300 rounded-lg px-3 py-2 w-full bg-gray-100 cursor-not-allowed">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <input value="{{ $customer->address }}" readonly class="border border-gray-300 rounded-lg px-3 py-2 w-full bg-gray-100 cursor-not-allowed">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                <input value="{{ $customer->city }}" readonly class="border border-gray-300 rounded-lg px-3 py-2 w-full bg-gray-100 cursor-not-allowed">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Province</label>
                <input value="{{ $customer->province }}" readonly class="border border-gray-300 rounded-lg px-3 py-2 w-full bg-gray-100 cursor-not-allowed">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Zip Code</label>
                <input value="{{ $customer->zip_code }}" readonly class="border border-gray-300 rounded-lg px-3 py-2 w-full bg-gray-100 cursor-not-allowed">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Country </label>
                <input value="{{ $customer->country }}" readonly class="border border-gray-300 rounded-lg px-3 py-2 w-full bg-gray-100 cursor-not-allowed">
            </div>
            <div class="col-span-1 md:col-span-2 flex gap-8">
                <div class="w-full">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Starting Date </label>
                    <input value="{{ $customer->starting_date }}" readonly class="border border-gray-300 rounded-lg px-3 py-2 w-full bg-gray-100 cursor-not-allowed">
                </div>
                <div class="w-full">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ending Date </label>
                    <input value="{{ $customer->ending_date }}" readonly class="border border-gray-300 rounded-lg px-3 py-2 w-full bg-gray-100 cursor-not-allowed">
                </div>
            </div>
            
            <!-- Grouped fields for Duration, Status, Payment -->
            <div class="col-span-1 md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Duration (months)</label>
                    <input value="{{ $customer->duration }} months" readonly class="border border-gray-300 rounded-lg px-3 py-2 w-full bg-gray-100 cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <input value="{{ $customer->status == 1 ? 'Active' : ($customer->status == 0 ? 'Inactive' : 'Deleted') }}" readonly class="border border-gray-300 rounded-lg px-3 py-2 w-full bg-gray-100 cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                    <input value="{{ $customer->payment_method == 'online' ? 'Online' : ($customer->payment_method == 'bank_transfer' ? 'Bank Transfer' : '') }}" readonly class="border border-gray-300 rounded-lg px-3 py-2 w-full bg-gray-100 cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Amount (Rs)</label>
                    <input value="{{ $customer->payment_amount }}" readonly class="border border-gray-300 rounded-lg px-3 py-2 w-full bg-gray-100 cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Receipt</label>
                    <input value="{{ $customer->payment_receipt ? 'Yes' : 'No' }}" readonly class="border border-gray-300 rounded-lg px-3 py-2 w-full bg-gray-100 cursor-not-allowed">
                </div>
            </div>

           <div class="col-span-1 md:col-span-2 mt-8 flex justify-end gap-4">
                <a href="{{ route('customers.index') }}"
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-8 py-2 rounded-lg shadow transition text-center">
                    Back
                </a>
            </div>
        </form>
        </div>        
    </div>
</x-app-layout>
