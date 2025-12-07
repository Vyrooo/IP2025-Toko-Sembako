<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Riwayat Barang Masuk</h4>
        <a href="{{ route('admin.stock-in.create') }}" class="btn btn-primary btn-sm">Input Barang Masuk</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Barang</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Supplier</th>
                        <th>Petugas</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stockIns as $stock)
                        <tr>
                            <td>{{ $stock->created_at->translatedFormat('d M Y') }}</td>
                            <td>{{ $stock->product?->name }}</td>
                            <td>{{ $stock->qty }}</td>
                            <td>Rp {{ number_format($stock->price, 0, ',', '.') }}</td>
                            <td>{{ $stock->supplier_name ?: '-' }}</td>
                            <td>{{ $stock->user?->name }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.stock-in.show', $stock) }}" class="btn btn-sm btn-info">Detail</a>
                                <form action="{{ route('admin.stock-in.destroy', $stock) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus riwayat ini?')">Hapus</button>
                                </form>
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
        @if($stockIns->hasPages())
            <div class="card-footer">
                {{ $stockIns->links() }}
            </div>
        @endif
    </div>
</x-app-layout>


