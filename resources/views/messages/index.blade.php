<x-app-layout>
    @section('page_title', 'Messages')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Message Management</h1>
                    <p class="text-gray-600">Manage and coordinate automated messages and commands</p>
                </div>
            </div>
        </div>

        <!-- Actions and Legend -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex flex-wrap gap-3">
                    <!-- <a href="{{ route('messages.create') }}" class="inline-flex items-center bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Message
                    </a> -->
                </div>
                <!-- Subscription Alert Legend -->
                <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500">
                    <span class="font-semibold text-gray-600">Subscription alerts:</span>
                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-yellow-50 border border-yellow-200 rounded-full"><span class="w-2 h-2 bg-yellow-400 rounded-full"></span> 7 days</span>
                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-orange-50 border border-orange-200 rounded-full"><span class="w-2 h-2 bg-orange-400 rounded-full"></span> 3 days</span>
                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-50 border border-red-200 rounded-full"><span class="w-2 h-2 bg-red-500 rounded-full"></span> Today</span>
                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 border border-gray-300 rounded-full"><span class="w-2 h-2 bg-gray-400 rounded-full"></span> Expired</span>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Key</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                    @forelse($messages as $message)
                        @php
                            $subscriptionMeta = [
                                'subscription_notify_7days'   => ['border' => 'border-l-4 border-yellow-400', 'bg' => 'bg-yellow-50',  'badge' => '⚠️ 7 Days',  'badgeCls' => 'bg-yellow-100 text-yellow-800'],
                                'subscription_notify_3days'   => ['border' => 'border-l-4 border-orange-400', 'bg' => 'bg-orange-50', 'badge' => '🔔 3 Days',  'badgeCls' => 'bg-orange-100 text-orange-800'],
                                'subscription_notify_today'   => ['border' => 'border-l-4 border-red-500',    'bg' => 'bg-red-50',    'badge' => '🚨 Today',   'badgeCls' => 'bg-red-100 text-red-800'],
                                'subscription_notify_expired' => ['border' => 'border-l-4 border-gray-400',   'bg' => 'bg-gray-50',   'badge' => '❌ Expired', 'badgeCls' => 'bg-gray-200 text-gray-700'],
                            ];
                            $meta = $subscriptionMeta[$message->key] ?? null;
                        @endphp
                        <tr class="hover:opacity-90 transition-colors duration-150 {{ $meta ? $meta['border'] . ' ' . $meta['bg'] : 'hover:bg-gray-50' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-semibold text-gray-900">{{ $message->name }}</span>
                                    @if($meta)
                                        <span class="px-2 py-0.5 text-xs font-bold rounded-full {{ $meta['badgeCls'] }}">{{ $meta['badge'] }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs font-mono rounded">{{ $message->key }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($message->is_active)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                        <span class="w-2 h-2 bg-gray-400 rounded-full mr-2"></span>
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('messages.edit', $message) }}" class="inline-flex items-center px-3 py-1.5 bg-amber-50 text-amber-700 hover:bg-amber-100 rounded-lg transition-colors duration-150">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('messages.destroy', $message) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Are you sure you want to delete this message?')" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 hover:bg-red-100 rounded-lg transition-colors duration-150">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <p class="mt-4 text-lg font-medium text-gray-900">No messages found</p>
                                <p class="mt-2 text-sm text-gray-500">Get started by adding a new message/command.</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $messages->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
