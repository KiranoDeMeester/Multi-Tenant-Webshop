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
