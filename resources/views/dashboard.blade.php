<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center h-9">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight ml-3">
                {{ __('Osnovna stran') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 flex items-center justify-between">
                    <!-- Napis Dobrodošli -->
                    <span class="text-2xl font-bold">{{ __("Dobrodošli") }}</span>

                    <!-- Neprebrana obvestila -->
                    <div class="alert-container">
                        <a href="{{ route('opozorila.index') }}">
                            <span class="badge bg-danger text-white font-bold px-4 py-2 rounded" id="neprebrana-opozorila">
                                Neprebrana obvestila: {{ $neprebranaOpozorila }}
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
