<?php

namespace App\Models;

use App\Enums\OpnameShift;
use App\Enums\OpnameStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Opname extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'reference',
        'opname_date',
        'user_id',
        'status',
        'shift',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => OpnameStatus::class,
            'shift' => OpnameShift::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->using(OpnameProduct::class);
    }

    public function opnameProducts(): HasMany
    {
        return $this->hasMany(OpnameProduct::class);
    }
}
