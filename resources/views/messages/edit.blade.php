<x-app-layout>
    @section('page_title', 'Edit Message')
    <div class="min-h-screen bg-[#f8fafc] py-12 transition-all duration-300">
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

            <form method="POST" action="{{ route('messages.update', $message) }}" class="space-y-8" novalidate>
                @csrf
                @method('PUT')
                
                @if ($errors->any())
                    <div class="bg-rose-50 border-l-4 border-rose-500 p-5 rounded-r-xl shadow-sm animate-in fade-in slide-in-from-top-4 duration-300">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-base font-bold text-rose-800">Please correct the following errors:</h3>
                                <div class="mt-2 text-sm text-rose-700 font-medium">
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
                
                <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden transition-all duration-300 hover:shadow-[0_8px_40px_rgb(0,0,0,0.08)]">
                    <!-- Elegant Header with Gradient -->
                    <div class="bg-gradient-to-r from-slate-900 to-indigo-950 px-8 py-5 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-indigo-500/20 rounded-lg backdrop-blur-sm ring-1 ring-white/10">
                                <svg class="w-5 h-5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-white tracking-wide">Configuration Details</h2>
                        </div>
                        <div class="hidden sm:block">
                            <span class="text-indigo-300 text-xs font-bold uppercase tracking-widest bg-indigo-500/10 px-3 py-1 rounded-full ring-1 ring-white/5">Template Editor</span>
                        </div>
                    </div>

                    <div class="p-8 space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Name Input -->
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 tracking-wide uppercase flex items-center">
                                    Display Name
                                    <span class="text-rose-500 ml-1">*</span>
                                </label>
                                <div class="relative group">
                                    <input name="name" 
                                           value="{{ old('name', $message->name) }}" 
                                           placeholder="e.g., Subscription Renewal" 
                                           class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3.5 text-slate-900 font-medium transition-all duration-200 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none hover:border-slate-300 @error('name') border-rose-500 ring-rose-200 @enderror">
                                </div>
                                @error('name')
                                    <p class="text-rose-600 text-xs font-bold mt-1.5 flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Key Input -->
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 tracking-wide uppercase flex items-center">
                                    Mapping Key
                                    <span class="text-rose-500 ml-1">*</span>
                                </label>
                                <div class="relative group">
                                    <input name="key" 
                                           value="{{ old('key', $message->key) }}" 
                                           placeholder="e.g., renewal_notice" 
                                           class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3.5 text-indigo-700 font-mono text-sm transition-all duration-200 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none hover:border-slate-300 @error('key') border-rose-500 ring-rose-200 @enderror">
                                </div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1.5 flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2"></path></svg>
                                    Used for system integration. Letters, numbers, and dashes only.
                                </p>
                                @error('key')
                                    <p class="text-rose-600 text-xs font-bold mt-1.5 flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Body Input Section -->
                        <div class="space-y-3">
                            <label class="text-sm font-bold text-slate-700 tracking-wide uppercase flex items-center">
                                Message content
                                <span class="text-rose-500 ml-1">*</span>
                            </label>
                            <div class="relative">
                                <textarea name="body" 
                                          rows="8" 
                                          placeholder="Type your message content here..." 
                                          class="w-full bg-slate-50 border-slate-200 rounded-2xl px-6 py-5 text-slate-800 font-medium leading-relaxed transition-all duration-200 focus:bg-white focus:ring-8 focus:ring-indigo-500/5 focus:border-indigo-500 outline-none hover:border-slate-300 @error('body') border-rose-500 ring-rose-200 @enderror">{{ old('body', $message->body) }}</textarea>
                                
                                <!-- Floating indicator if template has variables -->
                                <div class="absolute bottom-4 right-4 flex space-x-2">
                                    <div class="px-3 py-1.5 bg-slate-900/5 text-slate-500 backdrop-blur-md rounded-lg text-[10px] font-bold uppercase tracking-widest ring-1 ring-slate-900/5">
                                        Rich Template
                                    </div>
                                </div>
                            </div>
                            @error('body')
                                <p class="text-rose-600 text-xs font-bold mt-1.5 flex items-center">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Enhanced Placeholder Guide -->
                        @if(isset($message->key) && (in_array($message->key, ['subscription_notify_7days','subscription_notify_3days','subscription_notify_today','subscription_notify_expired']) || str_starts_with($message->key, 'subscription_notify')))
                        <div class="bg-indigo-50/50 border border-indigo-100 rounded-2xl p-6 transition-all hover:bg-indigo-50">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="p-1.5 bg-indigo-500 rounded-lg shadow-sm">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-indigo-900 font-extrabold text-sm uppercase tracking-wider">Dynamic Placeholders</h3>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="group bg-white p-4 rounded-xl border border-indigo-100 shadow-sm transition-all hover:scale-[1.02] hover:shadow-md cursor-default">
                                    <div class="text-indigo-600 font-mono text-xs font-bold mb-2 group-hover:text-indigo-700">{name}</div>
                                    <p class="text-[11px] text-indigo-400 font-medium leading-tight">Inserts the customer's full professional name.</p>
                                </div>
                                <div class="group bg-white p-4 rounded-xl border border-indigo-100 shadow-sm transition-all hover:scale-[1.02] hover:shadow-md cursor-default">
                                    <div class="text-indigo-600 font-mono text-xs font-bold mb-2 group-hover:text-indigo-700">{ending_date}</div>
                                    <p class="text-[11px] text-indigo-400 font-medium leading-tight">The formatted expiry date of the subscription.</p>
                                </div>
                                <div class="group bg-white p-4 rounded-xl border border-indigo-100 shadow-sm transition-all hover:scale-[1.02] hover:shadow-md cursor-default">
                                    <div class="text-indigo-600 font-mono text-xs font-bold mb-2 group-hover:text-indigo-700">{days_remaining}</div>
                                    <p class="text-[11px] text-indigo-400 font-medium leading-tight">Humanized time until expiry (e.g., "3 days").</p>
                                </div>
                                <div class="group bg-white p-4 rounded-xl border border-indigo-100 shadow-sm transition-all hover:scale-[1.02] hover:shadow-md cursor-default">
                                    <div class="text-indigo-600 font-mono text-xs font-bold mb-2 group-hover:text-indigo-700">{newspapers_taken}</div>
                                    <p class="text-[11px] text-indigo-400 font-medium leading-tight">A list of newspapers the customer has taken.</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Status Check -->
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <span class="text-sm font-bold text-slate-700 uppercase tracking-wide">Status</span>
                                <label class="relative inline-flex items-center cursor-pointer group">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $message->is_active) ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-14 h-7 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-500/20 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600 shadow-inner group-hover:bg-slate-300 transition-all"></div>
                                    <span class="ms-3 text-sm font-bold text-slate-500 peer-checked:text-indigo-600 transition-colors uppercase tracking-wider">Active</span>
                                </label>
                            </div>
                            <div class="text-[11px] text-slate-400 font-medium italic">
                                Last updated: {{ $message->updated_at->format('M d, Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons with polished styles and animations -->
                <div class="flex flex-col sm:flex-row justify-end items-center gap-5 pb-12">
                    <a href="{{ route('messages.index') }}" 
                       class="w-full sm:w-auto inline-flex items-center justify-center bg-white hover:bg-slate-50 text-slate-600 font-bold px-8 py-4 rounded-xl border-2 border-slate-200 hover:border-slate-300 shadow-sm transition-all duration-200 hover:scale-[0.98] active:scale-95 tracking-wide">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel Changes
                    </a>
                    <button type="submit" 
                            class="w-full sm:w-auto inline-flex items-center justify-center bg-slate-900 hover:bg-indigo-700 text-white font-bold px-10 py-4 rounded-xl shadow-[0_10px_20px_rgb(0,0,0,0.1)] hover:shadow-[0_15px_25px_rgb(79,70,229,0.3)] transition-all duration-300 hover:scale-[1.02] active:scale-95 tracking-wide group">
                        <svg class="w-5 h-5 mr-3 transition-transform group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Template
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
