<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'opozorilas'; // Ime tabele, če je potrebno eksplicitno

    protected $fillable = [
        'user_id',
        'message',
        'prebrano',
        'group_id', // Dodano za povezavo s skupinami
    ];

    /**
     * Relacija z uporabniki.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }


    /**
     * Statična metoda za ustvarjanje opozorila.
     *
     * @param int $userId ID uporabnika, ki prejme opozorilo
     * @param string $message Vsebina opozorila
     * @param int|null $groupId ID skupine, če je opozorilo povezano s skupino
     * @return Notification Novo ustvarjeno opozorilo
     */
    public static function createForUser(int $userId, string $message, ?int $groupId = null): self
    {
        return self::create([
            'user_id' => $userId,
            'message' => $message,
            'prebrano' => false,
            'group_id' => $groupId,
        ]);
    }
}
