<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockIn;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockInController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stockIns = StockIn::with(['product', 'user'])
            ->latest()
            ->paginate(15);

        return view('stock_in.index', compact('stockIns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::orderBy('name')->get();

        return view('stock_in.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'qty' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:0'],
            'supplier_name' => ['nullable', 'string', 'max:150'],
            'note' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated, $request) {
            $stockIn = StockIn::create([
                ...$validated,
                'user_id' => $request->user()->id,
            ]);

            $stockIn->product()->increment('stock', $validated['qty']);
        });

        return redirect()->route('admin.stock-in.index')->with('success', 'Stok barang berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StockIn $stockIn)
    {
        $stockIn->load(['product', 'user']);

        return view('stock_in.show', compact('stockIn'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockIn $stockIn)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StockIn $stockIn)
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockIn $stockIn): RedirectResponse
    {
        DB::transaction(function () use ($stockIn) {
            if ($stockIn->product) {
                $reduceBy = min($stockIn->qty, $stockIn->product->stock);
                $stockIn->product()->decrement('stock', $reduceBy);
            }
            $stockIn->delete();
        });

        return redirect()->route('admin.stock-in.index')->with('success', 'Riwayat stok berhasil dihapus.');
    }
}
