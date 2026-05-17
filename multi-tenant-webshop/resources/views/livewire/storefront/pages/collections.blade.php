@php
    $themeSettings = app(\App\Services\TenantManager::class)->getThemeSettings();
    $layoutType = $themeSettings['layout_type'] ?? 'modern';
@endphp

<div class="py-20 max-w-7xl mx-auto px-4">
    <div class="mb-16">
        <h1 class="{{ $layoutType === 'minimal' ? 'text-4xl font-light text-neutral-900 tracking-tight' : 'text-5xl font-black uppercase tracking-tighter mb-4' }}">
            {{ __('Onze Collecties') }}
        </h1>
        <p class="{{ $layoutType === 'minimal' ? 'text-sm text-neutral-500 font-medium max-w-xl mt-2' : 'text-xl text-zinc-500 font-medium max-w-2xl' }}">
            {{ __('Ontdek onze zorgvuldig samengestelde collecties, ontworpen voor de moderne minimalist.') }}
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
        @foreach($categories as $category)
            @php
                $displayImage = $category->getFirstMediaUrl('categories', 'large') ?: ($category->image ?: 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?auto=format&fit=crop&w=800&q=80');
            @endphp
            <a href="{{ route('storefront.products.index', ['category' => $category->slug]) }}" 
               class="group relative aspect-[4/5] overflow-hidden transition-all duration-700 {{ $layoutType === 'minimal' ? 'rounded-2xl shadow-sm hover:shadow-lg hover:scale-[1.01]' : 'rounded-[3rem] border-2 border-black shadow-[15px_15px_0px_0px_rgba(0,0,0,1)] hover:shadow-[25px_25px_0px_0px_rgba(0,0,0,1)]' }}">
                
                <img src="{{ $displayImage }}" 
                     alt="{{ $category->name }}" 
                     class="absolute inset-0 w-full h-full object-cover transition-all duration-700 group-hover:scale-105 {{ $layoutType === 'minimal' ? '' : 'grayscale group-hover:grayscale-0' }}">
                
                <div class="absolute inset-0 bg-gradient-to-t {{ $layoutType === 'minimal' ? 'from-black/60 via-black/10' : 'from-black/80 via-transparent' }} to-transparent"></div>
                
                <div class="absolute bottom-10 left-10 right-10">
                    <h2 class="{{ $layoutType === 'minimal' ? 'text-2xl font-semibold text-white tracking-tight mb-1' : 'text-4xl font-black text-white uppercase tracking-tighter mb-2' }}">
                        {{ $category->name }}
                    </h2>
                    <div class="flex items-center gap-2 text-white/80 font-black uppercase tracking-widest text-xs">
                        <span class="{{ $layoutType === 'minimal' ? 'font-semibold text-xs tracking-wider normal-case' : '' }}">{{ __('Bekijk Collectie') }}</span>
                        <flux:icon name="arrow-right" size="sm" class="group-hover:translate-x-2 transition-transform text-white/90" />
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
