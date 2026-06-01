<div class="py-12 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-12">
        <div class="flex items-center gap-2 text-sm font-bold text-zinc-500">
            <a href="{{ route('storefront.products.index') }}" wire:navigate class="hover:text-black transition-colors">{{ __('Winkel') }}</a>
            <flux:icon name="chevron-right" size="xs" />
            <span class="text-black">{{ __('Bestelling Volgen') }}</span>
        </div>

        <div class="mt-6 flex items-center justify-between">
            <h1 class="text-3xl font-black text-black tracking-tight uppercase">{{ __('Bestelling status') }}</h1>
        </div>
    </div>

    <div class="space-y-8">
        <div class="bg-white border-2 border-zinc-100 rounded-[2rem] overflow-hidden shadow-sm">
            <!-- Header/Meta -->
            <div class="bg-zinc-50/50 px-8 py-6 border-b border-zinc-100 flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-8">
                    <div>
                        <p class="text-xs uppercase font-bold text-zinc-400 mb-1 tracking-widest">{{ __('Bestelnummer') }}</p>
                        <p class="font-black text-black">#{{ $order->order_number }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase font-bold text-zinc-400 mb-1 tracking-widest">{{ __('Besteldatum') }}</p>
                        <p class="font-bold text-zinc-900">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase font-bold text-zinc-400 mb-1 tracking-widest">{{ __('Totaalbedrag') }}</p>
                        <p class="font-bold text-zinc-900">€{{ number_format($order->total_amount / 100, 2, ',', '.') }}</p>
                    </div>
                </div>
                
                <div>
                    @php
                        $statusColors = [
                            'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                            'paid' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                            'shipped' => 'bg-blue-100 text-blue-700 border-blue-200',
                            'cancelled' => 'bg-red-100 text-red-700 border-red-200',
                            'completed' => 'bg-zinc-100 text-zinc-700 border-zinc-200',
                        ];
                        $statusLabel = [
                            'pending' => 'In afwachting',
                            'paid' => 'Betaald',
                            'shipped' => 'Verzonden',
                            'cancelled' => 'Geannuleerd',
                            'completed' => 'Afgerond',
                        ];
                    @endphp
                    <span class="px-4 py-1.5 rounded-full text-xs font-black border uppercase tracking-wider {{ $statusColors[$order->status] ?? 'bg-zinc-100 text-zinc-700 border-zinc-200' }}">
                        {{ $statusLabel[$order->status] ?? $order->status }}
                    </span>
                </div>
            </div>

            <!-- Items -->
            <div class="p-8 border-b border-zinc-100">
                <h3 class="text-lg font-black text-black mb-6 uppercase tracking-tight">{{ __('Bestelde Artikelen') }}</h3>
                <div class="space-y-6">
                    @foreach ($order->items as $item)
                        <div class="flex items-center gap-6">
                            <div class="w-20 h-20 bg-zinc-50 rounded-2xl flex items-center justify-center p-2 border border-zinc-100 shrink-0">
                                @if($item->product?->getFirstMediaUrl('images'))
                                    <img src="{{ $item->product->getFirstMediaUrl('images') }}" class="w-full h-full object-contain">
                                @else
                                    <flux:icon name="shopping-bag" class="text-zinc-300 w-8 h-8" />
                                @endif
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-black">{{ $item->product_name }}</h4>
                                @if($item->productVariation)
                                    <p class="text-xs text-zinc-400 mt-0.5">
                                        {{ $item->productVariation->name }}
                                    </p>
                                @endif
                                <p class="text-sm text-zinc-500 mt-1">{{ __('Aantal: :qty', ['qty' => $item->quantity]) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-black text-black">€{{ number_format(($item->price * $item->quantity) / 100, 2, ',', '.') }}</p>
                                <p class="text-xs text-zinc-400">€{{ number_format($item->price / 100, 2, ',', '.') }} p.s.</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Customer & Shipping details -->
            <div class="p-8 bg-zinc-50/20 border-b border-zinc-100 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-sm font-black text-zinc-400 uppercase tracking-widest mb-4">{{ __('Klantgegevens') }}</h3>
                    <div class="text-zinc-700 space-y-1">
                        @if(!empty($order->customer_details))
                            <p class="font-bold text-black">{{ $order->customer_details['name'] ?? '' }}</p>
                            <p class="text-sm">{{ $order->customer_details['email'] ?? '' }}</p>
                            <p class="text-sm">{{ $order->customer_details['phone'] ?? '' }}</p>
                        @else
                            <p class="text-sm text-zinc-500 italic">{{ __('Geen gegevens beschikbaar.') }}</p>
                        @endif
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-black text-zinc-400 uppercase tracking-widest mb-4">{{ __('Verzendadres') }}</h3>
                    <div class="text-zinc-700 space-y-1">
                        @if(!empty($order->customer_details) && isset($order->customer_details['address']))
                            @php
                                $addr = $order->customer_details['address'];
                            @endphp
                            <p class="font-bold text-black">{{ $order->customer_details['name'] ?? '' }}</p>
                            <p class="text-sm">{{ $addr['line1'] ?? '' }}</p>
                            @if(!empty($addr['line2']))
                                <p class="text-sm">{{ $addr['line2'] }}</p>
                            @endif
                            <p class="text-sm">{{ $addr['postal_code'] ?? '' }} {{ $addr['city'] ?? '' }}</p>
                            <p class="text-sm uppercase tracking-wide font-medium">{{ $addr['country'] ?? '' }}</p>
                        @else
                            <p class="text-sm text-zinc-500 italic">{{ __('Geen verzendadres beschikbaar.') }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Breakdown & Total -->
            <div class="p-8 bg-zinc-50/50 flex justify-end">
                <div class="w-full sm:w-80 space-y-3 text-sm">
                    <div class="flex justify-between text-zinc-500 font-medium">
                        <span>{{ __('Subtotaal') }}</span>
                        <span>€{{ number_format(($order->total_amount - $order->shipping_amount - $order->tax_amount) / 100, 2, ',', '.') }}</span>
                    </div>
                    @if($order->tax_amount > 0)
                        <div class="flex justify-between text-zinc-500 font-medium">
                            <span>{{ __('Btw') }}</span>
                            <span>€{{ number_format($order->tax_amount / 100, 2, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-zinc-500 font-medium">
                        <span>{{ __('Verzendkosten') }}</span>
                        <span>€{{ number_format($order->shipping_amount / 100, 2, ',', '.') }}</span>
                    </div>
                    <div class="border-t border-zinc-200 pt-3 flex justify-between font-black text-lg text-black">
                        <span>{{ __('Totaal') }}</span>
                        <span>€{{ number_format($order->total_amount / 100, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Extra Note card -->
        <div class="bg-gradient-to-br from-zinc-900 to-black text-white p-8 rounded-[2rem] flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <h4 class="text-lg font-black uppercase tracking-tight mb-2">{{ __('Heb je vragen over je bestelling?') }}</h4>
                <p class="text-zinc-400 text-sm max-w-lg">
                    Neem gerust contact met ons op via onze supportpagina of mail ons direct. Houd je bestelnummer <strong>#{{ $order->order_number }}</strong> bij de hand voor een snelle afhandeling.
                </p>
            </div>
            <flux:button href="{{ route('storefront.products.index') }}" variant="primary" class="rounded-full bg-white !text-black hover:bg-zinc-200 px-8 py-3 tracking-widest font-black uppercase text-xs">
                {{ __('Terug naar winkel') }}
            </flux:button>
        </div>
    </div>
</div>
