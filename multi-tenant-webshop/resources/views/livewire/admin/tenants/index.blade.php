<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Webshops</h1>
        <flux:button variant="primary" icon="plus">Nieuwe Webshop</flux:button>
    </div>

    <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 p-6">
        <flux:table :paginate="$tenants">
            <flux:table.columns>
                <flux:table.column>Naam</flux:table.column>
                <flux:table.column>Database</flux:table.column>
                <flux:table.column>Stripe ID</flux:table.column>
                <flux:table.column>Aangemaakt op</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($tenants as $tenant)
                    <flux:table.row :key="$tenant->id">
                        <flux:table.cell>{{ $tenant->name }}</flux:table.cell>
                        <flux:table.cell>{{ $tenant->db_name }}</flux:table.cell>
                        <flux:table.cell>{{ $tenant->stripe_account_id ?? 'Niet verbonden' }}</flux:cell>
                        <flux:table.cell>{{ $tenant->created_at->format('d-m-Y') }}</flux:cell>
                        <flux:table.cell>
                            <flux:button variant="ghost" size="sm" icon="pencil-square" />
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </div>
</div>
