<div class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <flux:link href="{{ route('storefront.cart.index') }}" icon="arrow-left" class="text-neutral-500 hover:text-black transition-colors">
            {{ __('Terug naar winkelwagen') }}
        </flux:link>
        <h1 class="text-3xl sm:text-4xl font-black text-black uppercase tracking-tighter mt-3">{{ __('Afrekenen') }}</h1>
    </div>

    <form wire:submit="processCheckout">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            <!-- Left Column: Customer & Address Information -->
            <div class="lg:col-span-7 space-y-8">
                <!-- 1. Contact Info -->
                <div class="bg-white p-6 sm:p-8 rounded-3xl border-2 border-neutral-100 shadow-sm space-y-6">
                    <div class="flex items-center justify-between border-b border-neutral-100 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-black text-sm">1</div>
                            <h2 class="text-lg font-black text-black uppercase tracking-tight">{{ __('Contactgegevens') }}</h2>
                        </div>
                        @guest('customer')
                            <flux:link :href="route('storefront.login')" class="text-xs font-bold text-primary hover:underline">
                                {{ __('Al een account? Log in') }}
                            </flux:link>
                        @endguest
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <flux:field>
                            <flux:label class="text-black font-bold text-xs uppercase tracking-wider">{{ __('Voornaam') }} *</flux:label>
                            <flux:input wire:model="first_name" type="text" placeholder="Jan" input:class="!text-black !font-bold" required />
                            <flux:error name="first_name" />
                        </flux:field>

                        <flux:field>
                            <flux:label class="text-black font-bold text-xs uppercase tracking-wider">{{ __('Achternaam') }} *</flux:label>
                            <flux:input wire:model="last_name" type="text" placeholder="Jansen" input:class="!text-black !font-bold" required />
                            <flux:error name="last_name" />
                        </flux:field>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <flux:field>
                            <flux:label class="text-black font-bold text-xs uppercase tracking-wider">{{ __('E-mailadres') }} *</flux:label>
                            <flux:input wire:model="email" type="email" placeholder="jan@voorbeeld.be" icon="envelope" input:class="!text-black !font-bold" required />
                            <flux:error name="email" />
                        </flux:field>

                        <flux:field>
                            <flux:label class="text-black font-bold text-xs uppercase tracking-wider">{{ __('Telefoonnummer') }}</flux:label>
                            <flux:input wire:model="phone" type="tel" placeholder="+32 470 12 34 56" icon="phone" input:class="!text-black !font-bold" />
                            <flux:error name="phone" />
                        </flux:field>
                    </div>

                    @guest('customer')
                        <div class="pt-2">
                            <flux:checkbox wire:model.live="create_account" :label="__('Maak een account aan voor sneller afrekenen in de toekomst')" />
                        </div>

                        @if($create_account)
                            <div class="pt-2 animate-fade-in">
                                <flux:field>
                                    <flux:label class="text-black font-bold text-xs uppercase tracking-wider">{{ __('Kies een wachtwoord') }} *</flux:label>
                                    <flux:input wire:model="password" type="password" placeholder="Minimaal 8 tekens" icon="lock-closed" input:class="!text-black !font-bold" viewable required />
                                    <flux:error name="password" />
                                </flux:field>
                            </div>
                        @endif
                    @endguest
                </div>

                <!-- 2. Shipping Address -->
                <div class="bg-white p-6 sm:p-8 rounded-3xl border-2 border-neutral-100 shadow-sm space-y-6">
                    <div class="flex items-center gap-3 border-b border-neutral-100 pb-4">
                        <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-black text-sm">2</div>
                        <h2 class="text-lg font-black text-black uppercase tracking-tight">{{ __('Afleveradres') }}</h2>
                    </div>

                    @if($isCustomer && $savedAddresses->isNotEmpty())
                        <div class="space-y-3">
                            <flux:label class="text-black font-bold text-xs uppercase tracking-wider">{{ __('Kies uit opgeslagen adressen') }}</flux:label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($savedAddresses as $addr)
                                    <label class="border-2 rounded-2xl p-4 cursor-pointer transition-all flex items-start gap-3 {{ $selected_address_id == $addr->id ? 'border-primary bg-primary/5' : 'border-neutral-200 hover:border-neutral-400' }}">
                                        <input type="radio" wire:model.live="selected_address_id" value="{{ $addr->id }}" class="mt-1 text-primary focus:ring-primary">
                                        <div class="text-xs">
                                            <div class="font-bold text-black">{{ $addr->first_name }} {{ $addr->last_name }}</div>
                                            <div class="text-neutral-600">{{ $addr->street }} {{ $addr->house_number }}</div>
                                            <div class="text-neutral-600">{{ $addr->postal_code }} {{ $addr->city }}</div>
                                            <div class="text-neutral-400 font-bold uppercase tracking-wider text-[10px] mt-1">{{ $addr->country }}</div>
                                        </div>
                                    </label>
                                @endforeach

                                <label class="border-2 rounded-2xl p-4 cursor-pointer transition-all flex items-center gap-3 {{ $selected_address_id === 'new' ? 'border-primary bg-primary/5' : 'border-dashed border-neutral-300 hover:border-neutral-400' }}">
                                    <input type="radio" wire:model.live="selected_address_id" value="new" class="text-primary focus:ring-primary">
                                    <span class="text-xs font-bold text-neutral-700">{{ __('Nieuw afleveradres invoeren') }}</span>
                                </label>
                            </div>
                        </div>
                    @endif

                    @if(!$isCustomer || $savedAddresses->isEmpty() || $selected_address_id === 'new')
                        <div class="space-y-4 animate-fade-in">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="sm:col-span-2">
                                    <flux:field>
                                        <flux:label class="text-black font-bold text-xs uppercase tracking-wider">{{ __('Straatnaam') }} *</flux:label>
                                        <flux:input wire:model="shipping_street" type="text" placeholder="Kerkstraat" input:class="!text-black !font-bold" required />
                                        <flux:error name="shipping_street" />
                                    </flux:field>
                                </div>
                                <div>
                                    <flux:field>
                                        <flux:label class="text-black font-bold text-xs uppercase tracking-wider">{{ __('Huisnummer') }} *</flux:label>
                                        <flux:input wire:model="shipping_house_number" type="text" placeholder="12A" input:class="!text-black !font-bold" required />
                                        <flux:error name="shipping_house_number" />
                                    </flux:field>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <flux:field>
                                    <flux:label class="text-black font-bold text-xs uppercase tracking-wider">{{ __('Postcode') }} *</flux:label>
                                    <flux:input wire:model="shipping_postal_code" type="text" placeholder="1000" input:class="!text-black !font-bold" required />
                                    <flux:error name="shipping_postal_code" />
                                </flux:field>

                                <flux:field>
                                    <flux:label class="text-black font-bold text-xs uppercase tracking-wider">{{ __('Woonplaats') }} *</flux:label>
                                    <flux:input wire:model="shipping_city" type="text" placeholder="Brussel" input:class="!text-black !font-bold" required />
                                    <flux:error name="shipping_city" />
                                </flux:field>
                            </div>

                            <flux:field>
                                <flux:label class="text-black font-bold text-xs uppercase tracking-wider">{{ __('Land') }} *</flux:label>
                                <flux:select wire:model="shipping_country" required>
                                    <option value="België">België</option>
                                    <option value="Nederland">Nederland</option>
                                    <option value="Duitsland">Duitsland</option>
                                    <option value="Frankrijk">Frankrijk</option>
                                    <option value="Luxemburg">Luxemburg</option>
                                </flux:select>
                                <flux:error name="shipping_country" />
                            </flux:field>

                            @if($isCustomer)
                                <div class="pt-2">
                                    <flux:checkbox wire:model="save_to_address_book" :label="__('Dit adres opslaan in mijn adresboek')" />
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- 3. Billing Address -->
                <div class="bg-white p-6 sm:p-8 rounded-3xl border-2 border-neutral-100 shadow-sm space-y-6">
                    <div class="flex items-center gap-3 border-b border-neutral-100 pb-4">
                        <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-black text-sm">3</div>
                        <h2 class="text-lg font-black text-black uppercase tracking-tight">{{ __('Factuuradres') }}</h2>
                    </div>

                    <flux:checkbox wire:model.live="same_as_shipping" :label="__('Factuuradres is hetzelfde als afleveradres')" />

                    @if(!$same_as_shipping)
                        <div class="space-y-4 pt-2 animate-fade-in">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="sm:col-span-2">
                                    <flux:field>
                                        <flux:label class="text-black font-bold text-xs uppercase tracking-wider">{{ __('Straatnaam factuuradres') }} *</flux:label>
                                        <flux:input wire:model="billing_street" type="text" placeholder="Kerkstraat" input:class="!text-black !font-bold" required />
                                        <flux:error name="billing_street" />
                                    </flux:field>
                                </div>
                                <div>
                                    <flux:field>
                                        <flux:label class="text-black font-bold text-xs uppercase tracking-wider">{{ __('Huisnummer') }} *</flux:label>
                                        <flux:input wire:model="billing_house_number" type="text" placeholder="12" input:class="!text-black !font-bold" required />
                                        <flux:error name="billing_house_number" />
                                    </flux:field>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <flux:field>
                                    <flux:label class="text-black font-bold text-xs uppercase tracking-wider">{{ __('Postcode') }} *</flux:label>
                                    <flux:input wire:model="billing_postal_code" type="text" placeholder="1000" input:class="!text-black !font-bold" required />
                                    <flux:error name="billing_postal_code" />
                                </flux:field>

                                <flux:field>
                                    <flux:label class="text-black font-bold text-xs uppercase tracking-wider">{{ __('Woonplaats') }} *</flux:label>
                                    <flux:input wire:model="billing_city" type="text" placeholder="Brussel" input:class="!text-black !font-bold" required />
                                    <flux:error name="billing_city" />
                                </flux:field>
                            </div>

                            <flux:field>
                                <flux:label class="text-black font-bold text-xs uppercase tracking-wider">{{ __('Land') }} *</flux:label>
                                <flux:select wire:model="billing_country" required>
                                    <option value="België">België</option>
                                    <option value="Nederland">Nederland</option>
                                    <option value="Duitsland">Duitsland</option>
                                    <option value="Frankrijk">Frankrijk</option>
                                    <option value="Luxemburg">Luxemburg</option>
                                </flux:select>
                                <flux:error name="billing_country" />
                            </flux:field>
                        </div>
                    @endif
                </div>

                <!-- 4. Notes -->
                <div class="bg-white p-6 sm:p-8 rounded-3xl border-2 border-neutral-100 shadow-sm space-y-4">
                    <h3 class="text-sm font-black text-black uppercase tracking-wider">{{ __('Opmerkingen bij uw bestelling (optioneel)') }}</h3>
                    <flux:textarea wire:model="notes" placeholder="Bijv. instructies voor de bezorger..." rows="3" />
                </div>
            </div>

            <!-- Right Column: Order Summary & Payment Button -->
            <div class="lg:col-span-5">
                <div class="bg-white p-6 sm:p-8 rounded-3xl border-2 border-neutral-900 shadow-xl space-y-6 sticky top-24">
                    <h2 class="text-xl font-black text-black uppercase tracking-tight border-b border-neutral-100 pb-4">
                        {{ __('Besteloverzicht') }}
                    </h2>

                    <!-- Items list -->
                    <div class="divide-y divide-neutral-100 max-h-80 overflow-y-auto pr-1">
                        @foreach($items as $item)
                            <div class="py-3 flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3 min-w-0">
                                    @if(!empty($item['image']))
                                        <img src="{{ $item['image'] }}" class="w-12 h-12 object-cover rounded-xl border border-neutral-200 flex-shrink-0">
                                    @else
                                        <div class="w-12 h-12 bg-neutral-100 rounded-xl flex items-center justify-center text-neutral-400 flex-shrink-0">
                                            <flux:icon name="photo" class="w-5 h-5" />
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <div class="font-bold text-sm text-black truncate">{{ $item['name'] }}</div>
                                        @if(!empty($item['variation_name']))
                                            <div class="text-xs text-primary font-medium">{{ $item['variation_name'] }}</div>
                                        @endif
                                        <div class="text-xs text-neutral-500">{{ $item['quantity'] }}x €{{ number_format($item['price'], 2, ',', '.') }}</div>
                                    </div>
                                </div>
                                <div class="font-black text-sm text-black whitespace-nowrap">
                                    €{{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Totals Breakdown -->
                    <div class="space-y-3 pt-4 border-t border-neutral-100 text-sm">
                        <div class="flex justify-between text-neutral-600">
                            <span>{{ __('Subtotaal') }}</span>
                            <span class="font-bold text-black">€{{ number_format($subtotal, 2, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between text-neutral-600">
                            <span>{{ __('Verzendkosten') }}</span>
                            <span class="font-bold text-black">
                                @if($shippingFee > 0)
                                    €{{ number_format($shippingFee, 2, ',', '.') }}
                                @else
                                    <span class="text-emerald-600 font-black uppercase text-xs tracking-wider">{{ __('Gratis') }}</span>
                                @endif
                            </span>
                        </div>

                        <div class="flex justify-between text-neutral-500 text-xs">
                            <span>{{ __('Inclusief BTW (:vat%)', ['vat' => $vatPercentage]) }}</span>
                            <span>€{{ number_format($taxAmount, 2, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between text-lg font-black text-black pt-3 border-t border-neutral-200">
                            <span>{{ __('Totaalbedrag') }}</span>
                            <span class="text-primary text-xl">€{{ number_format($grandTotal, 2, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Pay Button -->
                    <div class="pt-4">
                        <flux:button type="submit" variant="primary" class="w-full h-14 bg-primary hover:bg-primary/95 text-white font-black text-base uppercase tracking-wider rounded-2xl shadow-lg border-none">
                            <flux:icon name="credit-card" class="mr-2 w-5 h-5" />
                            {{ __('Veilig Betalen via Stripe') }}
                        </flux:button>
                    </div>

                    <div class="text-center">
                        <p class="text-[11px] text-neutral-400 font-bold uppercase tracking-wider flex items-center justify-center gap-1">
                            <flux:icon name="lock-closed" class="w-3.5 h-3.5" />
                            {{ __('256-bit SSL Beveiligde Betaling') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
