<?php

namespace Database\Factories;

use App\Models\ListItem;
use App\Models\ShoppingList;
use Illuminate\Database\Eloquent\Factories\Factory;

class ListItemFactory extends Factory
{
    protected $model = ListItem::class;

    public function definition(): array
    {
        $amount = $this->faker->numberBetween(1, 20); // Naključno število artiklov
        $pricePerItem = $this->faker->randomFloat(2, 0.5, 100); // Cena posameznega artikla
        $purchased = $this->faker->numberBetween(0, $amount); // Naključno število kupljenih artiklov

        return [
            'shopping_list_id' => ShoppingList::factory(),
            'name' => $this->faker->words(2, true), // Ime artikla
            'amount' => $amount,
            'purchased' => $purchased,
            'price_per_item' => $pricePerItem,
            'total_price' => $amount * $pricePerItem,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

