<x-app-layout>
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <h4 class="mb-0">Laporan Penjualan</h4>
        <div class="btn-group">
            <a href="{{ request()->fullUrlWithQuery(['period' => request('period'), 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="btn btn-outline-secondary btn-sm disabled">Periode aktif: {{ request('period', 'daily') }}</a>
            <a href="{{ route(request()->routeIs('owner.*') ? 'owner.reports.sales.pdf' : 'admin.reports.sales.pdf', request()->query()) }}" class="btn btn-danger btn-sm">PDF Penjualan</a>
            <a href="{{ route(request()->routeIs('owner.*') ? 'owner.reports.sales.excel' : 'admin.reports.sales.excel', request()->query()) }}" class="btn btn-success btn-sm">Excel Penjualan</a>
        </div>
    </div>

    <form method="GET" class="row gy-2 gx-3 align-items-end mb-4">
        <div class="col-md-3">
            <label class="form-label">Periode</label>
            <select name="period" class="form-select">
                <option value="daily" @selected(request('period') === 'daily')>Harian</option>
                <option value="weekly" @selected(request('period') === 'weekly')>Mingguan</option>
                <option value="monthly" @selected(request('period') === 'monthly')>Bulanan</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Mulai</label>
            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Selesai</label>
            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary w-100">Terapkan</button>
        </div>
    </form>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-secondary text-uppercase small">Total Omset</div>
                    <div class="h4 mb-0">Rp {{ number_format($stats['total_sales'], 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-secondary text-uppercase small">Jumlah Transaksi</div>
                    <div class="h4 mb-0">{{ number_format($stats['total_transactions']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-secondary text-uppercase small">Rata-rata Transaksi</div>
                    <div class="h4 mb-0">Rp {{ number_format($stats['average'], 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Top Produk</h5>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route(request()->routeIs('owner.*') ? 'owner.reports.stock.pdf' : 'admin.reports.stock.pdf') }}" class="btn btn-outline-danger">PDF Stok</a>
                            <a href="{{ route(request()->routeIs('owner.*') ? 'owner.reports.stock.excel' : 'admin.reports.stock.excel') }}" class="btn btn-outline-success">Excel Stok</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Barang</th>
                                    <th>Qty</th>
                                    <th class="text-end">Omset</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topProducts as $product)
                                    <tr>
                                        <td>{{ $product['name'] }}</td>
                                        <td>{{ $product['qty'] }}</td>
                                        <td class="text-end">Rp {{ number_format($product['revenue'], 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">Belum ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Stok Tersedia</h5>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route(request()->routeIs('owner.*') ? 'owner.reports.stock_in.pdf' : 'admin.reports.stock_in.pdf', request()->query()) }}" class="btn btn-outline-danger">PDF Barang Masuk</a>
                            <a href="{{ route(request()->routeIs('owner.*') ? 'owner.reports.stock_in.excel' : 'admin.reports.stock_in.excel', request()->query()) }}" class="btn btn-outline-success">Excel Barang Masuk</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Barang</th>
                                    <th>Stok</th>
                                    <th>Satuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stockSummary as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->stock }}</td>
                                        <td>{{ $product->unit }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">Belum ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


