<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Sale;
use DomainException;
use Illuminate\Support\Facades\DB;

class SaleService
{
    public function __construct(
        private readonly AccountingService $accountingService
    ) {
    }

    /**
     * Create a sale, update stock, and post accounting entries.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Sale
    {
        return DB::transaction(function () use ($data): Sale {
            $product = Product::query()
                ->lockForUpdate()
                ->findOrFail((int) $data['product_id']);

            $quantity = (int) $data['quantity'];
            $discount = $this->money((float) ($data['discount'] ?? 0));
            $vatPercent = (float) ($data['vat_percent'] ?? config('accounting.default_vat_percent', 0));
            $paidAmount = $this->money((float) ($data['paid_amount'] ?? 0));
            $date = (string) ($data['date'] ?? now()->toDateString());

            if ($quantity > $product->stock) {
                throw new DomainException('Insufficient stock for the selected product.');
            }

            $subtotal = $this->money($quantity * (float) $product->sell_price);

            if ($discount > $subtotal) {
                throw new DomainException('Discount cannot be greater than subtotal.');
            }

            $netSale = $this->money($subtotal - $discount);
            $vatAmount = $this->money($netSale * ($vatPercent / 100));
            $totalAmount = $this->money($netSale + $vatAmount);

            if ($paidAmount > $totalAmount) {
                throw new DomainException('Paid amount cannot exceed total receivable.');
            }

            $dueAmount = $this->money($totalAmount - $paidAmount);
            $cogsAmount = $this->money($quantity * (float) $product->purchase_price);

            $sale = Sale::query()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $product->sell_price,
                'discount' => $discount,
                'vat_percent' => $vatPercent,
                'subtotal' => $subtotal,
                'vat_amount' => $vatAmount,
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'due_amount' => $dueAmount,
                'date' => $date,
            ]);

            $product->decrement('stock', $quantity);
            $sale->setRelation('product', $product);

            $this->accountingService->recordSale($sale, $cogsAmount);

            return $sale;
        });
    }

    private function money(float $amount): float
    {
        return round($amount, 2);
    }
}
