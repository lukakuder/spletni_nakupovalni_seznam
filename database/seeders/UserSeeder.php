<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(3)->create();

        // Ustvarjanje testnih uporabnikov
        User::create([
            'name' => 'Testni Uporabnik 1',
            'email' => 'test1@example.com',
            'password' => Hash::make('password1'),
        ]);

        User::create([
            'name' => 'Testni Uporabnik 2',
            'email' => 'test2@example.com',
            'password' => Hash::make('password2'),
        ]);

        User::create([
            'name' => 'Testni Uporabnik 3',
            'email' => 'test3@example.com',
            'password' => Hash::make('password3'),
        ]);
    }
}
