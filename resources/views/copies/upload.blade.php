<x-app-layout>

<div class="py-12 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">

        <!-- WITH WATERMARK SECTION -->
        <div>
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Upload the file with watermark</h2>
                <p class="text-gray-500 mt-2">Upload your document with watermark protection</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-8 py-6">
                    <h3 class="text-2xl font-bold text-white">File upload</h3>
                </div>

                <form method="POST" action="{{ route('copies.upload.store') }}" enctype="multipart/form-data" class="p-8">
                    @csrf
                    <input type="hidden" name="watermark" value="1">

                    <!-- CUSTOMER -->
                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Select Customer</label>
                        <input type="text" id="customer-search-watermark" placeholder="Type and click below to search customer" class="w-full border border-gray-300 rounded-lg px-4 py-2 mb-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" />
                        <select name="customer_id" 
                                id="customer-select-watermark"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                                >
                            <option value="">All Customers</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->first_name }} {{ $customer->last_name }} ({{ $customer->email }})</option>
                            @endforeach
                        </select>
                        <!-- @error('customer_id')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror -->
                    </div>

                    <!-- CATEGORY -->
                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Select Publication</label>
                        <div class="flex flex-wrap gap-4">
                            @foreach($publications as $publication)
                                <label class="flex items-center bg-gray-50 hover:bg-blue-50 border-2 border-gray-200 hover:border-blue-300 rounded-lg px-4 py-3 cursor-pointer transition">
                                    <input type="radio" name="publication_id"
                                           value="{{ $publication->id }}"
                                           class="w-4 h-4 text-blue-600 focus:ring-2 focus:ring-blue-500"
                                           >
                                    <span class="ml-3 font-medium text-gray-700">{{ $publication->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('publication_id')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- FILE -->
                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Choose E-Paper</label>
                        <input type="file"
                               name="file"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                               >
                        @error('file')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- MESSAGE -->
                    <!-- <div class="mb-8">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Message (Optional)</label>
                        <textarea name="message"
                                  rows="4"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                  placeholder="Enter message (optional)"></textarea>
                        @error('message')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div> -->

                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold px-8 py-4 rounded-lg hover:from-blue-700 hover:to-blue-800 transform hover:scale-[1.02] transition shadow-lg">
                        Send the E-Paper
                    </button>
                </form>
            </div>
        </div>

        <!-- WITHOUT WATERMARK SECTION -->
        <div>
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Upload the file without watermark</h2>
                <p class="text-gray-500 mt-2">Upload your document without watermark</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-600 to-gray-700 px-8 py-6">
                    <h3 class="text-2xl font-bold text-white">File upload</h3>
                </div>

                <form method="POST" action="{{ route('copies.upload.store') }}" enctype="multipart/form-data" class="p-8">
                    @csrf

                    <!-- CUSTOMER -->
                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Select Customer</label>
                        <input type="text" id="customer-search-plain" placeholder="Type and click below to search customer" class="w-full border border-gray-300 rounded-lg px-4 py-2 mb-3 focus:ring-2 focus:ring-gray-500 focus:border-transparent transition" />
                        <select name="customer_id" 
                                id="customer-select-plain"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-gray-500 focus:border-transparent transition" 
                                >
                            <option value="">All Customers</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->first_name }} {{ $customer->last_name }} ({{ $customer->email }})</option>
                            @endforeach
                        </select>
                        <!-- @error('customer_id')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror -->
                    </div>

                    <!-- CATEGORY -->
                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Select Publication</label>
                        <div class="flex flex-wrap gap-4">
                            @foreach($publications as $publication)
                                <label class="flex items-center bg-gray-50 hover:bg-gray-100 border-2 border-gray-200 hover:border-gray-400 rounded-lg px-4 py-3 cursor-pointer transition">
                                    <input type="radio" name="publication_id"
                                           value="{{ $publication->id }}"
                                           class="w-4 h-4 text-gray-600 focus:ring-2 focus:ring-gray-500"
                                           >
                                    <span class="ml-3 font-medium text-gray-700">{{ $publication->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('publication_id')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- FILE -->
                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Choose E-Paper</label>
                        <input type="file"
                               name="file"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-gray-500 focus:border-transparent transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100"
                               >
                        @error('file')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- MESSAGE -->
                    <!-- <div class="mb-8">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Message (Optional)</label>
                        <textarea name="message"
                                  rows="4"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-gray-500 focus:border-transparent transition"
                                  placeholder="Enter message (optional)"></textarea>
                        @error('message')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div> -->

                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-gray-600 to-gray-700 text-white font-semibold px-8 py-4 rounded-lg hover:from-gray-700 hover:to-gray-800 transform hover:scale-[1.02] transition shadow-lg">
                        Send the E-Paper
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

</x-app-layout>

<script>
    (function() {
        function attachFilter(inputId, selectId) {
            const input = document.getElementById(inputId);
            const select = document.getElementById(selectId);
            if (!input || !select) return;

            const options = Array.from(select.options);
            input.addEventListener('input', () => {
                const term = input.value.toLowerCase();

                options.forEach((opt, idx) => {
                    if (idx === 0) {
                        opt.hidden = false;
                        return;
                    }
                    const text = opt.textContent.toLowerCase();
                    opt.hidden = term.length ? !text.includes(term) : false;
                });

                // If current selection is hidden, reset to placeholder
                if (select.selectedOptions.length && select.selectedOptions[0].hidden) {
                    select.selectedIndex = 0;
                }
            });
        }

        attachFilter('customer-search-watermark', 'customer-select-watermark');
        attachFilter('customer-search-plain', 'customer-select-plain');
    })();
</script>
