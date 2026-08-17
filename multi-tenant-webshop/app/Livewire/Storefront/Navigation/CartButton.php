<?php

namespace App\Livewire\Storefront\Navigation;

use App\Services\CartService;
use Livewire\Attributes\On;
use Livewire\Component;

class CartButton extends Component
{
    public int $count = 0;

    public function mount()
    {
        $this->updateCount();
    }

    #[On('cart-updated')]
    public function updateCount()
    {
        $this->count = app(CartService::class)->getCount();
    }

    public function toggleCart()
    {
        $this->dispatch('toggle-cart');
    }

    public function render()
    {
        return <<<'HTML'
        <div class="relative">
            <button wire:click="toggleCart" class="p-2 text-neutral-600 hover:text-black transition-colors relative group focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 group-hover:scale-110 transition-transform">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                </svg>
                
                @if($count > 0)
                    <span class="absolute top-0 right-0 bg-indigo-600 text-white text-[10px] font-black h-5 w-5 flex items-center justify-center rounded-full border-2 border-white shadow-sm pointer-events-none transform translate-x-1 -translate-y-1 group-hover:scale-110 transition-transform">
                        {{ $count }}
                    </span>
                @endif
            </button>
        </div>
        HTML;
    }
}
