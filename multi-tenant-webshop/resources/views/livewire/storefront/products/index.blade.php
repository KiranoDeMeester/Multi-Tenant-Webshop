<div>
    @if(($themeSettings['layout_type'] ?? 'modern') === 'minimal')
        @include('livewire.storefront.products.index-minimal')
    @elseif(($themeSettings['layout_type'] ?? 'modern') === 'editorial')
        @include('livewire.storefront.products.index-editorial')
    @else
        @include('livewire.storefront.products.index-modern')
    @endif
</div>
