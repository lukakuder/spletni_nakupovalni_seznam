<?php
// Database/Seeders/ShoppingListSeeder.php
namespace Database\Seeders;

use App\Models\ShoppingList;
use App\Models\ListItem;
use App\Models\User;
use App\Models\Group;
use Illuminate\Database\Seeder;

class ShoppingListSeeder extends Seeder
{
    public function run()
    {
        // Ustvari sezname za uporabnike
        User::all()->each(function ($user) {
            $lists = ShoppingList::factory(2)->create([
                'user_id' => $user->id,
                'group_id' => null,
                'belongs_to_a_group' => false,
            ]);

            // Dodaj elemente na vsak seznam
            foreach ($lists as $list) {
                ListItem::factory(5)->create(['shopping_list_id' => $list->id]);
            }
        });

        // Ustvari sezname za skupine
        Group::all()->each(function ($group) {
            $lists = ShoppingList::factory(2)->create([
                'group_id' => $group->id,
                'user_id' => null,
                'belongs_to_a_group' => true,
            ]);

            foreach ($lists as $list) {
                ListItem::factory(5)->create(['shopping_list_id' => $list->id]);
            }
        });
    }
}
