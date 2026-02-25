@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Dashboard</h1>
        <a href="{{ route('sales.create') }}" class="btn btn-primary">New Sale</a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6 col-xl-2">
            <div class="card metric-card h-100">
                <div class="card-body">
                    <div class="metric-label">Total Sales</div>
                    <div class="h5 mb-0">{{ number_format($totalSales, 2) }} TK</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-2">
            <div class="card metric-card h-100">
                <div class="card-body">
                    <div class="metric-label">Total Expense</div>
                    <div class="h5 mb-0">{{ number_format($totalExpense, 2) }} TK</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-2">
            <div class="card metric-card h-100">
                <div class="card-body">
                    <div class="metric-label">Total Due</div>
                    <div class="h5 mb-0">{{ number_format($totalDue, 2) }} TK</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-2">
            <div class="card metric-card h-100">
                <div class="card-body">
                    <div class="metric-label">Current Stock</div>
                    <div class="h5 mb-0">{{ number_format($currentStock) }} units</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-2">
            <div class="card metric-card h-100">
                <div class="card-body">
                    <div class="metric-label">Profit</div>
                    <div class="h5 mb-0 {{ $profit >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ number_format($profit, 2) }} TK
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white">
            <h2 class="h6 mb-0">Recent Sales</h2>
        </div>
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Product</th>
                    <th class="text-end">Qty</th>
                    <th class="text-end">Subtotal</th>
                    <th class="text-end">Discount</th>
                    <th class="text-end">VAT</th>
                    <th class="text-end">Total</th>
                    <th class="text-end">Paid</th>
                    <th class="text-end">Due</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($recentSales as $sale)
                    <tr>
                        <td>{{ $sale->date->format('Y-m-d') }}</td>
                        <td>{{ $sale->product?->name }}</td>
                        <td class="text-end">{{ $sale->quantity }}</td>
                        <td class="text-end">{{ number_format((float) $sale->subtotal, 2) }}</td>
                        <td class="text-end">{{ number_format((float) $sale->discount, 2) }}</td>
                        <td class="text-end">{{ number_format((float) $sale->vat_amount, 2) }}</td>
                        <td class="text-end">{{ number_format((float) $sale->total_amount, 2) }}</td>
                        <td class="text-end">{{ number_format((float) $sale->paid_amount, 2) }}</td>
                        <td class="text-end">{{ number_format((float) $sale->due_amount, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">No sales recorded yet.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
