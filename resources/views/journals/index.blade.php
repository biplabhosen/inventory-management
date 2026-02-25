@extends('layouts.app')

@section('title', 'Ledger')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Journal Ledger</h1>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-white">
            <h2 class="h6 mb-0">Account Balances</h2>
        </div>
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>Account</th>
                    <th class="text-end">Total Debit</th>
                    <th class="text-end">Total Credit</th>
                    <th class="text-end">Net Balance</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($balances as $balance)
                    <tr>
                        <td>{{ $balance['account_name'] }}</td>
                        <td class="text-end">{{ number_format($balance['total_debit'], 2) }}</td>
                        <td class="text-end">{{ number_format($balance['total_credit'], 2) }}</td>
                        <td class="text-end">
                            {{ number_format($balance['net_balance'], 2) }} {{ $balance['balance_side'] }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-3 text-muted">No balances available.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @forelse ($entriesByDate as $date => $entries)
        <div class="card mb-3">
            <div class="card-header bg-white">
                <h2 class="h6 mb-0">{{ \Illuminate\Support\Carbon::parse($date)->format('d M Y') }}</h2>
            </div>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>Account</th>
                        <th class="text-end">Debit</th>
                        <th class="text-end">Credit</th>
                        <th>Reference</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $dateDebitTotal = 0;
                        $dateCreditTotal = 0;
                    @endphp
                    @foreach ($entries as $entry)
                        @php
                            $dateDebitTotal += (float) ($entry->debit ?? 0);
                            $dateCreditTotal += (float) ($entry->credit ?? 0);
                        @endphp
                        <tr>
                            <td>{{ $entry->account_name }}</td>
                            <td class="text-end">{{ number_format((float) ($entry->debit ?? 0), 2) }}</td>
                            <td class="text-end">{{ number_format((float) ($entry->credit ?? 0), 2) }}</td>
                            <td>{{ class_basename($entry->reference_type) }} #{{ $entry->reference_id }}</td>
                        </tr>
                    @endforeach
                    <tr class="table-light">
                        <td class="fw-semibold">Total</td>
                        <td class="text-end fw-semibold">{{ number_format($dateDebitTotal, 2) }}</td>
                        <td class="text-end fw-semibold">{{ number_format($dateCreditTotal, 2) }}</td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="alert alert-info">No journal entries posted yet.</div>
    @endforelse
@endsection
