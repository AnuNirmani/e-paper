
<div class="w-64 h-screen bg-gray-900 text-white fixed left-0 top-0 flex flex-col justify-between">
    <div>
        <div class="p-4 text-xl font-bold border-b border-gray-700">
            Admin Panel
        </div>
        <ul class="mt-4 space-y-2">
            <li>
                <a href="{{ route('dashboard') }}"
                   class="block px-4 py-2 hover:bg-gray-700">
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('users.index') }}"
                   class="block px-4 py-2 hover:bg-gray-700">
                    Users
                </a>
            </li>
            <li>
                <a href="{{ route('customers.index') }}"
                class="block px-4 py-2 hover:bg-gray-700">
                Customers
                </a>
            </li>

            <li>
                <a href="{{ route('publications.index') }}"
                class="block px-4 py-2 hover:bg-gray-700">
                Publications
                </a>
            </li>

            <li>
                <a href="{{ route('copies.index') }}"
                class="block px-4 py-2 hover:bg-gray-700">
                Copies
                </a>
            </li>

            <li>
                <a href="{{ route('copies.upload') }}"
                class="block px-4 py-2 hover:bg-gray-700">
                Upload Copy
                </a>

        </ul>
    </div>
    <div class="p-4">
        @if (Auth::check())
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full text-left px-4 py-2 bg-red-700 hover:bg-red-600 rounded">
                Logout
            </button>
        </form>
        @endif
    </div>
</div>


