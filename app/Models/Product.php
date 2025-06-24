<?php

namespace App\Models;

use App\Enums\ProductLocation;
use App\Enums\ProductType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'sku',
        'name',
        'type',
        'location',
        'unit',
        'price',
        'stock',
        'security_stock',
        'note',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => ProductType::class,
            'location' => ProductLocation::class,
        ];
    }

    public function suppliers(): BelongsToMany
    {
        return $this->belongsToMany(Supplier::class);
    }

    public function opnames(): BelongsToMany
    {
        return $this->belongsToMany(Opname::class)->using(OpnameProduct::class);
    }

    public function opnameProduct(): HasMany
    {
        return $this->hasMany(OpnameProduct::class);
    }

    public function forms(): BelongsToMany
    {
        return $this->belongsToMany(Form::class)->using(FormProduct::class);
    }

    public function formProducts(): HasMany
    {
        return $this->hasMany(FormProduct::class);
    }
}
