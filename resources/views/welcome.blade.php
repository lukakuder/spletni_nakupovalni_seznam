<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Nakupovalni seznam</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-b from-gray-900 via-gray-800 to-black text-gray-200 font-sans">
<header class="flex justify-between items-center px-6 py-4 bg-black shadow-lg">
    <!-- Changed text color to white -->
    <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Spletni nakupovalni seznam') }}
    </h1>

    @if (Route::has('login'))
        <nav class="flex space-x-4">
            @auth
                <!-- Changed text color to white with hover effect -->
                <a href="{{ url('/dashboard') }}"
                   class="text-white hover:bg-purple-500 hover:text-white px-4 py-2 rounded transition">
                    Dashboard
                </a>
            @else
                <!-- Changed text color to white with hover effect -->
                <a href="{{ route('login') }}"
                   class="text-l font-semibold text-gray-800 dark:text-gray-200 px-4 py-2 rounded transition">
                    Prijava
                </a>
                @if (Route::has('register'))
                    <!-- Changed text color to white with hover effect -->
                    <a href="{{ route('register') }}"
                       class="text-l font-semibold text-gray-800 dark:text-gray-200 px-4 py-2 rounded transition">
                        Registracija
                    </a>
                @endif
            @endauth
        </nav>
    @endif
</header>

</body>
</html>
