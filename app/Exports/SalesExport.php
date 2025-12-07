<?php

namespace App\Exports;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesExport implements FromCollection, WithHeadings
{
    public function __construct(private Carbon $start, private Carbon $end)
    {
    }

    public function collection(): Collection
    {
        return Transaction::with('user')
            ->whereBetween('created_at', [$this->start->copy()->startOfDay(), $this->end->copy()->endOfDay()])
            ->orderBy('created_at')
            ->get()
            ->map(function (Transaction $transaction) {
                return [
                    $transaction->id,
                    $transaction->created_at->format('d-m-Y H:i'),
                    $transaction->user?->name,
                    $transaction->total,
                    $transaction->paid,
                    $transaction->change,
                ];
            });
    }

    public function headings(): array
    {
        return ['ID', 'Tanggal', 'Kasir', 'Total', 'Dibayar', 'Kembalian'];
    }
}


