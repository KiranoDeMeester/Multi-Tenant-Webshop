<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ShopSaaS - Creëer je eigen multi-tenant webshop</title>
        <meta name="description" content="Start vandaag nog je eigen webshop voor slechts €29 per maand. Inclusief Stripe Connect, custom layouts en analytics." />

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxAppearance
        @livewireStyles
    </head>
    <body class="bg-neutral-50 dark:bg-neutral-950 text-neutral-900 dark:text-neutral-100 antialiased min-h-screen flex flex-col">
        <!-- Navigation -->
        <header class="w-full max-w-7xl mx-auto px-6 py-5 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="h-10 w-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-extrabold text-xl shadow-lg shadow-indigo-500/20">
                    S
                </div>
                <span class="font-extrabold tracking-tight text-xl bg-gradient-to-r from-indigo-600 to-violet-500 bg-clip-text text-transparent">ShopSaaS</span>
            </div>
            
            <nav class="flex items-center gap-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-indigo-600 text-white hover:bg-indigo-500 px-5 py-2 rounded-xl text-sm font-semibold transition shadow-md shadow-indigo-500/10">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-white text-sm font-semibold transition">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="border border-neutral-300 dark:border-neutral-700 hover:bg-neutral-100 dark:hover:bg-neutral-800 px-4 py-2 rounded-xl text-sm font-semibold transition">
                                Registreren
                            </a>
                        @endif
                    @endauth
                @endif
            </nav>
        </header>

        <!-- Main Content -->
        <main class="flex-grow w-full max-w-7xl mx-auto px-6 py-12 md:py-20 space-y-24">
            
            @if (request()->has('cancelled'))
                <div class="max-w-xl mx-auto bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900 rounded-2xl p-4 text-center">
                    <p class="text-sm font-semibold text-amber-805 dark:text-amber-400">De betaling is geannuleerd. Je kunt het opnieuw proberen wanneer je er klaar voor bent.</p>
                </div>
            @endif

            <!-- Hero Section -->
            <section class="text-center max-w-3xl mx-auto space-y-6">
                <div class="inline-flex items-center gap-2 bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-900/60 px-4 py-1.5 rounded-full text-indigo-700 dark:text-indigo-400 text-xs font-semibold uppercase tracking-wider">
                    ✨ Het ultieme e-commerce platform
                </div>
                
                <h1 class="text-4xl md:text-6xl font-black tracking-tight leading-tight">
                    Start vandaag nog jouw eigen <span class="bg-gradient-to-r from-indigo-600 to-violet-500 bg-clip-text text-transparent">Online Imperium</span>
                </h1>
                
                <p class="text-lg text-neutral-600 dark:text-neutral-400 leading-relaxed">
                    Creëer binnen enkele seconden een krachtige, volledig geïsoleerde webshop. Beheer producten, personaliseer je huisstijl, en accepteer betalingen direct op je eigen rekening via Stripe Connect.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                    <a href="{{ route('landlord.subscribe') }}" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-500 text-white font-bold px-8 py-4 rounded-xl text-base transition shadow-lg shadow-indigo-500/20 hover:scale-105 active:scale-95 text-center">
                        Start direct voor €29/maand
                    </a>
                    <a href="#pricing" class="w-full sm:w-auto border border-neutral-300 dark:border-neutral-700 hover:bg-neutral-100 dark:hover:bg-neutral-800 px-8 py-4 rounded-xl text-base font-semibold transition text-center">
                        Bekijk de prijzen
                    </a>
                </div>
            </section>

            <!-- Features Grid -->
            <section class="grid md:grid-cols-3 gap-8">
                <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-2xl p-6 space-y-4 shadow-sm">
                    <div class="h-12 w-12 bg-indigo-100 dark:bg-indigo-900/40 rounded-xl flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold">Volledige Data-isolatie</h3>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400">
                        Elke webshop krijgt een eigen afgeschermde database. Jouw klantinformatie, productcatalogus en instellingen zijn 100% veilig en gescheiden van andere shops.
                    </p>
                </div>

                <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-2xl p-6 space-y-4 shadow-sm">
                    <div class="h-12 w-12 bg-indigo-100 dark:bg-indigo-900/40 rounded-xl flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold">Stripe Connect Betalingen</h3>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400">
                        Koppel eenvoudig je eigen Stripe-account met één klik. Accepteer direct betalingen zoals iDEAL, Bancontact en Creditcards direct op je eigen rekening.
                    </p>
                </div>

                <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-2xl p-6 space-y-4 shadow-sm">
                    <div class="h-12 w-12 bg-indigo-100 dark:bg-indigo-900/40 rounded-xl flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold">Volledige Styling Controle</h3>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400">
                        Pas de kleuren, lettertypes en indeling van je webshop aan naar jouw eigen huisstijl met ons dynamische thema-dashboard.
                    </p>
                </div>
            </section>

            <!-- Pricing Section -->
            <section id="pricing" class="py-12 border-t border-neutral-200 dark:border-neutral-800">
                <div class="text-center space-y-4 max-w-2xl mx-auto mb-12">
                    <h2 class="text-3xl font-extrabold tracking-tight">Eén vast en transparant tarief</h2>
                    <p class="text-neutral-500 dark:text-neutral-400">Geen ingewikkelde pakketten of verborgen kosten. Je krijgt direct toegang tot alle functies voor een vast maandelijks bedrag.</p>
                </div>

                <div class="max-w-md mx-auto bg-white dark:bg-neutral-900 rounded-3xl border-2 border-indigo-600 dark:border-indigo-500 p-8 shadow-lg relative">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-indigo-600 text-white text-xs font-bold uppercase tracking-widest px-4 py-1 rounded-full shadow-md">
                        Aanbevolen
                    </div>

                    <div class="text-center space-y-4 pb-6 border-b border-neutral-200 dark:border-neutral-800">
                        <h3 class="text-xl font-bold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">Volledig Plan</h3>
                        <div class="flex items-center justify-center">
                            <span class="text-5xl font-black tracking-tight">€29</span>
                            <span class="text-neutral-500 dark:text-neutral-400 font-semibold ml-2">/ maand</span>
                        </div>
                        <p class="text-xs text-neutral-400 font-medium">Exclusief BTW, maandelijks opzegbaar</p>
                    </div>

                    <ul class="py-8 space-y-4 text-sm text-neutral-600 dark:text-neutral-300">
                        <li class="flex items-center gap-3">
                            <svg class="h-5 w-5 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Onbeperkt aantal producten & categorieën</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="h-5 w-5 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Eigen subdomein (bijv. <code>winkel.localhost</code>)</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="h-5 w-5 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Stripe Connect betalingen (iDEAL, Bancontact, etc.)</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="h-5 w-5 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Volledige styling controle & templates</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="h-5 w-5 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Automatische PDF facturatie & BTW</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="h-5 w-5 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Real-time Sales Analytics Dashboard</span>
                        </li>
                    </ul>

                    <a href="{{ route('landlord.subscribe') }}" class="w-full block bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-center px-5 py-4 rounded-xl transition shadow-lg shadow-indigo-500/20 active:scale-95">
                        Start Mijn Webshop Nu
                    </a>
                </div>
            </section>

            <!-- Contact Section -->
            <section class="max-w-4xl mx-auto py-12 border-t border-neutral-200 dark:border-neutral-800">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div class="space-y-6">
                        <h2 class="text-3xl font-extrabold tracking-tight">Klaar om te beginnen, of nog vragen?</h2>
                        <p class="text-neutral-500 dark:text-neutral-400">
                            Ons team staat klaar om je te helpen bij de start van je online onderneming. Heb je hulp nodig bij het koppelen van je domein, of wil je meer weten over de platform functionaliteiten? Stuur ons gerust een bericht!
                        </p>
                        
                        <div class="space-y-4">
                            <div class="flex items-center gap-4">
                                <div class="h-10 w-10 bg-indigo-50 dark:bg-indigo-950/40 rounded-xl flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm">Stuur ons een e-mail</h4>
                                    <p class="text-xs text-neutral-500">support@shopsaas.com</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Form Component -->
                    <livewire:landlord.contact-form />
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="w-full border-t border-neutral-200 dark:border-neutral-800 bg-neutral-100 dark:bg-neutral-900/60 mt-auto py-8">
            <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4">
                <span class="text-xs text-neutral-500">© {{ date('Y') }} ShopSaaS. Alle rechten voorbehouden.</span>
                <div class="flex gap-4 text-xs text-neutral-500">
                    <a href="#" class="hover:text-neutral-950 dark:hover:text-white transition">Privacybeleid</a>
                    <a href="#" class="hover:text-neutral-950 dark:hover:text-white transition">Algemene voorwaarden</a>
                </div>
            </div>
        </footer>

        @livewireScripts
    </body>
</html>
