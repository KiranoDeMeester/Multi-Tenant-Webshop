<div class="p-6 max-w-2xl mx-auto">
    <div class="mb-6">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item :href="route('tenant.products.manage', ['tenant' => request()->route('tenant')])">{{ __('Producten') }}</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ __('Nieuw Product') }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="mt-4">
            <flux:heading size="xl" level="1">{{ __('Nieuw Product Toevoegen') }}</flux:heading>
            <flux:text>{{ __('Vul de details in om een nieuw product aan je assortiment toe te voegen.') }}</flux:text>
        </div>
    </div>

    <form wire:submit="save">
        <div class="space-y-8">
            <flux:card>
                <div class="grid grid-cols-1 md:grid-cols-12 gap-10">
                    <!-- Image Upload Section -->
                    <div class="md:col-span-4">
                        <flux:field :label="__('Productafbeelding')">
                            <flux:text size="xs" class="mb-3">{{ __('Sleep een afbeelding hierheen of klik om te uploaden (max 2MB).') }}</flux:text>
                            
                            <div class="relative group cursor-pointer aspect-square bg-zinc-50 dark:bg-zinc-900 border-2 border-dashed border-zinc-200 dark:border-zinc-800 rounded-3xl flex items-center justify-center overflow-hidden transition-all duration-300 hover:border-indigo-400 hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10">
                                @if ($image)
                                    <img src="{{ $image->temporaryUrl() }}" class="absolute inset-0 w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <flux:icon name="pencil-square" size="lg" class="text-white" />
                                    </div>
                                @else
                                    <div class="text-center p-6 transition-transform duration-300 group-hover:scale-110">
                                        <div class="w-16 h-16 bg-white dark:bg-zinc-800 shadow-sm rounded-2xl flex items-center justify-center mx-auto mb-4">
                                            <flux:icon name="photo" size="xl" class="text-zinc-400" />
                                        </div>
                                        <flux:text size="sm" class="font-medium">{{ __('Afbeelding kiezen') }}</flux:text>
                                    </div>
                                @endif
                                <input type="file" wire:model="image" class="absolute inset-0 opacity-0 cursor-pointer">
                            </div>
                            <flux:error name="image" />
                        </flux:field>
                    </div>

                    <!-- Primary Info Section -->
                    <div class="md:col-span-8 space-y-6">
                        <flux:input wire:model="name" :label="__('Productnaam')" placeholder="Bijv. Draadloze Koptelefoon" class="text-lg font-bold" />
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <flux:input wire:model="sku" :label="__('SKU / Artikelnummer')" placeholder="Bijv. DK-001" />
                            
                            <flux:select wire:model="category_id" :label="__('Categorie')">
                                <option value="">{{ __('Geen categorie') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </flux:select>
                        </div>

                        <flux:textarea wire:model="description" :label="__('Beschrijving')" rows="5" placeholder="{{ __('Vertel iets meer over dit product...') }}" />
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

            <flux:card>
                <flux:heading size="lg" class="mb-6">{{ __('SEO Instellingen') }}</flux:heading>
                <flux:text class="mb-6">{{ __('Optimaliseer hoe dit product verschijnt in zoekmachines zoals Google.') }}</flux:text>
                
                <div class="space-y-6">
                    <flux:input wire:model="meta_title" :label="__('Meta Title')" :placeholder="$name ?: __('Kies een titel...')" />
                    <flux:textarea wire:model="meta_description" :label="__('Meta Description')" rows="3" :placeholder="__('Korte samenvatting voor zoekresultaten...')" />
                </div>
            </flux:card>

            <div class="flex items-center justify-end gap-4 pt-4">
                <flux:button :href="route('tenant.products.manage', ['tenant' => request()->route('tenant')])" variant="ghost" class="px-8">{{ __('Annuleren') }}</flux:button>
                <flux:button type="submit" variant="primary" class="px-10 h-12 text-lg">{{ __('Product Opslaan') }}</flux:button>
            </div>
        </div>
    </form>
</div>
