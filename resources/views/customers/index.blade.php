<x-app-layout>
    <h2 class="text-xl font-bold mb-4">Customers</h2>

    <a href="{{ route('customers.create') }}"
       class="bg-blue-600 text-white px-4 py-2 rounded">
        + Add Customer
    </a>

    <table class="w-full mt-4 border">
        <tr class="bg-gray-200">
            <th class="p-2">Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Action</th>
        </tr>

        @foreach($customers as $customer)
        <tr>
            <td class="p-2">{{ $customer->name }}</td>
            <td>{{ $customer->email }}</td>
            <td>{{ $customer->phone }}</td>
            <td>
                <a href="{{ route('customers.edit',$customer) }}" class="text-blue-600">Edit</a>
                |
                <form action="{{ route('customers.destroy',$customer) }}"
                      method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button class="text-red-600">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</x-app-layout>
