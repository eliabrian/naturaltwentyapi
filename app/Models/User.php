<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\ProductLocation;
use App\Enums\UserRole;
use App\Enums\UserType;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
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
        'type',
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
            'type' => UserType::class,
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    /**
     * Get the role that owns the user.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(related: Role::class);
    }

    /**
     * Return true if the User is an admin user.
     */
    public function isAdmin(): bool
    {
        return $this->role->id === UserRole::Admin->value;
    }

    /**
     * Return true if the current user is a Chef.
     */
    public function isChef(): bool
    {
        return $this->role->id === UserRole::Chef->value;
    }

    /**
     * Return true if the current user is a Cashier.
     */
    public function isCashier(): bool
    {
        return $this->role->id === UserRole::Cashier->value;
    }

    /**
     * Return true if the current user is a Game Master.
     */
    public function isGameMaster(): bool
    {
        return $this->role->id === UserRole::GameMaster->value;
    }

    /**
     * Return true if the current user is a Dungeon Master.
     */
    public function isDungeonMaster(): bool
    {
        return $this->role->id === UserRole::DungeonMaster->value;
    }

    public function getUserLocation(): string
    {
        switch ($this->role->id) {
            case UserRole::Cashier:
                return ProductLocation::Bar->value;
                break;
            default:
            case UserRole::Chef:
                return ProductLocation::Kitchen->value;
                break;
        }

        return ProductLocation::Storage->value;
    }
}
