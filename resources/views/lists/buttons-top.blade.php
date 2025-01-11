<div class="flex space-x-4">
    {{-- Gumb za uvoz podatkov --}}
    <x-primary-button id="import-button">
        {{ __('Uvozi podatke') }}
    </x-primary-button>

    {{-- Gumb za izvoz podatkov --}}
    <x-primary-button href="{{ route('lists.export', $list->id) }}">
        {{ __('Izvozi podatke') }}
    </x-primary-button>

    {{-- Gumb za izvoz poročila --}}
    <x-primary-button href="{{ route('lists.exportReport', $list->id) }}">
        {{ __('Izvozi poročilo') }}
    </x-primary-button>
</div>
