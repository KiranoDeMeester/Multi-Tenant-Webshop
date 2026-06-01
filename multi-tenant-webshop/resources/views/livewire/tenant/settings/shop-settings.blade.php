<div class="p-6 max-w-2xl mx-auto">
    <div class="mb-8">
        <flux:heading size="xl" level="1">{{ __('Winkel Instellingen') }}</flux:heading>
        <flux:text>{{ __('Beheer hier je SEO en verzendkosten.') }}</flux:text>
    </div>

    <form wire:submit="save">
        <div class="space-y-8">
            <!-- Pages Section -->
            <flux:card>
                <flux:heading size="lg" class="mb-6">{{ __('Winkel Informatie') }}</flux:heading>
                <flux:text class="mb-6">{{ __('Beheer hier de algemene informatie en de Over Ons pagina van je webshop.') }}</flux:text>
                
                <div class="space-y-6">
                    <flux:textarea wire:model="about_us_content" :label="__('Over Ons Inhoud')" rows="6" :placeholder="__('Vertel het verhaal van jouw webshop...')" />
                </div>
            </flux:card>

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

            <!-- Socials Section -->
            <flux:card>
                <flux:heading size="lg" class="mb-6">{{ __('Sociale Netwerken') }}</flux:heading>
                <flux:text class="mb-6">{{ __('Voeg hier de links toe naar je sociale media kanalen. Indien leeg gelaten, worden deze niet weergegeven.') }}</flux:text>
                
                <div class="space-y-6">
                    <flux:input wire:model="social_instagram" :label="__('Instagram URL')" :placeholder="__('https://instagram.com/jouwwinkel')" icon="globe-alt" />
                    <flux:input wire:model="social_tiktok" :label="__('TikTok URL')" :placeholder="__('https://tiktok.com/@jouwwinkel')" icon="globe-alt" />
                </div>
            </flux:card>

            <!-- Contact Section -->
            <flux:card>
                <flux:heading size="lg" class="mb-6">{{ __('Contactgegevens') }}</flux:heading>
                <flux:text class="mb-6">{{ __('Deze contactgegevens worden getoond op de contactpagina en in de webshop footer.') }}</flux:text>
                
                <div class="space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                        <flux:input wire:model="contact_email" type="email" :label="__('Contact E-mailadres')" icon="envelope" :placeholder="__('info@jouwwinkel.be')" />
                        <flux:input wire:model="contact_phone" :label="__('Contact Telefoonnummer')" icon="phone" :placeholder="__('+32 470 12 34 56')" />
                    </div>
                    <flux:textarea wire:model="contact_address" :label="__('Fysiek Adres')" rows="3" :placeholder="__('Winkelstraat 123&#10;1000 Brussel&#10;België')" />
                    <flux:textarea wire:model="contact_content" :label="__('Extra Contacttekst (Optioneel)')" rows="4" :placeholder="__('Heb je vragen? We zijn bereikbaar op werkdagen van 9:00 tot 17:00.')" />
                </div>
            </flux:card>

            <div class="flex items-center justify-end gap-4 pt-4">
                <flux:button type="submit" variant="primary" class="px-10 h-12 text-lg">{{ __('Instellingen Opslaan') }}</flux:button>
            </div>
        </div>
    </form>
</div>
