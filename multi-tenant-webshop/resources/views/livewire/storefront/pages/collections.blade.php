<div class="py-20 max-w-7xl mx-auto px-4">
    <div class="mb-16">
        <h1 class="text-5xl font-black uppercase tracking-tighter mb-4">{{ __('Onze Collecties') }}</h1>
        <p class="text-xl text-zinc-500 font-medium max-w-2xl">{{ __('Ontdek onze zorgvuldig samengestelde collecties, ontworpen voor de moderne minimalist.') }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
        @foreach($categories as $category)
            <a href="{{ route('storefront.products.index', ['category' => $category->slug]) }}" class="group relative aspect-[4/5] overflow-hidden rounded-[3rem] border-2 border-black shadow-[15px_15px_0px_0px_rgba(0,0,0,1)] hover:shadow-[25px_25px_0px_0px_rgba(0,0,0,1)] transition-all duration-700">
                <img src="{{ $category->image ?? 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?auto=format&fit=crop&w=800&q=80' }}" alt="{{ $category->name }}" class="absolute inset-0 w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700 group-hover:scale-110">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                <div class="absolute bottom-10 left-10 right-10">
                    <h2 class="text-4xl font-black text-white uppercase tracking-tighter mb-2">{{ $category->name }}</h2>
                    <div class="flex items-center gap-2 text-white/80 font-black uppercase tracking-widest text-xs">
                        <span>{{ __('Bekijk Collectie') }}</span>
                        <flux:icon name="arrow-right" size="sm" class="group-hover:translate-x-2 transition-transform" />
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
