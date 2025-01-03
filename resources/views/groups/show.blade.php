<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Shopping List Details') }}
        </h2>
    </x-slot>
    <!-- Gumb za dodajanje članov -->
    <a href="{{ route('groups.addMembersForm', $group->id) }}" class="btn btn-primary">Dodaj člane</a>






</x-app-layout>
