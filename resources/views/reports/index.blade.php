@extends('layouts.app')

@section('title', 'Financial Report')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Financial Report</h1>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('report.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="from" class="form-label">From</label>
                    <input type="date" class="form-control" id="from" name="from" value="{{ $from }}">
                </div>
                <div class="col-md-4">
                    <label for="to" class="form-label">To</label>
                    <input type="date" class="form-control" id="to" name="to" value="{{ $to }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">Generate Report</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6 col-xl-3">
            <div class="card metric-card h-100">
                <div class="card-body">
                    <div class="metric-label">Net Sales (Excl. VAT)</div>
                    <div class="h5 mb-0">{{ number_format($netSales, 2) }} TK</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card metric-card h-100">
                <div class="card-body">
                    <div class="metric-label">Total Expense (COGS)</div>
                    <div class="h5 mb-0">{{ number_format($totalExpense, 2) }} TK</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card metric-card h-100">
                <div class="card-body">
                    <div class="metric-label">Total Profit</div>
                    <div class="h5 mb-0 {{ $totalProfit >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ number_format($totalProfit, 2) }} TK
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card metric-card h-100">
                <div class="card-body">
                    <div class="metric-label">Total Due (A/R Balance)</div>
                    <div class="h5 mb-0 {{ $totalDue >= 0 ? '' : 'text-danger' }}">{{ number_format($totalDue, 2) }} TK</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <div><strong>Period:</strong> {{ $from }} to {{ $to }}</div>
            <div class="text-muted small mt-2">Formula: Profit = Net Sales (Sales Revenue only) - COGS. VAT Payable is excluded from revenue and profit.</div>
        </div>
    </div>
@endsection
