<div class="py-12">
    <div class="mb-12">
        <nav class="flex items-center gap-3 text-[10px] font-black uppercase tracking-[0.2em]">
            <a href="{{ route('storefront.products.index') }}" class="text-neutral-400 hover:text-black transition-colors">{{ __('Producten') }}</a>
            <span class="text-neutral-300">/</span>
            @if($product->category)
                <a href="{{ route('storefront.categories.show', ['categorySlug' => $product->category->slug]) }}" wire:navigate class="text-neutral-400 hover:text-black transition-colors">{{ $product->category->name }}</a>
            @else
                <span class="text-neutral-400">{{ __('Geen categorie') }}</span>
            @endif
            <span class="text-neutral-300">/</span>
            <span class="text-black">{{ $product->name }}</span>
        </nav>
    </div>

    @if (session()->has('message'))
        <div class="mb-8 p-4 bg-black text-white text-xs font-black uppercase tracking-widest rounded-xl animate-bounce">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-20">
        <!-- Product Image -->
        <div class="aspect-square rounded-[3rem] overflow-hidden bg-neutral-50 border-2 border-black hover:shadow-[20px_20px_0px_0px_rgba(0,0,0,1)] transition-all duration-500">
            @php
                $displayImage = $product->getFirstMediaUrl('products', 'large') ?: $product->image_url;
            @endphp
            @if($displayImage)
                <img src="{{ $displayImage }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center text-neutral-200">
                    <flux:icon name="photo" size="xl" class="h-32 w-32" />
                </div>
            @endif
        </div>

        <!-- Product Info -->
        <div class="flex flex-col justify-center">
            <div class="text-[10px] font-black uppercase tracking-[0.3em] text-secondary mb-4">{{ $product->category->name ?? '' }}</div>
            <h1 class="text-5xl md:text-6xl font-black mb-4 uppercase tracking-tighter">{{ $product->name }}</h1>
            <div class="text-xs font-bold text-neutral-400 uppercase tracking-widest mb-10">SKU: {{ $product->sku }}</div>

            <div class="text-4xl font-black mb-12">
                €{{ number_format($product->price, 2) }}
            </div>

            <div class="prose prose-neutral mb-12 text-black font-medium leading-relaxed max-w-md">
                {!! nl2br(e($product->description)) !!}
            </div>

            @if($product->stock > 0)
                <div class="space-y-8 pt-10 border-t-2 border-black">
                    <div class="flex items-center gap-10">
                        <div class="flex items-center border-2 border-black rounded-2xl overflow-hidden bg-white shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                            <button wire:click="decrement" class="p-4 hover:bg-black hover:text-white transition-all">
                                <flux:icon name="minus" size="xs" />
                            </button>
                            <span class="w-16 text-center font-black text-lg">{{ $quantity }}</span>
                            <button wire:click="increment" class="p-4 hover:bg-black hover:text-white transition-all">
                                <flux:icon name="plus" size="xs" />
                            </button>
                        </div>
                        <div class="text-[10px] font-black uppercase tracking-widest text-neutral-400">
                            {{ __('Nog :count op voorraad', ['count' => $product->stock]) }}
                        </div>
                    </div>

                    <button wire:click="addToCart" class="w-full h-20 bg-black text-white text-xl font-black uppercase tracking-[0.2em] rounded-3xl hover:bg-neutral-800 transition-all active:scale-95 shadow-2xl flex items-center justify-center gap-4 group">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6 group-hover:rotate-12 transition-transform">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                        </svg>
                        {{ __('In winkelmand') }}
                    </button>
                </div>
            @else
                <div class="pt-10 border-t-2 border-black">
                    <div class="w-full py-6 border-2 border-black text-black font-black uppercase tracking-[0.2em] text-center rounded-3xl">
                        {{ __('Momenteel uitverkocht') }}
                    </div>
                </div>
            @endif

            <div class="mt-16 grid grid-cols-3 gap-8 py-10 border-t-2 border-black">
                <div class="text-center">
                    <flux:icon name="truck" class="mx-auto mb-3 text-black" />
                    <div class="text-[9px] font-black uppercase tracking-[0.2em] text-black">{{ __('Express') }}</div>
                </div>
                <div class="text-center">
                    <flux:icon name="shield-check" class="mx-auto mb-3 text-black" />
                    <div class="text-[9px] font-black uppercase tracking-[0.2em] text-black">{{ __('Secure') }}</div>
                </div>
                <div class="text-center">
                    <flux:icon name="arrow-uturn-left" class="mx-auto mb-3 text-black" />
                    <div class="text-[9px] font-black uppercase tracking-[0.2em] text-black">{{ __('Return') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
