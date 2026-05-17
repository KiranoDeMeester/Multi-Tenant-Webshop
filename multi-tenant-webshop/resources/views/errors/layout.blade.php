<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    @php
        $tenantManager = app(\App\Services\TenantManager::class);
        $currentTenant = $tenantManager->getTenant();
        $theme = $tenantManager->getThemeSettings();

        $primaryColor = $theme['theme_primary_color'] ?? '#4f46e5';
        $secondaryColor = $theme['theme_secondary_color'] ?? '#10b981';
        $accentColor = $theme['theme_accent_color'] ?? '#f59e0b';
        $fontFamily = $theme['theme_font_family'] ?? 'Inter';
        $layoutType = $theme['layout_type'] ?? 'modern';
        
        $title = $title ?? __('Foutmelding');
        $shopName = $currentTenant ? $currentTenant->name : __('Kirano Webshops');
    @endphp

    <title>@yield('title') - {{ $shopName }}</title>

    <!-- Dynamic Theme Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family={{ str_replace(' ', '+', $fontFamily) }}:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --primary-color: {{ $primaryColor }};
            --secondary-color: {{ $secondaryColor }};
            --accent-color: {{ $accentColor }};
            --font-family: '{{ $fontFamily }}', sans-serif;
            --font-sans: '{{ $fontFamily }}', sans-serif !important;
        }

        body {
            font-family: var(--font-family) !important;
        }

        /* Dynamic primary/secondary text and backgrounds overrides */
        .text-primary { color: var(--primary-color) !important; }
        .bg-primary { background-color: var(--primary-color) !important; }
        .border-primary { border-color: var(--primary-color) !important; }
        .text-secondary { color: var(--secondary-color) !important; }
        .bg-secondary { background-color: var(--secondary-color) !important; }
        .border-secondary { border-color: var(--secondary-color) !important; }

        .hover-bg-primary:hover {
            background-color: var(--primary-color) !important;
            filter: brightness(0.95);
        }
        
        /* Premium Blob Animation */
        @keyframes float-blob {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.95); }
        }
        .animate-blob {
            animation: float-blob 10s infinite ease-in-out;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>
</head>
<body class="bg-neutral-50 text-neutral-900 min-h-screen flex flex-col justify-between selection:bg-primary/20 selection:text-primary">
    
    <!-- Decorative Ambient Blobs (Tenant Primary & Secondary Colors) -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full bg-primary/5 blur-3xl animate-blob"></div>
        <div class="absolute top-1/2 left-1/3 w-80 h-80 rounded-full bg-secondary/5 blur-3xl animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-20 -left-20 w-96 h-96 rounded-full bg-accent/5 blur-3xl animate-blob animation-delay-4000"></div>
    </div>

    <!-- Header Navigation -->
    <header class="relative z-10 w-full max-w-7xl mx-auto px-6 py-6 flex items-center justify-between border-b border-neutral-100/80">
        <a href="{{ $currentTenant ? route('storefront.products.index') : '/' }}" class="flex items-center gap-2">
            <span class="text-lg font-black uppercase tracking-[0.25em] text-neutral-900">
                {{ $shopName }}
            </span>
        </a>
        <a href="mailto:support@example.com" class="text-xs font-bold uppercase tracking-wider text-neutral-400 hover:text-neutral-950 transition-colors">
            {{ __('Support') }}
        </a>
    </header>

    <!-- Main Content Grid -->
    <main class="relative z-10 flex-1 flex items-center justify-center px-6 py-12">
        <div class="max-w-xl w-full text-center space-y-8">
            <!-- Asymmetric Error Card -->
            <div class="@if($layoutType === 'modern') border-2 border-neutral-950 shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] rounded-3xl @else shadow-xl border border-neutral-100 rounded-[2.5rem] @endif bg-white p-10 md:p-16 transition-all duration-500 hover:scale-[1.01]">
                
                <!-- Huge Neumorphic Error Code -->
                <div class="relative inline-block mb-6">
                    <h1 class="text-7xl md:text-8xl font-black tracking-tighter text-neutral-900 select-none">
                        @yield('code')
                    </h1>
                    <div class="absolute -bottom-2 inset-x-0 h-3 bg-secondary/20 rounded-full blur-xs"></div>
                </div>

                <div class="space-y-4">
                    <h2 class="text-xl md:text-2xl font-black uppercase tracking-tight text-neutral-900">
                        @yield('message')
                    </h2>
                    <p class="text-sm text-neutral-500 font-medium leading-relaxed max-w-md mx-auto">
                        @yield('description')
                    </p>
                </div>

                <!-- Action Button -->
                <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ $currentTenant ? route('storefront.products.index') : '/' }}" 
                       class="@if($layoutType === 'modern') bg-primary hover:bg-neutral-950 text-white border-2 border-neutral-950 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] rounded-xl @else bg-primary hover-bg-primary text-white rounded-2xl shadow-lg @endif px-8 py-3.5 text-xs font-bold uppercase tracking-wider transition-all duration-300 inline-flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                        {{ __('Terug naar Home') }}
                    </a>
                    
                    <button onclick="window.history.back()" 
                       class="@if($layoutType === 'modern') bg-white hover:bg-neutral-50 text-neutral-900 border-2 border-neutral-950 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] rounded-xl @else bg-neutral-100 hover:bg-neutral-200/80 text-neutral-700 rounded-2xl @endif px-8 py-3.5 text-xs font-bold uppercase tracking-wider transition-all duration-300 inline-flex items-center justify-center gap-2">
                        {{ __('Vorige Pagina') }}
                    </button>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 w-full max-w-7xl mx-auto px-6 py-6 text-center border-t border-neutral-100/80">
        <span class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">
            &copy; {{ date('Y') }} {{ $shopName }}. {{ __('Alle rechten voorbehouden.') }}
        </span>
    </footer>

</body>
</html>
