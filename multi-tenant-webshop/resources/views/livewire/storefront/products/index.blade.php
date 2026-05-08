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
                        <div class="group relative flex flex-col">
                            <a href="{{ route('storefront.products.show', ['slug' => $product->slug]) }}" class="relative aspect-[4/5] rounded-3xl overflow-hidden bg-neutral-100 mb-4 block">
                                @php
                                    $displayImage = $product->getFirstMediaUrl('products', 'large') ?: $product->image_url;
                                @endphp
                                @if($displayImage)
                                    <img src="{{ $displayImage }}" alt="{{ $product->name }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                @else
                                    <div class="absolute inset-0 flex items-center justify-center text-neutral-300">
                                        <flux:icon name="photo" size="xl" />
                                    </div>
                                @endif
                                
                                <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <flux:button variant="outline" icon="plus" size="sm" class="shadow-lg bg-white" circular />
                                </div>
                            </a>

                            <div class="flex-1 flex flex-col">
                                <div class="flex justify-between items-start mb-1">
                                    <h3 class="text-lg font-bold text-neutral-900 group-hover:text-primary transition-colors">
                                        <a href="{{ route('storefront.products.show', ['slug' => $product->slug]) }}">
                                            {{ $product->name }}
                                        </a>
                                    </h3>
                                    <span class="font-black text-lg">€{{ number_format($product->price, 2) }}</span>
                                </div>
                                <flux:text size="sm" class="mb-4">{{ $product->category->name ?? '' }}</flux:text>
                                
                                <div class="mt-auto">
                                    @if($product->stock > 0)
                                        <flux:button variant="primary" class="w-full" icon="shopping-bag">{{ __('In winkelmand') }}</flux:button>
                                    @else
                                        <flux:button variant="filled" disabled class="w-full">{{ __('Uitverkocht') }}</flux:button>
                                    @endif
                                </div>
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
