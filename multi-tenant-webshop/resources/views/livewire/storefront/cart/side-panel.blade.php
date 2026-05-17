<div x-data="{ 
    cartOpen: @entangle('open'),
    close() { this.cartOpen = false }
}" 
     x-on:open-cart.window="cartOpen = true"
     x-on:keydown.escape.window="close()"
     class="relative z-[100]"
>
    <!-- Backdrop -->
    <div x-show="cartOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="transition-opacity ease-linear duration-300" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-black/60 backdrop-blur-sm" 
         x-on:click="close()"
         style="display: none;"></div>

    <!-- Sidebar Panel -->
    <div x-show="cartOpen" 
         x-transition:enter="transition ease-in-out duration-300 transform" 
         x-transition:enter-start="translate-x-full" 
         x-transition:enter-end="translate-x-0" 
         x-transition:leave="transition ease-in-out duration-300 transform" 
         x-transition:leave-start="translate-x-0" 
         x-transition:leave-end="translate-x-full" 
         class="fixed inset-y-0 right-0 max-w-md w-full bg-white shadow-2xl flex flex-col"
         style="display: none;"
    >
        <!-- Header -->
        <div class="p-6 border-b border-zinc-100 flex items-center justify-between bg-white z-10">
            <div>
                <h2 class="text-xl font-black text-black">{{ __('Jouw Winkelwagentje') }}</h2>
                <p class="text-xs text-zinc-500">{{ trans_choice(':count item|:count items', $count, ['count' => $count]) }}</p>
            </div>
            <button x-on:click="close()" class="p-2 text-zinc-400 hover:text-black transition-colors rounded-full hover:bg-zinc-100">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Items (Scrollable) -->
        <div class="flex-1 overflow-y-auto p-6 space-y-6">
            @forelse($items as $key => $item)
                <div class="flex gap-4 group">
                    <div class="h-24 w-24 rounded-2xl bg-zinc-50 overflow-hidden flex-shrink-0 border border-zinc-100">
                        @if($item['image'])
                            <img src="{{ $item['image'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-zinc-300">
                                <flux:icon name="photo" />
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start mb-1">
                                <h4 class="text-sm font-bold text-black truncate pr-2 group-hover:text-primary transition-colors">{{ $item['name'] }}</h4>
                                <button wire:click="removeItem('{{ $key }}')" class="text-zinc-300 hover:text-red-500 transition-colors">
                                    <flux:icon name="trash" size="xs" />
                                </button>
                            </div>
                            <p class="text-[10px] text-zinc-400 uppercase tracking-widest font-bold">SKU: {{ $item['sku'] }}</p>
                        </div>
                        
                        <div class="flex items-center justify-between mt-2">
                            <div class="flex items-center bg-zinc-100 rounded-xl p-1 shadow-inner">
                                <button wire:click="updateQuantity('{{ $key }}', {{ $item['quantity'] - 1 }})" class="w-6 h-6 flex items-center justify-center rounded-lg hover:bg-white hover:shadow-sm transition-all">
                                    <flux:icon name="minus" size="xs" />
                                </button>
                                <span class="px-3 text-xs font-black text-black">{{ $item['quantity'] }}</span>
                                <button wire:click="updateQuantity('{{ $key }}', {{ $item['quantity'] + 1 }})" class="w-6 h-6 flex items-center justify-center rounded-lg hover:bg-white hover:shadow-sm transition-all">
                                    <flux:icon name="plus" size="xs" />
                                </button>
                            </div>
                            <div class="text-sm font-black text-black">
                                €{{ number_format($item['price'] * $item['quantity'], 2) }}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="h-full flex flex-col items-center justify-center text-center py-20 px-6">
                    <div class="w-24 h-24 bg-zinc-50 rounded-full flex items-center justify-center mb-6 shadow-inner">
                        <flux:icon name="shopping-bag" size="xl" class="text-zinc-200" />
                    </div>
                    <h3 class="text-lg font-black text-black">{{ __('Je wagentje is nog leeg') }}</h3>
                    <p class="text-sm text-zinc-500 mt-2">{{ __('Voeg wat moois toe uit onze catalogus.') }}</p>
                    <button x-on:click="close()" class="mt-8 px-8 py-3 bg-primary text-white font-bold rounded-xl hover:opacity-90 transition-all">
                        {{ __('Verder winkelen') }}
                    </button>
                </div>
            @endforelse
        </div>

        @if($count > 0)
            <div class="p-8 border-t border-zinc-100 bg-white shadow-[0_-10px_40px_-15px_rgba(0,0,0,0.1)]">
                <div class="flex items-center justify-between mb-6">
                    <span class="text-zinc-500 font-medium">{{ __('Totaal') }}</span>
                    <span class="text-2xl font-black text-black tracking-tight">€{{ number_format($total, 2) }}</span>
                </div>
                <a href="{{ route('storefront.cart.index') }}" class="block text-center text-sm font-bold text-zinc-500 hover:text-black transition-colors mb-6 underline decoration-zinc-200 underline-offset-4">
                    {{ __('Bekijk volledig winkelmandje') }}
                </a>
                <button class="w-full h-14 text-lg font-black bg-black text-white rounded-2xl hover:bg-neutral-800 transition-all flex items-center justify-center gap-2 shadow-lg high-contrast-dark">
                    {{ __('Afrekenen') }}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </button>
                <p class="text-[10px] text-center text-zinc-400 mt-4 uppercase tracking-widest font-bold">{{ __('Verzendkosten worden berekend bij checkout') }}</p>
            </div>
        @endif
    </div>
</div>
