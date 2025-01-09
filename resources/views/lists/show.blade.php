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
                    <x-tags.tags-display :tags="$list->tags" />


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
                        @foreach ($list->items as $item)
                            <div class="flex items-center justify-between mb-6">
                                <!-- Item Name and Progress -->
                                <div class="flex items-center justify-start" style="width: 60%">
                                    <span class="text-white font-medium"
                                          style="min-width: 100px">{{ $item->name }}</span>

                                    <!-- Purchased Text and Quantity -->
                                    <span class="text-sm text-gray-400 whitespace-nowrap"
                                          style="min-width: 80px">
                                        Kupljeno: {{ $item->purchased }}/{{ $item->amount }}
                                    </span>

                                    <!-- Progress Bar -->
                                    <div class="w-full bg-gray-300 rounded-full h-4"
                                         style="width: 100px; margin-left: 15px; border: 1px solid">
                                        <div style="width: {{ $item->amount > 0 ? ($item->purchased / $item->amount) * 100 : 0 }}%; background-color: #22c55e;"
                                             class="h-4 rounded-full">
                                        </div>
                                    </div>

                                    <!-- Percentage -->
                                    <span class="text-sm text-gray-400"
                                        style="margin-left: 15px">
                                        {{ round(($item->purchased / $item->amount) * 100, 2) }}%
                                    </span>
                                </div>

                                @if($item->purchased >= $item->amount)

                                @else
                                    <!-- Quantity Input and Button -->
                                    <form action="{{ route('items.markPurchased', $item->id) }}" method="POST" class="flex items-center space-x-2">
                                        @csrf
                                        @method('PATCH')

                                        <input type="number"
                                               name="quantity"
                                               class="w-12 text-black rounded-md text-center"
                                               placeholder="0"
                                               min="1"
                                               max="{{ $item->amount - $item->purchased }}"
                                               required
                                               style="width: 80px; color: black"
                                        >

                                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-gray-900 font-bold py-2 px-4 rounded">
                                            Kupi
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    @endif

                    @if(isset($divided))
                        <div class="mt-6">
                            @foreach($divided as $user)
                                @if($user['total_owed'] > 0)
                                    <p class="mt-4 text-sm text-gray-900">
                                        {{ __('Uporabnik ' . $user['name'] . ' mora dobiti: ' . $user['total_owed']) }}
                                    </p>@endif
                            @endforeach
                        </div>
                    @endif

                    <div class="mt-6">
                        <button id="open-modal-btn"
                                class="mt-6 bg-blue-500 hover:bg-blue-700 text-gray-900 font-bold py-2 px-4 rounded">
                            {{ __('Dodaj izdelek') }}
                        </button>

                        <button id="import-button"
                                class="bg-purple-500 hover:bg-purple-700 text-gray-900 font-bold py-2 px-4 rounded">
                            {{ __('Uvozi podatke') }}
                        </button>

                        <a href="{{ route('lists.export', $list->id) }}"
                           class="mt-6 bg-red-500 hover:bg-red-700 text-gray-900 font-bold py-2 px-4 rounded inline-block text-center">
                            {{ __('Izvozi podatke') }}
                        </a>

                        <a href="{{ route('lists.exportReport', $list->id) }}"
                           class="mt-6 bg-red-500 hover:bg-red-700 text-gray-900 font-bold py-2 px-4 rounded inline-block text-center">
                            {{ __('Izvozi poročilo seznama') }}
                        </a>

                        @if($list->group()->exists())
                            <a href="{{ route('lists.divide', $list->id) }}"
                               class="mt-6 bg-red-500 hover:bg-red-700 text-gray-900 font-bold py-2 px-4 rounded inline-block text-center">
                                {{ __('Razdeli seznam') }}
                            </a>
                        @endif

                        <div id="import-form-container" class="hidden mt-4">
                            <form action="{{ route('lists.import', $list->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <label for="import_file" class="block text-sm font-medium text-gray-900">
                                    {{ __('Naloži datoteko (.txt)') }}
                                </label>

                                <input type="file" id="import_file" name="import_file" accept=".txt"
                                       class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-gray-900 bg-white">

                                <div class="flex justify-end mt-4">
                                    <button type="submit"
                                            class="bg-green-500 hover:bg-green-700 text-gray-900 font-bold py-2 px-4 rounded">
                                        {{ __('Naloži') }}
                                    </button>
                                    <button type="button" id="close-import-form"
                                            class="bg-gray-500 hover:bg-gray-700 text-gray-900 font-bold py-2 px-4 rounded ml-2">
                                        {{ __('Prekliči') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button id="set-reminder-btn"
                                class="bg-blue-500 hover:bg-blue-700 text-gray-900 font-bold py-2 px-4 rounded">
                            {{ __('Nastavi opomnik') }}
                        </button>

                        <div id="reminder-form-container" class="hidden mt-4">
                            <form action="{{ route('lists.updateReminder', $list->id) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <label for="reminder_date" class="block text-sm font-medium text-gray-900">
                                    {{ __('Datum opomnika') }}
                                </label>

                                <input type="date" id="reminder_date" name="reminder_date"
                                       value="{{ $list->reminder_date ? $list->reminder_date->format('Y-m-d') : '' }}"
                                       class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-gray-900 bg-white">

                                <div class="flex justify-end mt-4">
                                    <button type="submit"
                                            class="bg-green-500 hover:bg-green-700 text-gray-900 font-bold py-2 px-4 rounded">
                                        {{ __('Shrani') }}
                                    </button>

                                    <button type="button" id="close-reminder-btn"
                                            class="bg-gray-500 hover:bg-gray-700 text-gray-900 font-bold py-2 px-4 rounded ml-2">
                                        {{ __('Zapri') }}
                                    </button>
                                </div>
                            </form>


                            @if ($list->reminder_date)
                                <p class="mt-4 text-sm text-gray-900">
                                    {{ __('Datuma opomnika: ') }}
                                    <span class="font-bold">{{ $list->reminder_date->format('d.m.Y') }}</span>
                                </p>
                            @endif
                        </div>
                    </div>

                    <h4 class="text-lg mt-6 font-bold">{{ __('Računi') }}</h4>
                    @if ($list->receipts->isEmpty())
                        <p class="text-gray-500">{{ __('Ni dodanih računov.') }}</p>
                    @else
                        <ul>
                            @foreach ($list->receipts as $receipt)
                                <li>
                                    {{ $receipt->name }} - <a href="{{ Storage::url($receipt->file_path) }}" class="text-blue-500 hover:underline" target="_blank">{{ __('Prenesi') }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <button id="open-receipt-modal"
                            class="mt-6 bg-blue-500 hover:bg-blue-700 text-gray-900 font-bold py-2 px-4 rounded">
                        {{ __('Dodaj račun') }}
                    </button>

                    <div id="receipt-modal" class="hidden mt-6">
                        <form action="{{ route('lists.storeReceipt', $list->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <label for="name" class="block text-sm font-medium text-gray-900">{{ __('Ime računa') }}</label>
                            <input type="text" id="name" name="name"
                                   class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-black">

                            <label for="file" class="block text-sm font-medium text-gray-900 mt-4">{{ __('Datoteka') }}</label>
                            <input type="file" id="file" name="file"
                                   class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-black">

                            <div class="flex justify-end mt-4">
                                <button type="submit"
                                        class="bg-purple-500 hover:bg-purple-700 text-gray-900 font-bold py-2 px-4 rounded">
                                    {{ __('Naloži') }}
                                </button>
                                <button type="button" id="close-receipt-modal"
                                        class="bg-gray-500 hover:bg-gray-700 text-gray-900 font-bold py-2 px-4 rounded ml-2">
                                    {{ __('Prekliči') }}
                                </button>
                            </div>
                        </form>
                    </div>

                    @if (session('success'))
                        <div id="success-message" class="mt-20 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
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
                <h4 class="text-lg font-bold mb-4 text-gray-900">{{ __('Dodaj izdelek') }}</h4> <!-- White text -->
                <form action="{{ route('lists.items.store', $list->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-900">{{ __('Izdelek') }}</label> <!-- White text -->
                        <input type="text" name="name" id="name" required
                               class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-black"> <!-- Black text inside input -->
                    </div>
                    <div class="mb-4">
                        <label for="amount" class="block text-sm font-medium text-gray-900">{{ __('Količina') }}</label> <!-- White text -->
                        <input type="number" name="amount" id="amount" required min="1"
                               class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-black"> <!-- Black text inside input -->
                    </div>
                    <div class="mb-4">
                        <label for="price_per_item" class="block text-sm font-medium text-gray-900">{{ __('Cena na kos') }}</label> <!-- White text -->
                        <input type="number" name="price_per_item" id="price_per_item" step="0.01" min="0"
                               class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-black"> <!-- Black text inside input -->
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-gray-900 font-bold py-2 px-4 rounded mr-2">
                            {{ __('Shrani') }}
                        </button>
                        <button type="button" id="close-modal-btn-bottom"
                                class="bg-gray-500 hover:bg-gray-700 text-gray-900 font-bold py-2 px-4 rounded">
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

    <script>
        const openReceiptModal = document.getElementById('open-receipt-modal');
        const closeReceiptModal = document.getElementById('close-receipt-modal');
        const receiptModal = document.getElementById('receipt-modal');

        openReceiptModal.addEventListener('click', () => {
            receiptModal.classList.remove('hidden');
        });

        closeReceiptModal.addEventListener('click', () => {
            receiptModal.classList.add('hidden');
        });
        document.getElementById('open-receipt-modal').addEventListener('click', () => {
            const successMessage = document.getElementById('success-message');
            if (successMessage) {
                successMessage.style.display = 'none';
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
        const successMessage = document.getElementById('success-message');
        const addReceiptButton = document.getElementById('open-receipt-modal');

        if (successMessage && addReceiptButton) {
        const buttonMarginBottom = parseInt(window.getComputedStyle(addReceiptButton).marginBottom, 10);
        successMessage.style.marginTop = `${buttonMarginBottom + 24}px`; // Odmik na podlagi gumbovega spodnjega roba
        }
        });
    </script>


</x-app-layout>
