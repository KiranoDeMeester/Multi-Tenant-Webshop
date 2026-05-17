<div class="py-12 max-w-7xl mx-auto">
    <!-- Asymmetric Editorial Hero Banner -->
    @if(($themeSettings['show_hero_banner'] ?? true) && !$search && !$category)
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center mb-20 bg-neutral-50/50 p-8 md:p-16 rounded-[2.5rem] border border-neutral-100">
            <div class="lg:col-span-7 space-y-6">
                <span class="inline-block text-[10px] font-bold tracking-[0.3em] uppercase text-secondary">
                    {{ __('LIMITED COLLECTION') }}
                </span>
                <h1 class="text-4xl md:text-6xl font-extralight tracking-tight text-neutral-900 leading-[1.1]">
                    @if(!empty($themeSettings['hero_title']))
                        {{ $themeSettings['hero_title'] }}
                    @else
                        {{ __('Curated spaces for the') }} <br>
                        <span class="italic font-serif text-primary">{{ __('artful mind') }}</span>.
                    @endif
                </h1>
                <p class="text-neutral-500 font-medium text-sm leading-relaxed max-w-lg">
                    {{ !empty($themeSettings['hero_subtitle']) ? $themeSettings['hero_subtitle'] : __('Ontdek onze nieuwste selectie meubels en designstukken, zorgvuldig ontworpen om een serene en harmonieuze sfeer te creëren in elk modern interieur.') }}
                </p>
                <div class="pt-4">
                    @if($categories->isNotEmpty())
                        <a href="{{ route('storefront.categories.show', ['categorySlug' => $categories->first()->slug]) }}" wire:navigate
                                class="group relative inline-flex items-center gap-3 px-8 py-3.5 border border-neutral-900 text-neutral-900 text-xs font-bold uppercase tracking-widest rounded-full overflow-hidden transition-all duration-500 hover:text-white">
                            <span class="absolute inset-0 bg-neutral-900 translate-y-full group-hover:translate-y-0 transition-transform duration-500 ease-out z-0"></span>
                            <span class="relative z-10">{{ __('Bekijk Collectie') }}</span>
                            <flux:icon name="arrow-right" size="sm" class="relative z-10 group-hover:translate-x-2 transition-transform text-neutral-900 group-hover:text-white" />
                        </a>
                    @endif
                </div>
            </div>
            
            <div class="lg:col-span-5 relative">
                <div class="aspect-[4/5] rounded-[3rem] overflow-hidden shadow-2xl relative">
                    <img src="{{ $themeSettings['hero_image_url'] ?? 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?auto=format&fit=crop&w=1200&q=80' }}" 
                         alt="Editorial Hero" class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-1000">
                </div>
                <!-- Floating Decorative Element -->
                <div class="absolute -bottom-6 -left-6 bg-white p-6 rounded-2xl border border-neutral-100 shadow-xl hidden md:flex items-center gap-4 max-w-xs">
                    <div class="h-10 w-10 rounded-xl bg-secondary/15 flex items-center justify-center text-secondary shrink-0">
                        <flux:icon name="sparkles" size="sm" />
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-neutral-400 uppercase tracking-wider">{{ __('Exclusief') }}</div>
                        <div class="text-xs font-bold text-neutral-800">{{ __('Handgemaakt & Duurzaam') }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-16">
        <!-- Elegant Sidebar Filters -->
        <aside class="w-full lg:w-60 shrink-0 space-y-10">
            <div class="pb-6 border-b border-neutral-100">
                <h3 class="text-xs font-bold uppercase tracking-[0.2em] text-neutral-400 mb-4">{{ __('Zoeken') }}</h3>
                <div class="relative">
                    <input wire:model.live.debounce.300ms="search" 
                           placeholder="{{ __('Typ om te zoeken...') }}" 
                           class="w-full py-2.5 bg-transparent border-b border-neutral-200 focus:outline-none focus:border-neutral-900 transition-colors text-sm font-medium text-neutral-800 placeholder-neutral-400"
                    />
                </div>
            </div>

            <div class="pb-6 border-b border-neutral-100">
                <h3 class="text-xs font-bold uppercase tracking-[0.2em] text-neutral-400 mb-4">{{ __('Collecties') }}</h3>
                <div class="flex flex-col gap-3">
                    <a href="{{ route('storefront.products.index') }}" wire:navigate
                            class="group flex items-center justify-between text-left text-xs font-bold uppercase tracking-wider transition-all {{ $category === '' ? 'text-secondary' : 'text-neutral-500 hover:text-neutral-900' }}">
                        <span>{{ __('Alle Producten') }}</span>
                        @if($category === '')
                            <span class="h-1.5 w-1.5 rounded-full bg-secondary"></span>
                        @endif
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('storefront.categories.show', ['categorySlug' => $cat->slug]) }}" wire:navigate
                                class="group flex items-center justify-between text-left text-xs font-bold uppercase tracking-wider transition-all {{ $category === $cat->slug ? 'text-secondary' : 'text-neutral-500 hover:text-neutral-900' }}">
                            <span>{{ $cat->name }}</span>
                            @if($category === $cat->slug)
                                <span class="h-1.5 w-1.5 rounded-full bg-secondary"></span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            <div>
                <h3 class="text-xs font-bold uppercase tracking-[0.2em] text-neutral-400 mb-4">{{ __('Sorteer') }}</h3>
                <select wire:model.live="sort" class="w-full py-2.5 bg-transparent border-b border-neutral-200 focus:outline-none focus:border-neutral-900 text-sm font-bold uppercase tracking-wider text-neutral-700">
                    <option value="latest">{{ __('Nieuwste') }}</option>
                    <option value="price_low">{{ __('Prijs: Laag - Hoog') }}</option>
                    <option value="price_high">{{ __('Prijs: Hoog - Laag') }}</option>
                </select>
            </div>
        </aside>

        <!-- Product Grid -->
        <div class="flex-1" id="products">
            <div class="mb-8 flex justify-between items-center pb-4 border-b border-neutral-100">
                <span class="text-xs font-bold uppercase tracking-[0.2em] text-neutral-400">
                    {{ __('Totaal :count resultaten', ['count' => $products->total()]) }}
                </span>
            </div>

            @if($products->isEmpty())
                <div class="py-24 text-center border border-dashed border-neutral-200 rounded-3xl">
                    <flux:icon name="magnifying-glass" size="xl" class="mx-auto mb-4 text-neutral-300" />
                    <h3 class="text-sm font-bold uppercase tracking-wider text-neutral-700">{{ __('Geen producten gevonden') }}</h3>
                    <p class="text-xs text-neutral-400 mt-1">{{ __('Probeer een andere zoekopdracht of filter.') }}</p>
                    <button class="mt-4 text-xs font-bold text-secondary uppercase tracking-wider hover:underline" wire:click="$set('search', '')">{{ __('Wis filters') }}</button>
                </div>
            @else
                <!-- Asymmetric Editorial Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-16">
                    @foreach($products as $product)
                        @php
                            // Alternate card style based on iteration
                            $isEven = $loop->iteration % 2 === 0;
                            $displayImage = $product->getFirstMediaUrl('products', 'large') ?: $product->image_url;
                        @endphp
                        <div class="group flex flex-col relative {{ $isEven ? 'md:translate-y-8' : '' }} transition-all duration-500">
                            <!-- Image container with sleek hover overlays -->
                            <div class="relative overflow-hidden bg-neutral-50 mb-6 transition-all duration-700 {{ $isEven ? 'rounded-[2rem]' : 'rounded-[3rem]' }} aspect-[4/5] border border-neutral-100 shadow-sm hover:shadow-2xl">
                                @if($displayImage)
                                    <img src="{{ $displayImage }}" alt="{{ $product->name }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-[1.5s] group-hover:scale-105">
                                @else
                                    <div class="absolute inset-0 flex items-center justify-center text-neutral-300 bg-neutral-100">
                                        <flux:icon name="photo" size="xl" />
                                    </div>
                                @endif

                                @if($product->stock <= 0)
                                    <div class="absolute top-6 left-6 z-20">
                                        <span class="bg-neutral-900/90 text-white text-[8px] font-bold px-3 py-1 rounded-full uppercase tracking-[0.25em]">{{ __('Uitverkocht') }}</span>
                                    </div>
                                @endif

                                <!-- Floating Glass Add-to-cart button on hover -->
                                <div class="absolute inset-x-0 bottom-0 p-6 translate-y-6 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500 ease-out bg-gradient-to-t from-neutral-900/20 to-transparent flex justify-center z-30">
                                    @if($product->stock > 0)
                                        <button wire:click.prevent="quickAddToCart('{{ $product->id }}')" 
                                                class="w-full backdrop-blur-md bg-white/80 hover:bg-neutral-950 hover:text-white text-neutral-900 py-3.5 rounded-2xl font-bold text-xs uppercase tracking-widest transition-all shadow-lg flex items-center justify-center gap-2 border border-white/20">
                                            <flux:icon name="shopping-bag" size="xs" />
                                            {{ __('In mandje') }}
                                        </button>
                                    @else
                                        <a href="{{ route('storefront.products.show', ['slug' => $product->slug]) }}" 
                                           class="w-full backdrop-blur-md bg-white/80 hover:bg-neutral-950 hover:text-white text-neutral-900 text-center py-3.5 rounded-2xl font-bold text-xs uppercase tracking-widest transition-all shadow-lg block border border-white/20">
                                            {{ __('Details') }}
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <!-- Details containing thin premium line dividers -->
                            <div class="flex flex-col">
                                <div class="flex items-baseline justify-between gap-4 border-b border-neutral-100 pb-3 mb-2">
                                    <h3 class="text-base font-light text-neutral-900 tracking-tight">
                                        <a href="{{ route('storefront.products.show', ['slug' => $product->slug]) }}" class="hover:text-primary transition-colors">
                                            {{ $product->name }}
                                        </a>
                                    </h3>
                                    <span class="font-bold text-sm text-neutral-950">€{{ number_format($product->price, 2) }}</span>
                                </div>
                                <div class="text-[9px] font-bold uppercase tracking-[0.25em] text-secondary">{{ $product->category->name ?? '' }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Spacing compensation for shifted cards in asymmetric grid -->
                <div class="h-16 md:h-24"></div>

                <!-- Editorial Pagination -->
                <div class="mt-16 border-t border-neutral-100 pt-8">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
