@extends('layouts.app')

@section('title', 'Sales')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Sales</h1>
        <a href="{{ route('sales.create') }}" class="btn btn-primary">Record Sale</a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Product</th>
                    <th class="text-end">Qty</th>
                    <th class="text-end">Unit Price</th>
                    <th class="text-end">Discount</th>
                    <th class="text-end">VAT %</th>
                    <th class="text-end">VAT</th>
                    <th class="text-end">Total</th>
                    <th class="text-end">Paid</th>
                    <th class="text-end">Due</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($sales as $sale)
                    <tr>
                        <td>{{ $sale->date->format('Y-m-d') }}</td>
                        <td>{{ $sale->product?->name }}</td>
                        <td class="text-end">{{ $sale->quantity }}</td>
                        <td class="text-end">{{ number_format((float) $sale->unit_price, 2) }}</td>
                        <td class="text-end">{{ number_format((float) $sale->discount, 2) }}</td>
                        <td class="text-end">{{ number_format((float) $sale->vat_percent, 2) }}</td>
                        <td class="text-end">{{ number_format((float) $sale->vat_amount, 2) }}</td>
                        <td class="text-end">{{ number_format((float) $sale->total_amount, 2) }}</td>
                        <td class="text-end">{{ number_format((float) $sale->paid_amount, 2) }}</td>
                        <td class="text-end">{{ number_format((float) $sale->due_amount, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center py-4 text-muted">No sales found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">
            {{ $sales->links() }}
        </div>
    </div>
@endsection
