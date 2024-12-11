<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Osnovna stran') }}
        </h2>
    </x-slot>



    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Dobrodošli") }}
                    <div class="alert-container">
                        <a href="{{ route('opozorila.index') }}">
                            <span class="badge bg-danger" id="neprebrana-opozorila">
                                Neprebrana opozorila: {{ $neprebranaOpozorila }}
                            </span>
                        </a>
                    </div>
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
