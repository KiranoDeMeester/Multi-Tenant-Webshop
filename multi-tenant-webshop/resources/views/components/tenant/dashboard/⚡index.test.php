<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('tenant.dashboard.index')
        ->assertStatus(200);
});
