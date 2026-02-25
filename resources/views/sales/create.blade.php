@extends('layouts.app')

@section('title', 'Create Sale')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h1 class="h5 mb-0">Record Sale</h1>
                    <a href="{{ route('sales.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('sales.store') }}" id="saleForm">
                        @csrf
                        <div class="mb-3">
                            <label for="product_id" class="form-label">Product</label>
                            <select class="form-select" id="product_id" name="product_id" required>
                                <option value="">Select product</option>
                                @foreach ($products as $product)
                                    <option
                                        value="{{ $product->id }}"
                                        data-sell-price="{{ $product->sell_price }}"
                                        data-stock="{{ $product->stock }}"
                                        {{ (string) old('product_id') === (string) $product->id ? 'selected' : '' }}
                                    >
                                        {{ $product->name }} (Stock: {{ $product->stock }}, Price: {{ number_format((float) $product->sell_price, 2) }} TK)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" min="1" class="form-control" id="quantity" name="quantity" value="{{ old('quantity', 1) }}" required>
                                <div class="form-text" id="stockHint">Available stock: -</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="date" class="form-label">Sale Date</label>
                                <input type="date" class="form-control" id="date" name="date" value="{{ old('date', now()->toDateString()) }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="discount" class="form-label">Discount (TK)</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="discount" name="discount" value="{{ old('discount', 0) }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="vat_percent" class="form-label">VAT (%)</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="vat_percent" name="vat_percent" value="{{ old('vat_percent', $defaultVatPercent) }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="paid_amount" class="form-label">Paid Amount (TK)</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="paid_amount" name="paid_amount" value="{{ old('paid_amount', 0) }}">
                            </div>
                        </div>

                        <div class="border rounded p-3 bg-light mb-3">
                            <div class="row gy-2">
                                <div class="col-md-3">
                                    <div class="small text-muted">Subtotal</div>
                                    <div class="fw-semibold"><span id="subtotalPreview">0.00</span> TK</div>
                                </div>
                                <div class="col-md-3">
                                    <div class="small text-muted">VAT Amount</div>
                                    <div class="fw-semibold"><span id="vatPreview">0.00</span> TK</div>
                                </div>
                                <div class="col-md-3">
                                    <div class="small text-muted">Total Receivable</div>
                                    <div class="fw-semibold"><span id="totalPreview">0.00</span> TK</div>
                                </div>
                                <div class="col-md-3">
                                    <div class="small text-muted">Due Amount</div>
                                    <div class="fw-semibold"><span id="duePreview">0.00</span> TK</div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit Sale</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            const productSelect = document.getElementById('product_id');
            const quantityInput = document.getElementById('quantity');
            const discountInput = document.getElementById('discount');
            const vatInput = document.getElementById('vat_percent');
            const paidInput = document.getElementById('paid_amount');
            const stockHint = document.getElementById('stockHint');
            const subtotalPreview = document.getElementById('subtotalPreview');
            const vatPreview = document.getElementById('vatPreview');
            const totalPreview = document.getElementById('totalPreview');
            const duePreview = document.getElementById('duePreview');

            const toNumber = (value) => Number.parseFloat(value || '0') || 0;
            const toInt = (value) => Number.parseInt(value || '0', 10) || 0;
            const money = (value) => value.toFixed(2);

            const calculate = () => {
                const option = productSelect.options[productSelect.selectedIndex];
                const unitPrice = toNumber(option?.dataset.sellPrice);
                const stock = toInt(option?.dataset.stock);
                const quantity = toInt(quantityInput.value);
                const discount = toNumber(discountInput.value);
                const vatPercent = toNumber(vatInput.value);
                const paidAmount = toNumber(paidInput.value);

                const subtotal = quantity * unitPrice;
                const netSale = Math.max(subtotal - discount, 0);
                const vatAmount = netSale * (vatPercent / 100);
                const total = netSale + vatAmount;
                const due = total - paidAmount;

                stockHint.textContent = `Available stock: ${stock}`;
                subtotalPreview.textContent = money(subtotal);
                vatPreview.textContent = money(vatAmount);
                totalPreview.textContent = money(total);
                duePreview.textContent = money(due);
                duePreview.classList.toggle('text-danger', due < 0);
            };

            [productSelect, quantityInput, discountInput, vatInput, paidInput].forEach((element) => {
                element.addEventListener('input', calculate);
                element.addEventListener('change', calculate);
            });

            calculate();
        })();
    </script>
@endpush
