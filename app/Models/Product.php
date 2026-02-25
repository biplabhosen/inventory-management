<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'purchase_price',
        'sell_price',
        'stock',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'purchase_price' => 'decimal:2',
            'sell_price' => 'decimal:2',
            'stock' => 'integer',
        ];
    }

    /**
     * Get all sales associated with the product.
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}
