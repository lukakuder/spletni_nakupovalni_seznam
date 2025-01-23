<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            //Pri tem lahk pride do napake ker more username bit unique
            //GroupSeeder::class,
            TagSeeder::class,
            GroupSeeder::class,
            ShoppingListSeeder::class,
        ]);
    }
}
