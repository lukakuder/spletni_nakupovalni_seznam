<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<style>html, body {
        overflow: hidden;
    }
</style>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Nakupovalni seznam</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen  font-sans" >
<header class="flex justify-between items-center px-6 py-4">
    <!-- Changed text color to white -->
    <a href="http://127.0.0.1:8080/" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Spletni nakupovalni seznam') }}
    </a>

    @if (Route::has('login'))
        <nav class="flex space-x-4">
            @auth
                <!-- Changed text color to white with hover effect -->
                <a href="{{ url('/dashboard') }}"
                   class="text-white hover:bg-purple-500 hover:text-white px-4 py-2 rounded transition">
                    Dashboard
                </a>
            @else
                <!-- Changed text color to white with hover effect-->
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
<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('E-pošta')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Geslo')" />

            <x-text-input id="password" class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Zapomni si') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Si pozabil geslo?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Prijavi se') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
</body>
</html>

