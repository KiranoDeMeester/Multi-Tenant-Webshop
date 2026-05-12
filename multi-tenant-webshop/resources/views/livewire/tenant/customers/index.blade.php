<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl" level="1">{{ __('Klanten') }}</flux:heading>
            <flux:text>{{ __('Beheer je klanten en bekijk hun bestelgeschiedenis.') }}</flux:text>
        </div>
    </div>

    <div class="mb-6">
        <flux:input wire:model.live="search" icon="magnifying-glass" placeholder="{{ __('Zoek klanten op naam of e-mail...') }}" class="max-w-md" />
    </div>

    <flux:card class="p-0 overflow-hidden">
        <flux:table :paginate="$customers">
            <flux:table.columns>
                <flux:table.column>{{ __('Klant') }}</flux:table.column>
                <flux:table.column>{{ __('E-mail') }}</flux:table.column>
                <flux:table.column align="center">{{ __('Bestellingen') }}</flux:table.column>
                <flux:table.column align="center">{{ __('Geregistreerd op') }}</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($customers as $customer)
                    <flux:table.row :key="$customer->id">
                        <flux:table.cell>
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-600 dark:text-zinc-400 font-bold">
                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                                </div>
                                <div class="font-medium text-zinc-900 dark:text-white">{{ $customer->name }}</div>
                            </div>
                        </flux:table.cell>
                        <flux:table.cell>
                            <span class="text-zinc-600 dark:text-zinc-400">{{ $customer->email }}</span>
                        </flux:table.cell>
                        <flux:table.cell align="center">
                            <flux:badge size="sm" color="zinc">{{ $customer->orders_count }}</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell align="center">
                            <span class="text-xs text-zinc-500">{{ $customer->created_at->format('d-m-Y') }}</span>
                        </flux:table.cell>
                        <flux:table.cell align="end">
                            <flux:button variant="ghost" icon="eye" :href="route('tenant.customers.show', ['customer' => $customer->id])" wire:navigate size="sm" />
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>

        @if($customers->hasPages())
            <div class="p-4 border-t border-zinc-200 dark:border-zinc-700">
                {{ $customers->links() }}
            </div>
        @endif
    </flux:card>
</div>
