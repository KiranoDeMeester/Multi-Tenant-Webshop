<div class="space-y-6">
    <div class="flex items-center gap-4">
        <flux:button icon="chevron-left" variant="ghost" href="{{ route('tenant.orders.show', $order) }}" />
        <flux:heading size="xl" level="1">{{ __('Bestelling bewerken') }}</flux:heading>
    </div>

    <flux:card>
        <form wire:submit="save" class="space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <flux:input wire:model="order_number" label="{{ __('Bestelnummer') }}" />

                <flux:select wire:model="status" label="{{ __('Status') }}">
                    <flux:select.option value="pending">{{ __('Wachtend') }}</flux:select.option>
                    <flux:select.option value="paid">{{ __('Betaald') }}</flux:select.option>
                    <flux:select.option value="shipped">{{ __('Verzonden') }}</flux:select.option>
                    <flux:select.option value="cancelled">{{ __('Geannuleerd') }}</flux:select.option>
                </flux:select>
            </div>

            <div class="pt-8 border-t flex justify-end gap-4">
                <flux:button href="{{ route('tenant.orders.index') }}" variant="ghost">{{ __('Annuleren') }}</flux:button>
                <flux:button type="submit" variant="primary" icon="check">{{ __('Opslaan') }}</flux:button>
            </div>
        </form>
    </flux:card>
</div>
