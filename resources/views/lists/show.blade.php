<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Seznam detajli') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-xl font-bold">Ime: {{ $list->name }}</h3>
                    <p class="text-white-500">Opis: {{ $list->description }}</p>
                    @if ($list->reminder_date)
                        <p class="text-sm text-white-600">
                            {{ __('Opomnik dne: ') }}
                            <span class="font-bold">{{ $list->reminder_date->format('d.m.Y') }}</span>
                        </p>
                    @endif

                    <h4 class="text-lg mt-6 font-bold">{{ __('Izdelki') }}</h4>
                    @if ($list->items->isEmpty())
                        <p class="text-gray-500">{{ __('Ni dodanih izdelkov.') }}</p>
                    @else
                        <ul>
                            @foreach ($list->items as $item)
                                <li>
                                    {{ $item->name }} - {{ $item->amount }}
                                    @if ($item->price_per_item)
                                        x {{ number_format($item->price_per_item, 2) }} € =
                                        {{ number_format($item->total_price, 2) }} €
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <button id="open-modal-btn"
                            class="mt-6 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('Dodaj izdelek') }}
                    </button>

                    <div class="mt-6">
                        <a href="{{ route('lists.export', $list->id) }}"
                           class="mt-6 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded inline-block text-center">
                            {{ __('Izvozi podatke') }}
                        </a>

                        <button id="import-button"
                                class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Naloži podatke') }}
                        </button>

                        <div id="import-form-container" class="hidden mt-4">
                            <form action="{{ route('lists.import', $list->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <label for="import_file" class="block text-sm font-medium text-white">
                                    {{ __('Naloži datoteko (.txt)') }}
                                </label>

                                <input type="file" id="import_file" name="import_file" accept=".txt"
                                       class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-black bg-white">

                                <div class="flex justify-end mt-4">
                                    <button type="submit"
                                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                        {{ __('Naloži') }}
                                    </button>
                                    <button type="button" id="close-import-form"
                                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded ml-2">
                                        {{ __('Prekliči') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>


                    <div class="mt-6">
                        <button id="set-reminder-btn"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Nastavi opomnik') }}
                        </button>

                        <div id="reminder-form-container" class="hidden mt-4">
                            <form action="{{ route('lists.updateReminder', $list->id) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <label for="reminder_date" class="block text-sm font-medium text-white">
                                    {{ __('Datum opomnika') }}
                                </label>

                                <input type="date" id="reminder_date" name="reminder_date"
                                       value="{{ $list->reminder_date ? $list->reminder_date->format('Y-m-d') : '' }}"
                                       class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-black bg-white">

                                <div class="flex justify-end mt-4">
                                    <button type="submit"
                                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                        {{ __('Shrani') }}
                                    </button>

                                    <button type="button" id="close-reminder-btn"
                                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded ml-2">
                                        {{ __('Zapri') }}
                                    </button>
                                </div>
                            </form>


                            @if ($list->reminder_date)
                                <p class="mt-4 text-sm text-white-600">
                                    {{ __('Datuma opomnika: ') }}
                                    <span class="font-bold">{{ $list->reminder_date->format('d.m.Y') }}</span>
                                </p>
                            @endif
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="mt-5 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div id="modal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen">
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-lg p-6 border border-gray-300">
                <h4 class="text-lg font-bold mb-4 text-white">{{ __('Dodaj izdelek') }}</h4> <!-- White text -->
                <form action="{{ route('lists.items.store', $list->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-white">{{ __('Izdelek') }}</label> <!-- White text -->
                        <input type="text" name="name" id="name" required
                               class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-black"> <!-- Black text inside input -->
                    </div>
                    <div class="mb-4">
                        <label for="amount" class="block text-sm font-medium text-white">{{ __('Količina') }}</label> <!-- White text -->
                        <input type="number" name="amount" id="amount" required min="1"
                               class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-black"> <!-- Black text inside input -->
                    </div>
                    <div class="mb-4">
                        <label for="price_per_item" class="block text-sm font-medium text-white">{{ __('Cena na kos') }}</label> <!-- White text -->
                        <input type="number" name="price_per_item" id="price_per_item" step="0.01" min="0"
                               class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-black"> <!-- Black text inside input -->
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mr-2">
                            {{ __('Shrani') }}
                        </button>
                        <button type="button" id="close-modal-btn-bottom"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Prekliči') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!--
        Modal script
        - Show modal when button is clicked
        - Hide modal when close button is clicked
    -->
    <script>
        const openModalBtn = document.getElementById('open-modal-btn');
        const closeModalBtns = document.querySelectorAll('#close-modal-btn, #close-modal-btn-bottom');
        const modal = document.getElementById('modal');

        openModalBtn.addEventListener('click', () => {
            modal.classList.remove('hidden');
        });

        closeModalBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                modal.classList.add('hidden');
            });
        });
    </script>

    <!--
        Reminder form script
        - Show reminder form when button is clicked
        - Hide reminder form when close button is clicked
     -->
    <script>
        document.getElementById('set-reminder-btn').addEventListener('click', function () {
            document.getElementById('reminder-form-container').classList.remove('hidden');
            this.classList.add('hidden');
        });

        document.getElementById('close-reminder-btn').addEventListener('click', function () {
            document.getElementById('reminder-form-container').classList.add('hidden');
            document.getElementById('set-reminder-btn').classList.remove('hidden');
        });
    </script>


    <!--
        Import form script
        - Show import form when button is clicked
        - Hide import form when close button is clicked
    -->
    <script>
        document.getElementById('import-button').addEventListener('click', function () {
            document.getElementById('import-form-container').classList.remove('hidden');
            this.classList.add('hidden');
        });

        document.getElementById('close-import-form').addEventListener('click', function () {
            document.getElementById('import-form-container').classList.add('hidden');
            document.getElementById('import-button').classList.remove('hidden');
        });
    </script>


</x-app-layout>
