<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight ml-3">
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

                    <div class="flex items-center space-x-4">
                        <p class="text-l text-gray-800 dark:text-gray-200">
                            {{ __('Filtriranje:') }}
                        </p>

                        <form action="{{ route('user.lists') }}" method="GET" class="flex items-center space-x-2 ml-1">
                            <!-- Input field for filtering -->
                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="h-10 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                                placeholder="Vnesite ime"
                                value="{{ request('name') }}"
                            >

                            <x-primary-button class="h-10 px-4 ml-1 text-sm">Filtriraj</x-primary-button>
                        </form>
                    </div>
                </div>

                <div id="list-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($lists as $list)
                        <div class="view-card bg-gray-100 dark:bg-gray-900 rounded-lg shadow-md p-4">
                            <a href="{{ route('lists.show', $list->id) }}">
                                <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                    {{ $list->name }}
                                </h4>
                            </a>

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

                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                <div class="flex flex-wrap gap-2">
                                    <span class="inline-flex items-center px-2.5 py-1.5">
                                        <p>{{ __('Oznake:') }}</p>
                                    </span>
                                    @if($list->tags && count($list->tags) > 0)
                                        @foreach($list->tags as $tag)
                                            <a href="{{ route('tag.show', $tag->id) }}" class="inline-flex items-center px-2.5 py-1.5 hover:bg-gray-300 focus:bg-gray-300 hover:text-gray-500 bg-gray-100 text-s text-gray-500 tracking-normal transition-colors duration-200 ease-in-out">
                                                {{ $tag->name }}
                                            </a>
                                        @endforeach
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1.5">
                                            <p>{{ __('Ta seznam nima oznak') }}</p>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Gumb za kopiranje seznama -->
                            <button
                                class="copy-list-btn bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700 mt-4"
                                data-id="{{ $list->id }}">
                                Kopiraj seznam
                            </button>
                        </div>

                        <div class="view-list hidden border-b border-gray-300 py-2">
                            <div class="flex justify-between items-center">
                                <div>
                                    <a href="{{ route('lists.show', $list->id) }}">
                                        <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200">
                                            {{ $list->name }}
                                        </h4>
                                    </a>
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

    <footer class="py-4 bg-gray-100 dark:bg-gray-900 text-center">
        <p class="text-xl text-gray-600 dark:text-gray-800 font-semibold">
            Spletni nakupovalni seznam
        </p>
    </footer>

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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const copyButtons = document.querySelectorAll('.copy-list-btn');

            copyButtons.forEach(button => {
                button.addEventListener('click', async function () {
                    const listId = this.dataset.id;
                    const button = this;

                    // Onemogoči gumb in prikaži nalaganje
                    button.disabled = true;
                    button.innerText = 'Kopiram...';

                    try {
                        const response = await fetch(`/lists/${listId}/duplicate`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            },
                        });

                        const data = await response.json();

                        if (response.ok) {
                            alert('Seznam uspešno kopiran.');

                            // Dodaj nov seznam v DOM
                            const listContainer = document.getElementById('list-container');
                            const newListHtml = `
                            <div class="view-card bg-gray-100 dark:bg-gray-900 rounded-lg shadow-md p-4">
                                <a href="/lists/${data.new_list.id}">
                                    <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                        ${data.new_list.name}
                                    </h4>
                                </a>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                    ${data.new_list.description || ''}
                                </p>
                                <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                                    <p>Ustvarjeno: ${new Date(data.new_list.created_at).toLocaleDateString()}</p>
                                    <p>${data.new_list.belongs_to_a_group ? 'Pripada skupini' : 'Ne pripada nobeni skupini'}</p>
                                </div>
                                <button
                                    class="copy-list-btn bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700 mt-4"
                                    data-id="${data.new_list.id}">
                                    Kopiraj seznam
                                </button>
                            </div>
                        `;

                            listContainer.insertAdjacentHTML('afterbegin', newListHtml);
                        } else {
                            throw new Error(data.error || 'Prišlo je do napake.');
                        }
                    } catch (error) {
                        alert(error.message);
                    } finally {
                        // Ponovno omogoči gumb
                        button.disabled = false;
                        button.innerText = 'Kopiraj seznam';
                    }
                });
            });
        });
    </script>


</x-app-layout>
