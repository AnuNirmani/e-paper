<x-app-layout>

<div class="py-12 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Upload and Send E-Paper</h2>
            <p class="text-gray-500 mt-2">Choose once, then decide watermark on send</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-8 py-6">
                <h3 class="text-2xl font-bold text-white">File upload</h3>
            </div>

            <form method="POST" action="{{ route('copies.upload.store') }}" enctype="multipart/form-data" class="p-8 space-y-8">
                @csrf

                <!-- WATERMARK SWITCH -->
                <div>
                    <span class="block text-sm font-semibold text-gray-700 mb-3">Watermark option</span>
                    <div class="flex gap-4">
                        <label class="flex items-center bg-blue-50 border-2 border-blue-200 rounded-lg px-4 py-3 cursor-pointer hover:border-blue-400 transition">
                            <input type="radio" name="watermark" value="1" class="w-4 h-4 text-blue-600" checked>
                            <span class="ml-3 font-medium text-gray-800">Send with watermark</span>
                        </label>
                        <label class="flex items-center bg-gray-50 border-2 border-gray-200 rounded-lg px-4 py-3 cursor-pointer hover:border-gray-400 transition">
                            <input type="radio" name="watermark" value="0" class="w-4 h-4 text-gray-600">
                            <span class="ml-3 font-medium text-gray-800">Send without watermark</span>
                        </label>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">If selected, PDFs are watermarked per customer before WhatsApp delivery.</p>
                </div>

                <!-- CUSTOMER -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Select Customer</label>
                    <input type="text" id="customer-search" placeholder="Type and click below to search customer" class="w-full border border-gray-300 rounded-lg px-4 py-2 mb-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" />
                    <select name="customer_id" 
                            id="customer-select"
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
                <div>
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
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Choose E-Paper</label>
                    <input type="file"
                           id="file-input"
                           name="file"
                           accept=".pdf,application/pdf"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                           >
                    <span id="file-error" class="text-red-500 text-sm mt-1 block hidden"></span>
                    @error('file')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" 
                        id="submit-button"
                        class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold px-8 py-4 rounded-lg hover:from-blue-700 hover:to-blue-800 transform hover:scale-[1.02] transition shadow-lg">
                    Send the E-Paper
                </button>
            </form>
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

                if (select.selectedOptions.length && select.selectedOptions[0].hidden) {
                    select.selectedIndex = 0;
                }
            });
        }

        attachFilter('customer-search', 'customer-select');

        // File validation
        const fileInput = document.getElementById('file-input');
        const fileError = document.getElementById('file-error');
        const submitButton = document.getElementById('submit-button');
        const minSize = 10 * 1024; // 10KB in bytes
        const maxSize = 25 * 1024 * 1024; // 25MB in bytes

        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            
            if (!file) {
                fileError.classList.add('hidden');
                submitButton.disabled = false;
                submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                return;
            }

            let errorMsg = '';

            // Check file type
            if (file.type !== 'application/pdf') {
                errorMsg = 'Only PDF files are allowed. Please select a PDF file.';
            }
            // Check minimum file size
            else if (file.size < minSize) {
                const sizeKB = (file.size / 1024).toFixed(2);
                errorMsg = `File size (${sizeKB}KB) is below the minimum required size of 10KB.`;
            }
            // Check file size
            else if (file.size > maxSize) {
                const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
                errorMsg = `File size (${sizeMB}MB) exceeds the maximum allowed size of 25MB.`;
            }

            if (errorMsg) {
                fileError.textContent = errorMsg;
                fileError.classList.remove('hidden');
                submitButton.disabled = true;
                submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                this.value = ''; // Clear the file input
            } else {
                fileError.classList.add('hidden');
                submitButton.disabled = false;
                submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        });
    })();
</script>
