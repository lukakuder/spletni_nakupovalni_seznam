<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Moji seznami') }}
            </h2>
            <x-primary-button href="{{ route('lists.create', ['belongs_to_a_group' => 0]) }}">
                {{ __('Ustvari Seznam') }}
            </x-primary-button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                        {{ __('Seznami') }}
                    </h3>
                    <button
                        id="toggle-view-btn"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        {{ __('Preklopi pogled') }}
                    </button>
                </div>

                <div id="list-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($lists as $list)
                        <div class="view-card bg-gray-100 dark:bg-gray-900 rounded-lg shadow-md p-4">
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                {{ $list->name }}
                            </h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                {{ $list->description }}
                            </p>
                            <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                                <p>{{ __('Ustvarjeno:') }} {{ $list->created_at->format('d.m.Y') }}</p>
                                @if ($list->belongs_to_a_group)
                                    <p>{{ __('Pripada skupini') }}</p>
                                @else
                                    <p>{{ __('Ne pripada nobeni skupini') }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="view-list hidden border-b border-gray-300 py-2">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200">
                                        {{ $list->name }}
                                    </h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $list->description }}
                                    </p>
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('lists.show', $list->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        {{ __('Ogled') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('toggle-view-btn').addEventListener('click', function () {
            const cards = document.querySelectorAll('.view-card');
            const lists = document.querySelectorAll('.view-list');
            const container = document.getElementById('list-container');

            cards.forEach(card => card.classList.toggle('hidden'));
            lists.forEach(list => list.classList.toggle('hidden'));

            // Update grid layout when switching views
            if (container.classList.contains('grid')) {
                container.classList.remove('grid', 'grid-cols-1', 'sm:grid-cols-2', 'lg:grid-cols-3');
            } else {
                container.classList.add('grid', 'grid-cols-1', 'sm:grid-cols-2', 'lg:grid-cols-3');
            }
        });
    </script>
</x-app-layout>
