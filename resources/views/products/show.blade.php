<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Detail Barang</h4>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Nama Barang</dt>
                <dd class="col-sm-9">{{ $product->name }}</dd>

                <dt class="col-sm-3">Kategori</dt>
                <dd class="col-sm-9">{{ $product->category?->name }}</dd>

                <dt class="col-sm-3">Harga Beli</dt>
                <dd class="col-sm-9">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</dd>

                <dt class="col-sm-3">Harga Jual</dt>
                <dd class="col-sm-9">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</dd>

                <dt class="col-sm-3">Stok</dt>
                <dd class="col-sm-9">{{ $product->stock }} {{ $product->unit }}</dd>

                <dt class="col-sm-3">Dibuat</dt>
                <dd class="col-sm-9">{{ $product->created_at->translatedFormat('d M Y H:i') }}</dd>
            </dl>
        </div>
    </div>
</x-app-layout>


