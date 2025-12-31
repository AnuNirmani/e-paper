<!-- <x-app-layout>
<div class="max-w-xl mx-auto mt-10 bg-white p-6 rounded-lg shadow">

            @section('page_title', 'Add New Publication')
    <h2 class="text-xl font-bold mb-6">Add Publication</h2>

    <form method="POST" action="{{ route('publications.store') }}">
        @csrf

        <div class="mb-4">
            <label class="block mb-1 font-medium">Publication Name</label>
            <input type="text" name="name"
                   class="w-full border rounded-lg px-3 py-2"
                   required>
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('publications.index') }}"
               class="bg-gray-300 px-6 py-2 rounded-lg">
                Cancel
            </a>
            <button class="bg-blue-600 text-white px-6 py-2 rounded-lg">
                Save
            </button>
        </div>
    </form>
    </div>
</div>
</x-app-layout> -->
