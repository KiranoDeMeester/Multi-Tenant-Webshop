<div class="py-20 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-4xl font-black text-black mb-12 uppercase tracking-tighter">{{ __('Retourneren & Ruilen') }}</h1>
    
    <div class="prose prose-zinc lg:prose-xl">
        <p class="text-lg text-zinc-600 leading-relaxed mb-8">
            Niet helemaal tevreden met je aankoop? Geen probleem. Bij {{ app(\App\Services\TenantManager::class)->getTenant()->name }} heb je 14 dagen de tijd om je bestelling te retourneren.
        </p>

        <h2 class="text-2xl font-bold text-black mt-12 mb-6 uppercase tracking-tight">{{ __('Hoe te retourneren?') }}</h2>
        <ol class="list-decimal list-inside text-zinc-600 space-y-4 mb-8">
            <li>Meld je retour aan via onze klantenservice of in je account.</li>
            <li>Verpak de artikelen in de originele verpakking indien mogelijk.</li>
            <li>Stuur het pakket naar ons retouradres (vermeld op het retourformulier).</li>
            <li>Zodra we je retour hebben ontvangen, storten we het bedrag binnen 5 werkdagen terug.</li>
        </ol>

        <h2 class="text-2xl font-bold text-black mt-12 mb-6 uppercase tracking-tight">{{ __('Voorwaarden') }}</h2>
        <p class="text-zinc-600 mb-6">
            Artikelen moeten ongedragen, ongewassen en voorzien van alle originele labels geretourneerd worden. De kosten voor retourzending zijn voor rekening van de klant, tenzij er sprake is van een defect of foutieve levering.
        </p>
    </div>

    <div class="mt-20 pt-10 border-t border-zinc-100">
        <flux:button href="{{ route('storefront.products.index') }}" variant="primary" class="rounded-full px-8 py-3">{{ __('Terug naar shoppen') }}</flux:button>
    </div>
</div>
