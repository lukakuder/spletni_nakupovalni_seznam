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
    <h1 class="text-2xl font-bold text-purple-300">Nakupovalni seznam</h1>
    @if (Route::has('login'))
        <nav class="flex space-x-4">
            @auth
                <a href="{{ url('/dashboard') }}"
                   class="text-purple-400 hover:bg-purple-500 hover:text-white px-4 py-2 rounded transition">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="text-purple-400 hover:bg-purple-500 hover:text-white px-4 py-2 rounded transition">
                    Prijava
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="text-purple-400 hover:bg-purple-500 hover:text-white px-4 py-2 rounded transition">
                        Registracija
                    </a>
                @endif
            @endauth
        </nav>
    @endif
</header>

<main class="flex items-center justify-center min-h-[calc(100vh-5rem)] text-center">
    <div>
        <p class="text-lg font-medium text-white">
            Dobrodošli v aplikaciji <span class="text-purple-400 font-semibold">Nakupovalni seznam</span>!
        </p>
    </div>
</main>
</body>
</html>
