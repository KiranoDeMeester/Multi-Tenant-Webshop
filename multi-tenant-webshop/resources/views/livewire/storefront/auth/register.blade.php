<div class="py-12">
    <div class="max-w-md mx-auto mb-6 px-4 animate-fade-in">
        <flux:link href="{{ route('storefront.products.index') }}" icon="arrow-left" class="text-neutral-500 hover:text-neutral-900 transition-colors">
            {{ __('Terug naar website') }}
        </flux:link>
    </div>

    <div class="max-w-md mx-auto bg-white rounded-3xl border border-neutral-100 shadow-xl overflow-hidden">
        <div class="p-8 bg-neutral-900 text-white text-center relative overflow-hidden">
            <div class="relative z-10">
                <flux:heading size="xl" class="text-white mb-2">{{ __('Nieuw Account') }}</flux:heading>
                <flux:text class="text-neutral-400">{{ __('Maak een klantaccount aan bij :name', ['name' => app(\App\Services\TenantManager::class)->getTenant()->name]) }}</flux:text>
            </div>
            <!-- Decorative gradient -->
            <div class="absolute inset-0 bg-gradient-to-br from-primary/20 to-transparent"></div>
        </div>

        <form wire:submit="register" class="p-8 space-y-5">
            <flux:field>
                <flux:label class="text-black font-bold">{{ __('Volledige Naam') }}</flux:label>
                <flux:input wire:model="name" type="text" placeholder="Jan Jansen" icon="user" input:class="!text-black !font-bold" required />
                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:label class="text-black font-bold">{{ __('E-mailadres') }}</flux:label>
                <flux:input wire:model="email" type="email" placeholder="jan@voorbeeld.be" icon="envelope" input:class="!text-black !font-bold" required />
                <flux:error name="email" />
            </flux:field>

            <flux:field>
                <flux:label class="text-black font-bold">{{ __('Telefoonnummer (optioneel)') }}</flux:label>
                <flux:input wire:model="phone" type="tel" placeholder="+32 470 12 34 56" icon="phone" input:class="!text-black !font-bold" />
                <flux:error name="phone" />
            </flux:field>

            <flux:field>
                <flux:label class="text-black font-bold">{{ __('Wachtwoord') }}</flux:label>
                <flux:input wire:model="password" type="password" placeholder="Minimaal 8 tekens" icon="lock-closed" input:class="!text-black !font-bold" viewable required />
                <flux:error name="password" />
            </flux:field>

            <flux:field>
                <flux:label class="text-black font-bold">{{ __('Wachtwoord Bevestigen') }}</flux:label>
                <flux:input wire:model="password_confirmation" type="password" placeholder="Herhaal wachtwoord" icon="lock-closed" input:class="!text-black !font-bold" viewable required />
            </flux:field>

            <flux:button type="submit" variant="primary" class="w-full h-12 bg-primary hover:bg-primary/95 border-none mt-2 font-bold">{{ __('Account Aanmaken') }}</flux:button>

            <div class="pt-6 border-t border-neutral-100 text-center space-y-4">
                <flux:text size="sm">
                    {{ __('Heb je al een account?') }}
                    <flux:link :href="route('storefront.login')" class="font-bold ml-1">{{ __('Log hier in') }}</flux:link>
                </flux:text>
            </div>
        </form>
    </div>
</div>
