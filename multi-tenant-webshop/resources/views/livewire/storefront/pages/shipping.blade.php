<div class="py-20 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-4xl font-black text-black mb-12 uppercase tracking-tighter">{{ __('Verzending & Bezorging') }}</h1>
    
    <div class="prose prose-zinc lg:prose-xl">
        <p class="text-lg text-zinc-600 leading-relaxed mb-8">
            Bij {{ app(\App\Services\TenantManager::class)->getTenant()->name }} doen we ons best om jouw bestelling zo snel mogelijk bij je te krijgen. Hieronder vind je alle informatie over onze verzendmethoden en levertijden.
        </p>

        <h2 class="text-2xl font-bold text-black mt-12 mb-6 uppercase tracking-tight">{{ __('Levertijden') }}</h2>
        <p class="text-zinc-600 mb-6">
            Bestellingen die op werkdagen vóór 16:00 uur zijn geplaatst, worden meestal de volgende dag bezorgd. Houd rekening met 1-3 werkdagen voor bezorging binnen Nederland en België.
        </p>

        <h2 class="text-2xl font-bold text-black mt-12 mb-6 uppercase tracking-tight">{{ __('Verzendkosten') }}</h2>
        <p class="text-zinc-600 mb-6">
            Wij hanteren een standaard verzendtarief van €4,95. Bij bestellingen boven de €50,- is de verzending geheel gratis.
        </p>

        <h2 class="text-2xl font-bold text-black mt-12 mb-6 uppercase tracking-tight">{{ __('Track & Trace') }}</h2>
        <p class="text-zinc-600 mb-6">
            Zodra jouw pakket ons magazijn verlaat, ontvang je een e-mail met een Track & Trace code zodat je de zending kunt volgen.
        </p>
    </div>

    <div class="mt-20 pt-10 border-t border-zinc-100">
        <flux:button href="{{ route('storefront.products.index') }}" variant="primary" class="rounded-full px-8 py-3">{{ __('Terug naar shoppen') }}</flux:button>
    </div>
</div>
