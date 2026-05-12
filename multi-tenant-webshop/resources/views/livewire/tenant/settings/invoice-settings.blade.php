<div class="p-6 max-w-4xl mx-auto">
    <div class="mb-6">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item :href="route('tenant.dashboard')">{{ __('Dashboard') }}</flux:breadcrumbs.item>
            <flux:breadcrumbs.item :href="route('tenant.settings')">{{ __('Instellingen') }}</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ __('Factuur Instellingen') }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>

    <div class="mb-8">
        <h1 class="text-3xl font-black text-black">Factuur Instellingen</h1>
        <p class="text-zinc-600 mt-1">Configureer hoe uw facturen eruit zien voor uw klanten.</p>
    </div>

    <form wire:submit="save" class="space-y-8">
        <div class="bg-white border-2 border-black rounded-[2.5rem] p-8 shadow-[15px_15px_0px_0px_rgba(0,0,0,1)]">
            <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
                <flux:icon name="building-office" size="sm" />
                Bedrijfsgegevens
            </h2>

            <div class="space-y-6">
                <flux:field>
                    <flux:label class="font-bold text-black">Bedrijfsnaam</flux:label>
                    <flux:input wire:model="company_name" placeholder="Uw Bedrijfsnaam BV" />
                    <flux:error name="company_name" />
                </flux:field>

                <flux:field>
                    <flux:label class="font-bold text-black">Adres op factuur</flux:label>
                    <flux:textarea wire:model="address" placeholder="Straatnaam 1&#10;1234 AB Stad&#10;Land" rows="4" />
                    <flux:error name="address" />
                </flux:field>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <flux:field>
                        <flux:label class="font-bold text-black">BTW Nummer</flux:label>
                        <flux:input wire:model="vat_number" placeholder="NL000000000B01" />
                        <flux:error name="vat_number" />
                    </flux:field>

                    <flux:field>
                        <flux:label class="font-bold text-black">Contact E-mail (voor vragen over facturen)</flux:label>
                        <flux:input wire:model="email" type="email" placeholder="facturatie@uwshop.nl" />
                        <flux:error name="email" />
                    </flux:field>
                </div>
            </div>
        </div>

        <div class="bg-white border-2 border-black rounded-[2.5rem] p-8 shadow-[15px_15px_0px_0px_rgba(0,0,0,1)]">
            <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
                <flux:icon name="paint-brush" size="sm" />
                Branding & Footer
            </h2>

            <div class="space-y-6">
                <flux:field>
                    <flux:label class="font-bold text-black">Logo op Factuur</flux:label>
                    
                    <div class="flex items-center gap-6 mt-2">
                        @if ($logo)
                            <div class="w-32 h-32 border-2 border-black rounded-2xl overflow-hidden bg-zinc-50 flex items-center justify-center p-2">
                                <img src="{{ $logo->temporaryUrl() }}" class="max-h-full max-w-full object-contain">
                            </div>
                        @elseif ($current_logo)
                            <div class="w-32 h-32 border-2 border-black rounded-2xl overflow-hidden bg-zinc-50 flex items-center justify-center p-2">
                                <img src="{{ asset('storage/' . $current_logo) }}" class="max-h-full max-w-full object-contain">
                            </div>
                        @else
                            <div class="w-32 h-32 border-2 border-dashed border-zinc-300 rounded-2xl flex items-center justify-center text-zinc-400 text-xs text-center p-4">
                                Geen logo geüpload
                            </div>
                        @endif

                        <div class="flex-1">
                            <flux:input type="file" wire:model="logo" accept="image/*" />
                            <p class="text-xs text-zinc-500 mt-2">Aanbevolen: PNG of JPG, max 1MB. Horizontaal logo werkt het best.</p>
                            <flux:error name="logo" />
                        </div>
                    </div>
                </flux:field>

                <flux:field>
                    <flux:label class="font-bold text-black">Footer Tekst</flux:label>
                    <flux:input wire:model="footer_text" placeholder="Bedankt voor uw vertrouwen!" />
                    <flux:error name="footer_text" />
                </flux:field>
            </div>
        </div>

        <div class="flex justify-end gap-4">
            <flux:button type="submit" variant="primary" class="bg-black text-white px-8 py-3 rounded-full font-bold hover:scale-105 transition-transform">
                Instellingen Opslaan
            </flux:button>
        </div>

        @if (session()->has('message'))
            <flux:callout variant="success" class="mt-4">
                {{ session('message') }}
            </flux:callout>
        @endif
    </form>
</div>
