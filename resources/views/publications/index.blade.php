<x-app-layout>
<div class="max-w-7xl mx-auto p-6">

    <div class="flex justify-between mb-6">
        <h2 class="text-2xl font-bold">Publications</h2>
        <a href="{{ route('publications.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">
            + Add Publication
        </a>
    </div>

    <table class="w-full border border-gray-300 rounded-lg overflow-hidden">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-3 text-left">Name</th>
                <th class="p-3 text-center">Status</th>
                <th class="p-3 text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($publications as $publication)
                <tr class="border-t">
                    <td class="p-3">{{ $publication->name }}</td>
                    <td class="p-3 text-center">
                        @if ($publication->status == 1)
                            <span class="text-green-600 font-semibold">Active</span>
                        @else
                            <span class="text-gray-500 font-semibold">Inactive</span>
                        @endif
                    </td>
                    <td class="p-3 text-center space-x-3">
                        <a href="{{ route('publications.edit', $publication->id) }}"
                           class="text-blue-600 hover:underline">Edit</a>

                        <form action="{{ route('publications.destroy', $publication->id) }}"
                              method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Delete this publication?')"
                                    class="text-red-600 hover:underline">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
</x-app-layout>
