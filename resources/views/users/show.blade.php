<!-- <x-app-layout>
    @section('page_title', 'User Details')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between mb-4">
            <a href="{{ route('users.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to users
            </a>
            <a href="{{ route('users.edit', $user) }}" class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition">Edit</a>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $user->name }}</h1>
            <div class="space-y-4">
                <div>
                    <div class="text-sm text-gray-500">Email</div>
                    <div class="text-gray-900 font-medium">{{ $user->email }}</div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm text-gray-500">Created</div>
                        <div class="text-gray-900 font-medium">{{ $user->created_at?->format('Y-m-d H:i') }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Last Updated</div>
                        <div class="text-gray-900 font-medium">{{ $user->updated_at?->format('Y-m-d H:i') }}</div>
                    </div>
                </div>
                @if ($user->email_verified_at)
                    <div>
                        <div class="text-sm text-gray-500">Email Verified</div>
                        <div class="text-gray-900 font-medium">{{ $user->email_verified_at->format('Y-m-d H:i') }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout> -->
