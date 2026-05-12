<div class="p-6 max-w-2xl mx-auto">
    <div class="mb-8">
        <flux:heading size="xl" level="1">{{ __('Winkel Instellingen') }}</flux:heading>
        <flux:text>{{ __('Beheer hier je SEO en verzendkosten.') }}</flux:text>
    </div>

    <form wire:submit="save">
        <div class="space-y-8">
            <!-- SEO Section -->
            <flux:card>
                <flux:heading size="lg" class="mb-6">{{ __('Algemene SEO') }}</flux:heading>
                <flux:text class="mb-6">{{ __('Deze instellingen worden gebruikt voor de homepage en als standaard voor andere pagina\'s.') }}</flux:text>
                
                <div class="space-y-6">
                    <flux:input wire:model="meta_title" :label="__('Standaard Meta Title')" :placeholder="__('Bijv. De Leukste Cadeauwinkel van Nederland')" />
                    <flux:textarea wire:model="meta_description" :label="__('Standaard Meta Description')" rows="3" :placeholder="__('Vertel kort wat je winkel uniek maakt...')" />
                </div>
            </flux:card>

            <!-- Shipping Section -->
            <flux:card>
                <flux:heading size="lg" class="mb-6">{{ __('Verzendinstellingen') }}</flux:heading>
                <flux:text class="mb-6">{{ __('Configureer hoe verzendkosten worden berekend in de checkout.') }}</flux:text>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                    <flux:input type="number" step="0.01" wire:model="shipping_fee" :label="__('Vaste Verzendkosten (€)')" icon="truck" />
                    <flux:input type="number" step="0.01" wire:model="free_shipping_threshold" :label="__('Gratis verzending vanaf (€)')" icon="gift" />
                </div>
                <flux:text size="xs" class="mt-4 text-zinc-500">{{ __('Stel de drempel op 0 in om altijd verzendkosten te rekenen, of de verzendkosten op 0 voor altijd gratis verzending.') }}</flux:text>
            </flux:card>

            <div class="flex items-center justify-end gap-4 pt-4">
                <flux:button type="submit" variant="primary" class="px-10 h-12 text-lg">{{ __('Instellingen Opslaan') }}</flux:button>
            </div>
        </div>
    </form>
</div>
