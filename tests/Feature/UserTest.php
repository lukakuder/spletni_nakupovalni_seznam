<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_delete_account()
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);

        $response = $this->actingAs($user)->delete('/profile', [
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_user_cannot_delete_account_with_wrong_password()
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);

        $response = $this->actingAs($user)->delete('/profile', [
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }
}
