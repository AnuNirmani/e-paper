<!-- <x-app-layout>
<div class="max-w-xl mx-auto p-6 bg-white shadow rounded">

    <h2 class="text-xl font-bold mb-4">Add Copy</h2>

    <form method="POST" action="{{ route('copies.store') }}">
        @csrf


        <div class="mb-4">
            <label class="block mb-1">Customer</label>
            <select name="customer_id" class="w-full border p-2 rounded">
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">
                        {{ $customer->first_name }}
                    </option>
                @endforeach
            </select>
        </div>


        <div class="mb-4">
            <label class="block mb-1">Publication</label>
            <select name="publication_id" class="w-full border p-2 rounded">
                @foreach($publications as $publication)
                    <option value="{{ $publication->id }}">
                        {{ $publication->name }}
                    </option>
                @endforeach
            </select>
        </div>


        <div class="mb-4">
            <label class="block mb-1">Message</label>
            <textarea name="message"
                      class="w-full border p-2 rounded"></textarea>
        </div>

        <div class="flex justify-end">
            <button class="bg-green-600 text-white px-6 py-2 rounded">
                Save
            </button>
        </div>

    </form>
</div>
</x-app-layout> -->
