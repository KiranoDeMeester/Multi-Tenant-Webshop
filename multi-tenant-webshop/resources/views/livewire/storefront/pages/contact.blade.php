<div class="py-20 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-16 text-center md:text-left">
        <h1 class="text-4xl sm:text-5xl font-black text-black mb-4 uppercase tracking-tighter">{{ __('Contact') }}</h1>
        <p class="text-lg text-zinc-500 max-w-xl leading-relaxed">
            {{ __('Heb je een vraag, opmerking of wil je gewoon hallo zeggen? Neem gerust contact met ons op. We helpen je graag verder.') }}
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
        <!-- Contact Card: Email -->
        @if($email)
            <div class="bg-white border-2 border-zinc-100 rounded-[2rem] p-8 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 bg-zinc-50 rounded-2xl flex items-center justify-center mb-6 border border-zinc-100 text-black">
                        <flux:icon name="envelope" class="w-6 h-6" />
                    </div>
                    <h3 class="text-lg font-black text-black uppercase tracking-tight mb-2">{{ __('E-mail') }}</h3>
                    <p class="text-zinc-500 text-sm mb-6">{{ __('Stuur ons een bericht en we antwoorden binnen 24 uur.') }}</p>
                </div>
                <a href="mailto:{{ $email }}" class="font-bold text-black hover:text-primary transition-colors text-sm break-all">
                    {{ $email }}
                </a>
            </div>
        @endif

        <!-- Contact Card: Phone -->
        @if($phone)
            <div class="bg-white border-2 border-zinc-100 rounded-[2rem] p-8 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 bg-zinc-50 rounded-2xl flex items-center justify-center mb-6 border border-zinc-100 text-black">
                        <flux:icon name="phone" class="w-6 h-6" />
                    </div>
                    <h3 class="text-lg font-black text-black uppercase tracking-tight mb-2">{{ __('Telefoon') }}</h3>
                    <p class="text-zinc-500 text-sm mb-6">{{ __('Bel ons rechtstreeks tijdens kantooruren.') }}</p>
                </div>
                <a href="tel:{{ $phone }}" class="font-bold text-black hover:text-primary transition-colors text-sm">
                    {{ $phone }}
                </a>
            </div>
        @endif

        <!-- Contact Card: Address -->
        @if($address)
            <div class="bg-white border-2 border-zinc-100 rounded-[2rem] p-8 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 bg-zinc-50 rounded-2xl flex items-center justify-center mb-6 border border-zinc-100 text-black">
                        <flux:icon name="map-pin" class="w-6 h-6" />
                    </div>
                    <h3 class="text-lg font-black text-black uppercase tracking-tight mb-2">{{ __('Bezoek Ons') }}</h3>
                    <p class="text-zinc-500 text-sm mb-6">{{ __('Ons hoofdkantoor en showroom.') }}</p>
                </div>
                <div class="text-sm font-bold text-zinc-700 leading-relaxed whitespace-pre-line">
                    {{ $address }}
                </div>
            </div>
        @endif
    </div>

    @if($content)
        <div class="bg-zinc-50 border-2 border-zinc-100 rounded-[2.5rem] p-8 sm:p-12 mb-16">
            <h3 class="text-xl font-black text-black uppercase tracking-tight mb-4">{{ __('Bereikbaarheid & Extra info') }}</h3>
            <div class="text-zinc-600 leading-relaxed text-base whitespace-pre-line">
                {{ $content }}
            </div>
        </div>
    @endif

    <div class="pt-8 border-t border-zinc-100 flex justify-center md:justify-start">
        <flux:button href="{{ route('storefront.products.index') }}" variant="primary" class="rounded-full px-8 py-3 uppercase font-black tracking-wider text-xs">
            {{ __('Terug naar winkel') }}
        </flux:button>
    </div>
</div>
