<x-app-layout>
<div class="p-6">

    <div class="flex justify-between mb-4">
        <h2 class="text-xl font-bold">Copies</h2>
        <a href="{{ route('copies.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded">
           Add Copy
        </a>
    </div>

    <table class="w-full border">
        <thead class="bg-gray-100">
            <tr>
                <th class="border p-2">Customer</th>
                <th class="border p-2">Publication</th>
                <th class="border p-2">Message</th>
            </tr>
        </thead>
        <tbody>
            @foreach($copies as $copy)
            <tr>
                <td class="border p-2">{{ $copy->customer_first_name }}</td>
                <td class="border p-2">{{ $copy->publication_name }}</td>
                <td class="border p-2">{{ $copy->message }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
</x-app-layout>
