<?php

namespace App\Livewire\Tenant\Customers;

use App\Models\Tenant\Customer;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;

    public Customer $customer;

    public function mount(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function render()
    {
        $orders = $this->customer->orders()
            ->latest()
            ->paginate(10);

        $totalSpent = $this->customer->orders()
            ->where('status', 'paid')
            ->sum('total_amount');

        return view('livewire.tenant.customers.show', [
            'orders' => $orders,
            'totalSpent' => $totalSpent / 100,
        ])->layout('layouts.tenant');
    }
}
