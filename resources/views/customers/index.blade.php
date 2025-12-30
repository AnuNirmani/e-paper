<x-app-layout>
        @section('page_title', 'Customers')
    <div class="max-w-5xl mx-auto mt-12">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-2 md:mb-0">Customers</h2>
            <a href="{{ route('customers.create') }}" class="bg-green-500 hover:bg-green-600 text-white font-semibold px-4 py-2 rounded-lg shadow transition text-base">+ Add Customer</a>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <table class="w-full text-base">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="p-4 text-left font-semibold rounded-tl-2xl">First Name</th>
                        <th class="p-4 text-left font-semibold">Last Name</th>
                        <th class="p-4 text-left font-semibold">Email</th>
                        <th class="p-4 text-left font-semibold">Phone</th>
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
