<div class="space-y-6">
    <div class="flex items-center justify-between">
        <flux:heading size="xl" level="1">{{ __('Bestellingen') }}</flux:heading>
    </div>

    <flux:card class="space-y-6">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <flux:input wire:model.live.debounce.300ms="search" placeholder="{{ __('Zoek op bestelnummer...') }}" icon="magnifying-glass" />
            </div>
            <div class="w-full md:w-48">
                <flux:select wire:model.live="status" placeholder="{{ __('Alle Statussen') }}">
                    <flux:select.option value="">{{ __('Alle Statussen') }}</flux:select.option>
                    <flux:select.option value="pending">{{ __('Wachtend') }}</flux:select.option>
                    <flux:select.option value="paid">{{ __('Betaald') }}</flux:select.option>
                    <flux:select.option value="shipped">{{ __('Verzonden') }}</flux:select.option>
                    <flux:select.option value="cancelled">{{ __('Geannuleerd') }}</flux:select.option>
                </flux:select>
            </div>
        </div>

        <flux:table :paginate="$orders">
            <flux:table.columns>
                <flux:table.column>{{ __('Bestelnummer') }}</flux:table.column>
                <flux:table.column>{{ __('Klant') }}</flux:table.column>
                <flux:table.column>{{ __('Totaal') }}</flux:table.column>
                <flux:table.column>{{ __('Status') }}</flux:table.column>
                <flux:table.column>{{ __('Datum') }}</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($orders as $order)
                    <flux:table.row :key="$order->id">
                        <flux:table.cell class="font-bold">
                            {{ $order->order_number }}
                        </flux:table.cell>
                        <flux:table.cell>
                            {{ $order->customer_details['name'] ?? ($order->customer?->name ?? 'Gast') }}
                            <div class="text-xs text-zinc-500">{{ $order->customer_details['email'] ?? $order->customer?->email }}</div>
                        </flux:table.cell>
                        <flux:table.cell>
                            €{{ number_format($order->total_amount / 100, 2) }}
                        </flux:table.cell>
                        <flux:table.cell>
                            @php
                                $color = match($order->status) {
                                    'paid' => 'green',
                                    'pending' => 'yellow',
                                    'shipped' => 'blue',
                                    'cancelled' => 'red',
                                    default => 'zinc'
                                };
                            @endphp
                            <flux:badge :color="$color" size="sm" inset="top bottom">
                                {{ ucfirst($order->status) }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            {{ $order->created_at->format('d M Y H:i') }}
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="flex justify-end gap-2">
                                <flux:button variant="ghost" icon="eye" size="sm" href="{{ route('tenant.orders.show', $order) }}" />
                                <flux:button variant="ghost" icon="pencil-square" size="sm" href="{{ route('tenant.orders.edit', $order) }}" />
                                
                                <flux:dropdown>
                                    <flux:button variant="ghost" icon="ellipsis-horizontal" size="sm" />
                                    <flux:menu>
                                        <flux:menu.item 
                                            icon="trash" 
                                            variant="danger" 
                                            wire:click="deleteOrder('{{ $order->id }}')"
                                            wire:confirm="{{ __('Weet je zeker dat je deze bestelling wilt verwijderen?') }}"
                                        >
                                            {{ __('Verwijderen') }}
                                        </flux:menu.item>
                                    </flux:menu>
                                </flux:dropdown>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </flux:card>
</div>
