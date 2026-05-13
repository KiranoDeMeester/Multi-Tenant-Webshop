@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="Kirano Platform" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-black text-white">
            <flux:icon name="building-storefront" variant="solid" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="Kirano Platform" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-black text-white">
            <flux:icon name="building-storefront" variant="solid" />
        </x-slot>
    </flux:brand>
@endif
