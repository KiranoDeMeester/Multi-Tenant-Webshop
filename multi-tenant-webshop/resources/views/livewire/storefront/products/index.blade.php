<div class="py-8">
    @if(($themeSettings['show_hero_banner'] ?? true) && !$search && !$category)
        <div class="relative rounded-3xl overflow-hidden bg-neutral-900 mb-12">
            <div class="absolute inset-0 opacity-40 bg-gradient-to-r from-neutral-900 to-transparent z-10"></div>
            <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=1600&q=80" 
                 alt="Hero" class="absolute inset-0 w-full h-full object-cover">
            
            <div class="relative z-20 p-12 md:p-20 flex flex-col items-start justify-center min-h-[400px] max-w-2xl">
                <flux:badge color="indigo" class="mb-4">{{ __('NIEUWE COLLECTIE') }}</flux:badge>
                <h1 class="text-4xl md:text-6xl font-black text-white mb-6 leading-tight">
                    {{ __('Stijlvol shoppen bij :name', ['name' => app(\App\Services\TenantManager::class)->getTenant()->name]) }}
                </h1>
                <div class="flex gap-4">
                    <flux:button variant="primary" class="px-8">{{ __('Shop Nu') }}</flux:button>
                </div>
            </div>
        </div>
    @endif

    <div class="flex flex-col md:flex-row gap-8">
        <!-- Sidebar Filters -->
        <aside class="w-full md:w-64 space-y-8">
            <div>
                <flux:heading size="lg" class="mb-4">{{ __('Zoeken') }}</flux:heading>
                <flux:input wire:model.live.debounce.300ms="search" placeholder="{{ __('Zoek producten...') }}" icon="magnifying-glass" />
            </div>

            <div>
                <flux:heading size="lg" class="mb-4">{{ __('Categorieën') }}</flux:heading>
                <div class="space-y-2">
                    <button wire:click="$set('category', '')" 
                            class="w-full text-left px-3 py-2 rounded-lg text-sm transition-colors {{ $category === '' ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-neutral-600 hover:bg-neutral-100' }}">
                        {{ __('Alle Producten') }}
                    </button>
                    @foreach($categories as $cat)
                        <button wire:click="$set('category', '{{ $cat->slug }}')" 
                                class="w-full text-left px-3 py-2 rounded-lg text-sm transition-colors {{ $category === $cat->slug ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-neutral-600 hover:bg-neutral-100' }}">
                            {{ $cat->name }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div>
                <flux:heading size="lg" class="mb-4">{{ __('Sorteren') }}</flux:heading>
                <flux:select wire:model.live="sort">
                    <option value="latest">{{ __('Nieuwste eerst') }}</option>
                    <option value="price_low">{{ __('Prijs: laag naar hoog') }}</option>
                    <option value="price_high">{{ __('Prijs: hoog naar laag') }}</option>
                </flux:select>
            </div>
        </aside>

        <!-- Product Grid -->
        <div class="flex-1">
            <div class="mb-6 flex justify-between items-center">
                <flux:text>{{ __('Toon :count producten', ['count' => $products->total()]) }}</flux:text>
            </div>

            @if($products->isEmpty())
                <div class="py-24 text-center bg-white rounded-3xl border border-neutral-100">
                    <flux:icon name="magnifying-glass" size="xl" class="mx-auto mb-4 text-neutral-300" />
                    <flux:heading size="lg">{{ __('Geen producten gevonden') }}</flux:heading>
                    <flux:text>{{ __('Probeer een andere zoekopdracht of filter.') }}</flux:text>
                    <flux:button variant="ghost" class="mt-4" wire:click="$set('search', '')">{{ __('Wis filters') }}</flux:button>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($products as $product)
                        <div class="group flex flex-col">
                            <div class="relative aspect-[4/5] rounded-[2rem] overflow-hidden bg-neutral-50 mb-6 border-2 border-black transition-all duration-500 group-hover:shadow-[10px_10px_0px_0px_rgba(0,0,0,1)]">
                                @php
                                    $displayImage = $product->getFirstMediaUrl('products', 'large') ?: $product->image_url;
                                @endphp
                                @if($displayImage)
                                    <img src="{{ $displayImage }}" alt="{{ $product->name }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                @else
                                    <div class="absolute inset-0 flex items-center justify-center text-neutral-200">
                                        <flux:icon name="photo" size="xl" />
                                    </div>
                                @endif
                                
                                @if($product->stock <= 0)
                                    <div class="absolute top-6 left-6 z-20">
                                        <span class="bg-black text-white text-[9px] font-black px-3 py-1.5 rounded-full uppercase tracking-[0.2em] high-contrast-dark">{{ __('Uitverkocht') }}</span>
                                    </div>
                                @endif
                                
                                <div class="absolute inset-0 flex items-center justify-center z-30 pointer-events-none">
                                    <div class="opacity-0 group-hover:opacity-100 translate-y-8 group-hover:translate-y-0 transition-all duration-500 pointer-events-auto">
                                        @if($product->stock > 0)
                                            <button wire:click.prevent="quickAddToCart('{{ $product->id }}')" class="bg-black text-white px-8 py-4 rounded-2xl font-black uppercase tracking-widest text-[10px] hover:bg-neutral-800 transition-all shadow-2xl active:scale-95 flex items-center gap-2 high-contrast-dark">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                                </svg>
                                                {{ __('In mandje') }}
                                            </button>
                                        @else
                                            <a href="{{ route('storefront.products.show', ['slug' => $product->slug]) }}" class="bg-white text-black border-2 border-black px-8 py-4 rounded-2xl font-black uppercase tracking-widest text-[10px] hover:bg-black hover:text-white transition-all shadow-2xl block active:scale-95">
                                                {{ __('Bekijk details') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col">
                                <div class="flex justify-between items-start gap-4 mb-2">
                                    <h3 class="text-sm font-black text-black uppercase tracking-wider leading-tight">
                                        <a href="{{ route('storefront.products.show', ['slug' => $product->slug]) }}">
                                            {{ $product->name }}
                                        </a>
                                    </h3>
                                    <span class="font-black text-lg">€{{ number_format($product->price, 2) }}</span>
                                </div>
                                <div class="text-[9px] font-black uppercase tracking-[0.3em] text-neutral-400">{{ $product->category->name ?? '' }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-12">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
