<div class="p-6">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold tracking-tight text-neutral-900 dark:text-white">Webshop Dashboard</h1>
        <p class="mt-2 text-lg text-neutral-600 dark:text-neutral-400">Welkom terug! Hier is een overzicht van je winkel.</p>
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
                    <p class="text-sm text-neutral-500">Omzet per dag in de afgelopen 30 dagen.</p>
                </div>
                <div class="bg-indigo-50 dark:bg-indigo-900/30 px-4 py-2 rounded-full text-indigo-700 dark:text-indigo-300 text-xs font-bold uppercase tracking-widest">
                    Laatste 30 dagen
                </div>
            </div>
            <div class="h-[350px]" x-data="{
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
                <div class="h-[300px]" x-data="{
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
                <div class="h-[300px]" x-data="{
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
