<div class="py-8 max-w-7xl mx-auto">
    @if(($themeSettings['show_hero_banner'] ?? true) && !$search && !$category)
        <div class="relative rounded-2xl overflow-hidden bg-neutral-50 mb-16 border border-neutral-100 shadow-xs">
            <div class="absolute inset-0 bg-gradient-to-r from-white/95 via-white/80 to-transparent z-10"></div>
            <img src="{{ $themeSettings['hero_image_url'] ?? 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=1600&q=80' }}" 
                 alt="Hero" class="absolute inset-0 w-full h-full object-cover">
            
            <div class="relative z-20 p-10 md:p-16 flex flex-col items-start justify-center min-h-[360px] max-w-xl">
                <span class="text-[10px] font-bold text-secondary tracking-[0.2em] uppercase mb-3">
                    {{ __('NIEUWE COLLECTIE') }}
                </span>
                <h1 class="text-3xl md:text-5xl font-light text-neutral-900 mb-4 leading-tight tracking-tight">
                    {{ !empty($themeSettings['hero_title']) ? $themeSettings['hero_title'] : __('Ontdek de collectie van :name', ['name' => app(\App\Services\TenantManager::class)->getTenant()->name]) }}
                </h1>
                <p class="text-neutral-500 mb-6 font-medium text-sm leading-relaxed max-w-sm">
                    {{ !empty($themeSettings['hero_subtitle']) ? $themeSettings['hero_subtitle'] : __('Stijlvolle en tijdloze ontwerpen, zorgvuldig geselecteerd voor jouw dagelijkse levensstijl.') }}
                </p>
                <button class="bg-secondary hover:bg-secondary/90 text-white px-6 py-2.5 rounded-lg text-xs font-bold tracking-wider active:scale-95 transition-all shadow-sm">
                    {{ __('Shop Nu') }}
                </button>
            </div>
        </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-12">
        <!-- Sidebar Filters -->
        <aside class="w-full lg:w-56 shrink-0 space-y-8">
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-neutral-400 mb-3">{{ __('Zoeken') }}</h3>
                <div class="relative">
                    <input wire:model.live.debounce.300ms="search" 
                           placeholder="{{ __('Zoek producten...') }}" 
                           class="w-full pl-3 pr-8 py-2 text-sm bg-neutral-50 border border-neutral-200 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all font-medium text-neutral-800"
                    />
                </div>
            </div>

            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-neutral-400 mb-3">{{ __('Categorieën') }}</h3>
                <div class="space-y-1">
                    <button wire:click="$set('category', '')" 
                            class="w-full text-left px-3 py-1.5 rounded-lg text-sm transition-colors font-medium {{ $category === '' ? 'bg-secondary/5 text-secondary font-semibold' : 'text-neutral-500 hover:bg-neutral-50 hover:text-neutral-800' }}">
                        {{ __('Alle Producten') }}
                    </button>
                    @foreach($categories as $cat)
                        <button wire:click="$set('category', '{{ $cat->slug }}')" 
                                class="w-full text-left px-3 py-1.5 rounded-lg text-sm transition-colors font-medium {{ $category === $cat->slug ? 'bg-secondary/5 text-secondary font-semibold' : 'text-neutral-500 hover:bg-neutral-50 hover:text-neutral-800' }}">
                            {{ $cat->name }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-neutral-400 mb-3">{{ __('Sorteren') }}</h3>
                <select wire:model.live="sort" class="w-full px-3 py-2 text-sm bg-neutral-50 border border-neutral-200 rounded-lg focus:outline-none focus:border-primary font-medium text-neutral-800">
                    <option value="latest">{{ __('Nieuwste eerst') }}</option>
                    <option value="price_low">{{ __('Prijs: laag naar hoog') }}</option>
                    <option value="price_high">{{ __('Prijs: hoog naar laag') }}</option>
                </select>
            </div>
        </aside>

        <!-- Product Grid -->
        <div class="flex-1" id="products">
            <div class="mb-6 flex justify-between items-center">
                <span class="text-xs font-semibold text-neutral-400">{{ __('Toon :count producten', ['count' => $products->total()]) }}</span>
            </div>

            @if($products->isEmpty())
                <div class="py-24 text-center bg-white rounded-2xl border border-neutral-100 shadow-xs">
                    <flux:icon name="magnifying-glass" size="xl" class="mx-auto mb-4 text-neutral-300" />
                    <h3 class="text-base font-bold text-neutral-700">{{ __('Geen producten gevonden') }}</h3>
                    <p class="text-xs text-neutral-400 mt-1">{{ __('Probeer een andere zoekopdracht of filter.') }}</p>
                    <button class="mt-4 text-xs font-bold text-primary underline" wire:click="$set('search', '')">{{ __('Wis filters') }}</button>
                </div>
            @else
                <!-- Minimal Layout Grid (4 columns, borderless, clean layout) -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-6 gap-y-10">
                    @foreach($products as $product)
                        <div class="group flex flex-col relative">
                            <!-- Image Frame -->
                            <div class="relative aspect-square rounded-xl overflow-hidden bg-neutral-100 mb-4 transition-all duration-300">
                                @php
                                    $displayImage = $product->getFirstMediaUrl('products', 'large') ?: $product->image_url;
                                @endphp
                                @if($displayImage)
                                    <img src="{{ $displayImage }}" alt="{{ $product->name }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                @else
                                    <div class="absolute inset-0 flex items-center justify-center text-neutral-300 bg-neutral-50">
                                        <flux:icon name="photo" size="lg" />
                                    </div>
                                @endif
                                
                                @if($product->stock <= 0)
                                    <div class="absolute top-2 left-2 z-20">
                                        <span class="bg-neutral-900/90 text-white text-[8px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">{{ __('Uitverkocht') }}</span>
                                    </div>
                                @endif
                                
                                <!-- Minimal Hover Add To Cart -->
                                <div class="absolute inset-0 flex items-end justify-center p-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-neutral-900/5">
                                    @if($product->stock > 0)
                                        <button wire:click.prevent="quickAddToCart('{{ $product->id }}')" 
                                                class="w-full bg-white/95 text-neutral-900 py-2 rounded-lg font-bold text-xs shadow-md hover:bg-white transition-all active:scale-95 flex items-center justify-center gap-1.5 border border-neutral-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 text-primary">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                            </svg>
                                            {{ __('In mandje') }}
                                        </button>
                                    @else
                                        <a href="{{ route('storefront.products.show', ['slug' => $product->slug]) }}" 
                                           class="w-full bg-white/95 text-neutral-900 text-center py-2 rounded-lg font-bold text-xs shadow-md hover:bg-white transition-all active:scale-95 block border border-neutral-100">
                                            {{ __('Details') }}
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <!-- Details Frame -->
                            <div class="flex flex-col justify-between flex-1">
                                <div>
                                    <h3 class="text-sm font-semibold text-neutral-800 group-hover:text-primary transition-colors line-clamp-1">
                                        <a href="{{ route('storefront.products.show', ['slug' => $product->slug]) }}">
                                            {{ $product->name }}
                                        </a>
                                    </h3>
                                    <div class="text-[10px] text-secondary font-medium uppercase tracking-wider mt-0.5">{{ $product->category->name ?? '' }}</div>
                                </div>
                                <div class="mt-2 flex items-center justify-between">
                                    <span class="font-bold text-sm text-neutral-950">€{{ number_format($product->price, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-16 border-t border-neutral-100 pt-8">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
