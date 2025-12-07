<?php

namespace App\Exports;

use App\Models\StockIn;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StockInExport implements FromCollection, WithHeadings
{
    public function __construct(private Carbon $start, private Carbon $end)
    {
    }

    public function collection(): Collection
    {
        return StockIn::with(['product', 'user'])
            ->whereBetween('created_at', [$this->start->copy()->startOfDay(), $this->end->copy()->endOfDay()])
            ->orderBy('created_at')
            ->get()
            ->map(fn (StockIn $stockIn) => [
                $stockIn->created_at->format('d-m-Y'),
                $stockIn->product?->name,
                $stockIn->qty,
                $stockIn->price,
                $stockIn->supplier_name,
                $stockIn->user?->name,
            ]);
    }

    public function headings(): array
    {
        return ['Tanggal', 'Barang', 'Qty', 'Harga', 'Supplier', 'Input Oleh'];
    }
}


