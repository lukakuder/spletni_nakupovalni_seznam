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
<body class="min-h-screen  font-sans">
<header class="flex justify-between items-center px-6 py-4 ">
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
    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Ime')"/>
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required
                          autofocus autocomplete="name"/>
            <x-input-error :messages="$errors->get('name')" class="mt-2"/>
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('E-pošta')"/>
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                          autocomplete="username"/>
            <x-input-error :messages="$errors->get('email')" class="mt-2"/>
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Geslo')"/>
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                          autocomplete="new-password"/>
            <x-input-error :messages="$errors->get('password')" class="mt-2"/>
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Potrdi geslo')"/>
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                          name="password_confirmation" required autocomplete="new-password"/>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2"/>
        </div>

        <!-- Profile Image -->
        <div class="mt-4">
            <x-input-label for="profile_image" :value="__('Profilna slika')"/>
            <label for="profile_image" class="cursor-pointer text-gray-700 py-2  rounded">Izberi datoteko</label>
            <input id="profile_image" type="file" name="profile_image" accept="image/*" class="hidden mt-1 w-full">
            <x-input-error :messages="$errors->get('profile_image')" class="mt-2"/>
        </div>


        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
               href="{{ route('login') }}">
                {{ __('Že imaš račun?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Ustvari račun') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
</body>
</html>

