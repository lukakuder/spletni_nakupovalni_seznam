<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class ShoppingList extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'group_id',
        'name',
        'description',
        'belongs_to_a_group'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'belongs_to_a_group' => 'boolean',
        ];
    }

    /**
     * The user that belong to the shopping list.
     */
    protected function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The group that belong to the shopping list.
     */
    protected function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    /**
     * The items that belong to the shopping list.
     */
    protected function items(): HasMany
    {
        return $this->hasMany(ListItem::class);
    }
}
