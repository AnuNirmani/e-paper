<div class="hidden lg:flex lg:w-64 h-screen bg-gray-900 text-white fixed left-0 top-0 flex flex-col justify-between">
    <div>
        <div class="p-4 text-xl font-bold border-b border-gray-700">
            Admin Panel
        </div>
        <ul class="mt-4 space-y-2">
            <li>
                <a href="{{ route('dashboard') }}"
                   class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('dashboard') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}">
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('users.index') }}"
                   class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('users.*') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}">
                    Users
                </a>
            </li>
            <li>
                <a href="{{ route('customers.index') }}"
                   class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('customers.*') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}">
                    Customers
                </a>
            </li>

            <li>
                <a href="{{ route('publications.index') }}"
                   class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('publications.*') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}">
                    Publications
                </a>
            </li>

            <li>
                <a href="{{ route('copies.index') }}"
                   class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('copies.index') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}">
                    Copies
                </a>
            </li>

            <li>
                <a href="{{ route('copies.upload') }}"
                   class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('copies.upload') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}">
                    Send E-paper
                </a>
            </li>

            <li>
                <a href="{{ Route::has('commands.index') ? route('commands.index') : (Route::has('messages.index') ? route('messages.index') : '#') }}"
                   class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('commands.*') || request()->routeIs('messages.*') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}">
                    Commands
                </a>
            </li>

            <li>
                <a href="{{ route('settings.watermark') }}"
                   class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('settings.*') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}">
                    Watermark Settings
                </a>
            </li>

            <li>
                <a href="{{ route('whatsapp.connect') }}"
                   class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('whatsapp.*') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}">
                    WhatsApp QR
                </a>
            </li>
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
