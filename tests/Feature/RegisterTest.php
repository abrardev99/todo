<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Mail;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;
    public function test_user_can_register_successfully()
    {
        $user = User::factory()->make();

        Mail::fake();

        $response = $this->postJson('/api/register',
            [
                'name' => $user->name,
                'email' => $user->email,
                'password' => 'password',
                'password_confirmation' => 'password'
            ]);

        $this->assertDatabaseHas('users', ['email' => $user->email]);

        $response
            ->assertStatus(201)
            ->assertJson(fn (AssertableJson $json) =>
            $json->has('data')
                ->where('data.name', $user->name)
                ->where('data.email', $user->email)
                ->etc()
            );

    }

    public function test_email_validation()
    {
        $user = User::factory()->make();

        Mail::fake();

        $response = $this->postJson('/api/register',
            [
                'name' => $user->name,
                'email' => '',
                'password' => 'password',
                'password_confirmation' => 'password'
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
        $user = User::factory()->make();

        Mail::fake();

        $response = $this->postJson('/api/register',
            [
                'name' => $user->name,
                'email' => $user->email,
                'password' => '',
                'password_confirmation' => 'password'
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
