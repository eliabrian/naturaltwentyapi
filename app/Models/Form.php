<?php

namespace App\Models;

use App\Enums\FormStatus;
use App\Enums\OpnameShift;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Form extends Model
{
    protected $fillable = [
        'reference',
        'form_date',
        'user_id',
        'status',
        'shift',
        'location',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'form_date' => 'datetime',
            'status' => FormStatus::class,
            'shift' => OpnameShift::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    public function formProducts(): HasMany
    {
        return $this->hasMany(FormProduct::class);
    }
}
