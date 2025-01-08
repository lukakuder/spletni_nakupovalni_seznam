<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Skupina ' . $group->name) }}
            </h2>
            <div style="display: flex; gap: 10px;">
                <x-primary-button
                    href="{{ route('lists.create', ['belongs_to_a_group' => 1, 'group_id' => $group->id]) }}">
                    {{ __('Ustvari Seznam') }}
                </x-primary-button>

                <x-primary-button href="{{ route('groups.addMembersForm', ['group' => $group->id]) }}">
                    {{ __('Dodaj Člane') }}
                </x-primary-button>

                <form method="POST" action="{{ route('groups.leave', ['group' => $group->id]) }}">
                    @csrf
                    <x-danger-button href="{{ route('groups.leave', ['group' => $group->id]) }}"
                                     onclick="return confirmLeave()">
                        {{ __('Izstopi') }}
                    </x-danger-button>
                </form>
            </div>

        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="mb-6">
                    <div style="display: flex; gap: 10px; justify-content: space-between; width: 100%;">
                        <div>
                            <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200">
                                {{ __('Člani skupine:') }}
                            </h4>

                            <ul>
                                @foreach($group->users as $user)
                                    <li>{{ $user->name }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <x-primary-button onclick="window.location='{{ route('groups.detailedShow', ['group' => $group->id]) }}'">
                            {{ __('Podroben ogled skupine') }}
                        </x-primary-button>

                    </div>
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
    <script>
        function confirmLeave() {
            return confirm("Ste prepričani, da želite zapustiti to skupino?");
        }
    </script>
</x-app-layout>
