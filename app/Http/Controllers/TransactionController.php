<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Services\TransactionService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(private readonly TransactionService $transactionService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::with(['user', 'items.product'])
            ->when(request('date'), fn ($query, $date) => $query->whereDate('created_at', $date))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::select('id', 'name', 'selling_price', 'stock', 'unit')
            ->orderBy('name')
            ->get();

        return view('kasir.pos', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
            'paid' => ['required', 'numeric', 'min:0'],
        ]);

        $items = collect($validated['items'])
            ->map(fn ($item) => [
                'product_id' => $item['product_id'],
                'qty' => $item['qty'],
            ])
            ->values()
            ->all();

        $transaction = $this->transactionService->process($request->user(), $items, $validated['paid']);

        $payload = [
            'message' => 'Transaksi berhasil diproses.',
            'transaction_id' => $transaction->id,
            'redirect_url' => route('kasir.transactions.show', $transaction),
            'receipt_url' => route('kasir.transactions.receipt', $transaction),
        ];

        return $request->expectsJson()
            ? response()->json($payload, 201)
            : redirect()->route('kasir.transactions.show', $transaction)->with('success', $payload['message']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $transaction->load(['items.product', 'user']);

        return view('transactions.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        abort(404);
    }

    public function receipt(Transaction $transaction)
    {
        $transaction->load(['items.product', 'user']);

        $pdf = Pdf::loadView('transactions.receipt', compact('transaction'));

        return $pdf->download("struk-{$transaction->id}.pdf");
    }

    public function products(Request $request): JsonResponse
    {
        $products = Product::query()
            ->select('id', 'name', 'selling_price', 'stock', 'unit')
            ->when($request->get('q'), fn ($query, $keyword) => $query->where('name', 'like', "%{$keyword}%"))
            ->limit(20)
            ->get();

        return response()->json($products);
    }
}
