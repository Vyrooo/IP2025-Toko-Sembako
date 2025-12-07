<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockInController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::get('/dashboard', function () {
    $role = auth()->user()->role ?? null;

    return match ($role) {
        'admin' => redirect()->route('admin.dashboard'),
        'kasir' => redirect()->route('kasir.pos'),
        'owner' => redirect()->route('owner.dashboard'),
        default => redirect()->route('login'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'adminOnly'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('stock-in', StockInController::class)
        ->parameters(['stock-in' => 'stockIn'])
        ->except(['edit', 'update']);
    Route::resource('users', UserController::class);
    Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::get('transactions/{transaction}/receipt', [TransactionController::class, 'receipt'])->name('transactions.receipt');

    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/sales/pdf', [ReportController::class, 'exportSalesPdf'])->name('reports.sales.pdf');
    Route::get('reports/sales/excel', [ReportController::class, 'exportSalesExcel'])->name('reports.sales.excel');
    Route::get('reports/stock/pdf', [ReportController::class, 'exportStockPdf'])->name('reports.stock.pdf');
    Route::get('reports/stock/excel', [ReportController::class, 'exportStockExcel'])->name('reports.stock.excel');
    Route::get('reports/stock-in/pdf', [ReportController::class, 'exportStockInPdf'])->name('reports.stock_in.pdf');
    Route::get('reports/stock-in/excel', [ReportController::class, 'exportStockInExcel'])->name('reports.stock_in.excel');
});

Route::middleware(['auth', 'kasirOnly'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/pos', [TransactionController::class, 'create'])->name('pos');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::get('/transactions/{transaction}/receipt', [TransactionController::class, 'receipt'])->name('transactions.receipt');
    Route::get('/products/search', [TransactionController::class, 'products'])->name('products.search');
});

Route::middleware(['auth', 'ownerOnly'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/sales/pdf', [ReportController::class, 'exportSalesPdf'])->name('reports.sales.pdf');
    Route::get('reports/sales/excel', [ReportController::class, 'exportSalesExcel'])->name('reports.sales.excel');
    Route::get('reports/stock/pdf', [ReportController::class, 'exportStockPdf'])->name('reports.stock.pdf');
    Route::get('reports/stock/excel', [ReportController::class, 'exportStockExcel'])->name('reports.stock.excel');
    Route::get('reports/stock-in/pdf', [ReportController::class, 'exportStockInPdf'])->name('reports.stock_in.pdf');
    Route::get('reports/stock-in/excel', [ReportController::class, 'exportStockInExcel'])->name('reports.stock_in.excel');
});

require __DIR__.'/auth.php';
