<x-app-layout>
        @section('page_title', 'Edit Customer')
    <div class="min-h-screen flex items-center justify-center bg-gray-100 py-8">
        <div class="w-full max-w-2xl bg-white rounded-xl shadow-lg p-10">
            <h2 class="text-2xl font-semibold text-gray-900 mb-2">Edit Customer</h2>
            <form method="POST" action="{{ route('customers.update', $customer) }}">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                        <input name="first_name" value="{{ old('first_name', $customer->first_name) }}" placeholder="First Name" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-200 focus:border-blue-400 @error('first_name') border-red-500 @enderror">
                        @error('first_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                        <input name="last_name" value="{{ old('last_name', $customer->last_name) }}" placeholder="Last Name" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-200 focus:border-blue-400 @error('last_name') border-red-500 @enderror">
                        @error('last_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone <span class="text-red-500">*</span></label>
                        <input name="phone" value="{{ old('phone', $customer->phone) }}" placeholder="Phone" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-200 focus:border-blue-400 @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp Number</label>
                        <input name="whatsapp_number" value="{{ old('whatsapp_number', $customer->whatsapp_number) }}" placeholder="WhatsApp Number" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-green-200 focus:border-green-400 @error('whatsapp_number') border-red-500 @enderror">
                        @error('whatsapp_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                        <input name="email" value="{{ old('email', $customer->email) }}" placeholder="Email" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-200 focus:border-blue-400 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <input name="address" value="{{ old('address', $customer->address) }}" placeholder="Address" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-200 focus:border-blue-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                        <input name="city" value="{{ old('city', $customer->city) }}" placeholder="City" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-200 focus:border-blue-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Province</label>
                        <input name="province" value="{{ old('province', $customer->province) }}" placeholder="Province" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-200 focus:border-blue-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Zip Code</label>
                        <input name="zip_code" value="{{ old('zip_code', $customer->zip_code) }}" placeholder="Zip Code" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-200 focus:border-blue-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Country <span class="text-red-500">*</span></label>
                        <input name="country" value="{{ old('country', $customer->country) }}" placeholder="Country" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-200 focus:border-blue-400 @error('country') border-red-500 @enderror">
                        @error('country')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-span-1 md:col-span-2 flex gap-8">
                        <div class="w-full">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Starting Date <span class="text-red-500">*</span></label>
                            <input name="starting_date" type="date" value="{{ old('starting_date', $customer->starting_date) }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-200 focus:border-blue-400 @error('starting_date') border-red-500 @enderror">
                            @error('starting_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="w-full">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ending Date <span class="text-red-500">*</span></label>
                            <input name="ending_date" type="date" value="{{ old('ending_date', $customer->ending_date) }}" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-200 focus:border-blue-400 @error('ending_date') border-red-500 @enderror">
                            @error('ending_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Duration (months)<span class="text-red-500">*</span></label>
                        <select name="duration" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-yellow-200 focus:border-yellow-400 @error('duration') border-red-500 @enderror">
                            <option value="1" {{ old('duration', $customer->duration) == 1 ? 'selected' : '' }}>1 month</option>
                            <option value="6" {{ old('duration', $customer->duration) == 6 ? 'selected' : '' }}>6 months</option>
                            <option value="12" {{ old('duration', $customer->duration) == 12 ? 'selected' : '' }}>12 months</option>
                            <option value="18" {{ old('duration', $customer->duration) == 18 ? 'selected' : '' }}>18 months</option>
                            <option value="24" {{ old('duration', $customer->duration) == 24 ? 'selected' : '' }}>24 months</option>
                        </select>
                        @error('duration')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                        <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-200 focus:border-blue-400 @error('status') border-red-500 @enderror">
                            <option value="1" {{ old('status', $customer->status) == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status', $customer->status) == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method <span class="text-red-500">*</span></label>
                        <select name="payment_method" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-200 focus:border-blue-400 @error('payment_method') border-red-500 @enderror">
                            <option value="online" {{ old('payment_method', $customer->payment_method) == 'online' ? 'selected' : '' }}>Online</option>
                            <option value="bank_transfer" {{ old('payment_method', $customer->payment_method) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        </select>
                        @error('payment_method')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Amount (Rs) <span class="text-red-500">*</span></label>
                        <input name="payment_amount" type="number" min="0" step="0.01" value="{{ old('payment_amount', $customer->payment_amount) }}" placeholder="Amount in Rs" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-yellow-200 focus:border-yellow-400 @error('payment_amount') border-red-500 @enderror">
                        @error('payment_amount')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Receipt <span class="text-red-500">*</span></label>
                        <select name="payment_receipt" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-200 focus:border-blue-400 @error('payment_receipt') border-red-500 @enderror">
                            <option value="1" {{ old('payment_receipt', $customer->payment_receipt) == 1 ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ old('payment_receipt', $customer->payment_receipt) == 0 ? 'selected' : '' }}>No</option>
                        </select>
                        @error('payment_receipt')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="mt-8">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Publications & Attachments</label>
                    <div class="overflow-x-auto rounded-lg border border-gray-200 bg-gray-50">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700">Select</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700">Publication</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700">Attachment Count</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($publications as $publication)
                                    <tr>
                                        <td class="px-4 py-2">
                                            <input type="checkbox" id="pub_{{ $publication->id }}" name="publications[{{ $publication->id }}][selected]" value="1" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" {{ isset($customerPublications[$publication->id]) ? 'checked' : '' }}>
                                        </td>
                                        <td class="px-4 py-2">
                                            <label for="pub_{{ $publication->id }}" class="text-gray-800">{{ $publication->name }}</label>
                                        </td>
                                        <td class="px-4 py-2">
                                            <input type="number" name="publications[{{ $publication->id }}][attachment_count]" min="1" value="{{ $customerPublications[$publication->id] ?? 1 }}" class="border border-gray-300 rounded-lg px-2 py-1 w-24 focus:ring-2 focus:ring-blue-200 focus:border-blue-400" placeholder="Attachments">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="flex flex-col md:flex-row justify-end items-center gap-4 mt-6">
                        <a href="{{ route('customers.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-8 py-2 rounded-lg shadow transition text-center">Cancel</a>
                        <button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-2 rounded-lg shadow transition">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
