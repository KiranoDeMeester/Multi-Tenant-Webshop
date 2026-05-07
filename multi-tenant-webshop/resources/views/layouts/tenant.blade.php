<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')

        @php
            $tenantManager = app(\App\Services\TenantManager::class);
            $theme = $tenantManager->getThemeSettings();
            
            $primaryColor = $theme['theme_primary_color'] ?? '#4f46e5';
            $secondaryColor = $theme['theme_secondary_color'] ?? '#10b981';
            $accentColor = $theme['theme_accent_color'] ?? '#f59e0b';
            $fontFamily = $theme['theme_font_family'] ?? 'Inter';
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

            /* Custom scrollbar using primary color */
            ::-webkit-scrollbar { width: 8px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { 
                background: {{ $primaryColor }}40; 
                border-radius: 10px; 
            }
            ::-webkit-scrollbar-thumb:hover { background: {{ $primaryColor }}80; }
        </style>
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        @php
            $tenant = app(\App\Services\TenantManager::class)->getTenant();
        @endphp

        <x-tenant-sidebar :tenant="$tenant" />

        <!-- Mobile Header (Similar to platform but with tenant toggle) -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
            <flux:spacer />
            <x-desktop-user-menu :name="auth()->user()->name" />
        </flux:header>

        <flux:main>
            {{ $slot }}
        </flux:main>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
