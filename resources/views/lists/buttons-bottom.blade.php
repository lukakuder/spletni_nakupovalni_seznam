<div class="mt-6 flex space-x-4">
    <x-primary-button id="open-modal-btn">
        {{ __('Dodaj izdelek') }}
    </x-primary-button>

    <x-primary-button id="set-reminder-btn">
        {{ __('Nastavi opomnik') }}
    </x-primary-button>

    <x-primary-button id="open-receipt-modal">
        {{ __('Dodaj račun') }}
    </x-primary-button>

    @if ($list->group()->exists())
        <x-primary-button :href="route('lists.divide', $list->id)" class="bg-red-500 hover:bg-red-700">
            {{ __('Razdeli seznam') }}
        </x-primary-button>
    @endif
</div>

