<x-app-layout>
    @section('page_title', 'Publications')
    <div class="max-w-5xl mx-auto mt-12">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-2 md:mb-0"></h2>
            <!-- <a href="{{ route('publications.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded-lg shadow transition text-base">+ Add Publication</a> -->
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <table class="w-full text-base">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="p-4 text-left font-semibold rounded-tl-2xl">Name</th>
                        <th class="p-4 text-left font-semibold">Status</th>
                        <th class="p-4 text-left font-semibold rounded-tr-2xl">Action</th>
                    </tr>
                </thead>
                <tbody>
                <!-- Add Publication Row -->
                <tr class="border-b bg-gray-50">
                    <form method="POST" action="{{ route('publications.store') }}" class="contents">
                        @csrf
                        <td class="p-4 align-middle">
                            <input type="text" name="name" placeholder="Publication Name *" value="{{ old('name') }}" class="w-full border rounded-lg px-3 py-2 @if(!session('edit_error_id') && $errors->has('name')) border-red-500 @endif" > 
                            @error('name')
                                @if(!session('edit_error_id'))
                                    @error('name')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                @endif
                            @enderror
                        </td>
                        <td class="p-4 align-middle">
                            <select name="status" class="w-full border rounded-lg px-3 py-2 @if(!session('edit_error_id') && $errors->has('status')) border-red-500 @endif">
                                <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                @if(!session('edit_error_id'))
                                    @error('status')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                @endif
                            @enderror
                        </td>
                        <td class="p-4 align-middle whitespace-nowrap">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded-lg shadow transition text-base">Add Publication</button>
                        </td>
                    </form>
                </tr>
                <!-- End Add Publication Row -->
                @forelse($publications as $publication)
                    <tr class="border-b last:border-b-0 hover:bg-gray-50 transition" id="publication-row-{{ $publication->id }}">
                        @php $editError = session('edit_error_id') == $publication->id; @endphp
                        <form id="edit-form-{{ $publication->id }}" method="POST" action="{{ route('publications.update', $publication->id) }}" @if($editError) style="display:table-row;" @else class="hidden" style="display:none;" @endif>
                            @csrf
                            @method('PATCH')
                            <td class="p-4 align-middle">
                                <span id="name-display-{{ $publication->id }}" @if($editError) style="display:none;" @endif>{{ $publication->name }}</span>
                                <input id="name-input-{{ $publication->id }}" type="text" name="name" value="{{ old('name', $publication->name) }}" class="border rounded-lg px-2 py-1 @if($editError && $errors->has('name')) border-red-500 @endif" style="width: 70%; @if(!$editError) display:none; @endif">
                                @if($editError && $errors->has('name'))
                                    <div class="text-red-500 text-sm mt-1">{{ $errors->first('name') }}</div>
                                @endif
                            </td>
                            <!-- Status Cell -->
                            <td class="p-4 align-middle">
                                <span id="status-display-{{ $publication->id }}" @if($editError) style="display:none;" @endif>
                                    @if ($publication->status == 1)
                                        <span class="text-blue-600 font-semibold">Active</span>
                                    @else
                                        <span class="text-gray-500 font-semibold">Inactive</span>
                                    @endif
                                </span>
                                <span id="status-edit-{{ $publication->id }}" class="relative @if(!$editError) hidden @endif">
                                    <select name="status" class="appearance-none border rounded-lg px-4 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-blue-400 @if($editError && $errors->has('status')) border-red-500 @endif">
                                        <option value="1" {{ old('status', $publication->status) == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('status', $publication->status) == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @if($editError && $errors->has('status'))
                                        <div class="text-red-500 text-sm mt-1">{{ $errors->first('status') }}</div>
                                    @endif
                                </span>
                            </td>
                        </form>
                        <!-- Actions Cell -->
                        <td class="p-4 align-middle whitespace-nowrap">
                            @php $editError = session('edit_error_id') == $publication->id; @endphp
                            <span id="action-buttons-{{ $publication->id }}" @if($editError) style="display:none;" @endif>
                                <button type="button" class="text-yellow-500 hover:text-yellow-800 font-semibold mr-2 transition-colors" onclick="enableEdit({{ $publication->id }})">Edit</button>
                                <form action="{{ route('publications.destroy', $publication->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-500 hover:text-red-800 font-semibold transition-colors" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </span>
                            <span id="edit-buttons-{{ $publication->id }}" @if($editError) style="display:inline;" @else class="hidden" @endif>
                                <button type="button" class="text-blue-500 font-semibold mr-2" onclick="submitEdit({{ $publication->id }})">Save</button>
                                <button type="button" class="text-gray-500 font-semibold" onclick="cancelEdit({{ $publication->id }})">Cancel</button>
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="p-6 text-center text-gray-400">No publications found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            <!-- Inline Edit Script -->
            <script>
                function enableEdit(id) {
                    document.getElementById('name-display-' + id).style.display = 'none';
                    document.getElementById('name-input-' + id).style.display = 'inline';
                    document.getElementById('edit-form-' + id).style.display = 'inline';
                    document.getElementById('status-display-' + id).style.display = 'none';
                    document.getElementById('status-edit-' + id).style.display = 'inline';
                    document.getElementById('action-buttons-' + id).style.display = 'none';
                    document.getElementById('edit-buttons-' + id).style.display = 'inline';
                }
                function cancelEdit(id) {
                    document.getElementById('name-display-' + id).style.display = 'inline';
                    document.getElementById('name-input-' + id).style.display = 'none';
                    document.getElementById('edit-form-' + id).style.display = 'none';
                    document.getElementById('status-display-' + id).style.display = 'inline';
                    document.getElementById('status-edit-' + id).style.display = 'none';
                    document.getElementById('action-buttons-' + id).style.display = 'inline';
                    document.getElementById('edit-buttons-' + id).style.display = 'none';
                }
                function submitEdit(id) {
                    document.getElementById('edit-form-' + id).submit();
                }
            </script>
        </div>
    </div>
</x-app-layout>
