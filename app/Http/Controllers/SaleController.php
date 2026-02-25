<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Services\SaleService;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SaleController extends Controller
{
    public function __construct(
        private readonly SaleService $saleService
    ) {
    }

    /**
     * List sales records.
     */
    public function index(): View
    {
        $sales = Sale::query()
            ->with('product')
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->paginate(20);

        return view('sales.index', compact('sales'));
    }

    /**
     * Show sale creation form.
     */
    public function create(): View
    {
        $products = Product::query()
            ->orderBy('name')
            ->get();

        $defaultVatPercent = (float) config('accounting.default_vat_percent', 0);

        return view('sales.create', compact('products', 'defaultVatPercent'));
    }

    /**
     * Store a sale transaction.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'vat_percent' => ['nullable', 'numeric', 'min:0'],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
            'date' => ['required', 'date'],
        ]);

        try {
            $this->saleService->create($validated);
        } catch (DomainException $exception) {
            return back()
                ->withInput()
                ->withErrors(['sale' => $exception->getMessage()]);
        }

        return redirect()
            ->route('sales.index')
            ->with('success', 'Sale recorded, stock updated, and journal entries posted.');
    }
}
