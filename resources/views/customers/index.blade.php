<x-app-layout>
        @section('page_title', 'Customers')
    <div class="max-w-8xl mx-auto mt-12">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
            <div class="flex gap-2 mb-2 md:mb-0">
                <a href="{{ route('customers.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded-lg shadow transition text-base">+ Add Customer</a>
                <form action="{{ route('customers.activateAll') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-gray-200 hover:bg-green-200 text-gray-800 font-medium py-2 px-4 rounded-md transition-colors duration-200">Active all</button>
                </form>
                <form action="{{ route('customers.deactivateAll') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-md transition-colors duration-200">Deactivate all</button>
                </form>
            </div>
            <div class="flex flex-col gap-2 md:gap-0 md:flex-row md:items-center">
                <form method="GET" action="" class="mr-4 flex gap-2">
                    <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 w-80" />
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded-lg shadow transition">Search</button>
                </form>
            </div>
        </div>
        <div class="mb-4">
            <span class="text-gray-700 text-base">All Active Accounts: {{ $activeCount ?? 0 }}</span>
        </div>
        <div class="mb-6">
            <span class="text-gray-700 text-base">
                @foreach($publicationStats as $i => $stat)
                    {{ strtoupper($stat['name']) }} Active Accounts: {{ $stat['active_accounts'] }}@if($i < count($publicationStats) - 1), @endif
                @endforeach
            </span>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <table class="w-full text-base">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="p-4 text-left font-semibold rounded-tl-2xl">First Name</th>
                        <th class="p-4 text-left font-semibold">Last Name</th>
                        <th class="p-4 text-left font-semibold">Email</th>
                        <th class="p-4 text-left font-semibold">Phone</th>
                        <th class="p-4 text-left font-semibold">WhatsApp Number</th>
                        <th class="p-4 text-left font-semibold">Status</th>
                        <th class="p-4 text-left font-semibold">Duration (months)</th>
                        <th class="p-4 text-left font-semibold">Ending Date</th>
                        <th class="p-4 text-left font-semibold rounded-tr-2xl">Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($customers as $customer)
                    <tr class="border-b last:border-b-0 hover:bg-gray-50 transition">
                        <td class="p-4 align-middle">{{ $customer->first_name }}</td>
                        <td class="p-4 align-middle">{{ $customer->last_name }}</td>
                        <td class="p-4 align-middle">{{ $customer->email }}</td>
                        <td class="p-4 align-middle">{{ $customer->phone }}</td>
                        <td class="p-4 align-middle">{{ $customer->whatsapp_number }}</td>
                        <td class="p-4 align-middle">
                            {{ $customer->status == 1 ? 'Active' : ($customer->status == 0 ? 'Inactive' : 'Deleted') }}
                        </td>
                        <td class="p-4 align-middle">{{ $customer->duration }}</td>
                        <td class="p-4 align-middle">{{ $customer->ending_date }}</td>
                        <td class="p-4 align-middle whitespace-nowrap">
                            <a href="{{ route('customers.show',$customer) }}" class="text-blue-500 hover:text-blue-800 font-semibold mr-2 transition-colors">View</a>
                            <a href="{{ route('customers.edit',$customer) }}" class="text-yellow-500 hover:text-yellow-800 font-semibold mr-2 transition-colors">Edit</a>
                            <form action="{{ route('customers.destroy',$customer) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-500 hover:text-red-800 font-semibold transition-colors" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-6 text-center text-gray-400">No customers found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
