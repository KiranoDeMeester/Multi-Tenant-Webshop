<div class="py-16 max-w-md mx-auto px-4 sm:px-6">
    <div class="bg-white p-8 sm:p-10 rounded-[2.5rem] border-2 border-zinc-100 shadow-sm space-y-6">
        <div class="text-center space-y-2">
            <h1 class="text-3xl font-black text-black tracking-tight">{{ __('Wachtwoord Vergeten') }}</h1>
            <p class="text-sm text-zinc-500">{{ __('Vul je e-mailadres in en we sturen je een link om een nieuw wachtwoord in te stellen.') }}</p>
        </div>

        @if($sent)
            <div class="bg-emerald-50 border border-emerald-200 p-4 rounded-2xl text-center space-y-3">
                <div class="w-10 h-10 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center mx-auto">
                    <flux:icon name="check" class="w-5 h-5" />
                </div>
                <p class="text-sm font-bold text-emerald-900">
                    {{ __('Als dit e-mailadres bij ons bekend is, hebben we zojuist een herstellink verzonden.') }}
                </p>
                <p class="text-xs text-emerald-700">
                    {{ __('Controleer ook je spamfolder.') }}
                </p>
            </div>

            <div class="text-center pt-2">
                <a href="{{ route('storefront.login') }}" wire:navigate class="text-sm font-bold text-black hover:underline">
                    {{ __('Terug naar inloggen') }}
                </a>
            </div>
        @else
            <form wire:submit="sendResetLink" class="space-y-4">
                <flux:field>
                    <flux:label class="text-black font-bold text-xs uppercase tracking-wider">{{ __('E-mailadres') }}</flux:label>
                    <flux:input wire:model="email" type="email" placeholder="naam@voorbeeld.nl" required />
                    <flux:error name="email" />
                </flux:field>

                <flux:button type="submit" variant="primary" class="w-full h-12 bg-black hover:bg-zinc-800 text-white font-bold rounded-2xl">
                    {{ __('Stuur Herstellink') }}
                </flux:button>
            </form>

            <div class="text-center pt-4 border-t border-zinc-100">
                <a href="{{ route('storefront.login') }}" wire:navigate class="text-sm font-bold text-zinc-500 hover:text-black transition-colors">
                    {{ __('Weet je je wachtwoord weer? Inloggen') }}
                </a>
            </div>
        @endif
    </div>
</div>
