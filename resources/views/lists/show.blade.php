<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Podrobnosti seznama') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-bold mb-4">{{ $list->name }}</h3>

                    <p class="text-gray-600 dark:text-gray-400 mb-2">
                        {{ __('Opis:') }} {{ $list->description }}
                    </p>

                    @if ($list->belongs_to_a_group)
                        <p class="text-gray-600 dark:text-gray-400 mb-2">
                            {{ __('Pripada skupini:') }}
                            <span class="font-semibold">{{ $list->group->name ?? __('Nepoznana skupina') }}</span>
                        </p>
                    @else
                        <p class="text-gray-600 dark:text-gray-400 mb-2">
                            {{ __('Seznam je zaseben.') }}
                        </p>
                    @endif

                    <p class="text-gray-600 dark:text-gray-400 mb-2">
                        {{ __('Ustvarjeno:') }} {{ $list->created_at->format('d.m.Y') }}
                    </p>

                    @if ($list->updated_at && $list->updated_at != $list->created_at)
                        <p class="text-gray-600 dark:text-gray-400 mb-2">
                            {{ __('Zadnjič posodobljeno:') }} {{ $list->updated_at->format('d.m.Y') }}
                        </p>
                    @endif

                    <div class="mt-6">
                        <a href="{{ route('user.lists') }}" class="text-blue-500 dark:text-blue-400 hover:underline">
                            {{ __('Nazaj na sezname') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
