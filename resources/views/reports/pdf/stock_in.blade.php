<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Laporan Barang Masuk</h2>
    <p>Periode: {{ $start->format('d/m/Y') }} - {{ $end->format('d/m/Y') }}</p>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Barang</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Supplier</th>
                <th>Petugas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($histories as $history)
                <tr>
                    <td>{{ $history->created_at->format('d/m/Y') }}</td>
                    <td>{{ $history->product?->name }}</td>
                    <td>{{ $history->qty }}</td>
                    <td>Rp {{ number_format($history->price, 0, ',', '.') }}</td>
                    <td>{{ $history->supplier_name }}</td>
                    <td>{{ $history->user?->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>


