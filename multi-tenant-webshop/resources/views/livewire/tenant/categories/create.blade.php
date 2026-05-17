<div class="p-6 max-w-2xl mx-auto">
    <div class="mb-6">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item :href="route('tenant.categories.index', ['tenant' => request()->route('tenant')])">{{ __('Categorieën') }}</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ __('Nieuwe Categorie') }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="mt-4">
            <flux:heading size="xl" level="1">{{ __('Nieuwe Categorie Toevoegen') }}</flux:heading>
            <flux:text>{{ __('Maak een nieuwe categorie aan om je producten te organiseren.') }}</flux:text>
        </div>
    </div>

    <form wire:submit="save">
        <flux:card class="space-y-6">
            <flux:input wire:model="name" :label="__('Categorienaam')" placeholder="Bijv. Wonen & Keuken" />
            
            <flux:textarea wire:model="description" :label="__('Beschrijving')" rows="4" placeholder="{{ __('Korte beschrijving van deze categorie...') }}" />

            <div>
                <flux:label>{{ __('Categorie Afbeelding') }}</flux:label>
                <div class="mt-2 flex items-center gap-4">
                    @if ($image)
                        <div class="w-20 h-20 rounded-xl overflow-hidden border border-neutral-200 shrink-0">
                            <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-cover">
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
                
                <flux:input wire:model="meta_title" :label="__('Meta Title')" :placeholder="$name ?: __('Kies een titel...')" />
                <flux:textarea wire:model="meta_description" :label="__('Meta Description')" rows="3" :placeholder="__('Korte samenvatting voor zoekresultaten...')" />
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-zinc-100 dark:border-zinc-800">
                <flux:button :href="route('tenant.categories.index', ['tenant' => request()->route('tenant')])" variant="ghost">{{ __('Annuleren') }}</flux:button>
                <flux:button type="submit" variant="primary">{{ __('Categorie Opslaan') }}</flux:button>
            </div>
        </flux:card>
    </form>
</div>
