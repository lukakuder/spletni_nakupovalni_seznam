<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Skupina ' . $group->name) }}
            </h2>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="container">
                    <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        Povabi člane v skupino: {{ $group->name }}
                    </h2>
                    <form action="{{ route('groups.addMembers', $group->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="users" class="text-gray-700 dark:text-gray-300">Izberite člane:</label>
                            <select name="users[]" id="users" class="form-control mt-2" multiple>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success mt-4">Pošlji povabila</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
