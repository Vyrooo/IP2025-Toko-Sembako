<?php

namespace App\Http\Controllers;

use App\Exports\SalesExport;
use App\Exports\StockExport;
use App\Exports\StockInExport;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        [$start, $end] = $this->resolveRange($request);

        $transactions = Transaction::with('items.product')
            ->whereBetween('created_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->get();

        $stats = [
            'total_sales' => $transactions->sum('total'),
            'total_transactions' => $transactions->count(),
            'average' => $transactions->avg('total') ?? 0,
        ];

        $topProducts = $transactions->flatMap->items
            ->groupBy('product_id')
            ->map(fn ($items) => [
                'name' => optional($items->first()->product)->name,
                'qty' => $items->sum('qty'),
                'revenue' => $items->sum(fn ($item) => $item->qty * $item->price),
            ])
            ->sortByDesc('qty')
            ->take(5);

        $stockSummary = Product::orderBy('stock')->take(10)->get();

        return view('reports.index', [
            'stats' => $stats,
            'topProducts' => $topProducts,
            'stockSummary' => $stockSummary,
            'start' => $start,
            'end' => $end,
        ]);
    }

    public function exportSalesPdf(Request $request)
    {
        [$start, $end] = $this->resolveRange($request);

        $transactions = Transaction::with('items.product', 'user')
            ->whereBetween('created_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->orderBy('created_at')
            ->get();

        $pdf = Pdf::loadView('reports.pdf.sales', compact('transactions', 'start', 'end'));

        return $pdf->download("laporan-penjualan-{$start->format('Ymd')}-{$end->format('Ymd')}.pdf");
    }

    public function exportSalesExcel(Request $request)
    {
        [$start, $end] = $this->resolveRange($request);

        return Excel::download(new SalesExport($start, $end), 'laporan-penjualan.xlsx');
    }

    public function exportStockPdf()
    {
        $products = Product::with('category')->orderBy('name')->get();
        $pdf = Pdf::loadView('reports.pdf.stock', compact('products'));

        return $pdf->download('laporan-stok.pdf');
    }

    public function exportStockExcel()
    {
        return Excel::download(new StockExport(), 'laporan-stok.xlsx');
    }

    public function exportStockInPdf(Request $request)
    {
        [$start, $end] = $this->resolveRange($request);

        $histories = StockIn::with(['product', 'user'])
            ->whereBetween('created_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->get();

        $pdf = Pdf::loadView('reports.pdf.stock_in', compact('histories', 'start', 'end'));

        return $pdf->download("laporan-stock-in-{$start->format('Ymd')}-{$end->format('Ymd')}.pdf");
    }

    public function exportStockInExcel(Request $request)
    {
        [$start, $end] = $this->resolveRange($request);

        return Excel::download(new StockInExport($start, $end), 'laporan-stock-in.xlsx');
    }

    private function resolveRange(Request $request): array
    {
        $period = $request->get('period', 'daily');
        $start = $request->get('start_date');
        $end = $request->get('end_date');

        if ($start && $end) {
            return [Carbon::parse($start), Carbon::parse($end)];
        }

        return match ($period) {
            'weekly' => [now()->startOfWeek(), now()->endOfWeek()],
            'monthly' => [now()->startOfMonth(), now()->endOfMonth()],
            default => [now()->startOfDay(), now()->endOfDay()],
        };
    }
}
