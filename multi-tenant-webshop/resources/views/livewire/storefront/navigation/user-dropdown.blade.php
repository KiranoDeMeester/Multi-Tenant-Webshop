<div>
    @if(auth('customer')->check() || auth('tenant')->check())
        @php
            $user = auth('customer')->user() ?? auth('tenant')->user();
            $isAdmin = auth('tenant')->check();
        @endphp
        <div class="flex items-center gap-4">
            <flux:dropdown align="end">
                <flux:button variant="ghost" icon="user" size="sm" class="text-zinc-800 hover:text-primary">
                    <span class="hidden sm:inline ml-2 text-sm font-medium text-zinc-800">{{ $user->name }}</span>
                </flux:button>
 
                <flux:menu class="w-48">
                    @if($isAdmin)
                        <flux:menu.item icon="squares-2x2" :href="route('tenant.dashboard')" class="font-medium">{{ __('Beheer Shop') }}</flux:menu.item>
                        <flux:menu.separator />
                    @endif
                    <flux:menu.item icon="user-circle">{{ __('Mijn Account') }}</flux:menu.item>
                    <flux:menu.item icon="shopping-bag">{{ __('Mijn Bestellingen') }}</flux:menu.item>
                    <flux:menu.separator />
                    <flux:menu.item wire:click="logout" wire:confirm="{{ __('Weet je zeker dat je wilt uitloggen?') }}" icon="arrow-right-start-on-rectangle" variant="danger">{{ __('Uitloggen') }}</flux:menu.item>
                </flux:menu>
            </flux:dropdown>
        </div>
    @else
        <flux:button variant="outline" icon="user" href="{{ route('storefront.login') }}" size="sm" class="border-neutral-200">
            <span class="hidden sm:inline ml-2 text-sm font-medium">{{ __('Inloggen') }}</span>
        </flux:button>
    @endif
</div>
