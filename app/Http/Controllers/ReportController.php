<?php

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use App\Models\Sale;
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

        $salesQuery = Sale::query()
            ->whereBetween('date', [$from, $to]);

        $totalSales = (float) (clone $salesQuery)->sum('total_amount');
        $totalDue = (float) (clone $salesQuery)->sum('due_amount');
        $totalExpense = (float) JournalEntry::query()
            ->where('account_name', config('accounting.accounts.cost_of_goods_sold'))
            ->whereBetween('date', [$from, $to])
            ->sum('debit');
        $totalProfit = round($totalSales - $totalExpense, 2);

        return view('reports.index', compact(
            'from',
            'to',
            'totalSales',
            'totalExpense',
            'totalProfit',
            'totalDue',
        ));
    }
}
