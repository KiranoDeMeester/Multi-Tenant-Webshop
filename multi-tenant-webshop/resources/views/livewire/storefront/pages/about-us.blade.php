<div class="py-24 max-w-4xl mx-auto px-4">
    <div class="text-center mb-20">
        <h1 class="text-6xl font-black uppercase tracking-tighter mb-6">{{ __('Over Ons') }}</h1>
        <div class="w-24 h-2 bg-black mx-auto rounded-full"></div>
    </div>

    <div class="bg-white border-2 border-black rounded-[3rem] p-16 shadow-[30px_30px_0px_0px_rgba(0,0,0,1)] relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-zinc-50 rounded-full -mr-32 -mt-32 -z-10"></div>
        
        <div class="prose prose-xl prose-zinc max-w-none font-bold text-zinc-900 leading-relaxed">
            {!! nl2br(e($content)) !!}
        </div>
        
        <div class="mt-16 pt-16 border-t border-zinc-100 flex items-center gap-6">
            <div class="w-16 h-16 bg-black rounded-2xl flex items-center justify-center text-white">
                <flux:icon name="building-storefront" variant="solid" />
            </div>
            <div>
                <div class="text-sm font-black uppercase tracking-[0.2em] text-zinc-400">{{ __('Gevestigd in') }}</div>
                <div class="text-xl font-black uppercase tracking-tight text-black">{{ $shopName }}</div>
            </div>
        </div>
    </div>
</div>
