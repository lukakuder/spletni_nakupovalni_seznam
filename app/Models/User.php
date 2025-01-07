<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage; // Import Storage facade


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_picture'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $appends = [
        'profile_picture_url',
    ];
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The groups that belong to the user.
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_user');
    }

    /**
     * Accessor for the profile picture URL.
     */
    /**
     * Accessor for the profile picture URL.
     */
    public function getProfilePictureUrlAttribute(): ?string
    {
        return $this->profile_picture
            ? asset('storage/' . $this->profile_picture)
            : asset('storage/profile_pictures/default-avatar.png');

    }
    /**
     * The lists that belong only to the user, and aren't associated with a group.
     */
    public function lists(): HasMany
    {
        return $this->hasMany(ShoppingList::class);
    }
}
