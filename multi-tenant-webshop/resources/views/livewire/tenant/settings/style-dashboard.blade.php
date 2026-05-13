<div class="p-6 max-w-5xl mx-auto">
    <div class="mb-6">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item :href="route('tenant.dashboard')">{{ __('Dashboard') }}</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ __('Winkel Design') }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <div class="mt-4">
            <flux:heading size="xl" level="1">{{ __('Huisstijl & Layout') }}</flux:heading>
            <flux:text>{{ __('Pas het uiterlijk en de functionaliteit van je webshop aan.') }}</flux:text>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-4">
            <flux:badge color="green">{{ session('message') }}</flux:badge>
        </div>
    @endif

    <!-- Simple Tabs -->
    <div class="flex gap-4 border-b border-neutral-100 dark:border-neutral-800 mb-8">
        <button wire:click="$set('tab', 'design')" 
                class="pb-4 px-2 text-sm font-bold transition-all border-b-2 {{ $tab === 'design' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-neutral-500 hover:text-neutral-700' }}">
            {{ __('Kleuren & Fonts') }}
        </button>
        <button wire:click="$set('tab', 'layout')" 
                class="pb-4 px-2 text-sm font-bold transition-all border-b-2 {{ $tab === 'layout' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-neutral-500 hover:text-neutral-700' }}">
            {{ __('Layout & Functies') }}
        </button>
        <a href="{{ route('tenant.settings.invoice') }}" 
                class="pb-4 px-2 text-sm font-bold transition-all border-b-2 border-transparent text-neutral-500 hover:text-neutral-700">
            {{ __('Factuur') }}
        </a>
        <a href="{{ route('tenant.settings.compliance') }}" 
                class="pb-4 px-2 text-sm font-bold transition-all border-b-2 border-transparent text-neutral-500 hover:text-neutral-700">
            {{ __('GDPR') }}
        </a>
    </div>

    <form wire:submit="save">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Settings Column -->
            <div class="lg:col-span-2 space-y-6">
                
                @if($tab === 'design')
                    <div class="space-y-6">
                        <flux:card class="space-y-6">
                            <div>
                                <flux:label>{{ __('Primaire Kleur') }}</flux:label>
                                <div class="mt-2 flex items-center gap-3">
                                    <input type="color" wire:model.live="primary_color" class="h-10 w-20 rounded border border-neutral-200 cursor-pointer">
                                    <flux:input wire:model="primary_color" placeholder="#000000" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                            </div>

                            <flux:select wire:model="font_family" :label="__('Lettertype')">
                                <option value="Inter">Inter (Standaard)</option>
                                <option value="Roboto">Roboto</option>
                                <option value="Outfit">Outfit</option>
                                <option value="Playfair Display">Playfair Display (Serif)</option>
                                <option value="Montserrat">Montserrat</option>
                            </flux:select>
                        </flux:card>
                    </div>
                @endif

                @if($tab === 'layout')
                    <div class="space-y-6">
                        <flux:card class="space-y-6">
                            <div>
                                <flux:label>{{ __('Webshop Layout') }}</flux:label>
                                <div class="grid grid-cols-2 gap-4 mt-2">
                                    <label class="relative flex flex-col items-center gap-2 p-4 border-2 rounded-xl cursor-pointer transition-all {{ $layout_type === 'modern' ? 'border-indigo-600 bg-indigo-50/50' : 'border-neutral-200 hover:border-neutral-300' }}">
                                        <input type="radio" wire:model.live="layout_type" value="modern" class="sr-only">
                                        <flux:icon name="rectangle-group" class="h-8 w-8 {{ $layout_type === 'modern' ? 'text-indigo-600' : 'text-neutral-400' }}" />
                                        <span class="font-bold {{ $layout_type === 'modern' ? 'text-indigo-900' : 'text-neutral-700' }}">{{ __('Modern') }}</span>
                                        <span class="text-xs text-center text-neutral-500">{{ __('Veel ruimte, grote afbeeldingen.') }}</span>
                                    </label>

                                    <label class="relative flex flex-col items-center gap-2 p-4 border-2 rounded-xl cursor-pointer transition-all {{ $layout_type === 'minimal' ? 'border-indigo-600 bg-indigo-50/50' : 'border-neutral-200 hover:border-neutral-300' }}">
                                        <input type="radio" wire:model.live="layout_type" value="minimal" class="sr-only">
                                        <flux:icon name="queue-list" class="h-8 w-8 {{ $layout_type === 'minimal' ? 'text-indigo-600' : 'text-neutral-400' }}" />
                                        <span class="font-bold {{ $layout_type === 'minimal' ? 'text-indigo-900' : 'text-neutral-700' }}">{{ __('Minimal') }}</span>
                                        <span class="text-xs text-center text-neutral-500">{{ __('Compact, focus op producten.') }}</span>
                                    </label>
                                </div>
                            </div>

                            <div class="space-y-4 pt-4 border-t border-neutral-100 dark:border-neutral-800">
                                <flux:heading size="md">{{ __('Extra Functies') }}</flux:heading>
                                
                                <flux:switch wire:model="show_hero_banner" :label="__('Toon Hero Banner op homepagina')" />
                                <flux:switch wire:model="show_newsletter_popup" :label="__('Toon Nieuwsbrief Popup')" />
                            </div>
                        </flux:card>
                    </div>
                @endif

                <div class="flex items-center justify-end gap-3">
                    <flux:button type="submit" variant="primary" class="px-8">{{ __('Opslaan & Publiceren') }}</flux:button>
                </div>
            </div>

            <!-- Preview Column -->
            <div class="space-y-6">
                <flux:heading size="lg">{{ __('Voorbeeld') }}</flux:heading>
                
                <div class="sticky top-6 rounded-2xl border border-neutral-200 p-6 bg-white dark:bg-neutral-900 shadow-xl overflow-hidden" 
                     style="font-family: '{{ $font_family }}', sans-serif;">
                    
                    <!-- Mini Browser UI -->
                    <div class="flex items-center gap-1.5 mb-4 px-1">
                        <div class="h-2 w-2 rounded-full bg-red-400"></div>
                        <div class="h-2 w-2 rounded-full bg-amber-400"></div>
                        <div class="h-2 w-2 rounded-full bg-green-400"></div>
                        <div class="ml-2 h-3 w-32 rounded bg-neutral-100 dark:bg-neutral-800"></div>
                    </div>

                    <div class="space-y-4">
                        <!-- Hero Preview -->
                        @if($show_hero_banner)
                            <div class="relative h-24 rounded-lg flex items-center justify-center overflow-hidden" style="background-color: {{ $primary_color }}20">
                                <div class="text-center">
                                    <div class="text-[10px] font-bold uppercase tracking-wider mb-1" style="color: {{ $primary_color }}">{{ __('Nieuwe Collectie') }}</div>
                                    <div class="text-xs font-bold" style="color: {{ $primary_color }}">{{ __('Ontdek onze shop') }}</div>
                                </div>
                                <div class="absolute bottom-1 right-1 h-8 w-8 rounded-full flex items-center justify-center text-white" style="background-color: {{ $accent_color }}">
                                    <flux:icon name="shopping-cart" size="xs" />
                                </div>
                            </div>
                        @endif

                        <div class="{{ $layout_type === 'minimal' ? 'space-y-2' : 'grid grid-cols-2 gap-2' }}">
                            <div class="aspect-square rounded-lg bg-neutral-50 dark:bg-neutral-800 border border-neutral-100 flex flex-col p-2">
                                <div class="flex-1 bg-neutral-200 dark:bg-neutral-700 rounded mb-2"></div>
                                <div class="h-1.5 w-full bg-neutral-300 dark:bg-neutral-600 rounded mb-1"></div>
                                <div class="h-1.5 w-1/2 bg-neutral-400 dark:bg-neutral-500 rounded"></div>
                            </div>
                            <div class="aspect-square rounded-lg bg-neutral-50 dark:bg-neutral-800 border border-neutral-100 flex flex-col p-2">
                                <div class="flex-1 bg-neutral-200 dark:bg-neutral-700 rounded mb-2"></div>
                                <div class="h-1.5 w-full bg-neutral-300 dark:bg-neutral-600 rounded mb-1"></div>
                                <div class="h-1.5 w-1/2 bg-neutral-400 dark:bg-neutral-500 rounded"></div>
                            </div>
                        </div>

                        @if($show_newsletter_popup)
                            <div class="p-3 rounded-lg border-2 border-dashed border-indigo-200 bg-indigo-50/50 flex items-center justify-between">
                                <span class="text-[10px] text-indigo-700 font-medium">{{ __('Newsletter Popup Active') }}</span>
                                <flux:icon name="envelope" size="xs" class="text-indigo-600" />
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
