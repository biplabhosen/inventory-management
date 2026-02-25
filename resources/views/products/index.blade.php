@extends('layouts.app')

@section('title', 'Products')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Products</h1>
        <a href="{{ route('products.create') }}" class="btn btn-primary">Add Product</a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th class="text-end">Purchase Price</th>
                    <th class="text-end">Sell Price</th>
                    <th class="text-end">Stock</th>
                    <th class="text-end">Inventory Value</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td class="text-end">{{ number_format((float) $product->purchase_price, 2) }} TK</td>
                        <td class="text-end">{{ number_format((float) $product->sell_price, 2) }} TK</td>
                        <td class="text-end">{{ number_format((int) $product->stock) }}</td>
                        <td class="text-end">
                            {{ number_format((float) $product->purchase_price * (int) $product->stock, 2) }} TK
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No products found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">
            {{ $products->links() }}
        </div>
    </div>
@endsection
