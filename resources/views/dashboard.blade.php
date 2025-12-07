<x-app-layout>
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="text-muted text-uppercase small mb-1">Total Barang</div>
                    <div class="h3 mb-0 fw-semibold text-dark">{{ number_format($totalProducts) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="text-muted text-uppercase small mb-1">Transaksi Hari Ini</div>
                    <div class="h3 mb-0 fw-semibold text-dark">{{ number_format($todayTransactions) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="text-muted text-uppercase small mb-1">Omset Hari Ini</div>
                    <div class="h5 mb-1 text-success fw-semibold">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</div>
                    <div class="text-muted small">Laba Hari Ini:</div>
                    <div class="h5 mb-0 text-success fw-semibold">Rp {{ number_format($todayProfit, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-2">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="card-title mb-0">Grafik Omset & Laba (7 Hari)</h5>
                            <div class="text-muted small">Performa penjualan dan keuntungan harian</div>
                        </div>
                    </div>
                    <canvas id="salesChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title">Stok Hampir Habis</h5>
                    <ul class="list-group list-group-flush">
                        @forelse($lowStocks as $product)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $product->name }}</span>
                                <span class="badge text-bg-danger">{{ $product->stock }} {{ $product->unit }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">Semua stok aman</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const chartCtx = document.getElementById('salesChart');
            if (chartCtx) {
                const labels = @json($chartLabels);
                const revenueData = @json($chartRevenueValues);
                const profitData = @json($chartProfitValues);

                new Chart(chartCtx, {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [
                            {
                                label: 'Omset',
                                data: revenueData,
                                borderColor: '#00a36f',
                                backgroundColor: 'rgba(0, 163, 111, 0.18)',
                                tension: 0.35,
                                fill: true,
                            },
                            {
                                label: 'Laba',
                                data: profitData,
                                borderColor: '#00664b',
                                backgroundColor: 'rgba(0, 102, 75, 0.12)',
                                tension: 0.35,
                                fill: true,
                            }
                        ]
                    },
                    options: {
                        plugins: {
                            legend: {
                                display: true,
                                labels: { boxWidth: 12 }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: value => new Intl.NumberFormat('id-ID', {
                                        style: 'currency',
                                        currency: 'IDR',
                                        maximumFractionDigits: 0,
                                    }).format(value)
                                }
                            }
                        }
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
