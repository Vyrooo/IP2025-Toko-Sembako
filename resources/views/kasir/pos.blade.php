<x-app-layout>
    <h4 class="mb-3">Kasir POS</h4>
    <div class="row g-3">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title">Daftar Barang</h5>
                    <input type="text" id="searchProduct" class="form-control mb-3" placeholder="Cari barang...">
                    <div class="table-responsive" style="max-height: 400px;">
                        <table class="table table-sm table-striped align-middle mb-0" id="productTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    <tr data-name="{{ strtolower($product->name) }}">
                                        <td>{{ $product->name }}</td>
                                        <td>Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                                        <td>{{ $product->stock }} {{ $product->unit }}</td>
                                        <td class="text-end">
                                            <button class="btn btn-primary btn-sm add-to-cart"
                                                data-id="{{ $product->id }}"
                                                data-name="{{ $product->name }}"
                                                data-price="{{ $product->selling_price }}"
                                                data-stock="{{ $product->stock }}">
                                                Tambah
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title">Keranjang</h5>
                    <div id="cartAlert" class="alert alert-danger d-none"></div>
                    <div class="table-responsive">
                        <table class="table table-sm mb-3" id="cartTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Barang</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total Bayar</label>
                        <div class="h4" id="cartTotal">Rp 0</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nominal Dibayar</label>
                        <input type="number" id="paidInput" class="form-control" min="0" step="1000">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kembalian</label>
                        <div class="h5" id="changeLabel">Rp 0</div>
                    </div>
                    <button id="submitTransaction" class="btn btn-success w-100">Proses Transaksi</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const cartTableBody = document.querySelector('#cartTable tbody');
            const cartTotalElement = document.getElementById('cartTotal');
            const changeLabel = document.getElementById('changeLabel');
            const paidInput = document.getElementById('paidInput');
            const cartAlert = document.getElementById('cartAlert');
            let cart = [];

            function showCartError(message) {
                cartAlert.textContent = message;
                cartAlert.classList.remove('d-none');
            }

            function renderCart() {
                cartTableBody.innerHTML = '';
                let total = 0;

                cart.forEach((item, index) => {
                    const subtotal = item.qty * item.price;
                    total += subtotal;

                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${item.name}</td>
                        <td class="text-center">
                            <input type="number" min="1" max="${item.stock}" class="form-control form-control-sm cart-qty" data-index="${index}" value="${item.qty}">
                        </td>
                        <td class="text-end">Rp ${subtotal.toLocaleString('id-ID')}</td>
                        <td class="text-end">
                            <button class="btn btn-danger btn-sm remove-item" data-index="${index}">x</button>
                        </td>
                    `;
                    cartTableBody.appendChild(row);
                });

                cartTotalElement.textContent = `Rp ${total.toLocaleString('id-ID')}`;
                const paid = Number(paidInput.value || 0);
                changeLabel.textContent = `Rp ${(paid - total).toLocaleString('id-ID')}`;
            }

            document.querySelectorAll('.add-to-cart').forEach(button => {
                button.addEventListener('click', () => {
                    const id = Number(button.dataset.id);
                    const stock = Number(button.dataset.stock);
                    if (stock < 1) {
                        showCartError('Stok barang ini habis.');
                        return;
                    }
                    const existing = cart.find(item => item.product_id === id);
                    if (existing) {
                        if (existing.qty >= stock) {
                            showCartError('Stok tidak mencukupi.');
                            return;
                        }
                        existing.qty += 1;
                    } else {
                        cart.push({
                            product_id: id,
                            name: button.dataset.name,
                            price: Number(button.dataset.price),
                            qty: 1,
                            stock,
                        });
                    }
                    renderCart();
                    cartAlert.classList.add('d-none');
                });
            });

            cartTableBody.addEventListener('input', (event) => {
                if (event.target.classList.contains('cart-qty')) {
                    const index = Number(event.target.dataset.index);
                    const newQty = Math.max(1, Number(event.target.value));
                    if (newQty > cart[index].stock) {
                        showCartError('Jumlah melebihi stok tersedia.');
                        event.target.value = cart[index].stock;
                        cart[index].qty = cart[index].stock;
                    } else {
                        cart[index].qty = newQty;
                        cartAlert.classList.add('d-none');
                    }
                    renderCart();
                }
            });

            cartTableBody.addEventListener('click', (event) => {
                if (event.target.classList.contains('remove-item')) {
                    cart.splice(Number(event.target.dataset.index), 1);
                    renderCart();
                }
            });

            paidInput.addEventListener('input', renderCart);

            document.getElementById('searchProduct').addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                document.querySelectorAll('#productTable tbody tr').forEach(row => {
                    row.classList.toggle('d-none', !row.dataset.name.includes(searchTerm));
                });
            });

            document.getElementById('submitTransaction').addEventListener('click', async () => {
                cartAlert.classList.add('d-none');
                if (!cart.length) {
                    cartAlert.textContent = 'Keranjang masih kosong.';
                    cartAlert.classList.remove('d-none');
                    return;
                }

                const payload = {
                    items: cart.map(item => ({ product_id: item.product_id, qty: item.qty })),
                    paid: Number(paidInput.value || 0),
                };

                try {
                    const response = await fetch("{{ route('kasir.transactions.store') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content'),
                        },
                        body: JSON.stringify(payload),
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw data;
                    }

                    window.location.href = data.redirect_url;
                } catch (error) {
                    const messages = Object.values(error.errors || { error: [error.message || 'Terjadi kesalahan'] })
                        .flat()
                        .join('<br>');
                    cartAlert.innerHTML = messages;
                    cartAlert.classList.remove('d-none');
                }
            });

            renderCart();
        </script>
    @endpush
</x-app-layout>

