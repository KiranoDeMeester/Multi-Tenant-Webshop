<div class="py-12">
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
        <div>
            <h1 class="text-6xl font-black text-black uppercase tracking-tighter">{{ __('Winkelwagen') }}</h1>
            <p class="text-xs font-black uppercase tracking-[0.3em] text-neutral-400 mt-4">{{ trans_choice(':count item geselecteerd|:count items geselecteerd', $count, ['count' => $count]) }}</p>
        </div>
        <a href="{{ route('storefront.products.index') }}" class="flex items-center gap-3 text-xs font-black uppercase tracking-[0.2em] text-neutral-400 hover:text-black transition-all group">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-4 h-4 group-hover:-translate-x-2 transition-transform">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            {{ __('Verder winkelen') }}
        </a>
    </div>

    @if($count > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-20">
            <!-- Items List -->
            <div class="lg:col-span-2 space-y-10">
                @foreach($items as $key => $item)
                    <div class="flex flex-col sm:flex-row gap-10 p-10 rounded-[2.5rem] bg-white border-2 border-black hover:shadow-[15px_15px_0px_0px_rgba(0,0,0,1)] transition-all duration-500 group">
                        <!-- Product Image -->
                        <div class="h-40 w-40 rounded-3xl bg-neutral-50 overflow-hidden flex-shrink-0 border-2 border-black">
                            @if($item['image'])
                                <img src="{{ $item['image'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-neutral-200">
                                    <flux:icon name="photo" size="xl" />
                                </div>
                            @endif
                        </div>

                        <!-- Product Details -->
                        <div class="flex-1 flex flex-col justify-between py-2">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-2xl font-black text-black uppercase tracking-tight group-hover:text-indigo-600 transition-colors">{{ $item['name'] }}</h3>
                                    <p class="text-[10px] text-neutral-400 font-black uppercase tracking-[0.3em] mt-2">SKU: {{ $item['sku'] }}</p>
                                </div>
                                <button wire:click="removeItem('{{ $key }}')" class="p-3 text-neutral-300 hover:text-black hover:bg-neutral-100 rounded-full transition-all">
                                    <flux:icon name="trash" size="sm" />
                                </button>
                            </div>

                            <div class="flex items-center justify-between mt-10">
                                <div class="flex items-center border-2 border-black rounded-2xl overflow-hidden bg-white shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                                    <button wire:click="updateQuantity('{{ $key }}', {{ $item['quantity'] - 1 }})" class="p-3 hover:bg-black hover:text-white transition-all">
                                        <flux:icon name="minus" size="xs" />
                                    </button>
                                    <span class="px-6 text-sm font-black text-black">{{ $item['quantity'] }}</span>
                                    <button wire:click="updateQuantity('{{ $key }}', {{ $item['quantity'] + 1 }})" class="p-3 hover:bg-black hover:text-white transition-all">
                                        <flux:icon name="plus" size="xs" />
                                    </button>
                                </div>
                                <div class="text-2xl font-black text-black">
                                    €{{ number_format($item['price'] * $item['quantity'], 2) }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="sticky top-32 p-10 rounded-[3rem] bg-black text-white shadow-2xl high-contrast-dark">
                    <h2 class="text-xs font-black uppercase tracking-[0.4em] mb-12 text-neutral-500">{{ __('Overzicht') }}</h2>
                    
                    <div class="space-y-6 mb-12">
                        <div class="flex justify-between text-neutral-400">
                            <span class="text-xs font-black uppercase tracking-widest">{{ __('Subtotaal') }}</span>
                            <span class="font-black text-white">€{{ number_format($total, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-neutral-400">
                            <span class="text-xs font-black uppercase tracking-widest">{{ __('Verzending') }}</span>
                            <span class="font-black text-indigo-400 uppercase tracking-widest text-[10px]">{{ __('Gratis') }}</span>
                        </div>

                        <div class="pt-6">
                            <flux:textarea wire:model="notes" placeholder="{{ __('Boodschap voor de winkelier (optioneel)...') }}" label="{{ __('Bestelnotitie') }}" rows="3" />
                        </div>
                    </div>

                    <div class="pt-10 border-t-2 border-neutral-900 flex justify-between items-center mb-12">
                        <span class="text-xs font-black uppercase tracking-[0.3em]">{{ __('Totaal') }}</span>
                        <span class="text-4xl font-black text-white tracking-tighter">€{{ number_format($total, 2) }}</span>
                    </div>

                    <button wire:click="checkout" wire:loading.attr="disabled" 
                        class="w-full h-20 bg-white border-4 border-black rounded-[2rem] shadow-[10px_10px_0px_0px_rgba(79,70,229,1)] hover:shadow-none hover:translate-x-1 hover:translate-y-1 transition-all flex items-center justify-center gap-4 group disabled:opacity-50 disabled:cursor-not-allowed !text-black">
                        <div wire:loading.remove class="flex items-center gap-4 !text-black">
                            <span class="text-xl font-black uppercase tracking-[0.2em] !text-black">{{ __('Afrekenen') }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-6 h-6 group-hover:translate-x-2 transition-transform !text-black">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </div>
                        <div wire:loading class="flex items-center gap-4 !text-black">
                            <span class="text-xl font-black uppercase tracking-[0.2em] !text-black">{{ __('Verwerken...') }}</span>
                        </div>
                    </button>

                    <div class="mt-12 grid grid-cols-3 gap-6 opacity-40">
                        <div class="flex flex-col items-center">
                            <flux:icon name="shield-check" size="xs" />
                        </div>
                        <div class="flex flex-col items-center">
                            <flux:icon name="truck" size="xs" />
                        </div>
                        <div class="flex flex-col items-center">
                            <flux:icon name="arrow-uturn-left" size="xs" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="py-32 flex flex-col items-center justify-center text-center">
            <div class="w-40 h-40 bg-neutral-50 border-4 border-black rounded-full flex items-center justify-center mb-12 shadow-[10px_10px_0px_0px_rgba(0,0,0,1)]">
                <flux:icon name="shopping-bag" size="xl" class="text-black h-16 w-16" />
            </div>
            <h2 class="text-5xl font-black text-black mb-6 uppercase tracking-tighter">{{ __('Winkelmandje leeg') }}</h2>
            <p class="text-neutral-400 max-w-sm mb-16 font-medium tracking-tight">{{ __('Je hebt nog geen items toegevoegd aan je collectie.') }}</p>
            <a href="{{ route('storefront.products.index') }}" class="px-16 py-6 bg-black text-white font-black uppercase tracking-[0.2em] rounded-3xl hover:bg-neutral-800 transition-all shadow-2xl active:scale-95">
                {{ __('Ontdek collectie') }}
            </a>
        </div>
    @endif
</div>
