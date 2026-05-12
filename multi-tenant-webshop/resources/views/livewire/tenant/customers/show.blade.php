<div class="p-6">
    <div class="mb-8 flex items-center gap-4">
        <flux:button icon="arrow-left" variant="ghost" :href="route('tenant.customers.index')" wire:navigate />
        <div>
            <flux:heading size="xl" level="1">{{ $customer->name }}</flux:heading>
            <flux:text>{{ $customer->email }}</flux:text>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <flux:card class="flex flex-col items-center justify-center p-8 text-center">
            <flux:text class="uppercase text-xs font-bold tracking-widest mb-2 text-zinc-500">{{ __('Totaal Bestellingen') }}</flux:text>
            <flux:heading size="xl" class="font-black">{{ $customer->orders()->count() }}</flux:heading>
        </flux:card>

        <flux:card class="flex flex-col items-center justify-center p-8 text-center">
            <flux:text class="uppercase text-xs font-bold tracking-widest mb-2 text-zinc-500">{{ __('Totaal Uitgegeven') }}</flux:text>
            <flux:heading size="xl" class="font-black text-indigo-600">€{{ number_format($totalSpent, 2, ',', '.') }}</flux:heading>
        </flux:card>

        <flux:card class="flex flex-col items-center justify-center p-8 text-center">
            <flux:text class="uppercase text-xs font-bold tracking-widest mb-2 text-zinc-500">{{ __('Klant Sinds') }}</flux:text>
            <flux:heading size="lg" class="font-bold">{{ $customer->created_at->format('d M Y') }}</flux:heading>
        </flux:card>
    </div>

    <flux:heading size="lg" class="mb-6">{{ __('Bestelgeschiedenis') }}</flux:heading>

    <flux:card class="p-0 overflow-hidden">
        <flux:table :paginate="$orders">
            <flux:table.columns>
                <flux:table.column>{{ __('Order #') }}</flux:table.column>
                <flux:table.column>{{ __('Datum') }}</flux:table.column>
                <flux:table.column>{{ __('Bedrag') }}</flux:table.column>
                <flux:table.column>{{ __('Status') }}</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($orders as $order)
                    <flux:table.row :key="$order->id">
                        <flux:table.cell class="font-bold">#{{ $order->order_number }}</flux:table.cell>
                        <flux:table.cell>{{ $order->created_at->format('d-m-Y H:i') }}</flux:table.cell>
                        <flux:table.cell>€{{ number_format($order->total_amount / 100, 2, ',', '.') }}</flux:table.cell>
                        <flux:table.cell>
                            @php
                                $statusColors = [
                                    'pending' => 'amber',
                                    'paid' => 'green',
                                    'shipped' => 'blue',
                                    'cancelled' => 'red',
                                    'completed' => 'zinc',
                                ];
                            @endphp
                            <flux:badge size="sm" :color="$statusColors[$order->status] ?? 'zinc'">
                                {{ strtoupper($order->status) }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell align="end">
                            <flux:button variant="ghost" icon="eye" :href="route('tenant.orders.show', ['order' => $order->id])" wire:navigate size="sm" />
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>

        @if($orders->hasPages())
            <div class="p-4 border-t border-zinc-200 dark:border-zinc-700">
                {{ $orders->links() }}
            </div>
        @endif
    </flux:card>
</div>
