<x-app-layout>
    @section('page_title', 'Copies Management')
    
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Copies Management</h1>
                    <p class="text-gray-600 mt-1">Publication copies sent to customers</p>
                </div>
                <!-- <a href="{{ route('copies.create') }}" class="w-full sm:w-auto bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 inline-flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add New Copy
                </a> -->
            </div>

            <!-- Statistics Cards -->
            <!-- <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                        <h3 class="text-white font-semibold text-sm uppercase tracking-wide">Total Copies</h3>
                    </div>
                    <div class="p-6">
                        <div class="text-4xl font-bold text-blue-600">{{ $copies->count() }}</div>
                        <p class="text-gray-600 text-sm mt-2">Total copies in system</p>
                    </div>
                </div>

                @php
                    $uniqueCustomers = $copies->pluck('customer_id')->unique()->count();
                @endphp
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                        <h3 class="text-white font-semibold text-sm uppercase tracking-wide">Unique Customers</h3>
                    </div>
                    <div class="p-6">
                        <div class="text-4xl font-bold text-green-600">{{ $uniqueCustomers }}</div>
                        <p class="text-gray-600 text-sm mt-2">Customers with copies</p>
                    </div>
                </div>

                @php
                    $uniquePublications = $copies->pluck('publication_id')->unique()->count();
                @endphp
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-amber-500 to-amber-600 px-6 py-4">
                        <h3 class="text-white font-semibold text-sm uppercase tracking-wide">Publications</h3>
                    </div>
                    <div class="p-6">
                        <div class="text-4xl font-bold text-amber-600">{{ $uniquePublications }}</div>
                        <p class="text-gray-600 text-sm mt-2">Active publications</p>
                    </div>
                </div>
            </div> -->

            <!-- Copies Table -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4">
                    <h2 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        All Copies
                    </h2>
                </div>
                
                @if($copies->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-gray-200">
                                <th class="px-6 py-4 text-left text-sm font-extrabold text-gray-700">Customer</th>
                                <th class="px-6 py-4 text-left text-sm font-extrabold text-gray-700">Publication</th>
                                <!-- <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Message</th> -->
                                <th class="px-6 py-4 text-left text-sm font-extrabold text-gray-700">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $currentDate = null;
                            @endphp
                            @foreach($copies as $copy)
                                @php
                                    $copyDate = \Carbon\Carbon::parse($copy->created_at)->format('Y-m-d');
                                @endphp
                                
                                @if($currentDate !== $copyDate)
                                    @php
                                        $currentDate = $copyDate;
                                    @endphp
                                    <tr class="bg-gradient-to-r from-gray-100 to-gray-50">
                                        <td colspan="3" class="px-6 py-3 border-t-2 border-gray-300">
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($copy->created_at)->format('l, F j, Y') }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <!-- <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                            {{ strtoupper(substr($copy->customer_first_name, 0, 1)) }}
                                        </div> -->
                                        <div class="ml-3">
                                            <p class="text-sm text-gray-900">{{ $copy->customer_first_name }}</p>
                                            <!-- <p class="text-xs text-gray-500">ID: #{{ $copy->customer_id }}</p> -->
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <!-- <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-amber-400 to-amber-600 rounded-lg flex items-center justify-center text-white font-bold text-xs">
                                            {{ strtoupper(substr($copy->publication_name, 0, 2)) }}
                                        </div> -->
                                        <div class="ml-3">
                                            <p class="text-sm text-gray-900">{{ $copy->publication_name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <!-- <td class="px-6 py-4">
                                    <span class="inline-block max-w-xs text-sm text-gray-600 truncate" title="{{ $copy->message ?? '-' }}">
                                        {{ $copy->message ?? '—' }}
                                    </span>
                                </td> -->
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <form method="POST" action="{{ route('copies.destroy', $copy->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this copy?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center bg-red-100 hover:bg-red-200 text-red-700 font-medium px-3 py-2 rounded-lg transition-colors" title="Delete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No copies yet</h3>
                    <p class="text-gray-500 mb-6">Start by creating your first publication copy.</p>
                    <!-- <a href="{{ route('copies.create') }}" class="inline-flex items-center justify-center bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add New Copy
                    </a> -->
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
