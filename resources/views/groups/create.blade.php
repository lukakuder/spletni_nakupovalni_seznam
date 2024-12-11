<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Ustvari novo skupino') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Create Group Form -->
                    <form action="{{ route('groups.store') }}" method="POST">
                        @csrf

                        <!-- Name of the group -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Ime Skupine</label>
                            <input type="text" id="name" name="name" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" value="{{ old('name') }}" required>
                        </div>

                        <!-- Description of the group -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Opis Skupine</label>
                            <textarea id="description" name="description" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>{{ old('description') }}</textarea>
                        </div>

                        <!-- Optional: Invite Members
                        <div class="mb-4">
                            <label for="members" class="block text-sm font-medium text-gray-700">Dodaj Člane (Email)</label>
                            <textarea id="members" name="members" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" placeholder="Vnesite email naslove, ločene z vejico.">{{ old('members') }}</textarea>
                        </div>v-->

                        <!-- Submit Button -->
                        <x-primary-button> Ustvari novo skupino </x-primary-button>

                        A

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
