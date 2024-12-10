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
                    <p class="text-gray-500">{{ $list->description }}</p>

                    <h4 class="text-lg mt-6 font-bold">{{ __('Items') }}</h4>
                    @if ($list->items->isEmpty())
                        <p class="text-gray-500">{{ __('No items in this list.') }}</p>
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
                        {{ __('Add Item') }}
                    </button>

                    <a href="{{ route('lists.export', $list->id) }}"
                       class="mt-6 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded inline-block text-center">
                        {{ __('Export') }}
                    </a>

                </div>
            </div>
        </div>
    </div>

    <div id="modal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen">
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-lg p-6 border border-gray-300">
                <h4 class="text-lg font-bold mb-4 text-white">{{ __('Add Item') }}</h4> <!-- White text -->
                <form action="{{ route('lists.items.store', $list->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-white">{{ __('Item Name') }}</label> <!-- White text -->
                        <input type="text" name="name" id="name" required
                               class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-black"> <!-- Black text inside input -->
                    </div>
                    <div class="mb-4">
                        <label for="amount" class="block text-sm font-medium text-white">{{ __('Amount') }}</label> <!-- White text -->
                        <input type="number" name="amount" id="amount" required min="1"
                               class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-black"> <!-- Black text inside input -->
                    </div>
                    <div class="mb-4">
                        <label for="price_per_item" class="block text-sm font-medium text-white">{{ __('Price Per Item') }}</label> <!-- White text -->
                        <input type="number" name="price_per_item" id="price_per_item" step="0.01" min="0"
                               class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-black"> <!-- Black text inside input -->
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
    </script>
</x-app-layout>
