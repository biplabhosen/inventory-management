<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Sale;
use App\Services\SaleService;
use Illuminate\Database\Seeder;

class SaleSeeder extends Seeder
{
    /**
     * Seed a sample sale based on the scenario.
     */
    public function run(): void
    {
        $product = Product::query()
            ->where('name', 'Demo Product')
            ->first();

        if (! $product) {
            return;
        }

        if (Sale::query()->where('product_id', $product->id)->exists()) {
            return;
        }

        app(SaleService::class)->create([
            'product_id' => $product->id,
            'quantity' => 10,
            'discount' => 50,
            'vat_percent' => 5,
            'paid_amount' => 1000,
            'date' => now()->toDateString(),
        ]);
    }
}
