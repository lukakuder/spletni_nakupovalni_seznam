<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Ste pozabili geslo? Ni težave. Samo sporočite nam svoj e-poštni naslov in poslali vam bomo povezavo za ponastavitev gesla, ki vam bo omogočila izbiro novega.') }}
    </div>

    <!-- Status seje -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- E-poštni naslov -->
        <div>
            <x-input-label for="email" :value="__('E-pošta')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Pošlji povezavo za ponastavitev gesla') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
