<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Izbriši račun') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Ko je vaš račun izbrisan, bodo vsi njegovi viri in podatki trajno izbrisani. Preden izbrišete svoj račun, prenesite vse podatke ali informacije, ki jih želite obdržati.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Izbriši račun') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Ali ste prepričani, da želite izbrisati svoj račun?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Ko je vaš račun izbrisan, bodo vsi njegovi viri in podatki trajno izbrisani. Prosimo, vnesite svoje geslo, da potrdite, da želite trajno izbrisati svoj račun.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Geslo') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Geslo') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Prekliči') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Izbriši račun') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
