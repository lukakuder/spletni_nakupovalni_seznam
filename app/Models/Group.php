<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Group extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description'
    ];
    public function getAllMembers()
    {
        return $this->users()->get();
    }
    /**
     * Get all shopping lists for the group.
     */
    public function getShoppingLists()
    {
        return $this->shoppingLists()->get(); // returns collection of lists
    }
    /**
     * The users that belong to the group.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_user');
    }

    /**
     * The lists that belong to the group.
     */
    protected function lists(): HasMany
    {
        return $this->hasMany(ShoppingList::class);
    }
}

