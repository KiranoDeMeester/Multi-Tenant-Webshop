<div class="p-6 max-w-4xl mx-auto">
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
                                    <option value="{{ $category->id }}" @selected($category->id == $category_id)>{{ $category->name }}</option>
                                @endforeach
                            </flux:select>
                        </div>

                        <flux:textarea wire:model="description" :label="__('Beschrijving')" rows="4" />
                    </div>
                </div>
            </flux:card>

            <!-- Pricing & Stock / Variations Section -->
            <flux:card>
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <flux:heading size="lg">{{ __('Voorraad & Variaties') }}</flux:heading>
                        <flux:text size="sm">{{ __('Beheer standaard voorraad of configureer productvarianten (maten, kleuren, etc.).') }}</flux:text>
                    </div>
                    <flux:button type="button" wire:click="toggleVariations" variant="{{ $has_variations ? 'primary' : 'outline' }}" size="sm">
                        {{ $has_variations ? __('Variaties Ingeschakeld') : __('Variaties Toevoegen') }}
                    </flux:button>
                </div>
                
                @if(!$has_variations)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                        <flux:input type="number" step="0.01" wire:model="price" :label="__('Verkoopprijs (€)')" icon="currency-euro" />
                        <flux:input type="number" wire:model="stock" :label="__('Huidige Voorraad')" icon="archive-box" />
                    </div>
                @else
                    <div class="space-y-6 animate-fade-in">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pb-4 border-b border-zinc-100 dark:border-zinc-800">
                            <flux:input type="number" step="0.01" wire:model="price" :label="__('Basis Verkoopprijs (€)')" icon="currency-euro" />
                            <div class="flex items-end">
                                <flux:button type="button" wire:click="addVariation" icon="plus" variant="outline" class="w-full">
                                    {{ __('Nieuwe Variatie Toevoegen') }}
                                </flux:button>
                            </div>
                        </div>

                        <div class="space-y-4">
                            @foreach($variations as $index => $var)
                                <div class="p-4 bg-zinc-50 dark:bg-zinc-900/60 rounded-2xl border border-zinc-200 dark:border-zinc-700 flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 flex-1 w-full">
                                        <div>
                                            <flux:label class="text-[10px] uppercase font-bold text-zinc-400">{{ __('Kenmerk') }}</flux:label>
                                            <flux:input wire:model="variations.{{ $index }}.attribute_name" placeholder="Bijv. Maat" size="sm" />
                                        </div>
                                        <div>
                                            <flux:label class="text-[10px] uppercase font-bold text-zinc-400">{{ __('Waarde') }}</flux:label>
                                            <flux:input wire:model="variations.{{ $index }}.attribute_value" placeholder="Bijv. Large" size="sm" />
                                        </div>
                                        <div>
                                            <flux:label class="text-[10px] uppercase font-bold text-zinc-400">{{ __('SKU') }}</flux:label>
                                            <flux:input wire:model="variations.{{ $index }}.sku" size="sm" />
                                        </div>
                                        <div>
                                            <flux:label class="text-[10px] uppercase font-bold text-zinc-400">{{ __('Voorraad') }}</flux:label>
                                            <flux:input type="number" wire:model="variations.{{ $index }}.stock" size="sm" />
                                        </div>
                                    </div>
                                    <button type="button" wire:click="removeVariation({{ $index }})" class="text-zinc-400 hover:text-red-500 p-2 mt-2 sm:mt-0 transition-colors">
                                        <flux:icon name="trash" size="sm" />
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </flux:card>

            <flux:card>
                <flux:heading size="lg" class="mb-6">{{ __('SEO Instellingen') }}</flux:heading>
                <flux:text class="mb-6">{{ __('Optimaliseer hoe dit product verschijnt in zoekmachines zoals Google.') }}</flux:text>
                
                <div class="space-y-6">
                    <flux:input wire:model="meta_title" :label="__('Meta Title')" :placeholder="$name" />
                    <flux:textarea wire:model="meta_description" :label="__('Meta Description')" rows="3" :placeholder="__('Korte samenvatting voor zoekresultaten...')" />
                </div>
            </flux:card>

            <div class="flex items-center justify-end gap-4 pt-4">
                <flux:button :href="route('tenant.products.manage', ['tenant' => request()->route('tenant')])" variant="ghost" class="px-8">{{ __('Annuleren') }}</flux:button>
                <flux:button type="submit" variant="primary" class="px-10 h-12 text-lg">{{ __('Wijzigingen Opslaan') }}</flux:button>
            </div>
        </div>
    </form>
</div>
