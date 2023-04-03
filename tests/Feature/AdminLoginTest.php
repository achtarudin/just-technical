<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $faker = Faker\Factory::create();
    $email      = "new@admin.com";
    $password   = 'secret214';

    $this->credential =  [
        'email'                 => $email ?? $faker->email,
        'password'              => $password,
    ];
});

it('has welcome page')->get('/')->assertStatus(200);


it('Validation Error When Body Is Empty Admin Login', function () {
    $response = $this->postJson('/apiv1/admin/login', []);
    $response->assertInvalid(['email', 'password']);
    $response->assertStatus(422);
});

it('Validation Error When Body Email Is Empty', function () {
    $response = $this->postJson('/apiv1/admin/login', array_merge($this->credential, ['email' => '']));
    $response->assertInvalid(['email']);
    $response->assertStatus(422);
});

it('Validation Error When Body Password Is Empty', function () {
    $response = $this->postJson('/apiv1/admin/login', array_merge($this->credential, ['password' => '']));
    $response->assertInvalid(['password']);
    $response->assertStatus(422);
});


it('Validation Error When Admin Not Found', function () {
    $response = $this->postJson('/apiv1/admin/login', array_merge($this->credential, ['email' => 'my@email.com']));
    $response->assertInvalid(['email']);
    $response->assertStatus(422);
});

it('Validation Error Let See What Error i get', function () {
    $response = $this->postJson('/apiv1/admin/login', array_merge($this->credential));
    $response->assertStatus(500);
});
