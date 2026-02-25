<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'product_id',
        'quantity',
        'unit_price',
        'discount',
        'vat_percent',
        'subtotal',
        'vat_amount',
        'total_amount',
        'paid_amount',
        'due_amount',
        'date',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'unit_price' => 'decimal:2',
            'discount' => 'decimal:2',
            'vat_percent' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'vat_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'due_amount' => 'decimal:2',
            'date' => 'date',
        ];
    }

    /**
     * Get the product sold in this transaction.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
