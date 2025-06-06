<!-- Header -->
<header class="bg-white dark:bg-gray-800 shadow-sm z-20 border-b border-gray-200 dark:border-gray-700">
    <div class="flex items-center justify-between h-16 px-4">
        <!-- Left side: Logo and toggle -->
        <div class="flex items-center">
            <strong>😋 {{ config('app.name') }}</strong>
        </div>

        <!-- Right side: Search, notifications, profile -->
        <div class="flex items-center space-x-4">
            
            <a href="{{ route('bookmarks.index') }}" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 transition duration-150 ease-in-out">
                Bookmarks
            </a>
            <a href="{{ route('dashboard') }}" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 transition duration-150 ease-in-out">
                Dashboard
            </a>
            <a href="{{ route('logout') }}" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 transition duration-150 ease-in-out" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
            
        </div>
    </div>
</header>
