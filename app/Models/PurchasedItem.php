<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasedItem extends Model
{
    use HasFactory;

    protected $fillable = ['list_item_id', 'user_id', 'quantity'];

    public function listItem()
    {
        return $this->belongsTo(ListItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function totalPurchasedForItem($itemId)
    {
        return self::where('list_item_id', $itemId)->sum('quantity');
    }
}
