<div class="py-12 max-w-4xl mx-auto">
    <div class="mb-8 flex justify-between items-end">
        <div>
            <nav class="flex items-center gap-2 text-sm font-medium mb-4">
                <a href="{{ route('storefront.account') }}" class="text-zinc-500 hover:text-black transition-colors">{{ __('Mijn Account') }}</a>
                <span class="text-zinc-300">/</span>
                <span class="text-black">{{ __('Adressen') }}</span>
            </nav>
            
            <h1 class="text-3xl font-black mt-4 text-black">{{ __('Mijn Adressen') }}</h1>
            <p class="text-zinc-600 mt-1">{{ __('Beheer je bezorg- en factuuradressen.') }}</p>
        </div>
        @if(!$showForm)
            <flux:button variant="primary" icon="plus" wire:click="createAddress">{{ __('Nieuw Adres') }}</flux:button>
        @endif
    </div>

    @if (session()->has('message'))
        <flux:callout variant="success" class="mb-6">{{ session('message') }}</flux:callout>
    @endif

    @if($showForm)
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-zinc-100 mb-12">
            <h2 class="text-xl font-bold mb-6 text-black">{{ $editingId ? __('Adres Bewerken') : __('Nieuw Adres Toevoegen') }}</h2>
            
            <form wire:submit="saveAddress" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <flux:field>
                        <flux:label class="text-black font-bold">{{ __('Voornaam') }}</flux:label>
                        <flux:input wire:model="first_name" input:class="!text-black !font-bold" />
                    </flux:field>
                    <flux:field>
                        <flux:label class="text-black font-bold">{{ __('Achternaam') }}</flux:label>
                        <flux:input wire:model="last_name" input:class="!text-black !font-bold" />
                    </flux:field>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2">
                        <flux:field>
                            <flux:label class="text-black font-bold">{{ __('Straat') }}</flux:label>
                            <flux:input wire:model="street" input:class="!text-black !font-bold" />
                        </flux:field>
                    </div>
                    <flux:field>
                        <flux:label class="text-black font-bold">{{ __('Huisnummer') }}</flux:label>
                        <flux:input wire:model="house_number" input:class="!text-black !font-bold" />
                    </flux:field>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <flux:field>
                        <flux:label class="text-black font-bold">{{ __('Postcode') }}</flux:label>
                        <flux:input wire:model="postal_code" input:class="!text-black !font-bold" />
                    </flux:field>
                    <flux:field>
                        <flux:label class="text-black font-bold">{{ __('Stad') }}</flux:label>
        <div class="max-w-2xl bg-white border-2 border-black rounded-[3rem] p-12 shadow-[20px_20px_0px_0px_rgba(0,0,0,1)] mb-20 animate-in fade-in slide-in-from-bottom-8 duration-500">
            <h2 class="text-3xl font-black mb-10 uppercase tracking-tight">{{ $editingId ? __('Adres Bewerken') : __('Nieuw Adres Toevoegen') }}</h2>
            
            <form wire:submit.prevent="saveAddress" class="space-y-8">
                <div class="grid grid-cols-2 gap-8">
                    <flux:input wire:model="first_name" label="{{ __('Voornaam') }}" placeholder="Bijv. Jan" />
                    <flux:input wire:model="last_name" label="{{ __('Achternaam') }}" placeholder="Bijv. Jansen" />
                </div>

                <div class="grid grid-cols-3 gap-8">
                    <div class="col-span-2">
                        <flux:input wire:model="street" label="{{ __('Straat') }}" placeholder="Bijv. Hoofdstraat" />
                    </div>
                    <flux:input wire:model="house_number" label="{{ __('Nr.') }}" placeholder="12A" />
                </div>

                <div class="grid grid-cols-2 gap-8">
                    <flux:input wire:model="postal_code" label="{{ __('Postcode') }}" placeholder="1234 AB" />
                    <flux:input wire:model="city" label="{{ __('Stad') }}" placeholder="Bijv. Amsterdam" />
                </div>

                <flux:select wire:model="country" label="{{ __('Land') }}">
                    <option value="België">België</option>
                    <option value="Nederland">Nederland</option>
                    <option value="Duitsland">Duitsland</option>
                    <option value="Frankrijk">Frankrijk</option>
                </flux:select>

                <div class="flex items-center gap-6 pt-10">
                    <button type="submit" class="btn-high-contrast flex-1">
                        {{ __('Adres Opslaan') }}
                    </button>
                    <button type="button" wire:click="$set('showForm', false)" class="btn-high-contrast-outline">
                        {{ __('Annuleren') }}
                    </button>
                </div>
            </form>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
        @foreach($addresses as $address)
            <div class="group bg-white border-2 border-black rounded-[2.5rem] p-10 hover:shadow-[15px_15px_0px_0px_rgba(0,0,0,1)] transition-all duration-500 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start mb-8">
                        <span class="text-[10px] font-black uppercase tracking-[0.3em] px-4 py-1.5 bg-neutral-100 rounded-full text-neutral-400 group-hover:bg-black group-hover:text-white transition-all">
                            {{ $address->type === 'shipping' ? __('Verzending') : __('Facturatie') }}
                        </span>
                        <div class="flex gap-2">
                            <button wire:click="editAddress('{{ $address->id }}')" class="p-2 text-neutral-300 hover:text-black hover:bg-neutral-100 rounded-full transition-all">
                                <flux:icon name="pencil-square" size="sm" />
                            </button>
                            <button wire:click="deleteAddress('{{ $address->id }}')" wire:confirm="{{ __('Weet je zeker dat je dit adres wilt verwijderen?') }}" class="p-2 text-neutral-300 hover:text-red-500 hover:bg-red-50 rounded-full transition-all">
                                <flux:icon name="trash" size="sm" />
                            </button>
                        </div>
                    </div>
                    
                    <h3 class="text-2xl font-black text-black mb-4 uppercase tracking-tight">{{ $address->first_name }} {{ $address->last_name }}</h3>
                    
                    <div class="space-y-1 text-sm font-bold text-neutral-500 leading-relaxed">
                        <p>{{ $address->street }} {{ $address->house_number }}</p>
                        <p>{{ $address->postal_code }} {{ $address->city }}</p>
                        <p class="uppercase tracking-widest text-[10px] mt-2 font-black text-neutral-400">{{ $address->country }}</p>
                    </div>
                </div>
            </div>
    </div>
</div>
