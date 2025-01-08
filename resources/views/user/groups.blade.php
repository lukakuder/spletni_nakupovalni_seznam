<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Moje skupine') }}
            </h2>
            <x-primary-button href="{{ route('groups.create') }}">
                Ustvari Skupino
            </x-primary-button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($groups->isEmpty())
                        <p>{{ __('Ni skupin.') }}</p>
                    @else
                        <ul>
                            @foreach($groups as $group)
                                <x-groups.group-card :group="$group" />
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <footer class="py-4 bg-gray-100 dark:bg-gray-900 text-center">
        <p class="text-xl text-gray-600 dark:text-gray-800 font-semibold">
            Spletni nakupovalni seznam
        </p>
    </footer>
</x-app-layout>
