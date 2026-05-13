<div class="p-6 max-w-5xl mx-auto">
    <div class="mb-6">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item :href="route('tenant.dashboard')">{{ __('Dashboard') }}</flux:breadcrumbs.item>
            <flux:breadcrumbs.item :href="route('tenant.settings')">{{ __('Instellingen') }}</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ __('GDPR & Compliance') }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="mt-4 flex items-center justify-between">
            <div>
                <flux:heading size="xl" level="1">{{ __('GDPR & Compliance') }}</flux:heading>
                <flux:text>{{ __('Beheer je cookiebanner en juridische documenten.') }}</flux:text>
            </div>
            <div class="flex items-center gap-3">
                 <flux:button wire:click="generateTemplate" variant="subtle" icon="document-duplicate">
                    {{ __('Genereer Privacy Policy') }}
                </flux:button>
            </div>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-4">
            <flux:badge color="green">{{ session('message') }}</flux:badge>
        </div>
    @endif

    <form wire:submit="save">
        <div class="grid grid-cols-1 gap-8">
            <div class="space-y-6">
                <flux:card class="space-y-6">
                    <flux:heading size="lg">{{ __('Cookie Banner') }}</flux:heading>
                    
                    <flux:switch wire:model="cookie_banner_enabled" :label="__('Cookie banner inschakelen')" 
                        description="Toon een banner aan nieuwe bezoekers om toestemming te vragen voor cookies." />

                    @if($cookie_banner_enabled)
                        <flux:textarea 
                            wire:model="cookie_banner_text" 
                            :label="__('Banner Tekst')" 
                            rows="3" />
                    @endif
                </flux:card>

                <flux:card class="space-y-6">
                    <div class="flex items-center justify-between">
                        <flux:heading size="lg">{{ __('Privacy Policy') }}</flux:heading>
                    </div>
                    
                    <flux:textarea 
                        wire:model="privacy_policy_content" 
                        :label="__('Inhoud Privacy Policy')" 
                        description="Deze tekst wordt getoond op de Privacy Policy pagina van je webshop."
                        rows="15" />
                </flux:card>

                <div class="flex items-center justify-end gap-3">
                    <flux:button type="submit" variant="primary" class="px-8">{{ __('Instellingen Opslaan') }}</flux:button>
                </div>
            </div>
        </div>
    </form>
</div>
