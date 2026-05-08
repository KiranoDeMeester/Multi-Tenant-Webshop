<div class="py-12 max-w-4xl mx-auto">
    <div class="mb-8">
        <nav class="flex items-center gap-2 text-sm font-medium mb-4">
            <a href="{{ route('storefront.account') }}" class="text-zinc-500 hover:text-black transition-colors">{{ __('Mijn Account') }}</a>
            <span class="text-zinc-300">/</span>
            <span class="text-black">{{ __('Profiel Instellingen') }}</span>
        </nav>
        
        <h1 class="text-3xl font-black mt-4 text-black">{{ __('Profiel Instellingen') }}</h1>
        <p class="text-zinc-600 mt-1">{{ __('Beheer je persoonlijke gegevens en beveiliging.') }}</p>
    </div>

    <div class="space-y-8">
        <!-- Persoonlijke Gegevens -->
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-zinc-100">
            <h2 class="text-xl font-bold mb-6 text-black">{{ __('Persoonlijke Gegevens') }}</h2>
            
            <form wire:submit="updateProfile" class="space-y-6">
                @if (session()->has('message'))
                    <flux:callout variant="success" class="mb-4">{{ session('message') }}</flux:callout>
                @endif

                <flux:field>
                    <flux:label class="text-black font-bold">{{ __('Naam') }}</flux:label>
                    <flux:input wire:model="name" input:class="!text-black !font-bold" />
                    <flux:error name="name" />
                </flux:field>

                <flux:field>
                    <flux:label class="text-black font-bold">{{ __('E-mailadres') }}</flux:label>
                    <flux:input wire:model="email" type="email" input:class="!text-black !font-bold" />
                    <flux:error name="email" />
                </flux:field>

                <div class="flex justify-end">
                    <flux:button type="submit" variant="primary">{{ __('Opslaan') }}</flux:button>
                </div>
            </form>
        </div>

        <!-- Wachtwoord Wijzigen -->
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-zinc-100">
            <h2 class="text-xl font-bold mb-6 text-black">{{ __('Wachtwoord Wijzigen') }}</h2>
            
            <form wire:submit="updatePassword" class="space-y-6">
                @if (session()->has('password_message'))
                    <flux:callout variant="success" class="mb-4">{{ session('password_message') }}</flux:callout>
                @endif

                <flux:field>
                    <flux:label class="text-black font-bold">{{ __('Nieuw Wachtwoord') }}</flux:label>
                    <flux:input wire:model="password" type="password" viewable input:class="!text-black !font-bold" />
                    <flux:error name="password" />
                </flux:field>

                <flux:field>
                    <flux:label class="text-black font-bold">{{ __('Bevestig Wachtwoord') }}</flux:label>
                    <flux:input wire:model="password_confirmation" type="password" viewable input:class="!text-black !font-bold" />
                </flux:field>

                <div class="flex justify-end">
                    <flux:button type="submit" variant="primary">{{ __('Wachtwoord Bijwerken') }}</flux:button>
                </div>
            </form>
        </div>
    </div>
</div>
