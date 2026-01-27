<x-app-layout>
    @section('page_title', 'Dashboard')
    
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
                <p class="text-gray-600 mt-2">Overview of your e-paper system</p>
            </div>

            <!-- Active Accounts Overview -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Active Accounts Overview</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Total Active Accounts -->
                    <a href="{{ route('customers.index', ['status' => 'active']) }}" class="block bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-200">
                        <div class="flex items-center justify-between mb-4">
                            <div class="bg-white bg-opacity-20 rounded-full p-3">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-sm font-medium opacity-90 mb-1">Total Active Accounts</h3>
                        <p class="text-4xl font-bold">{{ \App\Models\Customer::where('status', 1)->count() }}</p>
                    </a>

                    <!-- Dynamic Active Publications -->
                    @foreach(\App\Models\Publication::where('status', 1)->orderBy('name')->get() as $publication)
                        @php
                            // Count active customers linked via the publication-customer pivot
                            $activeAccountsCount = $publication->customers()
                                ->where('customers.status', 1)
                                ->count();
                        @endphp
                        <a href="{{ route('customers.index', ['publication_id' => $publication->id]) }}" class="block bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-200 cursor-pointer transform hover:scale-105">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-sm font-semibold text-gray-700">{{ $publication->name }}</h3>
                                <div class="bg-blue-100 rounded-full p-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-4xl font-bold text-gray-900 mb-2">{{ $activeAccountsCount }}</p>
                            <p class="text-sm text-gray-500">Active Accounts</p>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Publications Overview -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Publications Overview</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Total Publications -->
                    <a href="{{ route('publications.index') }}" class="block bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-200">
                        <div class="flex items-center justify-between mb-4">
                            <div class="bg-white bg-opacity-20 rounded-full p-3">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-sm font-medium opacity-90 mb-1">Total Publications</h3>
                        <p class="text-4xl font-bold">{{ \App\Models\Publication::where('status', '!=', -1)->count() }}</p>
                    </a>

                    <!-- Active Publications -->
                    <a href="{{ route('publications.index', ['status' => 'active']) }}" class="block bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-200 border-2 border-green-200">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-semibold text-gray-700">Active Publications</h3>
                            <div class="bg-green-100 rounded-full p-2">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-4xl font-bold text-green-600 mb-2">{{ \App\Models\Publication::where('status', 1)->count() }}</p>
                        <p class="text-sm text-gray-500">Currently Active</p>
                    </a>

                    <!-- Inactive Publications -->
                    <a href="{{ route('publications.index', ['status' => 'inactive']) }}" class="block bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-200 border-2 border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-semibold text-gray-700">Inactive Publications</h3>
                            <div class="bg-gray-100 rounded-full p-2">
                                <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-4xl font-bold text-gray-600 mb-2">{{ \App\Models\Publication::where('status', 0)->count() }}</p>
                        <p class="text-sm text-gray-500">Not Active</p>
                    </a>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <a href="{{ route('customers.index') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-200 group">
                        <div class="flex items-center">
                            <div class="bg-blue-100 rounded-full p-3 group-hover:bg-blue-200 transition-colors">
                                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">Manage Customers</h3>
                                <p class="text-sm text-gray-500">View and edit customer accounts</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('publications.index') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-200 group">
                        <div class="flex items-center">
                            <div class="bg-green-100 rounded-full p-3 group-hover:bg-green-200 transition-colors">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900 group-hover:text-green-600 transition-colors">Manage Publications</h3>
                                <p class="text-sm text-gray-500">View and edit publications</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('customers.create') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-200 group">
                        <div class="flex items-center">
                            <div class="bg-purple-100 rounded-full p-3 group-hover:bg-purple-200 transition-colors">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900 group-hover:text-purple-600 transition-colors">Add New Customer</h3>
                                <p class="text-sm text-gray-500">Create a new customer account</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>