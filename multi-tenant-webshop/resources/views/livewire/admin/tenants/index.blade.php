<div class="flex h-full w-full flex-1 flex-col gap-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black uppercase tracking-tighter">Webshops</h1>
            <p class="text-sm text-neutral-500">Beheer alle actieve webshops op het platform.</p>
        </div>
        <flux:button variant="primary" icon="plus" class="font-bold">Nieuwe Webshop</flux:button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-neutral-800 p-6 rounded-2xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg">
                    <flux:icon name="building-storefront" class="text-indigo-600 dark:text-indigo-400" />
                </div>
                <flux:badge color="indigo" size="sm">Totaal</flux:badge>
            </div>
            <div class="text-2xl font-black">{{ $totalTenants }}</div>
            <div class="text-xs text-neutral-500 font-bold uppercase tracking-widest mt-1">Geregistreerde Shops</div>
        </div>

        <div class="bg-white dark:bg-neutral-800 p-6 rounded-2xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-emerald-50 dark:bg-emerald-900/30 rounded-lg">
                    <flux:icon name="globe-alt" class="text-emerald-600 dark:text-emerald-400" />
                </div>
                <flux:badge color="emerald" size="sm">Actief</flux:badge>
            </div>
            <div class="text-2xl font-black">{{ $totalDomains }}</div>
            <div class="text-xs text-neutral-500 font-bold uppercase tracking-widest mt-1">Gekoppelde Domeinen</div>
        </div>

        <div class="bg-white dark:bg-neutral-800 p-6 rounded-2xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-amber-50 dark:bg-amber-900/30 rounded-lg">
                    <flux:icon name="credit-card" class="text-amber-600 dark:text-amber-400" />
                </div>
                <flux:badge color="amber" size="sm">Stripe</flux:badge>
            </div>
            <div class="text-2xl font-black">{{ $tenants->whereNotNull('stripe_account_id')->count() }}</div>
            <div class="text-xs text-neutral-500 font-bold uppercase tracking-widest mt-1">Betalingen Geactiveerd</div>
        </div>
    </div>

    <div class="bg-white dark:bg-neutral-800 rounded-2xl border border-neutral-200 dark:border-neutral-700 shadow-sm overflow-hidden">
        <flux:table :paginate="$tenants">
            <flux:table.columns>
                <flux:table.column>Webshop</flux:table.column>
                <flux:table.column>Status</flux:table.column>
                <flux:table.column>Stripe Connect</flux:table.column>
                <flux:table.column>Datum</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($tenants as $tenant)
                    <flux:table.row :key="$tenant->id">
                        <flux:table.cell>
                            <div class="flex flex-col">
                                <span class="font-bold text-neutral-900 dark:text-white">{{ $tenant->name }}</span>
                                <span class="text-xs text-neutral-500">{{ $tenant->primary_domain?->domain ?? 'Geen domein' }}</span>
                            </div>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:badge color="green" size="sm" inset>Actief</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            @if($tenant->stripe_account_id)
                                <div class="flex items-center gap-2 text-emerald-600 dark:text-emerald-400">
                                    <flux:icon name="check-circle" size="sm" variant="solid" />
                                    <span class="text-xs font-bold uppercase tracking-widest">Verbonden</span>
                                </div>
                            @else
                                <div class="flex items-center gap-2 text-neutral-400">
                                    <flux:icon name="x-circle" size="sm" variant="solid" />
                                    <span class="text-xs font-bold uppercase tracking-widest">Niet verbonden</span>
                                </div>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell>
                            <span class="text-sm font-medium">{{ $tenant->created_at->format('d M Y') }}</span>
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="flex items-center gap-2">
                                <flux:button variant="ghost" size="sm" icon="pencil-square" title="Bewerken" />
                                @if($tenant->primary_domain)
                                    <flux:button href="http://{{ $tenant->primary_domain->domain }}" target="_blank" variant="ghost" size="sm" icon="arrow-top-right-on-square" title="Bezoek Shop" />
                                @endif
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </div>
</div>
