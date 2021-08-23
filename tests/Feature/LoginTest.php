<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    public function test_user_can_login_with_correct_credentials()
    {
        $user = User::factory()->create();
        $response = $this->postJson('/api/login',
            [
                'email' => $user->email,
                'password' => 'password'
            ]);

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
            $json->has('meta')
                ->has('data')
                ->where('meta.token_type', 'Bearer')
                ->where('data.name', $user->name)
                ->where('data.email', $user->email)
                ->etc()
            );
    }

    public function test_user_can_not_login_with_incorrect_credentials()
    {
        $user = User::factory()->create();
        $response = $this->postJson('/api/login',
            [
                'email' => $user->email,
                'password' => 'incorrect'
            ]);

        $response
            ->assertStatus(422)
            ->assertJson(fn (AssertableJson $json) =>
            $json->has('message')
                ->has('errors')
                ->where('message', 'The given data was invalid.')
                ->etc()
            );
    }

    public function test_email_validation()
    {
        $response = $this->postJson('/api/login',
            [
                'email' => '',
                'password' => 'password'
            ]);
        $response
            ->assertStatus(422)
            ->assertJson(fn (AssertableJson $json) =>
            $json->has('message')
                ->has('errors')
                ->where('message', 'The given data was invalid.')
                ->where('errors.email.0', 'The email field is required.')
                ->etc()
            );
    }

    public function test_password_validation()
    {
        $response = $this->postJson('/api/login',
            [
                'email' => 'user@demo.com',
                'password' => ''
            ]);
        $response
            ->assertStatus(422)
            ->assertJson(fn (AssertableJson $json) =>
            $json->has('message')
                ->has('errors')
                ->where('message', 'The given data was invalid.')
                ->where('errors.password.0', 'The password field is required.')
                ->etc()
            );
    }
}
