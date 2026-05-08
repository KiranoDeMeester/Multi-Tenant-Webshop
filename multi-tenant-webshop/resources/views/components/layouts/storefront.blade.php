<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
    <head>
        @include('partials.head')

        @php
            $tenantManager = app(\App\Services\TenantManager::class);
            $theme = $tenantManager->getThemeSettings();
            
            $primaryColor = $theme['theme_primary_color'] ?? '#4f46e5';
            $secondaryColor = $theme['theme_secondary_color'] ?? '#10b981';
            $accentColor = $theme['theme_accent_color'] ?? '#f59e0b';
            $fontFamily = $theme['theme_font_family'] ?? 'Inter';
            
            $layoutType = $theme['layout_type'] ?? 'modern';
        @endphp

        <!-- Dynamic Theme Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family={{ str_replace(' ', '+', $fontFamily) }}:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <style>
            :root {
                --primary-color: {{ $primaryColor }};
                --secondary-color: {{ $secondaryColor }};
                --accent-color: {{ $accentColor }};
                --font-family: '{{ $fontFamily }}', sans-serif;
            }

            body {
                font-family: var(--font-family) !important;
            }

            .btn-primary {
                background-color: var(--primary-color);
                color: white;
            }

            .text-primary { color: var(--primary-color); }
            .bg-primary { background-color: var(--primary-color); }
            .border-primary { border-color: var(--primary-color); }
        </style>
    </head>
    <body class="min-h-screen bg-neutral-50 font-sans antialiased text-neutral-900">
        
        @if(auth('tenant')->check())
            <div class="bg-indigo-600 text-white py-2 px-4 shadow-sm relative z-[60]">
                <div class="max-w-7xl mx-auto flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                        <span class="text-xs font-bold tracking-wider uppercase">{{ __('Admin Modus Actief') }}</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <flux:text size="sm" class="text-indigo-100 hidden sm:block">{{ __('Je bekijkt de shop als beheerder') }}</flux:text>
                        <flux:button variant="primary" size="xs" icon="squares-2x2" :href="route('tenant.dashboard')" class="bg-white !text-indigo-600 hover:bg-indigo-50 border-none font-bold">
                            {{ __('Dashboard') }}
                        </flux:button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Navbar -->
        <header class="bg-white border-b border-neutral-100 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center gap-8">
                        <a href="/" class="text-2xl font-black tracking-tighter" style="color: var(--primary-color)">
                            {{ app(\App\Services\TenantManager::class)->getTenant()->name }}
                        </a>
                        
                        <nav class="hidden md:flex items-center gap-6">
                            <a href="{{ route('storefront.products.index') }}" class="text-sm font-medium hover:text-primary transition-colors">{{ __('Home') }}</a>
                            <a href="{{ route('storefront.products.index') }}" class="text-sm font-medium hover:text-primary transition-colors">{{ __('Producten') }}</a>
                            <a href="#" class="text-sm font-medium hover:text-primary transition-colors">{{ __('Over ons') }}</a>
                        </nav>
                    </div>

                    <div class="flex items-center gap-4">
                        <flux:button variant="ghost" icon="magnifying-glass" size="sm" />
                        <flux:button variant="ghost" icon="shopping-bag" size="sm" />
                        
                        <div class="h-6 w-px bg-neutral-200"></div>

                        <livewire:storefront.navigation.user-dropdown />
                    </div>
                </div>
            </div>
        </header>

        <!-- Dynamic Layout Container -->
        <main class="{{ $layoutType === 'modern' ? 'py-12' : 'py-6' }}">
            <div class="{{ $layoutType === 'modern' ? 'max-w-7xl' : 'max-w-5xl' }} mx-auto px-4 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-neutral-100 py-12 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <div class="text-xl font-black mb-4" style="color: var(--primary-color)">
                        {{ app(\App\Services\TenantManager::class)->getTenant()->name }}
                    </div>
                    <p class="text-neutral-500 max-w-sm">
                        De beste producten, speciaal voor jou geselecteerd met oog voor kwaliteit en design.
                    </p>
                </div>
                <div>
                    <h4 class="font-bold mb-4">{{ __('Klantenservice') }}</h4>
                    <ul class="space-y-2 text-sm text-neutral-500">
                        <li><a href="#" class="hover:text-primary transition-colors">Contact</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors">Verzending</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors">Retourneren</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">{{ __('Socials') }}</h4>
                    <div class="flex gap-4">
                        <flux:button variant="ghost" icon="heart" size="sm" />
                        <flux:button variant="ghost" icon="share" size="sm" />
                    </div>
                </div>
            </div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pt-8 border-t border-neutral-50 text-center text-xs text-neutral-400">
                &copy; {{ date('Y') }} {{ app(\App\Services\TenantManager::class)->getTenant()->name }}. All rights reserved. Powered by Kirano Platform.
            </div>
        </footer>

        @fluxScripts
    </body>
</html>
