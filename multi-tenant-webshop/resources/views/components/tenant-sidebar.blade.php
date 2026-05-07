@props(['tenant'])

<flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
    <flux:sidebar.header>
        <div class="flex items-center gap-2">
            <flux:avatar src="{{ $tenant->logo_url ?? '' }}" :name="$tenant->name" />
            <flux:heading class="font-bold truncate">{{ $tenant->name }}</flux:heading>
        </div>
        <flux:sidebar.collapse class="lg:hidden" />
    </flux:sidebar.header>

    <flux:sidebar.nav>
        <flux:sidebar.group :heading="__('Winkel Beheer')" class="grid">
            <flux:sidebar.item icon="home" :href="route('tenant.dashboard', ['tenant' => $tenant->slug])" :current="request()->routeIs('tenant.dashboard')" wire:navigate>
                {{ __('Dashboard') }}
            </flux:sidebar.item>
            
            <flux:sidebar.item icon="archive-box" :href="route('tenant.products.index', ['tenant' => $tenant->slug])" :current="request()->routeIs('tenant.products.*')" wire:navigate>
                {{ __('Producten') }}
            </flux:sidebar.item>

            <flux:sidebar.item icon="tag" :href="route('tenant.categories.index', ['tenant' => $tenant->slug])" :current="request()->routeIs('tenant.categories.*')" wire:navigate>
                {{ __('Categorieën') }}
            </flux:sidebar.item>
        </flux:sidebar.group>

        <flux:sidebar.group :heading="__('Verkoop')" class="grid">
            <flux:sidebar.item icon="shopping-cart" :href="route('tenant.orders.index', ['tenant' => $tenant->slug])" :current="request()->routeIs('tenant.orders.*')" wire:navigate>
                {{ __('Bestellingen') }}
            </flux:sidebar.item>
            
            <flux:sidebar.item icon="users" :href="route('tenant.customers.index', ['tenant' => $tenant->slug])" :current="request()->routeIs('tenant.customers.*')" wire:navigate>
                {{ __('Klanten') }}
            </flux:sidebar.item>
        </flux:sidebar.group>

        <flux:sidebar.group :heading="__('Instellingen')" class="grid">
            <flux:sidebar.item icon="cog-6-tooth" :href="route('tenant.settings', ['tenant' => $tenant->slug])" :current="request()->routeIs('tenant.settings')" wire:navigate>
                {{ __('Winkel Instellingen') }}
            </flux:sidebar.item>
        </flux:sidebar.group>
    </flux:sidebar.nav>

    <flux:spacer />

    <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
</flux:sidebar>
