<x-app-layout>
    @section('page_title', 'Publications')
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Publications Management</h1>
            <p class="text-gray-600">Manage your newspaper and magazine publications</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium mb-1">Total Publications</p>
                        <p class="text-3xl font-bold">{{ $publications->count() }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-200 p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium mb-1">Active Publications</p>
                        <p class="text-2xl font-bold text-green-600">{{ $publications->where('status', 1)->count() }}</p>
                    </div>
                    <div class="bg-green-50 rounded-full p-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-200 p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium mb-1">Inactive Publications</p>
                        <p class="text-2xl font-bold text-gray-600">{{ $publications->where('status', 0)->count() }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-full p-3">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Publication Card -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-md p-6 mb-6 border border-blue-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add New Publication
            </h3>
            <form method="POST" action="{{ route('publications.store') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Publication Name</label>
                        <input type="text" name="name" placeholder="Enter publication name *" value="{{ old('name') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @if(!session('edit_error_id') && $errors->has('name')) border-red-500 ring-2 ring-red-200 @endif" >
                        @error('name')
                            @if(!session('edit_error_id'))
                                <div class="text-red-500 text-sm mt-1 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @endif
                        @enderror
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @if(!session('edit_error_id') && $errors->has('status')) border-red-500 ring-2 ring-red-200 @endif">
                            <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            @if(!session('edit_error_id'))
                                <div class="text-red-500 text-sm mt-1 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @endif
                        @enderror
                    </div>
                    <div class="md:col-span-1 flex items-end">
                        <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Publication
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Publications Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    All Publications
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                @forelse($publications as $publication)
                    <tr class="hover:bg-gray-50 transition-colors duration-150" id="publication-row-{{ $publication->id }}">
                        @php $editError = session('edit_error_id') == $publication->id; @endphp
                        <form id="edit-form-{{ $publication->id }}" method="POST" action="{{ route('publications.update', $publication->id) }}" @if($editError) style="display:table-row;" @else class="hidden" style="display:none;" @endif>
                            @csrf
                            @method('PATCH')
                            <!-- Name Column -->
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <!-- <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-lg flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($publication->name, 0, 2)) }}
                                    </div> -->
                                    <div class="ml-4">
                                        <span id="name-display-{{ $publication->id }}" @if($editError) style="display:none;" @endif class="text-sm font-semibold text-gray-900">{{ $publication->name }}</span>
                                        <input id="name-input-{{ $publication->id }}" type="text" name="name" value="{{ old('name', $publication->name) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @if($editError && $errors->has('name')) border-red-500 ring-2 ring-red-200 @endif" style="@if(!$editError) display:none; @endif">
                                        @if($editError && $errors->has('name'))
                                            <div class="text-red-500 text-sm mt-1 flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $errors->first('name') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <!-- Status Column -->
                            <td class="px-6 py-4">
                                <span id="status-display-{{ $publication->id }}" @if($editError) style="display:none;" @endif>
                                    <label class="flex items-center cursor-pointer">
                                        <input type="checkbox" 
                                               {{ $publication->status == 1 ? 'checked' : '' }} 
                                               onchange="togglePublicationStatus({{ $publication->id }})"
                                               class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-2 focus:ring-green-500 cursor-pointer">
                                        <span class="ml-2 text-xs font-semibold {{ $publication->status == 1 ? 'text-green-800' : 'text-gray-800' }}">
                                            {{ $publication->status == 1 ? 'Active' : 'Inactive' }}
                                        </span>
                                    </label>
                                </span>
                                <span id="status-edit-{{ $publication->id }}" class="@if(!$editError) hidden @endif">
                                    <select name="status" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @if($editError && $errors->has('status')) border-red-500 ring-2 ring-red-200 @endif">
                                        <option value="1" {{ old('status', $publication->status) == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('status', $publication->status) == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @if($editError && $errors->has('status'))
                                        <div class="text-red-500 text-sm mt-1 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $errors->first('status') }}
                                        </div>
                                    @endif
                                </span>
                            </td>
                        </form>
                        <!-- Actions Column -->
                        <td class="px-6 py-4 text-sm font-medium">
                            @php $editError = session('edit_error_id') == $publication->id; @endphp
                            <div id="action-buttons-{{ $publication->id }}" @if($editError) style="display:none;" @endif class="flex items-center gap-2">
                                <button type="button" class="inline-flex items-center px-3 py-1.5 bg-amber-50 text-amber-700 hover:bg-amber-100 rounded-lg transition-colors duration-150" onclick="enableEdit({{ $publication->id }})">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </button>
                                <form action="{{ route('publications.destroy', $publication->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this publication?')" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 hover:bg-red-100 rounded-lg transition-colors duration-150">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                            <div id="edit-buttons-{{ $publication->id }}" @if($editError) style="display:flex;" @else class="hidden" @endif class="flex items-center gap-2">
                                <button type="button" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg transition-all duration-150 shadow-sm hover:shadow" onclick="submitEdit({{ $publication->id }})">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Save
                                </button>
                                <button type="button" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-150" onclick="cancelEdit({{ $publication->id }})">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Cancel
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="mt-4 text-lg font-medium text-gray-900">No publications found</p>
                            <p class="mt-2 text-sm text-gray-500">Get started by adding your first publication above.</p>
                        </td>
                    </tr>
                @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $publications->links() }}
            </div>
        </div>

    </div>

    <!-- Inline Edit Script -->
    <script>
        function enableEdit(id) {
            document.getElementById('name-display-' + id).style.display = 'none';
            document.getElementById('name-input-' + id).style.display = 'inline';
            document.getElementById('edit-form-' + id).style.display = 'inline';
            document.getElementById('status-display-' + id).style.display = 'none';
            document.getElementById('status-edit-' + id).style.display = 'inline';
            document.getElementById('action-buttons-' + id).style.display = 'none';
            document.getElementById('edit-buttons-' + id).style.display = 'flex';
        }
        function cancelEdit(id) {
            document.getElementById('name-display-' + id).style.display = 'inline';
            document.getElementById('name-input-' + id).style.display = 'none';
            document.getElementById('edit-form-' + id).style.display = 'none';
            document.getElementById('status-display-' + id).style.display = 'inline';
            document.getElementById('status-edit-' + id).style.display = 'none';
            document.getElementById('action-buttons-' + id).style.display = 'flex';
            document.getElementById('edit-buttons-' + id).style.display = 'none';
        }
        function submitEdit(id) {
            document.getElementById('edit-form-' + id).submit();
        }
        function togglePublicationStatus(publicationId) {
            fetch(`/publications/${publicationId}/toggle-status`, {
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
                alert('Failed to update status');
            });
        }
    </script>
</x-app-layout>
