<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StockExport implements FromCollection, WithHeadings
{
    public function collection(): Collection
    {
        return Product::with('category')
            ->orderBy('name')
            ->get()
            ->map(fn (Product $product) => [
                $product->name,
                $product->category?->name,
                $product->stock,
                $product->unit,
                $product->selling_price,
            ]);
    }

    public function headings(): array
    {
        return ['Barang', 'Kategori', 'Stok', 'Satuan', 'Harga Jual'];
    }
}


