<?php

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Render financial report by date range.
     */
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $from = Carbon::parse($validated['from'] ?? now()->startOfMonth()->toDateString())->toDateString();
        $to = Carbon::parse($validated['to'] ?? now()->toDateString())->toDateString();

        $salesRevenueAccount = (string) config('accounting.accounts.sales_revenue');
        $cogsAccount = (string) config('accounting.accounts.cost_of_goods_sold');
        $accountsReceivableAccount = (string) config('accounting.accounts.accounts_receivable');

        $netSales = (float) JournalEntry::query()
            ->where('account_name', $salesRevenueAccount)
            ->whereBetween('date', [$from, $to])
            ->sum('credit');

        $totalExpense = (float) JournalEntry::query()
            ->where('account_name', $cogsAccount)
            ->whereBetween('date', [$from, $to])
            ->sum('debit');

        // Due is the closing Accounts Receivable balance as of the report end date.
        $accountsReceivableDebit = (float) JournalEntry::query()
            ->where('account_name', $accountsReceivableAccount)
            ->whereDate('date', '<=', $to)
            ->sum('debit');

        $accountsReceivableCredit = (float) JournalEntry::query()
            ->where('account_name', $accountsReceivableAccount)
            ->whereDate('date', '<=', $to)
            ->sum('credit');

        $totalDue = round($accountsReceivableDebit - $accountsReceivableCredit, 2);
        $totalProfit = round($netSales - $totalExpense, 2);

        return view('reports.index', compact(
            'from',
            'to',
            'netSales',
            'totalExpense',
            'totalProfit',
            'totalDue',
        ));
    }
}
