<x-app-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                        {{ __('Skupina ' . $group->name) }}
                    </h3>
                </div>

                <div class="mb-6">
                    <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200">
                        {{ __('Člani skupine:') }}
                    </h4>
                    <div class="ml-4">
                        <ul>
                            @foreach($group->users as $user)
                                <li>{{ $user->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div>
                    <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200">
                        {{ __('Seznami skupine:') }}
                    </h4>
                    <div class="ml-4">
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
