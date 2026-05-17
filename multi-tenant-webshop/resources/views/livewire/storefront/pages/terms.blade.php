<div class="py-24 max-w-4xl mx-auto px-4">
    <div class="mb-20">
        <h1 class="text-6xl font-black uppercase tracking-tighter mb-4">{{ __('Algemene Voorwaarden') }}</h1>
        <p class="text-sm font-black uppercase tracking-[0.3em] text-zinc-400">{{ __('Laatste update: ') . now()->format('d M Y') }}</p>
    </div>

    <div class="bg-white border-2 border-black rounded-[3rem] p-16 shadow-[20px_20px_0px_0px_rgba(0,0,0,1)]">
        <div class="prose prose-zinc prose-lg max-w-none font-bold text-zinc-800 leading-relaxed">
            {!! nl2br(e($content)) !!}
        </div>
    </div>
</div>
