<div class="p-6 max-w-4xl mx-auto">
    <div class="mb-6">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item :href="route('tenant.dashboard')">{{ __('Dashboard') }}</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ __('Huisstijl & Design') }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="mt-4">
            <flux:heading size="xl" level="1">{{ __('Huisstijl & Design') }}</flux:heading>
            <flux:text>{{ __('Pas de kleuren en het uiterlijk van je webshop aan.') }}</flux:text>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-4">
            <flux:badge color="green">{{ session('message') }}</flux:badge>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Settings Form -->
        <form wire:submit="save" class="space-y-6">
            <flux:card class="space-y-6">
                <div>
                    <flux:label>{{ __('Primaire Kleur') }}</flux:label>
                    <div class="mt-2 flex items-center gap-3">
                        <input type="color" wire:model.live="primary_color" class="h-10 w-20 rounded border border-neutral-200 cursor-pointer">
                        <flux:input wire:model="primary_color" placeholder="#000000" />
                    </div>
                    <flux:text size="sm" class="mt-2">{{ __('Wordt gebruikt voor knoppen, links en hoofdaccenten.') }}</flux:text>
                </div>

                <div>
                    <flux:label>{{ __('Secundaire Kleur') }}</flux:label>
                    <div class="mt-2 flex items-center gap-3">
                        <input type="color" wire:model.live="secondary_color" class="h-10 w-20 rounded border border-neutral-200 cursor-pointer">
                        <flux:input wire:model="secondary_color" placeholder="#000000" />
                    </div>
                </div>

                <div>
                    <flux:label>{{ __('Accent Kleur') }}</flux:label>
                    <div class="mt-2 flex items-center gap-3">
                        <input type="color" wire:model.live="accent_color" class="h-10 w-20 rounded border border-neutral-200 cursor-pointer">
                        <flux:input wire:model="accent_color" placeholder="#000000" />
                    </div>
                </div>

                <flux:select wire:model="font_family" :label="__('Lettertype')">
                    <option value="Inter">Inter (Standaard)</option>
                    <option value="Roboto">Roboto</option>
                    <option value="Outfit">Outfit</option>
                    <option value="Playfair Display">Playfair Display (Serif)</option>
                    <option value="Montserrat">Montserrat</option>
                </flux:select>

                <div class="pt-4 border-t border-neutral-100 dark:border-neutral-800">
                    <flux:button type="submit" variant="primary" class="w-full">{{ __('Opslaan & Toepassen') }}</flux:button>
                </div>
            </flux:card>
        </form>

        <!-- Live Preview -->
        <div class="space-y-6">
            <flux:heading size="lg">{{ __('Live Voorbeeld') }}</flux:heading>
            
            <div class="rounded-2xl border border-neutral-200 p-8 bg-white dark:bg-neutral-900 shadow-sm space-y-6" 
                 style="font-family: '{{ $font_family }}', sans-serif;">
                
                <div class="space-y-2">
                    <h3 class="text-xl font-bold" style="color: {{ $primary_color }}">{{ __('Jouw Webshop Titel') }}</h3>
                    <p class="text-neutral-600 dark:text-neutral-400">
                        Dit is hoe je teksten en kleuren eruit zullen zien voor je klanten.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <button class="px-4 py-2 rounded-lg text-white font-medium shadow-sm transition-all" 
                            style="background-color: {{ $primary_color }}">
                        {{ __('Primaire Knop') }}
                    </button>
                    
                    <button class="px-4 py-2 rounded-lg text-white font-medium shadow-sm transition-all" 
                            style="background-color: {{ $secondary_color }}">
                        {{ __('Secundaire Actie') }}
                    </button>

                    <div class="px-3 py-1 rounded-full text-xs font-bold" 
                         style="background-color: {{ $accent_color }}20; color: {{ $accent_color }}">
                        {{ __('Accent Badge') }}
                    </div>
                </div>

                <div class="pt-4 border-t border-neutral-100 dark:border-neutral-800 flex items-center gap-2">
                    <div class="h-2 w-2 rounded-full" style="background-color: {{ $secondary_color }}"></div>
                    <span class="text-xs text-neutral-500 uppercase tracking-wider font-bold">{{ __('Systeem Status: Online') }}</span>
                </div>
            </div>

            <flux:card class="bg-indigo-50 dark:bg-indigo-900/20 border-indigo-100">
                <div class="flex gap-3">
                    <flux:icon name="information-circle" class="text-indigo-600" />
                    <flux:text size="sm" class="text-indigo-900 dark:text-indigo-200">
                        {{ __('Wijzigingen zijn direct zichtbaar in de storefront nadat je op opslaan hebt geklikt.') }}
                    </flux:text>
                </div>
            </flux:card>
        </div>
    </div>
</div>
