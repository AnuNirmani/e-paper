<x-app-layout>
    @section('page_title', 'Edit Message')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <a href="{{ route('messages.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 mb-4 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Messages
                </a>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Message</h1>
                <p class="text-gray-600">Modify the message template or automated command.</p>
            </div>

            <form method="POST" action="{{ route('messages.update', $message) }}" novalidate>
                @csrf
                @method('PUT')
                
                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">There were {{ $errors->count() }} error(s) with your submission</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                
                <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                        <h2 class="text-lg font-semibold text-white flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                            </svg>
                            Message Configuration
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Name <span class="text-red-500">*</span>
                                    </label>
                                    <input name="name" value="{{ old('name', $message->name) }}" placeholder="e.g., Subscription Ending" class="border border-gray-300 rounded-lg px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('name') border-red-500 ring-2 ring-red-200 @enderror">
                                    @error('name')
                                        <p class="text-red-500 text-sm mt-1 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Key <span class="text-red-500">*</span>
                                    </label>
                                    <input name="key" value="{{ old('key', $message->key) }}" placeholder="e.g., subscription_ending" class="border border-gray-300 rounded-lg px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('key') border-red-500 ring-2 ring-red-200 @enderror">
                                    <p class="mt-1 text-xs text-gray-500 italic">Unique identifier using letters, numbers, and dashes.</p>
                                    @error('key')
                                        <p class="text-red-500 text-sm mt-1 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Message Body <span class="text-red-500">*</span>
                                </label>
                                <textarea name="body" rows="6" placeholder="Enter the message content here..." class="border border-gray-300 rounded-lg px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('body') border-red-500 ring-2 ring-red-200 @enderror">{{ old('body', $message->body) }}</textarea>
                                @error('body')
                                    <p class="text-red-500 text-sm mt-1 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror

                                <!-- Placeholder Guide -->
                                @if(in_array($message->key, ['subscription_notify_7days','subscription_notify_3days','subscription_notify_today','subscription_notify_expired']))
                                <div class="mt-3 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                    <p class="text-xs font-semibold text-blue-700 mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Available Placeholders
                                    </p>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                                        <div class="flex items-start gap-2">
                                            <code class="px-2 py-0.5 bg-white border border-blue-200 rounded text-xs text-blue-800 font-mono shrink-0">{name}</code>
                                            <span class="text-xs text-blue-600">Customer's full name</span>
                                        </div>
                                        <div class="flex items-start gap-2">
                                            <code class="px-2 py-0.5 bg-white border border-blue-200 rounded text-xs text-blue-800 font-mono shrink-0">{ending_date}</code>
                                            <span class="text-xs text-blue-600">Subscription end date (d/m/Y)</span>
                                        </div>
                                        <div class="flex items-start gap-2">
                                            <code class="px-2 py-0.5 bg-white border border-blue-200 rounded text-xs text-blue-800 font-mono shrink-0">{days_remaining}</code>
                                            <span class="text-xs text-blue-600">e.g. "7 days" or "today"</span>
                                        </div>
                                    </div>
                                </div>
                                @endif

                            <div>
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $message->is_active) ? 'checked' : '' }} class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-2 focus:ring-blue-500 cursor-pointer">
                                    <span class="ml-2 text-sm font-medium text-gray-700">Is Active</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-end items-center gap-4">
                    <a href="{{ route('messages.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center bg-white hover:bg-gray-50 text-gray-700 font-semibold px-8 py-3 rounded-lg border-2 border-gray-300 hover:border-gray-400 shadow-sm transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel
                    </a>
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold px-8 py-3 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
