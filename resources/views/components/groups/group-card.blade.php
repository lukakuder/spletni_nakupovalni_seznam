<div class="bg-gray-100 dark:bg-gray-700 shadow-sm sm:rounded-lg mb-8 w-full  sm:w-1/3">
    <div class="p-6 h-full">
        <!-- Naslov skupine -->
        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200">
            <a href="{{ route('groups.show', $group->id) }}" class="hover:underline">
                {{ $group->name }}
            </a>
        </h3>

        <!-- Opis skupine -->
        <p class="text-gray-600 dark:text-gray-300 mt-2">
            {{ $group->description ?? 'Opis ni na voljo' }}
        </p>

        <!-- Člani skupine -->
        @if($group->users->isNotEmpty())
            <p class="mt-4 text-sm text-gray-500 dark:text-gray-400 font-semibold">
                {{ __('Člani:') }}
            </p>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                @foreach($group->users->take(5) as $member)
                    <span class="inline-block mr-2">{{ $member->name }}</span>
                @endforeach
                @if($group->users->count() > 5)
                    <span class="text-gray-400">+{{ $group->users->count() - 5 }} {{ __('več') }}</span>
                @endif
            </p>
        @endif

        <!-- Datum ustvarjanja -->
        <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
            {{ __('Ustvarjeno:') }}
            <span class="font-medium">
                {{ $group && $group->created_at ? $group->created_at->format('d.m.Y') : 'Datum ni na voljo' }}
            </span>
        </p>
    </div>
</div>
