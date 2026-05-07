<x-layouts.storefront>
    @php
        $tenantManager = app(\App\Services\TenantManager::class);
        $theme = $tenantManager->getThemeSettings();
        $showHero = (bool) ($theme['show_hero_banner'] ?? true);
    @endphp

    @if($showHero)
        <div class="relative rounded-3xl overflow-hidden bg-neutral-900 mb-12">
            <div class="absolute inset-0 opacity-40 bg-gradient-to-r from-neutral-900 to-transparent z-10"></div>
            <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=1600&q=80" 
                 alt="Hero" class="absolute inset-0 w-full h-full object-cover">
            
            <div class="relative z-20 p-12 md:p-24 flex flex-col items-start justify-center min-h-[500px] max-w-2xl">
                <flux:badge color="indigo" class="mb-4">{{ __('NIEUWE COLLECTIE') }}</flux:badge>
                <h1 class="text-5xl md:text-7xl font-black text-white mb-6 leading-tight">
                    {{ __('Stijlvol shoppen bij :name', ['name' => $tenantManager->getTenant()->name]) }}
                </h1>
                <p class="text-xl text-neutral-200 mb-8">
                    {{ __('Ontdek onze nieuwste producten en geniet van exclusieve deals, speciaal voor jou geselecteerd.') }}
                </p>
                <div class="flex gap-4">
                    <flux:button variant="primary" class="px-8">{{ __('Shop Nu') }}</flux:button>
                    <flux:button variant="ghost" class="px-8 text-white hover:bg-white/10">{{ __('Bekijk Alles') }}</flux:button>
                </div>
            </div>
        </div>
    @endif

    <div class="py-12 text-center">
        <flux:heading size="xl" class="mb-2">{{ __('Onze Producten') }}</flux:heading>
        <flux:text>{{ __('Binnenkort kun je hier ons volledige assortiment bekijken.') }}</flux:text>
        
        <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @for($i = 1; $i <= 4; $i++)
                <div class="group">
                    <div class="aspect-[3/4] rounded-2xl bg-neutral-100 mb-4 overflow-hidden relative">
                        <div class="absolute inset-0 flex items-center justify-center text-neutral-300">
                            <flux:icon name="photo" size="xl" />
                        </div>
                    </div>
                    <div class="h-4 w-3/4 bg-neutral-100 rounded mb-2"></div>
                    <div class="h-4 w-1/4 bg-neutral-100 rounded"></div>
                </div>
            @endfor
        </div>
    </div>
</x-layouts.storefront>
