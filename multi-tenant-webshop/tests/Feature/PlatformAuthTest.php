<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->migrateLandlord();
});

test('login page is accessible on central domain', function () {
    Config::set('app.central_domain', 'localhost');

    $response = $this->get('http://platform.localhost/login');

    $response->assertStatus(200);
    $response->assertSee('Inloggen');
});

test('login page is not accessible on tenant domain', function () {
    Config::set('app.central_domain', 'localhost');

    $response = $this->get('http://tenant1.localhost/login');

    // Because the route is not defined for the tenant domain group
    $response->assertStatus(404);
});

test('platform admin can log in on central domain', function () {
    Config::set('app.central_domain', 'localhost');

    $user = User::factory()->create([
        'email' => 'admin@platform.test',
        'password' => bcrypt('password'),
    ]);

    $response = $this->post('http://platform.localhost/login', [
        'email' => 'admin@platform.test',
        'password' => 'password',
    ]);

    $response->assertRedirect('/dashboard');
    $this->assertAuthenticatedAs($user);
});
