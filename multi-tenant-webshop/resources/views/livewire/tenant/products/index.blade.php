<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl" level="1">{{ __('Producten') }}</flux:heading>
            <flux:text>{{ __('Beheer je assortiment en voorraad.') }}</flux:text>
        </div>
        <flux:button :href="route('tenant.products.create', ['tenant' => $tenantSlug])" variant="primary" icon="plus" wire:navigate>{{ __('Product Toevoegen') }}</flux:button>
    </div>

    <flux:card class="p-0 overflow-hidden">
        <flux:table :paginate="$products">
            <flux:table.columns>
                <flux:table.column sortable wire:click="sortBy('name')">{{ __('Product') }}</flux:table.column>
                <flux:table.column>{{ __('Categorie') }}</flux:table.column>
                <flux:table.column sortable wire:click="sortBy('price')">{{ __('Prijs') }}</flux:table.column>
                <flux:table.column sortable wire:click="sortBy('stock')">{{ __('Voorraad') }}</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($products as $product)
                    <flux:table.row :key="$product->id">
                        <flux:table.cell>
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-lg bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center overflow-hidden">
                                    @if($product->image_url)
                                        <img src="{{ $product->image_url }}" alt="" class="object-cover w-full h-full">
                                    @else
                                        <flux:icon name="archive-box" class="text-zinc-400" />
                                    @endif
                                </div>
                                <div>
                                    <div class="font-medium text-zinc-900 dark:text-white">{{ $product->name }}</div>
                                    <div class="text-xs text-zinc-500">{{ $product->sku }}</div>
                                </div>
                            </div>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:badge size="sm">{{ $product->category->name ?? 'Geen categorie' }}</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            €{{ number_format($product->price, 2) }}
                        </flux:table.cell>
                        <flux:table.cell>
                            @if($product->stock <= 5)
                                <flux:badge color="red" size="sm">{{ $product->stock }}</flux:badge>
                            @else
                                <flux:badge color="green" size="sm">{{ $product->stock }}</flux:badge>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell align="end">
                            <flux:dropdown>
                                <flux:button variant="ghost" icon="ellipsis-horizontal" size="sm" />
                                <flux:menu>
                                    <flux:menu.item icon="pencil-square" :href="route('tenant.products.edit', ['tenant' => $tenantSlug, 'product' => $product->id])" wire:navigate>{{ __('Bewerken') }}</flux:menu.item>
                                    <flux:menu.item icon="trash" variant="danger" wire:click="deleteProduct('{{ $product->id }}')" wire:confirm="{{ __('Weet je zeker dat je dit product wilt verwijderen?') }}">{{ __('Verwijderen') }}</flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>

        <div class="p-4 border-t border-zinc-200 dark:border-zinc-700">
            {{ $products->links() }}
        </div>
    </flux:card>
</div>
