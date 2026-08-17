<div class="py-16 max-w-md mx-auto px-4 sm:px-6">
    <div class="bg-white p-8 sm:p-10 rounded-[2.5rem] border-2 border-zinc-100 shadow-sm space-y-6">
        <div class="text-center space-y-2">
            <h1 class="text-3xl font-black text-black tracking-tight">{{ __('Nieuw Wachtwoord') }}</h1>
            <p class="text-sm text-zinc-500">{{ __('Voer je e-mailadres en een nieuw wachtwoord in om direct in te loggen.') }}</p>
        </div>

        <form wire:submit="resetPassword" class="space-y-4">
            <input type="hidden" wire:model="token" />

            <flux:field>
                <flux:label class="text-black font-bold text-xs uppercase tracking-wider">{{ __('E-mailadres') }}</flux:label>
                <flux:input wire:model="email" type="email" required />
                <flux:error name="email" />
            </flux:field>

            <flux:field>
                <flux:label class="text-black font-bold text-xs uppercase tracking-wider">{{ __('Nieuw Wachtwoord') }}</flux:label>
                <flux:input wire:model="password" type="password" placeholder="Minimaal 8 tekens" required />
                <flux:error name="password" />
            </flux:field>

            <flux:field>
                <flux:label class="text-black font-bold text-xs uppercase tracking-wider">{{ __('Bevestig Nieuw Wachtwoord') }}</flux:label>
                <flux:input wire:model="password_confirmation" type="password" placeholder="Herhaal wachtwoord" required />
                <flux:error name="password_confirmation" />
            </flux:field>

            <flux:button type="submit" variant="primary" class="w-full h-12 bg-black hover:bg-zinc-800 text-white font-bold rounded-2xl">
                {{ __('Wachtwoord Opslaan & Inloggen') }}
            </flux:button>
        </form>
    </div>
</div>
