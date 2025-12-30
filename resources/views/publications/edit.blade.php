<x-app-layout>
<div class="max-w-xl mx-auto mt-10 bg-white p-6 rounded-lg shadow">

    <h2 class="text-xl font-bold mb-6">Edit Publication</h2>

    <form method="POST" action="{{ route('publications.update', $publication->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block mb-1 font-medium">Publication Name</label>
            <input type="text" name="name"
                   value="{{ $publication->name }}"
                   class="w-full border rounded-lg px-3 py-2"
                   required>
        </div>

        <div class="mb-6">
            <label class="block mb-1 font-medium">Status</label>
            <select name="status" class="w-full border rounded-lg px-3 py-2">
                <option value="1" {{ $publication->status == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ $publication->status == 0 ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('publications.index') }}"
               class="bg-gray-300 px-6 py-2 rounded-lg">
                Cancel
            </a>
            <button class="bg-blue-600 text-white px-6 py-2 rounded-lg">
                Update
            </button>
        </div>

</form>
</div>
</x-app-layout>
