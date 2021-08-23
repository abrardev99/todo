<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_me()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->getJson('api/me');

        $response
            ->assertStatus(200)
            ->assertJson(fn(AssertableJson $json) => $json->has('data')
                ->where('data.name', $user->name)
                ->where('data.email', $user->email)
                ->etc()
            );

    }
}
