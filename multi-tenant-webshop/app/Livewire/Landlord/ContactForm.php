<?php

namespace App\Livewire\Landlord;

use App\Models\Landlord\ContactMessage;
use Livewire\Component;

class ContactForm extends Component
{
    public string $name = '';
    public string $email = '';
    public string $message = '';
    public bool $success = false;

    protected array $rules = [
        'name' => 'required|string|min:2|max:100',
        'email' => 'required|email|max:255',
        'message' => 'required|string|min:10|max:2000',
    ];

    protected array $messages = [
        'name.required' => 'Vul je naam in.',
        'email.required' => 'Vul je e-mailadres in.',
        'email.email' => 'Vul een geldig e-mailadres in.',
        'message.required' => 'Vul een bericht in.',
        'message.min' => 'Het bericht moet minimaal 10 tekens bevatten.',
    ];

    public function submit()
    {
        $validatedData = $this->validate();

        ContactMessage::create($validatedData);

        $this->reset(['name', 'email', 'message']);
        $this->success = true;
    }

    public function render()
    {
        return view('livewire.landlord.contact-form');
    }
}
