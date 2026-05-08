<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl" level="1">{{ __('Categorieën') }}</flux:heading>
            <flux:text>{{ __('Beheer de categorieën van je shop.') }}</flux:text>
        </div>
        <flux:button :href="route('tenant.categories.create')" variant="primary" icon="plus" wire:navigate>{{ __('Categorie Toevoegen') }}</flux:button>
    </div>

    @if (session()->has('message'))
        <flux:callout variant="success" class="mb-6">{{ session('message') }}</flux:callout>
    @endif

    @if (session()->has('error'))
        <flux:callout variant="danger" class="mb-6">{{ session('error') }}</flux:callout>
    @endif

    <flux:card class="p-0 overflow-hidden">
        <flux:table :paginate="$categories">
            <flux:table.columns>
                <flux:table.column sortable wire:click="sortBy('name')">{{ __('Naam') }}</flux:table.column>
                <flux:table.column>{{ __('Aantal Producten') }}</flux:table.column>
                <flux:table.column>{{ __('Aangemaakt op') }}</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($categories as $category)
                    <flux:table.row :key="$category->id">
                        <flux:table.cell>
                            <div class="font-medium text-zinc-900 dark:text-white">{{ $category->name }}</div>
                            <div class="text-xs text-zinc-500">{{ $category->slug }}</div>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:badge size="sm" color="zinc">{{ $category->products_count ?? $category->products()->count() }}</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            {{ $category->created_at->format('d-m-Y') }}
                        </flux:table.cell>
                        <flux:table.cell align="end">
                            <flux:dropdown>
                                <flux:button variant="ghost" icon="ellipsis-horizontal" size="sm" />
                                <flux:menu>
                                    <flux:menu.item icon="pencil-square" :href="route('tenant.categories.edit', ['category' => $category->id])" wire:navigate>{{ __('Bewerken') }}</flux:menu.item>
                                    <flux:menu.item icon="trash" variant="danger" wire:click="deleteCategory('{{ $category->id }}')" wire:confirm="{{ __('Weet je zeker dat je deze categorie wilt verwijderen?') }}">{{ __('Verwijderen') }}</flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>

        <div class="p-4 border-t border-zinc-200 dark:border-zinc-700">
            {{ $categories->links() }}
        </div>
    </flux:card>
</div>
