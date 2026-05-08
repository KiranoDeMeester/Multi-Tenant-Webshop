<div class="py-8">
    <div class="mb-8">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item :href="route('storefront.products.index')">{{ __('Producten') }}</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ $product->category->name ?? __('Geen categorie') }}</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ $product->name }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>

    @if (session()->has('message'))
        <div class="mb-6">
            <flux:badge color="green">{{ session('message') }}</flux:badge>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Product Image -->
        <div class="aspect-square rounded-3xl overflow-hidden bg-neutral-100 border border-neutral-100">
            @if($product->image_url)
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center text-neutral-300">
                    <flux:icon name="photo" size="xl" class="h-24 w-24" />
                </div>
            @endif
        </div>

        <!-- Product Info -->
        <div class="flex flex-col">
            <flux:badge size="sm" class="self-start mb-4">{{ $product->category->name ?? '' }}</flux:badge>
            <h1 class="text-4xl font-black mb-2">{{ $product->name }}</h1>
            <flux:text size="lg" class="mb-6">SKU: {{ $product->sku }}</flux:text>

            <div class="text-3xl font-black mb-8" style="color: var(--primary-color)">
                €{{ number_format($product->price, 2) }}
            </div>

            <div class="prose prose-neutral dark:prose-invert mb-8">
                {!! nl2br(e($product->description)) !!}
            </div>

            @if($product->stock > 0)
                <div class="space-y-6 pt-6 border-t border-neutral-100 dark:border-neutral-800">
                    <div class="flex items-center gap-6">
                        <div class="flex items-center border border-neutral-200 dark:border-neutral-700 rounded-xl overflow-hidden">
                            <button wire:click="decrement" class="p-3 hover:bg-neutral-50 dark:hover:bg-neutral-800 transition-colors">
                                <flux:icon name="minus" size="xs" />
                            </button>
                            <span class="w-12 text-center font-bold">{{ $quantity }}</span>
                            <button wire:click="increment" class="p-3 hover:bg-neutral-50 dark:hover:bg-neutral-800 transition-colors">
                                <flux:icon name="plus" size="xs" />
                            </button>
                        </div>
                        <flux:text size="sm">{{ __('Nog :count op voorraad', ['count' => $product->stock]) }}</flux:text>
                    </div>

                    <flux:button variant="primary" size="lg" class="w-full h-14 text-lg" icon="shopping-bag" wire:click="addToCart">
                        {{ __('In winkelmand') }}
                    </flux:button>
                </div>
            @else
                <div class="pt-6 border-t border-neutral-100">
                    <flux:badge color="red" size="lg" class="w-full justify-center py-4">{{ __('Momenteel uitverkocht') }}</flux:badge>
                </div>
            @endif

            <div class="mt-12 grid grid-cols-3 gap-4 py-6 border-t border-neutral-100">
                <div class="text-center">
                    <flux:icon name="truck" class="mx-auto mb-2 text-neutral-400" />
                    <div class="text-[10px] font-bold uppercase tracking-wider text-neutral-500">{{ __('Snelle levering') }}</div>
                </div>
                <div class="text-center">
                    <flux:icon name="shield-check" class="mx-auto mb-2 text-neutral-400" />
                    <div class="text-[10px] font-bold uppercase tracking-wider text-neutral-500">{{ __('Veilig betalen') }}</div>
                </div>
                <div class="text-center">
                    <flux:icon name="arrow-uturn-left" class="mx-auto mb-2 text-neutral-400" />
                    <div class="text-[10px] font-bold uppercase tracking-wider text-neutral-500">{{ __('30 dagen retour') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
