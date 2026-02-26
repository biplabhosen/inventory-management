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
        $salesRevenueAccount = (string) config('accounting.accounts.sales_revenue');
        $cogsAccount = (string) config('accounting.accounts.cost_of_goods_sold');
        $accountsReceivableAccount = (string) config('accounting.accounts.accounts_receivable');

        $netSales = (float) JournalEntry::query()
            ->where('account_name', $salesRevenueAccount)
            ->sum('credit');

        $totalExpense = (float) JournalEntry::query()
            ->where('account_name', $cogsAccount)
            ->sum('debit');

        $accountsReceivableDebit = (float) JournalEntry::query()
            ->where('account_name', $accountsReceivableAccount)
            ->sum('debit');
        $accountsReceivableCredit = (float) JournalEntry::query()
            ->where('account_name', $accountsReceivableAccount)
            ->sum('credit');

        $totalDue = round($accountsReceivableDebit - $accountsReceivableCredit, 2);
        $currentStock = (int) Product::query()->sum('stock');
        $profit = round($netSales - $totalExpense, 2);

        $recentSales = Sale::query()
            ->with('product')
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        return view('dashboard.index', compact(
            'netSales',
            'totalExpense',
            'totalDue',
            'currentStock',
            'profit',
            'recentSales',
        ));
    }
}
