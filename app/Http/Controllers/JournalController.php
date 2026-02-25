<?php

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use Illuminate\View\View;

class JournalController extends Controller
{
    /**
     * Show the ledger grouped by date with account balances.
     */
    public function index(): View
    {
        $entriesByDate = JournalEntry::query()
            ->orderByDesc('date')
            ->orderBy('id')
            ->get()
            ->groupBy(fn (JournalEntry $entry): string => $entry->date->toDateString());

        $balances = JournalEntry::query()
            ->selectRaw('account_name, COALESCE(SUM(debit), 0) as total_debit, COALESCE(SUM(credit), 0) as total_credit')
            ->groupBy('account_name')
            ->orderBy('account_name')
            ->get()
            ->map(function (JournalEntry $row): array {
                $totalDebit = (float) $row->total_debit;
                $totalCredit = (float) $row->total_credit;

                return [
                    'account_name' => $row->account_name,
                    'total_debit' => $totalDebit,
                    'total_credit' => $totalCredit,
                    'net_balance' => round(abs($totalDebit - $totalCredit), 2),
                    'balance_side' => $totalDebit >= $totalCredit ? 'Dr' : 'Cr',
                ];
            });

        return view('journals.index', compact('entriesByDate', 'balances'));
    }
}
