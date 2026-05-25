<?php

use App\Livewire\Landlord\ContactForm;
use App\Models\Landlord\ContactMessage;
use Livewire\Livewire;

beforeEach(function () {
    $this->migrateLandlord();
});

test('contact form can be submitted successfully', function () {
    Livewire::test(ContactForm::class)
        ->set('name', 'John Doe')
        ->set('email', 'john@example.com')
        ->set('message', 'Dit is een testbericht van minstens tien tekens.')
        ->call('submit')
        ->assertHasNoErrors()
        ->assertSet('success', true);

    expect(ContactMessage::count())->toBe(1);
    expect(ContactMessage::first()->name)->toBe('John Doe');
});

test('contact form validation errors', function () {
    Livewire::test(ContactForm::class)
        ->set('name', '')
        ->set('email', 'invalid-email')
        ->set('message', 'kort')
        ->call('submit')
        ->assertHasErrors(['name', 'email', 'message']);

    expect(ContactMessage::count())->toBe(0);
});
