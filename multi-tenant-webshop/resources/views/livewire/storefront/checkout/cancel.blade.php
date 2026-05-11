<div class="py-24">
    <div class="max-w-3xl mx-auto text-center">
        <div class="mb-12 flex justify-center">
            <div class="h-32 w-32 bg-red-600 rounded-[2.5rem] flex items-center justify-center shadow-[15px_15px_0px_0px_rgba(0,0,0,1)] border-4 border-black">
                <flux:icon name="x-mark" size="xl" class="text-white h-16 w-16" />
            </div>
        </div>

        <h1 class="text-6xl font-black text-black uppercase tracking-tighter mb-6">{{ __('Betaling afgebroken') }}</h1>
        <p class="text-xl text-neutral-500 font-medium tracking-tight mb-12">
            {{ __('Je betaling is niet voltooid. Geen zorgen, je items staan nog in je winkelwagen.') }}
        </p>

        <div class="flex flex-col sm:flex-row gap-6 justify-center">
            <a href="{{ route('storefront.cart.index') }}" class="px-12 py-6 bg-black text-white font-black uppercase tracking-[0.2em] rounded-3xl hover:bg-neutral-800 transition-all shadow-xl">
                {{ __('Terug naar winkelwagen') }}
            </a>
            <a href="{{ route('storefront.products.index') }}" class="px-12 py-6 bg-white text-black border-4 border-black font-black uppercase tracking-[0.2em] rounded-3xl hover:bg-neutral-50 transition-all shadow-xl">
                {{ __('Verder winkelen') }}
            </a>
        </div>
    </div>
</div>
