<?php

namespace App\Livewire\Storefront\Account;

use App\Models\Tenant\CustomerAddress;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.storefront')]
class Addresses extends Component
{
    public $user;

    public bool $showForm = false;

    public ?string $editingId = null;

    // Form fields
    public string $type = 'shipping';

    public string $first_name = '';

    public string $last_name = '';

    public string $street = '';

    public string $house_number = '';

    public string $postal_code = '';

    public string $city = '';

    public string $country = 'België';

    protected $rules = [
        'first_name' => 'required|string|min:2|max:50',
        'last_name' => 'required|string|min:2|max:50',
        'street' => 'required|string|min:3|max:100',
        'house_number' => 'required|string|max:10',
        'postal_code' => 'required|string|min:4|max:10',
        'city' => 'required|string|min:2|max:50',
        'country' => 'required|string|in:België,Nederland,Duitsland,Frankrijk',
    ];

    public function mount()
    {
        $this->user = auth('customer')->user() ?? auth('tenant')->user();

        if (! $this->user) {
            return redirect()->route('storefront.login');
        }
    }

    public function createAddress()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function editAddress(string $id)
    {
        $address = CustomerAddress::where('customer_id', $this->user->id)->findOrFail($id);
        $this->editingId = $id;
        $this->type = $address->type;
        $this->first_name = $address->first_name;
        $this->last_name = $address->last_name;
        $this->street = $address->street;
        $this->house_number = $address->house_number;
        $this->postal_code = $address->postal_code;
        $this->city = $address->city;
        $this->country = $address->country;
        $this->showForm = true;
    }

    public function saveAddress()
    {
        $this->validate();

        $data = [
            'customer_id' => $this->user->id,
            'type' => $this->type,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'street' => $this->street,
            'house_number' => $this->house_number,
            'postal_code' => $this->postal_code,
            'city' => $this->city,
            'country' => $this->country,
        ];

        if ($this->editingId) {
            CustomerAddress::where('customer_id', $this->user->id)
                ->where('id', $this->editingId)
                ->update($data);
        } else {
            CustomerAddress::create($data);
        }

        $this->showForm = false;
        $this->resetForm();
        session()->flash('message', __('Adres succesvol opgeslagen!'));
    }

    public function deleteAddress(string $id)
    {
        CustomerAddress::where('customer_id', $this->user->id)
            ->where('id', $id)
            ->delete();

        session()->flash('message', __('Adres verwijderd.'));
    }

    protected function resetForm()
    {
        $this->editingId = null;
        $this->first_name = '';
        $this->last_name = '';
        $this->street = '';
        $this->house_number = '';
        $this->postal_code = '';
        $this->city = '';
    }

    public function render()
    {
        $addresses = CustomerAddress::where('customer_id', $this->user->id)->get();

        return view('livewire.storefront.account.addresses', [
            'addresses' => $addresses,
        ]);
    }
}
