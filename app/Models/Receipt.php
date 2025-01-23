<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model za tabelo 'receipts', ki predstavlja račune naložene za posamezne nakupovalne sezname.
 */
class Receipt extends Model
{
    use HasFactory;

    // Polja, ki jih je mogoče množično dodeliti
    protected $fillable = ['shopping_list_id', 'name', 'file_path'];

    /**
     * Povezava z modelom ShoppingList.
     * Vsak račun pripada določenemu nakupovalnemu seznamu.
     */
    public function list()
    {
        return $this->belongsTo(ShoppingList::class, 'shopping_list_id');
    }
}
