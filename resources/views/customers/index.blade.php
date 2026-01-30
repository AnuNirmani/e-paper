<x-app-layout>
    @section('page_title', 'Customers')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Customer Management</h1>
                    <p class="text-gray-600">Manage and monitor all your customer accounts</p>
                </div>
                @if($selectedPublication)
                    <div class="bg-blue-50 border-2 border-blue-200 rounded-lg px-4 py-2">
                        <p class="text-sm text-gray-600">Filtered by Publication:</p>
                        <p class="text-lg font-semibold text-blue-600">{{ $selectedPublication->name }}</p>
                        <a href="{{ route('customers.index') }}" class="text-sm text-blue-500 hover:text-blue-700 mt-1">Clear Filter</a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Active Accounts Card -->
            <a href="{{ route('customers.index', ['status' => 'active']) }}" class="block bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white hover:shadow-xl transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium mb-1">Total Active Accounts</p>
                        <p class="text-3xl font-bold">{{ $activeCount ?? 0 }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </a>

            <!-- Publication Stats Cards -->
            @foreach($publicationStats as $stat)
            <a href="{{ route('customers.index', ['publication_id' => $stat['id'] ?? null]) }}" class="block bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-200 p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium mb-1">{{ strtoupper($stat['name']) }}</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stat['active_accounts'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">Active Accounts</p>
                    </div>
                    <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-full p-3">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                        </svg>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <!-- Actions and Search Bar -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('customers.create') }}" class="inline-flex items-center bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Customer
                    </a>
                    @php($endingSortActive = request('sort') === 'ending_today')
                    <a href="{{ route('customers.index', array_merge(request()->query(), ['sort' => 'ending_today'])) }}"
                       class="inline-flex items-center font-semibold px-5 py-3 rounded-lg border transition-all duration-200 {{ $endingSortActive ? 'bg-indigo-600 text-white border-indigo-600 hover:bg-indigo-700 hover:border-indigo-700 shadow-md' : 'bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border-indigo-200 hover:border-indigo-300' }}">
                        <svg class="w-5 h-5 mr-2 {{ $endingSortActive ? 'text-white' : 'text-indigo-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-12 6h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        Sort By Ending Date
                    </a>
                    @php($endingSortActive = request('sort') === 'id')
                    <a href="{{ route('customers.index', array_merge(request()->query(), ['sort' => 'id'])) }}"
                       class="inline-flex items-center font-semibold px-5 py-3 rounded-lg border transition-all duration-200 {{ $endingSortActive ? 'bg-indigo-600 text-white border-indigo-600 hover:bg-indigo-700 hover:border-indigo-700 shadow-md' : 'bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border-indigo-200 hover:border-indigo-300' }}">
                        <svg class="w-5 h-5 mr-2 {{ $endingSortActive ? 'text-white' : 'text-indigo-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-12 6h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        Sort By Id
                    </a>
                    <form action="{{ route('customers.activateAll') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center bg-green-50 hover:bg-green-100 text-green-700 font-semibold px-5 py-3 rounded-lg border border-green-200 hover:border-green-300 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Activate All
                        </button>
                    </form>
                    <form action="{{ route('customers.deactivateAll') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center bg-gray-50 hover:bg-gray-100 text-gray-700 font-semibold px-5 py-3 rounded-lg border border-gray-200 hover:border-gray-300 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                            </svg>
                            Deactivate All
                        </button>
                    </form>
                </div>

                <!-- Search Form -->
                <form method="GET" action="" class="flex gap-2 w-full lg:w-auto">
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                    <div class="relative flex-1 lg:w-80">
                        <input type="text" name="search" placeholder="Search customers..." value="{{ request('search') }}" class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" />
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                        Search
                    </button>
                </form>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">WhatsApp</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Duration</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Ending Date</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                    @forelse($customers as $customer)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <!-- <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($customer->first_name, 0, 1)) }}{{ strtoupper(substr($customer->last_name, 0, 1)) }}
                                    </div> -->
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ $customer->first_name }} {{ $customer->last_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $customer->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="text-sm text-gray-900 text-center">{{ $customer->phone }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="text-sm text-gray-900 text-center">{{ $customer->whatsapp_number }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($customer->status != -1)
                                    <label class="flex items-center cursor-pointer">
                                        <input type="checkbox" 
                                               {{ $customer->status == 1 ? 'checked' : '' }} 
                                               onchange="toggleCustomerStatus({{ $customer->id }})"
                                               class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-2 focus:ring-green-500 cursor-pointer">
                                        <span class="ml-2 text-xs font-semibold {{ $customer->status == 1 ? 'text-green-800' : 'text-gray-800' }}">
                                            {{ $customer->status == 1 ? 'Active' : 'Inactive' }}
                                        </span>
                                    </label>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                        <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                        Deleted
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="text-sm font-medium text-gray-900 text-center">{{ $customer->duration }} 
                                    <span class="text-gray-500 text-xs">{{ $customer->duration == 1 ? 'month' : 'months' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="text-sm text-gray-900 text-center">{{ $customer->ending_date ? $customer->ending_date->format('Y-m-d') : '' }}</div>
                                @if($customer->ending_date)
                                    <div class="text-xs text-gray-500 text-center">{{ $customer->remaining_text }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('customers.show',$customer) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-lg transition-colors duration-150">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View
                                    </a>
                                    <a href="{{ route('customers.edit',$customer) }}" class="inline-flex items-center px-3 py-1.5 bg-amber-50 text-amber-700 hover:bg-amber-100 rounded-lg transition-colors duration-150">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit
                                    </a>
                                    <button onclick="sendSubscriptionNotification({{ $customer->id }}, '{{ $customer->first_name }} {{ $customer->last_name }}')" 
                                            class="inline-flex items-center px-3 py-1.5 bg-green-50 text-green-700 hover:bg-green-100 rounded-lg transition-colors duration-150"
                                            title="Send subscription ending notification via WhatsApp">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                        </svg>
                                        Notify
                                    </button>
                                    <form action="{{ route('customers.destroy',$customer) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Are you sure you want to delete this customer?')" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 hover:bg-red-100 rounded-lg transition-colors duration-150">
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
                            <td colspan="7" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <p class="mt-4 text-lg font-medium text-gray-900">No customers found</p>
                                <p class="mt-2 text-sm text-gray-500">Get started by adding a new customer.</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination Links -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $customers->links() }}
            </div>
        </div>
    </div>

    <!-- Custom Modal -->
    <div id="customModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-center mb-4">
                    <div id="modalIcon" class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full">
                        <!-- Icon will be inserted here -->
                    </div>
                </div>
                <h3 id="modalTitle" class="text-lg leading-6 font-semibold text-gray-900 text-center mb-2"></h3>
                <div class="mt-2 px-7 py-3">
                    <p id="modalMessage" class="text-sm text-gray-600 text-center"></p>
                </div>
                <div id="modalButtons" class="flex gap-3 px-4 py-3 justify-center">
                    <!-- Buttons will be inserted here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        // Custom Modal Functions
        const modal = {
            show: function(title, message, type = 'info', buttons = null) {
                const modalEl = document.getElementById('customModal');
                const modalIcon = document.getElementById('modalIcon');
                const modalTitle = document.getElementById('modalTitle');
                const modalMessage = document.getElementById('modalMessage');
                const modalButtons = document.getElementById('modalButtons');

                modalTitle.textContent = title;
                modalMessage.textContent = message;

                // Set icon based on type
                let iconHTML = '';
                let iconBgClass = '';
                
                if (type === 'success') {
                    iconBgClass = 'bg-green-100';
                    iconHTML = '<svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
                } else if (type === 'error') {
                    iconBgClass = 'bg-red-100';
                    iconHTML = '<svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
                } else if (type === 'confirm') {
                    iconBgClass = 'bg-blue-100';
                    iconHTML = '<svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                } else {
                    iconBgClass = 'bg-blue-100';
                    iconHTML = '<svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                }

                modalIcon.className = `flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full ${iconBgClass}`;
                modalIcon.innerHTML = iconHTML;

                // Set buttons
                if (buttons) {
                    modalButtons.innerHTML = buttons;
                } else {
                    modalButtons.innerHTML = '<button onclick="modal.hide()" class="px-6 py-2 bg-blue-600 text-white text-base font-medium rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">OK</button>';
                }

                modalEl.classList.remove('hidden');
            },

            hide: function() {
                document.getElementById('customModal').classList.add('hidden');
            },

            confirm: function(title, message, onConfirm, onCancel = null) {
                const buttons = `
                    <button onclick="modal.handleConfirm(true)" class="px-6 py-2 bg-blue-600 text-white text-base font-medium rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">OK</button>
                    <button onclick="modal.handleConfirm(false)" class="px-6 py-2 bg-gray-200 text-gray-700 text-base font-medium rounded-lg shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400">Cancel</button>
                `;
                
                this.confirmCallback = onConfirm;
                this.cancelCallback = onCancel;
                this.show(title, message, 'confirm', buttons);
            },

            handleConfirm: function(confirmed) {
                this.hide();
                if (confirmed && this.confirmCallback) {
                    this.confirmCallback();
                } else if (!confirmed && this.cancelCallback) {
                    this.cancelCallback();
                }
            },

            success: function(message) {
                this.show('Success', message, 'success');
            },

            error: function(message) {
                this.show('Error', message, 'error');
            }
        };

        function toggleCustomerStatus(customerId) {
            fetch(`/customers/${customerId}/toggle-status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                modal.error('Failed to update status');
            });
        }

        function sendSubscriptionNotification(customerId, customerName) {
            modal.confirm(
                'Send Notification',
                `Send subscription ending notification to ${customerName} via WhatsApp?`,
                function() {
                    sendNotificationRequest(customerId, customerName);
                }
            );
        }

        function sendNotificationRequest(customerId, customerName) {
            fetch(`/whatsapp/send-subscription-notification/${customerId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    modal.success(data.message);
                } else {
                    modal.error(data.message || 'Failed to send notification');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                modal.error('Failed to send notification. Please try again.');
            });
        }
    </script>
</x-app-layout>