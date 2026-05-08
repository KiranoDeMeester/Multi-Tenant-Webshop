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
    <body class="min-h-screen bg-white font-sans antialiased text-black is-storefront">
        
        @if(auth('tenant')->check())
            <div class="bg-black py-3 px-4 border-b-4 border-indigo-600 sticky top-0 z-[70] high-contrast-dark">
                <div class="max-w-7xl mx-auto flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-2.5 h-2.5 bg-green-400 rounded-full animate-pulse"></div>
                        <span class="text-xs font-black tracking-[0.2em] uppercase">{{ __('Admin Modus Actief') }}</span>
                    </div>
                    <div class="flex items-center gap-6">
                        <span class="text-xs font-bold text-neutral-400 hidden sm:block uppercase tracking-wider">{{ __('Beheermodus') }}</span>
                        <a href="{{ route('tenant.dashboard') }}" class="bg-white !text-black px-4 py-1.5 rounded-lg text-xs font-black hover:bg-neutral-200 transition-colors uppercase tracking-widest">
                            {{ __('Dashboard') }}
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Navbar -->
        <header class="bg-white border-b-2 border-black sticky {{ auth('tenant')->check() ? 'top-[48px]' : 'top-0' }} z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <div class="flex items-center gap-12">
                        <a href="/" class="text-3xl font-black tracking-tighter text-black uppercase">
                            {{ app(\App\Services\TenantManager::class)->getTenant()->name }}
                        </a>
                        
                        <nav class="hidden md:flex items-center gap-8">
                            <a href="{{ route('storefront.products.index') }}" class="text-xs font-black uppercase tracking-[0.2em] hover:text-indigo-600 transition-colors">{{ __('Producten') }}</a>
                            <a href="#" class="text-xs font-black uppercase tracking-[0.2em] hover:text-indigo-600 transition-colors">{{ __('Collecties') }}</a>
                            <a href="#" class="text-xs font-black uppercase tracking-[0.2em] hover:text-indigo-600 transition-colors">{{ __('Over ons') }}</a>
                        </nav>
                    </div>

                    <div class="flex items-center gap-6">
                        <button class="p-2 text-black hover:scale-110 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                        </button>
                        
                        <livewire:storefront.navigation.cart-button />
                        
                        <div class="h-8 w-0.5 bg-black"></div>

                        <livewire:storefront.navigation.user-dropdown />
                    </div>
                </div>
            </div>
        </header>

        <livewire:storefront.cart.side-panel />

        <!-- Dynamic Layout Container -->
        <main class="py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-black py-20 mt-20 high-contrast-dark">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-16">
                <div class="col-span-1 md:col-span-2">
                    <div class="text-3xl font-black mb-6 uppercase tracking-tighter">
                        {{ app(\App\Services\TenantManager::class)->getTenant()->name }}
                    </div>
                    <p class="text-neutral-400 max-w-sm font-medium leading-relaxed">
                        De beste producten, speciaal voor jou geselecteerd met oog voor kwaliteit en design. Ervaar de nieuwe standaard in online shoppen.
                    </p>
                </div>
                <div>
                    <h4 class="text-xs font-black uppercase tracking-[0.3em] mb-8 text-neutral-500">{{ __('Support') }}</h4>
                    <ul class="space-y-4 text-sm font-bold">
                        <li><a href="#" class="hover:text-indigo-400 transition-colors uppercase tracking-widest">{{ __('Contact') }}</a></li>
                        <li><a href="#" class="hover:text-indigo-400 transition-colors uppercase tracking-widest">{{ __('Verzending') }}</a></li>
                        <li><a href="#" class="hover:text-indigo-400 transition-colors uppercase tracking-widest">{{ __('Retourneren') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-black uppercase tracking-[0.3em] mb-8 text-neutral-500">{{ __('Connect') }}</h4>
                    <div class="flex gap-6">
                        <a href="#" class="text-white hover:text-indigo-400 transition-colors font-black uppercase tracking-widest">{{ __('Instagram') }}</a>
                        <a href="#" class="text-white hover:text-indigo-400 transition-colors font-black uppercase tracking-widest">{{ __('Tiktok') }}</a>
                    </div>
                </div>
            </div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-20 pt-10 border-t border-neutral-900 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="text-[10px] font-black uppercase tracking-[0.3em] text-neutral-600">
                    &copy; {{ date('Y') }} {{ app(\App\Services\TenantManager::class)->getTenant()->name }}. Powered by Kirano Platform.
                </div>
                <div class="flex gap-8 text-[10px] font-black uppercase tracking-[0.3em] text-neutral-600">
                    <a href="#">Privacy</a>
                    <a href="#">Terms</a>
                    <a href="#">Cookies</a>
                </div>
            </div>
        </footer>

        @fluxScripts
    </body>
</html>
