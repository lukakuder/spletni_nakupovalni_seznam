<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Shopping List Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-xl font-bold">{{ $list->name }}</h3>
                    <p class="text-white-500">Description: {{ $list->description }}</p>
                    @if ($list->reminder_date)
                        <p class="text-sm text-white-600">
                            {{ __('Reminder set to: ') }}
                            <span class="font-bold">{{ $list->reminder_date->format('d.m.Y') }}</span>
                        </p>
                    @endif

                    <h4 class="text-lg mt-6 font-bold">{{ __('Items') }}</h4>
                    @if ($list->items->isEmpty())
                        <p class="text-gray-500">{{ __('No items in this list.') }}</p>
                    @else
                        <ul>
                            @foreach ($list->items as $item)
                                <li class="mb-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            {{ $item->name }} - {{ $item->amount }}
                                            @if ($item->price_per_item)
                                                x {{ number_format($item->price_per_item, 2) }} € =
                                                {{ number_format($item->total_price, 2) }} €
                                            @endif
                                            <p>
                                                <strong>{{ __('Purchased:') }}</strong>
                                                {{ $item->purchased_quantity }} / {{ $item->amount }}
                                            </p>
                                        </div>

                                        <form action="{{ route('list-items.update-purchased', $item->id) }}" method="POST" class="flex items-center">
                                            @csrf
                                            <input type="number" name="purchased_quantity" min="0" max="{{ $item->amount }}"
                                                   value="{{ $item->purchased_quantity }}"
                                                   class="w-16 border rounded-md text-black px-2 py-1 mr-2">
                                            <button type="submit"
                                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded">
                                                {{ __('Update') }}
                                            </button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <button id="open-modal-btn"
                            class="mt-6 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('Add Item') }}
                    </button>

                    <a href="{{ route('lists.export', $list->id) }}"
                       class="mt-6 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded inline-block text-center">
                        {{ __('Export') }}
                    </a>

                    <div class="mt-6">
                        <button id="set-reminder-btn"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Set Reminder') }}
                        </button>

                        <div id="reminder-form-container" class="hidden mt-4">
                            <form action="{{ route('lists.updateReminder', $list->id) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <label for="reminder_date" class="block text-sm font-medium text-white">
                                    {{ __('Reminder Date') }}
                                </label>

                                <input type="date" id="reminder_date" name="reminder_date"
                                       value="{{ $list->reminder_date ? $list->reminder_date->format('Y-m-d') : '' }}"
                                       class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-black bg-white">

                                <div class="flex justify-end mt-4">
                                    <button type="submit"
                                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                        {{ __('Save Reminder') }}
                                    </button>

                                    <button type="button" id="close-reminder-btn"
                                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded ml-2">
                                        {{ __('Close') }}
                                    </button>
                                </div>
                            </form>

                            @if ($list->reminder_date)
                                <p class="mt-4 text-sm text-white-600">
                                    {{ __('Reminder set to: ') }}
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

                    @if ($errors->any())
                        <div class="mt-5 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div id="modal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen">
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-lg p-6 border border-gray-300">
                <h4 class="text-lg font-bold mb-4 text-white">{{ __('Add Item') }}</h4>
                <form action="{{ route('lists.items.store', $list->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-white">{{ __('Item Name') }}</label>
                        <input type="text" name="name" id="name" required
                               class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-black">
                    </div>
                    <div class="mb-4">
                        <label for="amount" class="block text-sm font-medium text-white">{{ __('Amount') }}</label>
                        <input type="number" name="amount" id="amount" required min="1"
                               class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-black">
                    </div>
                    <div class="mb-4">
                        <label for="price_per_item" class="block text-sm font-medium text-white">{{ __('Price Per Item') }}</label>
                        <input type="number" name="price_per_item" id="price_per_item" step="0.01" min="0"
                               class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-black">
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mr-2">
                            {{ __('Save Item') }}
                        </button>
                        <button type="button" id="close-modal-btn-bottom"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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

        document.getElementById('set-reminder-btn').addEventListener('click', function () {
            document.getElementById('reminder-form-container').classList.remove('hidden');
            this.classList.add('hidden');
        });

        document.getElementById('close-reminder-btn').addEventListener('click', function () {
            document.getElementById('reminder-form-container').classList.add('hidden');
            document.getElementById('set-reminder-btn').classList.remove('hidden');
        });
    </script>
</x-app-layout>
