<div class="py-12 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-12">
        <div class="flex items-center gap-2 text-sm font-bold text-zinc-500">
            <a href="{{ route('storefront.account') }}" wire:navigate class="hover:text-black transition-colors">{{ __('Mijn Account') }}</a>
            <flux:icon name="chevron-right" size="xs" />
            <span class="text-black">{{ __('Mijn Bestellingen') }}</span>
        </div>

        <div class="mt-6 flex items-center justify-between">
            <h1 class="text-3xl font-black text-black">{{ __('Mijn Bestellingen') }}</h1>
        </div>
    </div>

    @if (session()->has('message'))
        <flux:callout variant="success" class="mb-8">
            {{ session('message') }}
        </flux:callout>
    @endif

    @if (session()->has('error'))
        <flux:callout variant="danger" class="mb-8">
            {{ session('error') }}
        </flux:callout>
    @endif

    <div class="space-y-6">
        @forelse ($orders as $order)
            <div class="bg-white border-2 border-zinc-100 rounded-[2rem] overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                <!-- Header -->
                <div class="bg-zinc-50/50 px-8 py-6 border-b border-zinc-100 flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-8">
                        <div>
                            <p class="text-xs uppercase font-bold text-zinc-400 mb-1 tracking-widest">{{ __('Bestelnummer') }}</p>
                            <p class="font-black text-black">#{{ $order->order_number }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase font-bold text-zinc-400 mb-1 tracking-widest">{{ __('Datum') }}</p>
                            <p class="font-bold text-zinc-900">{{ $order->created_at->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase font-bold text-zinc-400 mb-1 tracking-widest">{{ __('Totaal') }}</p>
                            <p class="font-bold text-zinc-900">€{{ number_format($order->total_amount / 100, 2, ',', '.') }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
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
                        <span class="px-4 py-1.5 rounded-full text-xs font-bold border {{ $statusColors[$order->status] ?? 'bg-zinc-100' }}">
                            {{ strtoupper($statusLabel[$order->status] ?? $order->status) }}
                        </span>

                        <a href="{{ route('storefront.account.orders.invoice', $order) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-zinc-100 hover:bg-zinc-200 text-zinc-700 transition-colors">
                            <flux:icon name="arrow-down-tray" size="xs" />
                            {{ __('Factuur (PDF)') }}
                        </a>

                        @if (in_array($order->status, ['pending', 'paid']))
                            <flux:button 
                                wire:click="cancelOrder('{{ $order->id }}')" 
                                wire:confirm="Weet je zeker dat je deze bestelling wilt annuleren?"
                                size="sm" 
                                variant="danger" 
                                class="rounded-full px-4"
                            >
                                {{ __('Annuleren') }}
                            </flux:button>
                        @endif
                    </div>
                </div>

                <!-- Content -->
                <div class="p-8">
                    <div class="space-y-6">
                        @foreach ($order->items as $item)
                            <div class="flex items-center gap-6">
                                <div class="w-16 h-16 bg-zinc-50 rounded-2xl flex items-center justify-center p-2 border border-zinc-100">
                                    @if($item->product?->getFirstMediaUrl('images'))
                                        <img src="{{ $item->product->getFirstMediaUrl('images') }}" class="w-full h-full object-contain">
                                    @else
                                        <flux:icon name="shopping-bag" class="text-zinc-300" />
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-black">{{ $item->product_name }}</h4>
                                    <p class="text-sm text-zinc-500">{{ __('Aantal: :qty', ['qty' => $item->quantity]) }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-black text-black">€{{ number_format($item->price / 100, 2, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-[3rem] border-2 border-dashed border-zinc-200 p-20 text-center">
                <div class="w-20 h-20 bg-zinc-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <flux:icon name="shopping-bag" size="xl" class="text-zinc-300" />
                </div>
                <h3 class="text-xl font-bold text-black mb-2">{{ __('Nog geen bestellingen') }}</h3>
                <p class="text-zinc-500 mb-8">{{ __('Zodra je een bestelling plaatst, verschijnt deze hier.') }}</p>
                <flux:button variant="primary" :href="route('storefront.products.index')" class="rounded-full px-8 py-3">{{ __('Nu shoppen') }}</flux:button>
            </div>
        @endforelse

        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    </div>
</div>
