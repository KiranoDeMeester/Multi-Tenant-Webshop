<div class="py-12">
    @if(!$showChoiceModal)
        <div class="max-w-md mx-auto mb-6 px-4 animate-fade-in">
            <flux:link href="{{ route('storefront.products.index') }}" icon="arrow-left" class="text-neutral-500 hover:text-neutral-900 transition-colors">
                {{ __('Terug naar website') }}
            </flux:link>
        </div>
    @endif
    <div class="max-w-md mx-auto bg-white rounded-3xl border border-neutral-100 shadow-xl overflow-hidden">
        <div class="p-8 bg-neutral-900 text-white text-center relative overflow-hidden">
            <div class="relative z-10">
                <flux:heading size="xl" class="text-white mb-2">{{ __('Welkom terug!') }}</flux:heading>
                <flux:text class="text-neutral-400">{{ __('Log in op je account bij :name', ['name' => app(\App\Services\TenantManager::class)->getTenant()->name]) }}</flux:text>
            </div>
            <!-- Decorative gradient -->
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/20 to-transparent"></div>
        </div>


        @if($showChoiceModal)
            <div class="p-8 space-y-6 text-center animate-fade-in">
                <flux:heading size="lg">{{ __('Wat wil je vandaag doen?') }}</flux:heading>
                <flux:text class="mb-8">{{ __('Je bent ingelogd als beheerder. Kies je bestemming:') }}</flux:text>
                
                <div class="grid grid-cols-1 gap-4">
                    <flux:button wire:click="goToShop" variant="outline" icon="shopping-bag" class="h-16 text-lg justify-start px-6">
                        <div class="text-left">
                            <div class="font-bold">{{ __('Ik wil shoppen') }}</div>
                            <div class="text-xs text-neutral-500">{{ __('Ga naar de publieke webshop') }}</div>
                        </div>
                    </flux:button>

                    <flux:button wire:click="goToDashboard" variant="primary" icon="squares-2x2" class="h-16 text-lg justify-start px-6">
                        <div class="text-left">
                            <div class="font-bold">{{ __('Ik wil beheren') }}</div>
                            <div class="text-xs text-indigo-200">{{ __('Ga naar het merchant dashboard') }}</div>
                        </div>
                    </flux:button>
                </div>
            </div>
        @else
            <form wire:submit="login" class="p-8 space-y-6">
                <flux:field>
                    <flux:label class="text-black font-bold">{{ __('E-mailadres') }}</flux:label>
                    <flux:input wire:model="email" type="email" placeholder="je@voorbeeld.be" icon="envelope" input:class="!text-black !font-bold" />
                </flux:field>
                
                <flux:field>
                    <flux:label class="text-black font-bold">{{ __('Wachtwoord') }}</flux:label>
                    <flux:input wire:model="password" type="password" placeholder="••••••••" icon="lock-closed" input:class="!text-black !font-bold" viewable />
                </flux:field>

                <div class="flex items-center justify-between">
                    <flux:checkbox :label="__('Onthoud mij')" />
                    <flux:link href="#" class="text-sm">{{ __('Wachtwoord vergeten?') }}</flux:link>
                </div>

                <flux:button type="submit" variant="primary" class="w-full h-12 bg-indigo-600 hover:bg-indigo-700 border-none">{{ __('Inloggen') }}</flux:button>

                <div class="pt-6 border-t border-neutral-100 text-center space-y-4">
                    <flux:text size="sm">
                        {{ __('Nog geen account?') }}
                        <flux:link href="#" class="font-bold ml-1">{{ __('Registreer hier') }}</flux:link>
                    </flux:text>
                    
                    <flux:separator />
                    
                    <flux:text size="xs" class="text-neutral-400 italic">
                        {{ __('Ben je een platform beheerder?') }}
                        <flux:link :href="route('home')" class="ml-1">{{ __('Ga naar Platform Home') }}</flux:link>
                    </flux:text>
                </div>
            </form>
        @endif
    </div>
</div>
