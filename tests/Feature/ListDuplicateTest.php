<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\ShoppingList;
use App\Models\ListItem;

class ListDuplicateTest extends TestCase
{
    use RefreshDatabase;

    public function test_duplicate_list_without_items()
    {
        $user = User::factory()->create();
        $list = ShoppingList::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->postJson(route('lists.duplicate', $list->id))
            ->assertStatus(201)
            ->assertJsonPath('new_list.name', "{$list->name} (kopija)");
    }

    public function test_duplicate_list_with_items()
    {
        $user = User::factory()->create();
        $list = ShoppingList::factory()->create(['user_id' => $user->id]);
        ListItem::factory()->count(3)->create(['list_id' => $list->id]);

        $response = $this->actingAs($user)
            ->postJson(route('lists.duplicate', $list->id));

        $response->assertStatus(201);
        $this->assertCount(3, ListItem::where('list_id', $response->json('new_list.id'))->get());
    }

    public function test_duplicate_list_not_owned_by_user()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $list = ShoppingList::factory()->create(['user_id' => $otherUser->id]);

        $this->actingAs($user)
            ->postJson(route('lists.duplicate', $list->id))
            ->assertStatus(403);
    }

    public function test_duplicate_nonexistent_list()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson(route('lists.duplicate', 9999))
            ->assertStatus(404);
    }
}
