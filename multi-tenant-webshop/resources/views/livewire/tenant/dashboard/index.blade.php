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
