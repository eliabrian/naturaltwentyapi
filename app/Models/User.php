<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role_id' => UserRole::class,
        ];
    }

    /**
     * Get the role that owns the user.
     *
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(related: Role::class);
    }

    /**
     * Return true if the User is an admin user.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role->id === UserRole::Admin->value;
    }

    /**
     * Return true if the current user is a Chef.
     *
     * @return bool
     */
    public function isChef(): bool
    {
        return $this->role->id === UserRole::Chef->value;
    }

    /**
     * Return true if the current user is a Cashier.
     *
     * @return bool
     */
    public function isCashier(): bool
    {
        return $this->role->id === UserRole::Cashier->value;
    }

    /**
     * Return true if the current user is a Game Master.
     *
     * @return bool
     */
    public function isGameMaster(): bool
    {
        return $this->role->id === UserRole::GameMaster->value;
    }

    /**
     * Return true if the current user is a Dungeon Master.
     *
     * @return bool
     */
    public function isDungeonMaster(): bool
    {
        return $this->role->id === UserRole::DungeonMaster->value;
    }
}
