<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opozorilo extends Model
{
    use HasFactory;
    protected $table = 'opozorilas'; // Dodano, če želite eksplicitno določiti ime tabele

    protected $fillable = [
        'user_id',
        'message',
        'prebrano'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
