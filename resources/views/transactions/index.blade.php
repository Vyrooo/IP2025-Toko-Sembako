@php($routePrefix = request()->routeIs('admin.*') ? 'admin' : 'kasir')
<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Daftar Transaksi</h4>
        <a href="{{ route('kasir.pos') }}" class="btn btn-primary btn-sm">Buka POS</a>
    </div>

    <form method="GET" class="row gy-2 gx-3 align-items-end mb-3">
        <div class="col-md-4">
            <label class="form-label">Tanggal</label>
            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
        </div>
        <div class="col-md-4">
            <button class="btn btn-outline-secondary w-100">Filter</button>
        </div>
    </form>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>Kasir</th>
                        <th>Total</th>
                        <th>Bayar</th>
                        <th>Kembali</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>#{{ $transaction->id }}</td>
                            <td>{{ $transaction->created_at->translatedFormat('d M Y H:i') }}</td>
                            <td>{{ $transaction->user?->name }}</td>
                            <td>Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($transaction->paid, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($transaction->change, 0, ',', '.') }}</td>
                            <td class="text-end">
                                <a href="{{ route($routePrefix.'.transactions.show', $transaction) }}" class="btn btn-sm btn-info">Detail</a>
                                <a href="{{ route($routePrefix.'.transactions.receipt', $transaction) }}" class="btn btn-sm btn-success">Struk PDF</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
            <div class="card-footer">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</x-app-layout>

