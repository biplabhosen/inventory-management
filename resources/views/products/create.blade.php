@extends('layouts.app')

@section('title', 'Create Product')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h1 class="h5 mb-0">Create Product</h1>
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('products.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="purchase_price" class="form-label">Purchase Price (TK)</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="purchase_price" name="purchase_price" value="{{ old('purchase_price') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="sell_price" class="form-label">Sell Price (TK)</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="sell_price" name="sell_price" value="{{ old('sell_price') }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="opening_stock" class="form-label">Opening Stock (units)</label>
                            <input type="number" min="0" class="form-control" id="opening_stock" name="opening_stock" value="{{ old('opening_stock', 0) }}" required>
                            <div class="form-text">
                                Opening stock posting automatically creates Dr Inventory / Cr Capital.
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
