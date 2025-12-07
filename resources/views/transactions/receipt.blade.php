<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border-bottom: 1px solid #ddd; padding: 6px 4px; text-align: left; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h2>Toko Sembako 350 - Struk Transaksi</h2>
    <p>No: #{{ $transaction->id }}<br>
        Tanggal: {{ $transaction->created_at->translatedFormat('d M Y H:i') }}<br>
        Kasir: {{ $transaction->user?->name }}
    </p>

    <table>
        <thead>
            <tr>
                <th>Barang</th>
                <th>Qty</th>
                <th>Harga</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaction->items as $item)
                <tr>
                    <td>{{ $item->product?->name }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="text-right">
        Total: Rp {{ number_format($transaction->total, 0, ',', '.') }}<br>
        Bayar: Rp {{ number_format($transaction->paid, 0, ',', '.') }}<br>
        Kembalian: Rp {{ number_format($transaction->change, 0, ',', '.') }}
    </p>
    <p style="text-align:center; margin-top:20px;">Terima kasih telah berbelanja!</p>
</body>
</html>


