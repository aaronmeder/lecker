<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Lecker - Delicious Bookmarks Manager</title>

    <!-- Styles -->
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>

<body class="bg-white dark:bg-gray-900 min-h-screen flex flex-col justify-center items-center p-6">
    <div class="max-w-2xl w-full text-center">
        <h1 class="text-4xl font-bold mb-2">Lecker</h1>
        <p class="text-xl text-gray-600 dark:text-gray-300 mb-8">Delicious Bookmarks Manager</p>

        @if (Route::has('login'))
            <div class="flex flex-col gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="px-6 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
                        Register
                    </a>
                @endauth
            </div>
        @endif
    </div>
</body>
</html>
