<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = ['shopping_list_id', 'name', 'file_path'];

    public function list()
    {
        return $this->belongsTo(ShoppingList::class, 'shopping_list_id');
    }
}
