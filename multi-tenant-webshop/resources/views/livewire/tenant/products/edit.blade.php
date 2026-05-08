<div class="p-6 max-w-2xl mx-auto">
    <div class="mb-6">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item :href="route('tenant.products.manage', ['tenant' => request()->route('tenant')])">{{ __('Producten') }}</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ __('Product Bewerken') }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="mt-4">
            <flux:heading size="xl" level="1">{{ __('Product Bewerken: :name', ['name' => $product->name]) }}</flux:heading>
            <flux:text>{{ __('Pas de details van je product aan.') }}</flux:text>
        </div>
    </div>

    <form wire:submit="save">
        <div class="space-y-8">
            <flux:card>
                <div class="grid grid-cols-1 md:grid-cols-12 gap-10">
                    <!-- Image Section -->
                    <div class="md:col-span-4">
                        <flux:field :label="__('Productafbeelding')">
                            <flux:text size="xs" class="mb-3">{{ __('Klik op de afbeelding om een nieuwe versie te uploaden.') }}</flux:text>
                            
                            <div class="relative group cursor-pointer aspect-square bg-zinc-50 dark:bg-zinc-900 border-2 border-dashed border-zinc-200 dark:border-zinc-800 rounded-3xl flex items-center justify-center overflow-hidden transition-all duration-300 hover:border-indigo-400 hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10">
                                @if ($newImage)
                                    <img src="{{ $newImage->temporaryUrl() }}" class="absolute inset-0 w-full h-full object-cover">
                                @elseif($product->getFirstMediaUrl('products'))
                                    <img src="{{ $product->getFirstMediaUrl('products', 'thumb') }}" class="absolute inset-0 w-full h-full object-cover">
                                @else
                                    <div class="text-center p-6 transition-transform duration-300 group-hover:scale-110">
                                        <div class="w-16 h-16 bg-white dark:bg-zinc-800 shadow-sm rounded-2xl flex items-center justify-center mx-auto mb-4">
                                            <flux:icon name="photo" size="xl" class="text-zinc-400" />
                                        </div>
                                        <flux:text size="sm" class="font-medium">{{ __('Afbeelding kiezen') }}</flux:text>
                                    </div>
                                @endif
                                
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <flux:icon name="camera" size="lg" class="text-white" />
                                </div>
                                
                                <input type="file" wire:model="newImage" class="absolute inset-0 opacity-0 cursor-pointer">
                            </div>
                            <flux:error name="newImage" />
                        </flux:field>
                    </div>

                    <!-- Primary Info Section -->
                    <div class="md:col-span-8 space-y-6">
                        <flux:input wire:model="name" :label="__('Productnaam')" class="text-lg font-bold" />
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <flux:input wire:model="sku" :label="__('SKU / Artikelnummer')" />
                            
                            <flux:select wire:model="category_id" :label="__('Categorie')">
                                <option value="">{{ __('Geen categorie') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </flux:select>
                        </div>

                        <flux:textarea wire:model="description" :label="__('Beschrijving')" rows="5" />
                    </div>
                </div>
            </flux:card>

            <flux:card>
                <flux:heading size="lg" class="mb-6">{{ __('Voorraad & Prijs') }}</flux:heading>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                    <flux:input type="number" step="0.01" wire:model="price" :label="__('Verkoopprijs (€)')" icon="currency-euro" />
                    <flux:input type="number" wire:model="stock" :label="__('Huidige Voorraad')" icon="archive-box" />
                </div>
            </flux:card>

            <div class="flex items-center justify-end gap-4 pt-4">
                <flux:button :href="route('tenant.products.manage', ['tenant' => request()->route('tenant')])" variant="ghost" class="px-8">{{ __('Annuleren') }}</flux:button>
                <flux:button type="submit" variant="primary" class="px-10 h-12 text-lg">{{ __('Wijzigingen Opslaan') }}</flux:button>
            </div>
        </div>
    </form>
</div>
