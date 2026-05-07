<div class="p-6 max-w-2xl mx-auto">
    <div class="mb-6">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item :href="route('tenant.products.index', ['tenant' => request()->route('tenant')])">{{ __('Producten') }}</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ __('Product Bewerken') }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="mt-4">
            <flux:heading size="xl" level="1">{{ __('Product Bewerken: :name', ['name' => $product->name]) }}</flux:heading>
            <flux:text>{{ __('Pas de details van je product aan.') }}</flux:text>
        </div>
    </div>

    <form wire:submit="save">
        <flux:card class="space-y-6">
            <flux:input wire:model="name" :label="__('Productnaam')" />
            
            <div class="grid grid-cols-2 gap-4">
                <flux:input wire:model="sku" :label="__('SKU / Artikelnummer')" />
                
                <flux:select wire:model="category_id" :label="__('Categorie')">
                    <option value="">{{ __('Geen categorie') }}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </flux:select>
            </div>

            <flux:textarea wire:model="description" :label="__('Beschrijving')" rows="3" />

            <div class="grid grid-cols-2 gap-4">
                <flux:input type="number" step="0.01" wire:model="price" :label="__('Prijs (€)')" />
                <flux:input type="number" wire:model="stock" :label="__('Voorraad')" />
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-zinc-100 dark:border-zinc-800">
                <flux:button :href="route('tenant.products.index', ['tenant' => request()->route('tenant')])" variant="ghost">{{ __('Annuleren') }}</flux:button>
                <flux:button type="submit" variant="primary">{{ __('Wijzigingen Opslaan') }}</flux:button>
            </div>
        </flux:card>
    </form>
</div>
