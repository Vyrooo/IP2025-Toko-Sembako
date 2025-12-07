<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Detail Barang Masuk</h4>
        <a href="{{ route('admin.stock-in.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Tanggal</dt>
                <dd class="col-sm-9">{{ $stockIn->created_at->translatedFormat('d M Y H:i') }}</dd>

                <dt class="col-sm-3">Barang</dt>
                <dd class="col-sm-9">{{ $stockIn->product?->name }}</dd>

                <dt class="col-sm-3">Qty</dt>
                <dd class="col-sm-9">{{ $stockIn->qty }}</dd>

                <dt class="col-sm-3">Harga</dt>
                <dd class="col-sm-9">Rp {{ number_format($stockIn->price, 0, ',', '.') }}</dd>

                <dt class="col-sm-3">Supplier</dt>
                <dd class="col-sm-9">{{ $stockIn->supplier_name ?: '-' }}</dd>

                <dt class="col-sm-3">Catatan</dt>
                <dd class="col-sm-9">{{ $stockIn->note ?: '-' }}</dd>

                <dt class="col-sm-3">Input Oleh</dt>
                <dd class="col-sm-9">{{ $stockIn->user?->name }}</dd>
            </dl>
        </div>
    </div>
</x-app-layout>


