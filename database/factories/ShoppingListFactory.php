<?php

namespace Database\Factories;

use App\Models\ShoppingList;
use App\Models\User;
use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShoppingListFactory extends Factory
{
    protected $model = ShoppingList::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'group_id' => $this->faker->boolean(50) ? Group::factory() : null, // Približno polovica seznamov je povezana z grupami
            'belongs_to_a_group' => function (array $attributes) {
                return $attributes['group_id'] !== null;
            },
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'reminder_date' => $this->faker->optional()->dateTimeBetween('now', '+1 year'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
