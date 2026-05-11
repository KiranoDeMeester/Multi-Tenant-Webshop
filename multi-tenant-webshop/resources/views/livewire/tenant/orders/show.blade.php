<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <flux:button icon="chevron-left" variant="ghost" href="{{ route('tenant.orders.index') }}" />
            <flux:heading size="xl" level="1">{{ __('Bestelling') }} {{ $order->order_number }}</flux:heading>
        </div>
        <div class="flex items-center gap-2">
            @php
                $color = match($order->status) {
                    'paid' => 'green',
                    'pending' => 'yellow',
                    'shipped' => 'blue',
                    'cancelled' => 'red',
                    default => 'zinc'
                };
            @endphp
            <flux:badge :color="$color">{{ ucfirst($order->status) }}</flux:badge>
            <flux:button variant="primary" icon="pencil-square" href="{{ route('tenant.orders.edit', $order) }}">{{ __('Bewerken') }}</flux:button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Items -->
        <div class="lg:col-span-2 space-y-6">
            <flux:card class="space-y-4">
                <flux:heading size="lg">{{ __('Producten') }}</flux:heading>
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>{{ __('Product') }}</flux:table.column>
                        <flux:table.column>{{ __('Prijs') }}</flux:table.column>
                        <flux:table.column>{{ __('Aantal') }}</flux:table.column>
                        <flux:table.column class="text-right">{{ __('Subtotaal') }}</flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @foreach ($order->items as $item)
                            <flux:table.row>
                                <flux:table.cell>
                                    <div class="font-bold text-black">{{ $item->product_name }}</div>
                                    <div class="text-xs text-zinc-500">SKU: {{ $item->sku ?? 'N/A' }}</div>
                                </flux:table.cell>
                                <flux:table.cell>€{{ number_format($item->price / 100, 2) }}</flux:table.cell>
                                <flux:table.cell>{{ $item->quantity }}</flux:table.cell>
                                <flux:table.cell class="text-right font-bold">
                                    €{{ number_format(($item->price * $item->quantity) / 100, 2) }}
                                </flux:table.cell>
                            </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>

                <div class="pt-4 border-t flex flex-col items-end space-y-2">
                    <div class="flex justify-between w-full max-w-xs">
                        <span class="text-zinc-500">{{ __('Totaal') }}</span>
                        <span class="text-2xl font-black text-black">€{{ number_format($order->total_amount / 100, 2) }}</span>
                    </div>
                </div>
            </flux:card>

            @if($order->customer_details)
                <flux:card class="space-y-4">
                    <flux:heading size="lg">{{ __('Verzendinformatie') }}</flux:heading>
                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <p class="text-xs font-black uppercase tracking-widest text-zinc-400 mb-2">{{ __('Naam') }}</p>
                            <p class="font-bold">{{ $order->customer_details['name'] ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-black uppercase tracking-widest text-zinc-400 mb-2">{{ __('Email') }}</p>
                            <p class="font-bold">{{ $order->customer_details['email'] ?? 'N/A' }}</p>
                        </div>
                        @if(isset($order->customer_details['address']))
                            <div class="col-span-2">
                                <p class="text-xs font-black uppercase tracking-widest text-zinc-400 mb-2">{{ __('Adres') }}</p>
                                <p class="font-bold">
                                    {{ $order->customer_details['address']['line1'] ?? '' }}<br>
                                    {{ $order->customer_details['address']['postal_code'] ?? '' }} {{ $order->customer_details['address']['city'] ?? '' }}<br>
                                    {{ $order->customer_details['address']['country'] ?? '' }}
                                </p>
                            </div>
                        @endif
                    </div>
                </flux:card>
            @endif
        </div>

        <!-- Sidebar / History -->
        <div class="space-y-6">
            <flux:card class="space-y-4">
                <flux:heading size="lg">{{ __('Klantgegevens') }}</flux:heading>
                @if($order->customer)
                    <div class="flex items-center gap-4">
                        <flux:avatar :name="$order->customer->name" />
                        <div>
                            <div class="font-bold">{{ $order->customer->name }}</div>
                            <div class="text-xs text-zinc-500">{{ $order->customer->email }}</div>
                        </div>
                    </div>
                    
                    @if($order->notes)
                        <div class="mt-8 pt-8 border-t">
                            <flux:heading size="md" class="mb-4">{{ __('Bestelnotitie van klant') }}</flux:heading>
                            <div class="bg-amber-50 border border-amber-200 p-4 rounded-xl text-sm text-amber-900 italic">
                                "{{ $order->notes }}"
                            </div>
                        </div>
                    @endif

                    <flux:button variant="ghost" size="sm" class="w-full" href="{{ route('tenant.customers.index') }}">{{ __('Bekijk Klant') }}</flux:button>
                @else
                    <p class="text-sm text-zinc-500">{{ __('Gastbestelling') }}</p>
                    @if($order->notes)
                        <div class="mt-8 pt-8 border-t">
                            <flux:heading size="md" class="mb-4">{{ __('Bestelnotitie van klant') }}</flux:heading>
                            <div class="bg-amber-50 border border-amber-200 p-4 rounded-xl text-sm text-amber-900 italic">
                                "{{ $order->notes }}"
                            </div>
                        </div>
                    @endif
                @endif
            </flux:card>

            <flux:card class="space-y-4">
                <flux:heading size="lg">{{ __('Betaalinformatie') }}</flux:heading>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-zinc-400 mb-1">{{ __('Betaalmethode') }}</p>
                        <p class="font-bold">Stripe</p>
                    </div>
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-zinc-400 mb-1">{{ __('Payment Intent ID') }}</p>
                        <p class="text-xs font-mono break-all">{{ $order->stripe_payment_intent_id ?? 'N/A' }}</p>
                    </div>
                </div>
            </flux:card>
        </div>
    </div>
</div>
