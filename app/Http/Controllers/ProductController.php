<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\AccountingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        private readonly AccountingService $accountingService
    ) {
    }

    /**
     * Display all products.
     */
    public function index(): View
    {
        $products = Product::query()
            ->latest('id')
            ->paginate(15);

        return view('products.index', compact('products'));
    }

    /**
     * Show product creation form.
     */
    public function create(): View
    {
        return view('products.create');
    }

    /**
     * Store a new product and opening inventory journal.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'sell_price' => ['required', 'numeric', 'min:0'],
            'opening_stock' => ['required', 'integer', 'min:0'],
        ]);

        DB::transaction(function () use ($validated): void {
            $product = Product::query()->create([
                'name' => $validated['name'],
                'purchase_price' => $validated['purchase_price'],
                'sell_price' => $validated['sell_price'],
                'stock' => $validated['opening_stock'],
            ]);

            $this->accountingService->recordOpeningInventory($product);
        });

        return redirect()
            ->route('products.index')
            ->with('success', 'Product created and opening inventory posted.');
    }
}
