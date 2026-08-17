<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <flux:heading size="xl" level="1">{{ __('Kortingscodes & Coupons') }}</flux:heading>
            <flux:subheading>{{ __('Beheer acties, percentages en vaste kortingsbedragen voor je klanten.') }}</flux:subheading>
        </div>
        <flux:button variant="primary" icon="plus" wire:click="openCreateModal">
            {{ __('Nieuwe Kortingscode') }}
        </flux:button>
    </div>

    <!-- Search & Filters -->
    <flux:card class="p-4">
        <div class="flex items-center gap-4">
            <div class="flex-1">
                <flux:input wire:model.live.debounce.300ms="search" placeholder="{{ __('Zoeken op code...') }}" icon="magnifying-glass" clearable />
            </div>
        </div>
    </flux:card>

    <!-- Table -->
    <flux:card class="p-0 overflow-hidden">
        <flux:table>
            <flux:table.columns>
                <flux:table.column>{{ __('Code') }}</flux:table.column>
                <flux:table.column>{{ __('Type / Waarde') }}</flux:table.column>
                <flux:table.column>{{ __('Min. Bestelbedrag') }}</flux:table.column>
                <flux:table.column>{{ __('Gebruikt / Max') }}</flux:table.column>
                <flux:table.column>{{ __('Vervaldatum') }}</flux:table.column>
                <flux:table.column>{{ __('Status') }}</flux:table.column>
                <flux:table.column class="text-right">{{ __('Acties') }}</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($coupons as $coupon)
                    <flux:table.row>
                        <flux:table.cell class="font-mono font-bold text-black">
                            <span class="bg-zinc-100 px-2.5 py-1 rounded-md border border-zinc-200">{{ $coupon->code }}</span>
                        </flux:table.cell>
                        <flux:table.cell>
                            @if($coupon->type === 'percentage')
                                <span class="font-bold text-indigo-600">{{ $coupon->value }}% korting</span>
                            @else
                                <span class="font-bold text-emerald-600">€{{ number_format($coupon->value / 100, 2, ',', '.') }} korting</span>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell>
                            @if($coupon->min_order_amount)
                                €{{ number_format($coupon->min_order_amount / 100, 2, ',', '.') }}
                            @else
                                <span class="text-zinc-400">—</span>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell>
                            <span class="font-medium">{{ $coupon->used_count }}</span>
                            <span class="text-zinc-400">/ {{ $coupon->max_uses ?? '∞' }}</span>
                        </flux:table.cell>
                        <flux:table.cell>
                            @if($coupon->expires_at)
                                <span class="{{ $coupon->expires_at->isPast() ? 'text-red-500 font-bold' : 'text-zinc-600' }}">
                                    {{ $coupon->expires_at->format('d-m-Y') }}
                                </span>
                            @else
                                <span class="text-zinc-400">{{ __('Geen verloopdatum') }}</span>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell>
                            <button wire:click="toggleActive('{{ $coupon->id }}')" class="cursor-pointer">
                                @if($coupon->is_active && (!$coupon->expires_at || !$coupon->expires_at->isPast()))
                                    <flux:badge color="green" size="sm">{{ __('Actief') }}</flux:badge>
                                @else
                                    <flux:badge color="zinc" size="sm">{{ __('Inactief') }}</flux:badge>
                                @endif
                            </button>
                        </flux:table.cell>
                        <flux:table.cell class="text-right">
                            <div class="flex items-center justify-end gap-2">
                                <flux:button size="xs" variant="ghost" icon="pencil-square" wire:click="edit('{{ $coupon->id }}')" />
                                <flux:button size="xs" variant="ghost" class="text-red-500 hover:text-red-700" icon="trash" wire:click="delete('{{ $coupon->id }}')" wire:confirm="{{ __('Weet je zeker dat je deze kortingscode wilt verwijderen?') }}" />
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="7" class="text-center py-8 text-zinc-500">
                            {{ __('Geen kortingscodes gevonden.') }}
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>

        @if($coupons->hasPages())
            <div class="p-4 border-t border-zinc-100">
                {{ $coupons->links() }}
            </div>
        @endif
    </flux:card>

    <!-- Modal Create/Edit -->
    <flux:modal wire:model="showModal" class="space-y-6">
        <div>
            <flux:heading size="lg">{{ $editingCouponId ? __('Kortingscode Bewerken') : __('Nieuwe Kortingscode') }}</flux:heading>
            <flux:subheading>{{ __('Stel de kortingscode, regels en voorwaarden in.') }}</flux:subheading>
        </div>

        <form wire:submit="save" class="space-y-4">
            <flux:input wire:model="code" label="{{ __('Kortingscode') }}" placeholder="bv. ZOMER10 of WELKOM" />

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:select wire:model.live="type" label="{{ __('Type Korting') }}">
                    <option value="percentage">{{ __('Percentage (%)') }}</option>
                    <option value="fixed">{{ __('Vast Bedrag (€)') }}</option>
                </flux:select>

                <flux:input wire:model="value" label="{{ $type === 'percentage' ? __('Percentage (%)') : __('Kortingsbedrag (€)') }}" type="number" step="{{ $type === 'percentage' ? '1' : '0.01' }}" min="1" placeholder="bv. 10" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:input wire:model="min_order_amount" label="{{ __('Minimaal Bestelbedrag (€, optioneel)') }}" type="number" step="0.01" min="0" placeholder="bv. 50.00" />

                <flux:input wire:model="max_uses" label="{{ __('Max. Aantal Keer Te Gebruiken (optioneel)') }}" type="number" min="1" placeholder="bv. 100" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:input wire:model="expires_at" label="{{ __('Vervaldatum (optioneel)') }}" type="date" />

                <div class="flex items-center pt-8">
                    <flux:checkbox wire:model="is_active" label="{{ __('Kortingscode direct actief') }}" />
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t">
                <flux:button variant="ghost" wire:click="$set('showModal', false)">{{ __('Annuleren') }}</flux:button>
                <flux:button type="submit" variant="primary">{{ __('Opslaan') }}</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
