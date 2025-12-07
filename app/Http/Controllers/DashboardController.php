<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $todayTransactions = Transaction::whereDate('created_at', today())->count();
        $todayRevenue = Transaction::whereDate('created_at', today())->sum('total');

        // Laba hari ini = Σ (harga jual - harga beli) * qty
        $todayProfit = TransactionItem::query()
            ->whereHas('transaction', fn ($q) => $q->whereDate('created_at', today()))
            ->with('product')
            ->get()
            ->sum(function (TransactionItem $item) {
                $purchase = $item->product?->purchase_price ?? 0;
                return ($item->price - $purchase) * $item->qty;
            });

        $lowStocks = Product::where('stock', '<', 10)
            ->orderBy('stock')
            ->take(5)
            ->get();

        $chartRange = collect(range(6, 0))->map(fn ($day) => today()->subDays($day));

        // Omset per hari untuk 7 hari terakhir
        $rawRevenueChart = Transaction::selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->whereDate('created_at', '>=', today()->subDays(6))
            ->groupBy('date')
            ->pluck('total', 'date');

        // Laba per hari untuk 7 hari terakhir
        $rawProfitChart = TransactionItem::query()
            ->whereHas('transaction', fn ($q) => $q->whereDate('created_at', '>=', today()->subDays(6)))
            ->with(['transaction', 'product'])
            ->get()
            ->groupBy(fn (TransactionItem $item) => $item->transaction->created_at->toDateString())
            ->map(function ($items) {
                return $items->sum(function (TransactionItem $item) {
                    $purchase = $item->product?->purchase_price ?? 0;
                    return ($item->price - $purchase) * $item->qty;
                });
            });

        $chartLabels = $chartRange->map(fn (Carbon $date) => $date->translatedFormat('d M'));
        $chartRevenueValues = $chartRange->map(fn (Carbon $date) => (float) ($rawRevenueChart[$date->toDateString()] ?? 0));
        $chartProfitValues = $chartRange->map(fn (Carbon $date) => (float) ($rawProfitChart[$date->toDateString()] ?? 0));

        return view('dashboard', [
            'totalProducts' => $totalProducts,
            'todayTransactions' => $todayTransactions,
            'todayRevenue' => $todayRevenue,
            'todayProfit' => $todayProfit,
            'lowStocks' => $lowStocks,
            'chartLabels' => $chartLabels,
            'chartRevenueValues' => $chartRevenueValues,
            'chartProfitValues' => $chartProfitValues,
        ]);
    }
}
