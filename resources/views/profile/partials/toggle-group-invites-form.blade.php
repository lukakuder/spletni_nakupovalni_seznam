<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Povabila v skupine') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Tukaj lahko spremenite, če vas lahko ostali povabijo v skupine ali ne.") }}
        </p>
    </header>

    <!-- Save Button -->
    <div class="flex items-center gap-4 mt-2">
        <x-primary-button href="{{ route('user.toggleInvites') }}">
            {{ $user->allow_group_invites ? __('Onemogoči') : __('Omogoči') }}
        </x-primary-button>
    </div>
</section>