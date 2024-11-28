<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class UserFactoryTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_creates_a_user_with_valid_data()
    {
        // Ustvari uporabnika
        $user = User::factory()->create();

        // Preveri, ali je uporabnik pravilno ustvarjen v bazi
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => $user->email,
        ]);
    }

    #[Test]
    public function it_creates_multiple_users()
    {
        // Ustvari 10 uporabnikov
        $users = User::factory()->count(10)->create();

        // Preveri, ali je baza posodobljena z 10 zapisi
        $this->assertCount(10, $users);
        $this->assertDatabaseCount('users', 10);
    }
}
