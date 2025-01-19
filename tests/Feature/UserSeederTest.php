<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserSeederTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_it_seeds_users_correctly()
    {
        $this->seed(UserSeeder::class);

        // Expect a total of 6 users
        $this->assertDatabaseCount('users', 6);

        // Validate specific users
        $this->assertDatabaseHas('users', ['email' => 'test1@example.com']);
        $this->assertDatabaseHas('users', ['email' => 'test2@example.com']);
        $this->assertDatabaseHas('users', ['email' => 'test3@example.com']);
    }


}


