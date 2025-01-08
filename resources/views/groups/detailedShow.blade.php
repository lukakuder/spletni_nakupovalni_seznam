<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Skupina ' . $group->name) }}
            </h2>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="mb-6">
                    <div>
                        <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200">
                            {{ __('Člani skupine:') }}
                        </h4>

                        <div class="flex flex-wrap gap-4" id="user-list">
                            @foreach($group->users as $user)
                                <div class="user-item p-2 rounded border cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 w-32">
                                    <label class="flex items-center">
                                        <input type="checkbox" value="{{ $user->id }}" class="user-checkbox mr-2">
                                        <span>{{ $user->name }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div >
                    <x-primary-button id="processSelectedUsers" class="bg-blue-500 text-white p-2 rounded">
                        {{ __('Obdelaj izbrane uporabnike') }}
                    </x-primary-button>
                </div>
                <div>
                    <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200">
                        {{ __('Seznami skupine:') }}
                    </h4>
                    <div>
                        @if(count($group->lists) > 0)
                            <ul>
                                @foreach($group->lists as $list)
                                    <li><a href="{{ route('lists.show', $list->id) }}">{{ $list->name }}</a></li>
                                @endforeach
                            </ul>
                        @else
                            <p>{{ __('Skupina nima seznamov.') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
