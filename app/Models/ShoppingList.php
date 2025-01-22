<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Spatie\Tags\HasTags;

class ShoppingList extends Model
{
    use HasFactory, Notifiable, HasTags;

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
        'belongs_to_a_group',
        'reminder_date',
        'receipt_image',
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
            'reminder_date' => 'date',
        ];
    }

    /**
     * The user that belong to the shopping list.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The group that belong to the shopping list.
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    /**
     * The items that belong to the shopping list.
     */
    public function items()
    {
        return $this->hasMany(ListItem::class, 'shopping_list_id');
    }

    /**
     * ScopeFilter for filtering
     *
     * @param $query
     * @param $filters
     * @return mixed
     */
    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }
}
