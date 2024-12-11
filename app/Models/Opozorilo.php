<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opozorilo extends Model
{
    use HasFactory;

    // Nastavite ime tabele, če se razlikuje od privzete oblike
    protected $table = 'opozorila';

    protected $fillable = ['user_id', 'sporočilo', 'prebrano','cas'];
}
