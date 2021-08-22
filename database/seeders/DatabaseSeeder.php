<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $user = \App\Models\User::factory()->create(['email' => 'user@demo.com']);
         \App\Models\Todo::factory(50)->create(['user_id' => $user->id]);
    }
}
