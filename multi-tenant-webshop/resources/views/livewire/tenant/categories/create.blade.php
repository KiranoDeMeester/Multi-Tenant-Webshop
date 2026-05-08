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

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-zinc-100 dark:border-zinc-800">
                <flux:button :href="route('tenant.categories.index', ['tenant' => request()->route('tenant')])" variant="ghost">{{ __('Annuleren') }}</flux:button>
                <flux:button type="submit" variant="primary">{{ __('Categorie Opslaan') }}</flux:button>
            </div>
        </flux:card>
    </form>
</div>
