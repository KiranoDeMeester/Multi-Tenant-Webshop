<div class="p-6">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-neutral-900 dark:text-white">Webshop Dashboard</h1>
            <p class="mt-2 text-lg text-neutral-600 dark:text-neutral-400">Welkom terug! Hier is een overzicht van je winkel.</p>
        </div>

        <!-- Filters -->
        <div class="flex items-center gap-3">
            <div class="flex flex-col gap-1">
                <label for="date-range" class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Periode</label>
                <select id="date-range" wire:model.live="dateRange" class="rounded-xl border border-neutral-200 bg-white px-4 py-2 text-sm font-semibold text-neutral-800 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white">
                    <option value="7">Laatste 7 dagen</option>
                    <option value="30">Laatste 30 dagen</option>
                    <option value="90">Laatste 90 dagen</option>
                    <option value="all">Volledige geschiedenis</option>
                </select>
            </div>

            <div class="flex flex-col gap-1">
                <label for="order-status" class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Betaalstatus</label>
                <select id="order-status" wire:model.live="status" class="rounded-xl border border-neutral-200 bg-white px-4 py-2 text-sm font-semibold text-neutral-800 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white">
                    <option value="paid">Betaald</option>
                    <option value="pending">In afwachting</option>
                    <option value="cancelled">Geannuleerd</option>
                    <option value="all">Alle bestellingen</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Tenant Onboarding Tutorial -->
    <div class="mb-8 overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-900 to-indigo-950 p-8 text-white shadow-lg relative border border-indigo-950">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_30%,rgba(99,102,241,0.15),transparent)]"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 text-xs font-bold uppercase tracking-wider mb-4 border border-indigo-500/30">
                    <flux:icon name="academic-cap" class="w-3.5 h-3.5" />
                    <span>Onboarding Gids & Handleiding</span>
                </div>
                <h2 class="text-2xl font-extrabold tracking-tight text-white uppercase">{{ __('Volledige Winkel Setup Gids') }}</h2>
                <p class="mt-2 text-indigo-200 text-sm leading-relaxed">
                    Volg deze 4 essentiële stappen om jouw webshop volledig correct te configureren en verkoopklaar te maken:
                </p>
                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs text-indigo-100 font-medium">
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-indigo-500 flex items-center justify-center text-white shrink-0 font-bold">1</div>
                        <div>
                            <strong class="text-white block text-sm mb-0.5">Stripe Connecten</strong>
                            Stel je betaalprofiel in via <a href="{{ route('tenant.payments') }}" class="underline text-indigo-300 hover:text-white transition-colors">Betalingen & Stripe</a> om online betalingen te kunnen ontvangen.
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-indigo-500 flex items-center justify-center text-white shrink-0 font-bold">2</div>
                        <div>
                            <strong class="text-white block text-sm mb-0.5">Producten & Variaties</strong>
                            Voeg categorieën toe en maak producten aan met prijzen (in centen) en voorraadniveaus onder <a href="{{ route('tenant.products.manage') }}" class="underline text-indigo-300 hover:text-white transition-colors">Producten</a>.
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-indigo-500 flex items-center justify-center text-white shrink-0 font-bold">3</div>
                        <div>
                            <strong class="text-white block text-sm mb-0.5">Styling & Thema's</strong>
                            Kies je gewenste lay-out (Modern, Minimal of Editorial), merkkleuren en lettertypen in <a href="{{ route('tenant.settings') }}" class="underline text-indigo-300 hover:text-white transition-colors font-bold">Winkel Dashboard</a>.
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-indigo-500 flex items-center justify-center text-white shrink-0 font-bold">4</div>
                        <div>
                            <strong class="text-white block text-sm mb-0.5">Contact & SEO</strong>
                            Vul contactgegevens en social media links in onder <a href="{{ route('tenant.settings.shop') }}" class="underline text-indigo-300 hover:text-white transition-colors">Winkelinstellingen</a>. Hiermee activeer je automatisch de contactpagina en de correcte links in de footer.
                        </div>
                    </div>
                </div>
            </div>
            <div class="shrink-0 flex items-center justify-center">
                <div class="w-24 h-24 bg-white/5 rounded-3xl border border-white/10 flex items-center justify-center text-indigo-300">
                    <flux:icon name="sparkles" class="w-12 h-12" />
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <!-- Products Stat -->
        <div class="overflow-hidden rounded-2xl bg-white p-6 shadow-sm border border-neutral-200 dark:bg-neutral-800 dark:border-neutral-700 transition-all hover:shadow-md">
            <div class="flex items-center gap-4">
                <div class="rounded-xl bg-indigo-50 p-3 dark:bg-indigo-900/30">
                    <flux:icon name="shopping-bag" class="h-6 w-6 text-indigo-600 dark:text-indigo-400" />
                </div>
                <div>
                    <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Totaal Producten</p>
                    <p class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $productCount }}</p>
                </div>
            </div>
        </div>

        <!-- Categories Stat -->
        <div class="overflow-hidden rounded-2xl bg-white p-6 shadow-sm border border-neutral-200 dark:bg-neutral-800 dark:border-neutral-700 transition-all hover:shadow-md">
            <div class="flex items-center gap-4">
                <div class="rounded-xl bg-emerald-50 p-3 dark:bg-emerald-900/30">
                    <flux:icon name="tag" class="h-6 w-6 text-emerald-600 dark:text-emerald-400" />
                </div>
                <div>
                    <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Categorieën</p>
                    <p class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $categoryCount }}</p>
                </div>
            </div>
        </div>

        <!-- Stock Warning Stat -->
        <div class="overflow-hidden rounded-2xl bg-white p-6 shadow-sm border border-neutral-200 dark:bg-neutral-800 dark:border-neutral-700 transition-all hover:shadow-md">
            <div class="flex items-center gap-4">
                <div class="rounded-xl bg-amber-50 p-3 dark:bg-amber-900/30">
                    <flux:icon name="exclamation-triangle" class="h-6 w-6 text-amber-600 dark:text-amber-400" />
                </div>
                <div>
                    <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Lage Voorraad</p>
                    <p class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $stockWarningCount }}</p>
                </div>
            </div>
        </div>

        <!-- Revenue Stat -->
        <div class="overflow-hidden rounded-2xl bg-white p-6 shadow-sm border border-neutral-200 dark:bg-neutral-800 dark:border-neutral-700 transition-all hover:shadow-md">
            <div class="flex items-center gap-4">
                <div class="rounded-xl bg-blue-50 p-3 dark:bg-blue-900/30">
                    <flux:icon name="currency-euro" class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                </div>
                <div>
                    <p class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Totale Omzet</p>
                    <p class="text-2xl font-bold text-neutral-900 dark:text-white">€{{ number_format($totalRevenue, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="mt-8 grid grid-cols-1 gap-6">
        <!-- Sales Volume Chart -->
        <div class="rounded-2xl bg-white p-8 shadow-sm border border-neutral-200 dark:bg-neutral-800 dark:border-neutral-700">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-xl font-black text-black dark:text-white">Omzet Volume</h2>
                    <p class="text-sm text-neutral-500">Omzet per dag in de geselecteerde periode.</p>
                </div>
                <div class="bg-indigo-50 dark:bg-indigo-900/30 px-4 py-2 rounded-full text-indigo-700 dark:text-indigo-300 text-xs font-bold uppercase tracking-widest">
                    @if($dateRange === 'all')
                        Volledige geschiedenis
                    @else
                        Laatste {{ $dateRange }} dagen
                    @endif
                </div>
            </div>
            <div class="h-[350px]" wire:key="sales-chart-{{ $dateRange }}-{{ $status }}" x-data="{
                init() {
                    new Chart(this.$refs.canvas, {
                        type: 'line',
                        data: {
                            labels: @js($chartSales['labels']),
                            datasets: [{
                                label: 'Omzet (€)',
                                data: @js($chartSales['data']),
                                borderColor: '#4f46e5',
                                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                                fill: true,
                                tension: 0.4,
                                borderWidth: 3,
                                pointRadius: 4,
                                pointBackgroundColor: '#4f46e5',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: '#000',
                                    padding: 12,
                                    bodyFont: { family: 'Inter', size: 14, weight: 'bold' },
                                    callbacks: {
                                        label: (context) => ' €' + context.parsed.y.toFixed(2)
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: { color: 'rgba(0,0,0,0.05)' },
                                    ticks: { callback: (value) => '€' + value }
                                },
                                x: {
                                    grid: { display: false }
                                }
                            }
                        }
                    });
                }
            }">
                <canvas x-ref="canvas"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Top Products Chart -->
            <div class="rounded-2xl bg-white p-8 shadow-sm border border-neutral-200 dark:bg-neutral-800 dark:border-neutral-700">
                <h2 class="text-xl font-black text-black dark:text-white mb-6">Populairste Producten</h2>
                <div class="h-[300px]" wire:key="top-products-chart-{{ $dateRange }}-{{ $status }}" x-data="{
                    init() {
                        new Chart(this.$refs.canvas, {
                            type: 'bar',
                            data: {
                                labels: @js($chartTopProducts['labels']),
                                datasets: [{
                                    label: 'Aantal Verkocht',
                                    data: @js($chartTopProducts['data']),
                                    backgroundColor: ['#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
                                    borderRadius: 12,
                                    borderSkipped: false,
                                    barThickness: 30
                                }]
                            },
                            options: {
                                indexAxis: 'y',
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false }
                                },
                                scales: {
                                    x: { grid: { display: false } },
                                    y: { grid: { display: false } }
                                }
                            }
                        });
                    }
                }">
                    <canvas x-ref="canvas"></canvas>
                </div>
            </div>

            <!-- Customer Growth Chart -->
            <div class="rounded-2xl bg-white p-8 shadow-sm border border-neutral-200 dark:bg-neutral-800 dark:border-neutral-700">
                <h2 class="text-xl font-black text-black dark:text-white mb-6">Klantengroei</h2>
                <div class="h-[300px]" wire:key="customer-growth-chart-{{ $dateRange }}" x-data="{
                    init() {
                        new Chart(this.$refs.canvas, {
                            type: 'line',
                            data: {
                                labels: @js($chartCustomers['labels']),
                                datasets: [{
                                    label: 'Nieuwe Klanten',
                                    data: @js($chartCustomers['data']),
                                    borderColor: '#10b981',
                                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                    fill: true,
                                    tension: 0.4,
                                    borderWidth: 3,
                                    pointRadius: 0
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false }
                                },
                                scales: {
                                    y: { beginAtZero: true, ticks: { stepSize: 1 } },
                                    x: { grid: { display: false } }
                                }
                            }
                        });
                    }
                }">
                    <canvas x-ref="canvas"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity / Quick Actions -->
    <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="rounded-2xl bg-white p-6 shadow-sm border border-neutral-200 dark:bg-neutral-800 dark:border-neutral-700">
            <h2 class="text-xl font-bold mb-4">Snelkoppelingen</h2>
            <div class="space-y-3">
                <flux:button :href="route('tenant.products.create')" variant="filled" class="w-full justify-start" icon="plus" wire:navigate>
                    {{ __('Nieuw Product Toevoegen') }}
                </flux:button>
                <flux:button :href="route('tenant.settings')" variant="ghost" class="w-full justify-start" icon="cog-6-tooth" wire:navigate>
                    {{ __('Winkel Instellingen') }}
                </flux:button>
                <flux:button :href="route('tenant.payments')" variant="ghost" class="w-full justify-start" icon="credit-card" wire:navigate>
                    {{ __('Betalingen & Stripe') }}
                </flux:button>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm border border-neutral-200 dark:bg-neutral-800 dark:border-neutral-700">
            <h2 class="text-xl font-bold mb-4">Winkel Status</h2>
            <div class="flex items-center gap-2">
                <div class="h-2.5 w-2.5 rounded-full bg-emerald-500"></div>
                <span class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Live en online</span>
            </div>
            <p class="mt-4 text-sm text-neutral-500">Je webshop is momenteel bereikbaar via het publieke domein.</p>
        </div>
    </div>
</div>

