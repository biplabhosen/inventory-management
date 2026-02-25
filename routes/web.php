<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('products', ProductController::class)
    ->only(['index', 'create', 'store']);

Route::resource('sales', SaleController::class)
    ->only(['index', 'create', 'store']);

Route::get('/ledger', [JournalController::class, 'index'])->name('ledger.index');
Route::get('/report', [ReportController::class, 'index'])->name('report.index');
