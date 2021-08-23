<?php

namespace Tests\Feature;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class TodoTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_show_his_all_todos()
    {
        $this->loginAsUser();
        Todo::factory()->count(10)->create();

        $response = $this->getJson('api/todo');

        $this->assertDatabaseCount('todos', 10);

        $response
            ->assertStatus(200)
            ->assertJson(fn(AssertableJson $json) => $json->has('data', 10)
                ->etc()
            );

    }

    public function test_user_can_show_single_todo()
    {
        $this->loginAsUser();
        $todo = Todo::factory()->create();

        $response = $this->getJson('api/todo/1');

        $response
            ->assertStatus(200)
            ->assertJson(fn(AssertableJson $json) => $json->has('data')
                ->where('data.title', $todo->title)
                ->etc()
            );

    }

    public function test_user_can_delete_todo()
    {
        $this->loginAsUser();
        Todo::factory()->create();
        $this->assertDatabaseCount('todos', 1);

        $response = $this->deleteJson('api/todo/1');

        $this->assertDatabaseCount('todos', 0);

        $response->assertStatus(204);

    }

    public function test_user_can_create_todo()
    {
        $this->loginAsUser();
        $todo = Todo::factory()->make();

        $response = $this->postJson('/api/todo',
            [
                'title' => $todo->title,
                'description' => $todo->description,
            ]);

        $response
            ->assertStatus(201)
            ->assertJson(fn(AssertableJson $json) => $json->has('meta')
                ->has('data')
                ->where('meta.message', 'Todo created successfully')
                ->where('data.title', $todo->title)
                ->where('data.description', $todo->description)
                ->etc()
            );
    }

    public function test_title_validation()
    {

        $this->loginAsUser();
        $todo = Todo::factory()->make();

        $response = $this->postJson('/api/todo',
            [
                'title' => '',
                'description' => $todo->description,
            ]);

        $response
            ->assertStatus(422)
            ->assertJson(fn (AssertableJson $json) =>
            $json->has('message')
                ->has('errors')
                ->where('message', 'The given data was invalid.')
                ->where('errors.title.0', 'The title field is required.')
                ->etc()
            );
    }

    public function test_description_validation()
    {

        $this->loginAsUser();
        $todo = Todo::factory()->make();

        $response = $this->postJson('/api/todo',
            [
                'title' => $todo->title,
                'description' => '',
            ]);

        $response
            ->assertStatus(422)
            ->assertJson(fn (AssertableJson $json) =>
            $json->has('message')
                ->has('errors')
                ->where('message', 'The given data was invalid.')
                ->where('errors.description.0', 'The description field is required.')
                ->etc()
            );
    }

    private function loginAsUser()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
    }
}
