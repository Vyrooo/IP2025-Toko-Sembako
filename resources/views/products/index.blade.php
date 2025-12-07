<x-app-layout>
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <h4 class="mb-0">Data Barang</h4>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">Tambah Barang</a>
    </div>

    <form method="GET" class="row gy-2 gx-3 align-items-end mb-3">
        <div class="col-md-4">
            <label class="form-label">Cari</label>
            <input type="text" name="q" class="form-control" placeholder="Nama barang" value="{{ request('q') }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Kategori</label>
            <select name="category_id" class="form-select">
                <option value="">Semua</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
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
                        <th>#</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Stok</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category?->name }}</td>
                            <td>Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                            <td>{{ $product->stock }} {{ $product->unit }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-info">Detail</a>
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus barang ini?')">Hapus</button>
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
        @if($products->hasPages())
            <div class="card-footer">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</x-app-layout>


