<div class="py-24">
    <div class="max-w-3xl mx-auto text-center">
        <div class="mb-12 flex justify-center">
            <div class="h-32 w-32 bg-indigo-600 rounded-[2.5rem] flex items-center justify-center shadow-[15px_15px_0px_0px_rgba(0,0,0,1)] border-4 border-black">
                <flux:icon name="check" size="xl" class="text-white h-16 w-16" />
            </div>
        </div>

        <h1 class="text-6xl font-black text-black uppercase tracking-tighter mb-6">{{ __('Bedankt voor je bestelling!') }}</h1>
        <p class="text-xl text-neutral-500 font-medium tracking-tight mb-12">
            {{ __('Je bestelling is succesvol geplaatst en wordt momenteel verwerkt.') }}
        </p>

        @if($order)
            <div class="bg-white border-4 border-black p-10 rounded-[3rem] shadow-[20px_20px_0px_0px_rgba(0,0,0,1)] text-left mb-16">
                <div class="flex justify-between items-center mb-10 pb-10 border-b-2 border-neutral-100">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-neutral-400 mb-1">{{ __('Bestelnummer') }}</p>
                        <h2 class="text-2xl font-black text-black">{{ $order->order_number }}</h2>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-neutral-400 mb-1">{{ __('Datum') }}</p>
                        <h2 class="text-xl font-black text-black">{{ $order->created_at->format('d-m-Y') }}</h2>
                    </div>
                </div>

                <div class="space-y-6 mb-10">
                    @foreach($order->items as $item)
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-4">
                                <span class="h-8 w-8 bg-neutral-100 rounded-lg flex items-center justify-center text-xs font-black border-2 border-black">{{ $item->quantity }}x</span>
                                <span class="font-bold text-black uppercase tracking-tight text-sm">{{ $item->product_name }}</span>
                            </div>
                            <span class="font-black text-black">€{{ number_format($item->price * $item->quantity / 100, 2) }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="pt-10 border-t-4 border-black flex justify-between items-center">
                    <span class="text-xs font-black uppercase tracking-[0.3em] text-neutral-400">{{ __('Totaal betaald') }}</span>
                    <span class="text-4xl font-black text-black tracking-tighter">€{{ number_format($order->total_amount / 100, 2) }}</span>
                </div>
            </div>
        @endif

        <div class="flex flex-col sm:flex-row gap-6 justify-center">
            <a href="{{ route('storefront.products.index') }}" class="px-12 py-6 bg-black text-white font-black uppercase tracking-[0.2em] rounded-3xl hover:bg-neutral-800 transition-all shadow-xl">
                {{ __('Terug naar winkel') }}
            </a>
            @auth('customer')
                <a href="{{ route('storefront.account') }}" class="px-12 py-6 bg-white text-black border-4 border-black font-black uppercase tracking-[0.2em] rounded-3xl hover:bg-neutral-50 transition-all shadow-xl">
                    {{ __('Bekijk bestelling') }}
                </a>
            @endauth
        </div>
    </div>
</div>
