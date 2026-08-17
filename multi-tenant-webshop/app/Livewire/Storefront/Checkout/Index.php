<?php

namespace App\Livewire\Storefront\Checkout;

use App\Actions\Tenant\PrepareCheckoutAction;
use App\Models\Tenant\Customer;
use App\Models\Tenant\CustomerAddress;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.storefront')]
#[Title('Afrekenen')]
class Index extends Component
{
    // Contact details
    public string $email = '';
    public string $first_name = '';
    public string $last_name = '';
    public string $phone = '';

    // Guest account creation
    public bool $create_account = false;
    public string $password = '';

    // Shipping Address
    public ?string $selected_address_id = null;
    public string $shipping_street = '';
    public string $shipping_house_number = '';
    public string $shipping_postal_code = '';
    public string $shipping_city = '';
    public string $shipping_country = 'België';
    public bool $save_to_address_book = true;

    // Billing Address
    public bool $same_as_shipping = true;
    public string $billing_street = '';
    public string $billing_house_number = '';
    public string $billing_postal_code = '';
    public string $billing_city = '';
    public string $billing_country = 'België';

    // Order notes
    public string $notes = '';

    public function mount()
    {
        $cartService = app(CartService::class);
        if (empty($cartService->getItems())) {
            session()->flash('error', __('Uw winkelwagen is leeg. Voeg eerst producten toe.'));
            return redirect()->route('storefront.products.index');
        }

        $customer = Auth::guard('customer')->user();
        if ($customer) {
            $this->email = $customer->email ?? '';
            $nameParts = explode(' ', $customer->name ?? '', 2);
            $this->first_name = $nameParts[0] ?? '';
            $this->last_name = $nameParts[1] ?? '';
            $this->phone = $customer->phone ?? '';

            // Load saved addresses
            $savedAddresses = CustomerAddress::where('customer_id', $customer->id)->get();
            if ($savedAddresses->isNotEmpty()) {
                $primary = $savedAddresses->firstWhere('type', 'shipping') ?? $savedAddresses->first();
                $this->selected_address_id = $primary->id;
                $this->fillShippingFromAddress($primary);
            }
        }
    }

    public function updatedSelectedAddressId($addressId)
    {
        if ($addressId === 'new') {
            $this->shipping_street = '';
            $this->shipping_house_number = '';
            $this->shipping_postal_code = '';
            $this->shipping_city = '';
            $this->shipping_country = 'België';
            return;
        }

        $customer = Auth::guard('customer')->user();
        if ($customer && $addressId) {
            $address = CustomerAddress::where('customer_id', $customer->id)->find($addressId);
            if ($address) {
                $this->fillShippingFromAddress($address);
            }
        }
    }

    protected function fillShippingFromAddress(CustomerAddress $address): void
    {
        $this->first_name = $this->first_name ?: $address->first_name;
        $this->last_name = $this->last_name ?: $address->last_name;
        $this->shipping_street = $address->street;
        $this->shipping_house_number = $address->house_number;
        $this->shipping_postal_code = $address->postal_code;
        $this->shipping_city = $address->city;
        $this->shipping_country = $address->country;
    }

    protected function rules(): array
    {
        $rules = [
            'email' => 'required|email|max:255',
            'first_name' => 'required|string|min:2|max:100',
            'last_name' => 'required|string|min:2|max:100',
            'phone' => 'nullable|string|max:30',
            'shipping_street' => 'required|string|min:2|max:150',
            'shipping_house_number' => 'required|string|max:20',
            'shipping_postal_code' => 'required|string|min:3|max:20',
            'shipping_city' => 'required|string|min:2|max:100',
            'shipping_country' => 'required|string|in:België,Nederland,Duitsland,Frankrijk,Luxemburg',
        ];

        if (!$this->same_as_shipping) {
            $rules['billing_street'] = 'required|string|min:2|max:150';
            $rules['billing_house_number'] = 'required|string|max:20';
            $rules['billing_postal_code'] = 'required|string|min:3|max:20';
            $rules['billing_city'] = 'required|string|min:2|max:100';
            $rules['billing_country'] = 'required|string|in:België,Nederland,Duitsland,Frankrijk,Luxemburg';
        }

        if (!Auth::guard('customer')->check() && $this->create_account) {
            $rules['password'] = 'required|string|min:8';
            $rules['email'] = 'required|email|max:255|unique:customers,email';
        }

        return $rules;
    }

    public function processCheckout(PrepareCheckoutAction $prepareCheckout)
    {
        $this->validate();

        $customer = Auth::guard('customer')->user();

        // 1. Create account if guest requested
        if (!$customer && $this->create_account) {
            $customer = Customer::create([
                'name' => trim($this->first_name . ' ' . $this->last_name),
                'email' => $this->email,
                'phone' => $this->phone ?: null,
                'password' => Hash::make($this->password),
                'email_verified_at' => now(),
            ]);

            Auth::guard('customer')->login($customer);
        }

        // 2. Save address to address book if logged in and option enabled
        if ($customer && $this->save_to_address_book) {
            CustomerAddress::firstOrCreate([
                'customer_id' => $customer->id,
                'street' => $this->shipping_street,
                'house_number' => $this->shipping_house_number,
                'postal_code' => $this->shipping_postal_code,
                'city' => $this->shipping_city,
            ], [
                'type' => 'shipping',
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'country' => $this->shipping_country,
            ]);
        }

        // 3. Assemble snapshot details
        $customerDetails = [
            'name' => trim($this->first_name . ' ' . $this->last_name),
            'email' => $this->email,
            'phone' => $this->phone,
            'shipping_address' => [
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'street' => $this->shipping_street,
                'house_number' => $this->shipping_house_number,
                'postal_code' => $this->shipping_postal_code,
                'city' => $this->shipping_city,
                'country' => $this->shipping_country,
            ],
            'billing_address' => $this->same_as_shipping ? [
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'street' => $this->shipping_street,
                'house_number' => $this->shipping_house_number,
                'postal_code' => $this->shipping_postal_code,
                'city' => $this->shipping_city,
                'country' => $this->shipping_country,
            ] : [
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'street' => $this->billing_street,
                'house_number' => $this->billing_house_number,
                'postal_code' => $this->billing_postal_code,
                'city' => $this->billing_city,
                'country' => $this->billing_country,
            ],
        ];

        try {
            $checkoutUrl = $prepareCheckout->execute($this->notes, $customerDetails);
            return redirect($checkoutUrl);
        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        $cartService = app(CartService::class);
        $customer = Auth::guard('customer')->user();
        $savedAddresses = $customer ? CustomerAddress::where('customer_id', $customer->id)->get() : collect();

        $subtotal = $cartService->getTotal();
        $shippingFee = $cartService->getShippingFee();
        $grandTotal = $cartService->getGrandTotal();

        $vatPercentage = (float) (\App\Models\Tenant\Setting::where('key', 'invoice_vat_percentage')->first()?->value ?? 21);
        $taxAmount = $subtotal - ($subtotal / (1 + ($vatPercentage / 100)));

        return view('livewire.storefront.checkout.index', [
            'items' => $cartService->getItems(),
            'subtotal' => $subtotal,
            'shippingFee' => $shippingFee,
            'grandTotal' => $grandTotal,
            'taxAmount' => $taxAmount,
            'vatPercentage' => $vatPercentage,
            'savedAddresses' => $savedAddresses,
            'isCustomer' => (bool) $customer,
        ]);
    }
}
