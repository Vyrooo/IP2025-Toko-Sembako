@php($routePrefix = request()->routeIs('admin.*') ? 'admin' : 'kasir')
<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Detail Transaksi #{{ $transaction->id }}</h4>
        <div class="d-flex gap-2">
            <a href="{{ route($routePrefix.'.transactions.receipt', $transaction) }}" class="btn btn-success btn-sm">Cetak Struk</a>
            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">Kembali</a>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Tanggal</dt>
                <dd class="col-sm-9">{{ $transaction->created_at->translatedFormat('d M Y H:i') }}</dd>

                <dt class="col-sm-3">Kasir</dt>
                <dd class="col-sm-9">{{ $transaction->user?->name }}</dd>

                <dt class="col-sm-3">Total</dt>
                <dd class="col-sm-9">Rp {{ number_format($transaction->total, 0, ',', '.') }}</dd>

                <dt class="col-sm-3">Bayar</dt>
                <dd class="col-sm-9">Rp {{ number_format($transaction->paid, 0, ',', '.') }}</dd>

                <dt class="col-sm-3">Kembalian</dt>
                <dd class="col-sm-9">Rp {{ number_format($transaction->change, 0, ',', '.') }}</dd>
            </dl>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Barang</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaction->items as $item)
                        <tr>
                            <td>{{ $item->product?->name }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->qty * $item->price, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

