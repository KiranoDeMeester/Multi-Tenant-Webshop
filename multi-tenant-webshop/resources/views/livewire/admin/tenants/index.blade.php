<x-layouts::app :title="__('Webshops')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Webshops</h1>
            <flux:button variant="primary" icon="plus">Nieuwe Webshop</flux:button>
        </div>

        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 p-6">
            <flux:table :paginate="$tenants">
                <flux:columns>
                    <flux:column>Naam</flux:column>
                    <flux:column>Database</flux:column>
                    <flux:column>Stripe ID</flux:column>
                    <flux:column>Aangemaakt op</flux:column>
                    <flux:column></flux:column>
                </flux:columns>

                <flux:rows>
                    @foreach ($tenants as $tenant)
                        <flux:row :key="$tenant->id">
                            <flux:cell>{{ $tenant->name }}</flux:cell>
                            <flux:cell>{{ $tenant->db_name }}</flux:cell>
                            <flux:cell>{{ $tenant->stripe_account_id ?? 'Niet verbonden' }}</flux:cell>
                            <flux:cell>{{ $tenant->created_at->format('d-m-Y') }}</flux:cell>
                            <flux:cell>
                                <flux:button variant="ghost" size="sm" icon="pencil-square" />
                            </flux:cell>
                        </flux:row>
                    @endforeach
                </flux:rows>
            </flux:table>
        </div>
    </div>
</x-layouts::app>
