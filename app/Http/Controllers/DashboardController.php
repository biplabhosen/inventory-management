<?php

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display high-level KPIs.
     */
    public function index(): View
    {
        $totalSales = (float) Sale::query()->sum('total_amount');
        $totalExpense = (float) JournalEntry::query()
            ->where('account_name', config('accounting.accounts.cost_of_goods_sold'))
            ->sum('debit');
        $totalDue = (float) Sale::query()->sum('due_amount');
        $currentStock = (int) Product::query()->sum('stock');
        $profit = round($totalSales - $totalExpense, 2);

        $recentSales = Sale::query()
            ->with('product')
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        return view('dashboard.index', compact(
            'totalSales',
            'totalExpense',
            'totalDue',
            'currentStock',
            'profit',
            'recentSales',
        ));
    }
}
