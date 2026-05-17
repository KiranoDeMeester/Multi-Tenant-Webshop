<div class="p-6 max-w-2xl mx-auto">
    <div class="mb-6">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item :href="route('tenant.categories.index', ['tenant' => request()->route('tenant')])">{{ __('Categorieën') }}</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ __('Categorie Bewerken') }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="mt-4">
            <flux:heading size="xl" level="1">{{ __('Categorie Bewerken: :name', ['name' => $category->name]) }}</flux:heading>
            <flux:text>{{ __('Pas de details van deze categorie aan.') }}</flux:text>
        </div>
    </div>

    <form wire:submit="save">
        <flux:card class="space-y-6">
            <flux:input wire:model="name" :label="__('Categorienaam')" />
            
            <flux:textarea wire:model="description" :label="__('Beschrijving')" rows="4" />

            <div>
                <flux:label>{{ __('Categorie Afbeelding') }}</flux:label>
                <div class="mt-2 flex items-center gap-4">
                    @if ($image)
                        <div class="w-20 h-20 rounded-xl overflow-hidden border border-neutral-200 shrink-0">
                            <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-cover">
                        </div>
                    @elseif ($image_url)
                        <div class="w-20 h-20 rounded-xl overflow-hidden border border-neutral-200 shrink-0 relative group">
                            <img src="{{ $image_url }}" class="w-full h-full object-cover">
                            <button type="button" wire:click="deleteImage" class="absolute inset-0 bg-black/50 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <flux:icon name="trash" class="size-5" />
                            </button>
                        </div>
                    @endif
                    
                    <div class="flex-1">
                        <input type="file" wire:model="image" id="image" class="hidden" accept="image/*">
                        <label for="image" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg border border-neutral-200 bg-white text-neutral-800 hover:bg-neutral-50 cursor-pointer shadow-sm dark:bg-zinc-800 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-700 transition-colors">
                            <flux:icon name="photo" class="size-4" />
                            {{ __('Kies Afbeelding') }}
                        </label>
                        <div wire:loading wire:target="image" class="text-sm text-neutral-500 ml-2 inline-block">
                            {{ __('Uploaden...') }}
                        </div>
                        @error('image') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-zinc-100 dark:border-zinc-800 space-y-6">
                <flux:heading size="lg">{{ __('SEO Instellingen') }}</flux:heading>
                <flux:text size="sm">{{ __('Optimaliseer hoe deze categorie verschijnt in zoekmachines.') }}</flux:text>
                
                <flux:input wire:model="meta_title" :label="__('Meta Title')" :placeholder="$name" />
                <flux:textarea wire:model="meta_description" :label="__('Meta Description')" rows="3" :placeholder="__('Korte samenvatting voor zoekresultaten...')" />
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-zinc-100 dark:border-zinc-800">
                <flux:button :href="route('tenant.categories.index', ['tenant' => request()->route('tenant')])" variant="ghost">{{ __('Annuleren') }}</flux:button>
                <flux:button type="submit" variant="primary">{{ __('Wijzigingen Opslaan') }}</flux:button>
            </div>
        </flux:card>
    </form>
</div>
