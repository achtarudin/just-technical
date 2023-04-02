<?php

use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('Welcome API')->get('/apiv1/welcome')->assertStatus(200)->assertJson(['message' => 'welcome to my api']);

beforeEach(function () {
    $faker = Faker\Factory::create();
    $password = 'SuperSecret214';
    $this->credential =  [
        'name'                  => $faker->name,
        'email'                 => $faker->email,
        'password'              => $password,
        'password_confirmation' => $password,
    ];
});

it('Validation Error When Body Is Empty', function () {
    $response = $this->postJson('/apiv1/registration', []);
    $response->assertInvalid(['name', 'email', 'password', 'password_confirmation']);
    $response->assertStatus(422);
});

it('Validation Error When Body Name Is Empty', function () {
    $response = $this->postJson('/apiv1/registration', array_merge($this->credential, ['name' => '']));
    $response->assertInvalid(['name']);
    $response->assertStatus(422);
});

it('Validation Error When Body Email Is Empty', function () {
    $response = $this->postJson('/apiv1/registration', array_merge($this->credential, ['email' => '']));
    $response->assertInvalid(['email']);
    $response->assertStatus(422);
});

it('Validation Error When Body Password Is Empty', function () {
    $response = $this->postJson('/apiv1/registration', array_merge($this->credential, ['password' => '']));
    $response->assertInvalid(['password']);
    $response->assertStatus(422);
});

it('Validation Error When Body Password Confirmation Is Empty', function () {
    $response = $this->postJson('/apiv1/registration', array_merge($this->credential, ['password_confirmation' => '']));
    $response->assertInvalid(['password_confirmation']);
    $response->assertStatus(422);
});

it('Validation Error When Body Password And Password Confirmation Not Same', function () {
    $response = $this->postJson('/apiv1/registration', array_merge($this->credential, ['password_confirmation' => 'Helllo word']));
    $response->assertInvalid(['password']);
    $response->assertStatus(422);
});

it('Validation Success When All Body Is Set', function () {
    $credential = $this->credential;
    $response = $this->postJson('/apiv1/registration', $credential);

    $user = UserModel::where('email', $credential['email'])->first();

    expect($user != null)->toBeTrue();
    expect($user->user_type != null)->toBeTrue();
    expect($user->otp_registration != null)->toBeTrue();

    $response
        ->assertStatus(200)
        ->assertJson(['message' => "Registration Success for email : " . $user->email]);
});
