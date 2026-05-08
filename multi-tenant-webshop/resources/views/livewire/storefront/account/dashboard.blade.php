<div class="py-12 max-w-4xl mx-auto">
    <div class="flex items-center gap-6 mb-12">
        <div class="h-20 w-20 rounded-full bg-indigo-600 flex items-center justify-center text-white text-3xl font-black shadow-xl ring-4 ring-indigo-50">
            {{ substr($user->name, 0, 1) }}
        </div>
        <div>
            <h1 class="text-3xl font-black text-black">{{ __('Welkom terug, :name', ['name' => $user->name]) }}</h1>
            <p class="text-zinc-500 text-lg">{{ $user->email }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Bestellingen Kaart -->
        <a href="#" class="bg-white p-8 rounded-3xl shadow-sm border border-zinc-100 flex flex-col items-center text-center group hover:border-indigo-400 hover:shadow-md transition-all duration-300">
            <div class="w-14 h-14 bg-zinc-950 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-indigo-600 transition-colors">
                <flux:icon name="shopping-bag" class="text-white" />
            </div>
            <h2 class="text-xl font-bold text-black mb-2">{{ __('Mijn Bestellingen') }}</h2>
            <p class="text-sm text-zinc-500 mb-6 flex-1">{{ __('Bekijk je bestelgeschiedenis en status.') }}</p>
            <span class="text-sm font-bold text-indigo-600 group-hover:translate-x-1 transition-transform inline-flex items-center gap-1">
                {{ __('Bekijk alles') }} <flux:icon name="arrow-right" size="xs" />
            </span>
        </a>

        <!-- Profiel Kaart -->
        <a href="{{ route('storefront.account.profile') }}" wire:navigate class="bg-white p-8 rounded-3xl shadow-sm border border-zinc-100 flex flex-col items-center text-center group hover:border-indigo-400 hover:shadow-md transition-all duration-300">
            <div class="w-14 h-14 bg-zinc-950 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-indigo-600 transition-colors">
                <flux:icon name="user" class="text-white" />
            </div>
            <h2 class="text-xl font-bold text-black mb-2">{{ __('Profiel Instellingen') }}</h2>
            <p class="text-sm text-zinc-500 mb-6 flex-1">{{ __('Beheer je persoonlijke gegevens en wachtwoord.') }}</p>
            <span class="text-sm font-bold text-indigo-600 group-hover:translate-x-1 transition-transform inline-flex items-center gap-1">
                {{ __('Bewerken') }} <flux:icon name="arrow-right" size="xs" />
            </span>
        </a>

        <!-- Adressen Kaart -->
        <a href="{{ route('storefront.account.addresses') }}" wire:navigate class="bg-white p-8 rounded-3xl shadow-sm border border-zinc-100 flex flex-col items-center text-center group hover:border-indigo-400 hover:shadow-md transition-all duration-300">
            <div class="w-14 h-14 bg-zinc-950 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-indigo-600 transition-colors">
                <flux:icon name="map-pin" class="text-white" />
            </div>
            <h2 class="text-xl font-bold text-black mb-2">{{ __('Adressen') }}</h2>
            <p class="text-sm text-zinc-500 mb-6 flex-1">{{ __('Beheer je bezorg- en factuuradressen.') }}</p>
            <span class="text-sm font-bold text-indigo-600 group-hover:translate-x-1 transition-transform inline-flex items-center gap-1">
                {{ __('Beheer adressen') }} <flux:icon name="arrow-right" size="xs" />
            </span>
        </a>
    </div>

    <div class="mt-16">
        <h2 class="text-2xl font-black text-black mb-8">{{ __('Recente Bestellingen') }}</h2>
        <div class="bg-white rounded-3xl shadow-sm border border-zinc-100 flex flex-col items-center justify-center py-20 text-center">
            <div class="w-20 h-20 bg-zinc-50 rounded-full flex items-center justify-center mb-6">
                <flux:icon name="shopping-bag" size="xl" class="text-zinc-300" />
            </div>
            <p class="text-zinc-500 text-lg mb-8">{{ __('Je hebt nog geen bestellingen geplaatst.') }}</p>
            <flux:button variant="primary" :href="route('storefront.products.index')">{{ __('Nu shoppen') }}</flux:button>
        </div>
    </div>
</div>
