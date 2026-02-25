<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Services\AccountingService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Seed a demo product with opening stock accounting.
     */
    public function run(): void
    {
        if (Product::query()->where('name', 'Demo Product')->exists()) {
            return;
        }

        DB::transaction(function (): void {
            $product = Product::query()->create([
                'name' => 'Demo Product',
                'purchase_price' => 100,
                'sell_price' => 200,
                'stock' => 50,
            ]);

            app(AccountingService::class)->recordOpeningInventory($product);
        });
    }
}
