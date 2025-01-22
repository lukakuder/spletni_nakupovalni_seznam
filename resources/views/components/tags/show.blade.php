<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Oznaka ' . $tag->name) }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Seznami ki pripadajo oznaki " . strtolower($tag->name)) . ":" }}

                    @if($lists)
                        @foreach($lists as $list)
                            <div class="flex justify-between items-center">
                                <a href="{{ route('lists.show', $list->id) }}" class="text-lg ml-4 font-semibold text-gray-800 dark:text-gray-200 hover:text-gray-600 dark:hover:text-gray-300">
                                    {{ $list->name }}
                                </a>
                            </div>
                        @endforeach
                    @else
                        <p>{{ __('Ni povezanih seznamov.') }}</p>
                    @endif

                    {{ __("Skupine ki pripadajo oznaki " . strtolower($tag->name)) . ":" }}

                    @if(count($groups) > 0)
                        @foreach($groups as $group)
                            <div class="flex justify-between items-center">
                                <a href="{{ route('groups.show', $group->id) }}" class="text-lg font-semibold text-gray-800 dark:text-gray-200 hover:text-gray-600 dark:hover:text-gray-300">
                                    {{ $group->name }}
                                </a>
                            </div>
                        @endforeach
                    @else
                        <p class="ml-4">{{ __('Ni povezanih skupin.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
