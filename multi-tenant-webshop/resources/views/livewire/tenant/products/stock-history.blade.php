<div class="p-6 max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <flux:heading size="xl" level="1">{{ __('Voorraadhistorie & Auditlog') }}</flux:heading>
            <flux:text>{{ __('Volledig inzicht in alle voorraadwijzigingen, verkopen, herstellingen en handmatige correcties.') }}</flux:text>
        </div>
        <flux:button wire:click="openAdjustModal" variant="primary" icon="plus">
            {{ __('Voorraad Aanpassen') }}
        </flux:button>
    </div>

    @if (session()->has('message'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-sm font-bold animate-fade-in">
            {{ session('message') }}
        </div>
    @endif

    <!-- Filters -->
    <flux:card>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <flux:input wire:model.live.debounce.300ms="search" placeholder="Zoek op product, SKU of reden..." icon="magnifying-glass" />
            </div>
            <div>
                <flux:select wire:model.live="typeFilter">
                    <option value="">{{ __('Alle Mutatietypes') }}</option>
                    <option value="sale">{{ __('Verkoop (Order)') }}</option>
                    <option value="cancel_restitution">{{ __('Annulering (Herstel)') }}</option>
                    <option value="purchase">{{ __('Inkoop') }}</option>
                    <option value="adjustment">{{ __('Handmatige Correctie') }}</option>
                    <option value="return">{{ __('Retour') }}</option>
                </flux:select>
            </div>
            <div>
                <flux:select wire:model.live="selectedProductId">
                    <option value="">{{ __('Alle Producten') }}</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}">{{ $p->name }} (SKU: {{ $p->sku }})</option>
                    @endforeach
                </flux:select>
            </div>
        </div>
    </flux:card>

    <!-- Table -->
    <flux:card class="overflow-x-auto p-0">
        <table class="w-full text-left text-sm">
            <thead class="bg-zinc-50 dark:bg-zinc-900/50 border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 uppercase text-[10px] font-bold tracking-wider">
                <tr>
                    <th class="px-6 py-4">{{ __('Datum & Tijd') }}</th>
                    <th class="px-6 py-4">{{ __('Product & Variant') }}</th>
                    <th class="px-6 py-4">{{ __('Type') }}</th>
                    <th class="px-6 py-4">{{ __('Aantal') }}</th>
                    <th class="px-6 py-4">{{ __('Voorraad Vóór / Na') }}</th>
                    <th class="px-6 py-4">{{ __('Toelichting / Order') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse($mutations as $m)
                    <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-900/30 transition-colors">
                        <td class="px-6 py-4 text-xs font-mono text-zinc-500 whitespace-nowrap">
                            {{ $m->created_at->format('d-m-Y H:i:s') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-black dark:text-white">{{ $m->product?->name ?? 'Verwijderd product' }}</div>
                            @if($m->variation)
                                <div class="text-xs text-primary font-medium">
                                    Variant: {{ $m->variation->attributeValues->pluck('value')->implode(' / ') ?: $m->variation->sku }}
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @switch($m->type)
                                @case('sale')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300">
                                        {{ __('Verkoop') }}
                                    </span>
                                    @break
                                @case('cancel_restitution')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                                        {{ __('Herstel') }}
                                    </span>
                                    @break
                                @case('purchase')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">
                                        {{ __('Inkoop') }}
                                    </span>
                                    @break
                                @case('adjustment')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                                        {{ __('Correctie') }}
                                    </span>
                                    @break
                                @default
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black bg-zinc-100 text-zinc-800">
                                        {{ $m->type }}
                                    </span>
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-mono font-black text-sm {{ $m->quantity < 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                            {{ $m->quantity > 0 ? '+' . $m->quantity : $m->quantity }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs font-mono">
                            <span class="text-zinc-400">{{ $m->stock_before }}</span>
                            <span class="text-zinc-300 mx-1">→</span>
                            <span class="font-bold text-black dark:text-white">{{ $m->stock_after }}</span>
                        </td>
                        <td class="px-6 py-4 text-xs text-zinc-600 dark:text-zinc-300">
                            @if($m->order)
                                <div class="font-bold">Order #{{ $m->order->order_number }}</div>
                            @endif
                            <div class="text-zinc-500">{{ $m->description }}</div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-zinc-400">
                            {{ __('Geen voorraadmutaties gevonden.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($mutations->hasPages())
            <div class="p-4 border-t border-zinc-100 dark:border-zinc-800">
                {{ $mutations->links() }}
            </div>
        @endif
    </flux:card>

    <!-- Modal for manual stock adjustment -->
    @if($showAdjustModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 animate-fade-in">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl space-y-6">
                <div class="flex justify-between items-center">
                    <flux:heading size="lg">{{ __('Handmatige Voorraadcorrectie') }}</flux:heading>
                    <button wire:click="$set('showAdjustModal', false)" class="text-zinc-400 hover:text-black">
                        <flux:icon name="x-mark" size="sm" />
                    </button>
                </div>

                <form wire:submit="saveAdjustment" class="space-y-4">
                    <flux:field>
                        <flux:label class="font-bold">{{ __('Product') }} *</flux:label>
                        <flux:select wire:model.live="adjustProductId" required>
                            <option value="">{{ __('Selecteer product...') }}</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} (Huidig: {{ $p->total_stock }})</option>
                            @endforeach
                        </flux:select>
                        <flux:error name="adjustProductId" />
                    </flux:field>

                    @if($adjustProductId)
                        @php
                            $selectedP = $products->firstWhere('id', $adjustProductId);
                        @endphp
                        @if($selectedP && $selectedP->variations->isNotEmpty())
                            <flux:field>
                                <flux:label class="font-bold">{{ __('Specifieke Variant') }}</flux:label>
                                <flux:select wire:model="adjustVariationId">
                                    <option value="">{{ __('Geen specifieke variant') }}</option>
                                    @foreach($selectedP->variations as $var)
                                        <option value="{{ $var->id }}">
                                            {{ $var->attributeValues->pluck('value')->implode(' / ') ?: $var->sku }} (Voorraad: {{ $var->stock }})
                                        </option>
                                    @endforeach
                                </flux:select>
                            </flux:field>
                        @endif
                    @endif

                    <div class="grid grid-cols-2 gap-4">
                        <flux:field>
                            <flux:label class="font-bold">{{ __('Type') }} *</flux:label>
                            <flux:select wire:model="adjustType" required>
                                <option value="adjustment">{{ __('Correctie (+/-)') }}</option>
                                <option value="purchase">{{ __('Inkoop (+)') }}</option>
                                <option value="return">{{ __('Retour (+)') }}</option>
                            </flux:select>
                        </flux:field>

                        <flux:field>
                            <flux:label class="font-bold">{{ __('Aanpassing') }} *</flux:label>
                            <flux:input type="number" wire:model="adjustDelta" placeholder="+5 of -2" required />
                            <flux:error name="adjustDelta" />
                        </flux:field>
                    </div>

                    <flux:field>
                        <flux:label class="font-bold">{{ __('Reden / Opmerking') }}</flux:label>
                        <flux:input wire:model="adjustReason" placeholder="Bijv. Jaarlijkse inventarisatie..." />
                    </flux:field>

                    <div class="flex justify-end gap-3 pt-4 border-t border-zinc-100 dark:border-zinc-800">
                        <flux:button type="button" wire:click="$set('showAdjustModal', false)" variant="ghost">{{ __('Annuleren') }}</flux:button>
                        <flux:button type="submit" variant="primary">{{ __('Correctie Opslaan') }}</flux:button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
