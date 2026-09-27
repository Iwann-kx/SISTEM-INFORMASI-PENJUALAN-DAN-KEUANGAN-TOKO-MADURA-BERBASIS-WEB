@extends('layouts.warung')

@section('title', 'Pembelian')
@section('section', 'Pembelian barang')

@section('content')
<div class="page-heading">
    <div><div class="eyebrow">Persediaan masuk</div><h1>Pembelian</h1><p>Catat barang masuk dari supplier dan perbarui stok.</p></div>
    <details class="inline-details">
        <summary class="button secondary"><i class="bi bi-person-plus"></i> Supplier baru</summary>
        <div style="position:absolute;right:0;top:42px;width:min(390px,calc(100vw - 34px));padding:17px;background:#fff;border:1px solid var(--line);border-radius:8px;box-shadow:var(--shadow);z-index:4">
            <form method="POST" action="{{ route('supplier.store') }}">
                @csrf
                <div class="field-grid">
                    <div class="field" style="grid-column:1/-1"><label for="supplier-name">Nama supplier</label><input class="control" id="supplier-name" name="nama_supplier" value="{{ old('nama_supplier') }}" required maxlength="255"></div>
                    <div class="field" style="grid-column:1/-1"><label for="supplier-address">Alamat</label><textarea class="control" id="supplier-address" name="alamat" rows="2">{{ old('alamat') }}</textarea></div>
                    <div class="field" style="grid-column:1/-1"><label for="supplier-phone">Telepon</label><input class="control" id="supplier-phone" name="telepon" value="{{ old('telepon') }}" maxlength="20"></div>
                </div>
                <div class="form-actions"><button class="button" type="submit"><i class="bi bi-check-lg"></i> Simpan supplier</button></div>
            </form>
        </div>
    </details>
</div>

<form method="POST" action="{{ route('pembelian.store') }}" id="purchase-form">
    @csrf
    <div class="columns">
        <section class="panel">
            <div class="panel-head"><div><h2>Informasi pembelian</h2><small>{{ $supplierList->count() }} supplier terdaftar</small></div></div>
            <div class="panel-body">
                @if ($supplierList->isEmpty())
                    <div class="notice error">Tambahkan supplier terlebih dahulu sebelum mencatat pembelian.</div>
                @else
                    <div class="field" style="max-width:470px;margin-bottom:22px">
                        <label for="purchase-supplier">Supplier</label>
                        <select class="control" id="purchase-supplier" name="supplier_id" required>
                            <option value="">Pilih supplier</option>
                            @foreach ($supplierList as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->nama_supplier }}{{ $supplier->telepon ? ' · '.$supplier->telepon : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="field-grid" style="grid-template-columns:minmax(0,1fr) 120px auto;align-items:end">
                    <div class="field"><label for="purchase-product">Barang</label>
                        <select class="control" id="purchase-product">
                            <option value="">Pilih barang</option>
                            @foreach ($barangList as $barang)
                                <option value="{{ $barang->id }}" data-name="{{ $barang->nama_barang }}" data-code="{{ $barang->kode_barang }}" data-price="{{ $barang->harga_beli }}">{{ $barang->nama_barang }} · {{ $barang->kode_barang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field"><label for="purchase-qty">Jumlah</label><input class="control" id="purchase-qty" type="number" min="1" value="1"></div>
                    <button class="button" id="add-purchase-item" type="button"><i class="bi bi-plus-lg"></i> Tambah</button>
                </div>
                <div style="margin-top:23px">
                    <div class="panel-head" style="padding:0 0 12px;border:0"><h2>Daftar barang</h2><span class="muted" style="font-size:11px">Harga beli saat ini</span></div>
                    @if ($barangList->isEmpty())
                        <div class="empty">Belum ada barang terdaftar. Tambahkan melalui menu Stok Barang.</div>
                    @else
                        <div class="table-wrap"><table style="min-width:0"><thead><tr><th>Barang</th><th>Stok</th><th class="text-right">Harga beli</th></tr></thead><tbody>
                            @foreach ($barangList as $barang)
                                <tr><td><span class="table-primary">{{ $barang->nama_barang }}</span><span class="table-sub">{{ $barang->kode_barang }}</span></td><td>{{ $barang->stok_barang }} {{ $barang->satuan }}</td><td class="text-right mono">Rp {{ number_format($barang->harga_beli, 0, ',', '.') }}</td></tr>
                            @endforeach
                        </tbody></table></div>
                    @endif
                </div>
            </div>
        </section>

        <section class="panel">
            <div class="panel-head"><div><h2>Daftar pembelian</h2><small id="purchase-count">0 barang</small></div><button class="button small secondary" type="button" id="clear-purchase"><i class="bi bi-arrow-counterclockwise"></i> Kosongkan</button></div>
            <div class="panel-body">
                <div id="purchase-cart" class="cart-list"><div class="empty" style="padding:20px 0">Belum ada barang dipilih.</div></div>
                <div id="purchase-fields"></div>
                <div class="cart-summary"><span>Total pembelian</span><strong id="purchase-total">Rp 0</strong></div>
                <button class="button" type="submit" style="width:100%;margin-top:18px" {{ $supplierList->isEmpty() || $barangList->isEmpty() ? 'disabled' : '' }}><i class="bi bi-check2-circle"></i> Simpan pembelian</button>
            </div>
        </section>
    </div>
</form>
@endsection

@push('scripts')
<script>
    const purchaseCart = new Map();
    const purchaseSelect = document.getElementById('purchase-product');
    const purchaseCartElement = document.getElementById('purchase-cart');
    const purchaseFields = document.getElementById('purchase-fields');
    const formatPurchaseMoney = (value) => `Rp ${Number(value).toLocaleString('id-ID')}`;

    function renderPurchaseCart() {
        purchaseCartElement.replaceChildren();
        purchaseFields.replaceChildren();
        let total = 0;
        let index = 0;

        if (purchaseCart.size === 0) {
            const empty = document.createElement('div');
            empty.className = 'empty';
            empty.textContent = 'Belum ada barang dipilih.';
            purchaseCartElement.append(empty);
        }

        for (const [id, item] of purchaseCart) {
            const subtotal = item.price * item.qty;
            total += subtotal;
            const line = document.createElement('div');
            line.className = 'cart-line';
            const description = document.createElement('div');
            description.innerHTML = '<span class="cart-line-name"></span><small></small>';
            description.querySelector('.cart-line-name').textContent = item.name;
            description.querySelector('small').textContent = `${item.qty} × ${formatPurchaseMoney(item.price)}`;
            const amount = document.createElement('strong');
            amount.className = 'mono';
            amount.textContent = formatPurchaseMoney(subtotal);
            const remove = document.createElement('button');
            remove.type = 'button';
            remove.className = 'button small danger';
            remove.title = `Hapus ${item.name}`;
            remove.setAttribute('aria-label', `Hapus ${item.name}`);
            remove.innerHTML = '<i class="bi bi-x-lg"></i>';
            remove.addEventListener('click', () => { purchaseCart.delete(id); renderPurchaseCart(); });
            line.append(description, amount, remove);
            purchaseCartElement.append(line);

            for (const [key, value] of Object.entries({ barang_id: id, qty: item.qty })) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `items[${index}][${key}]`;
                input.value = value;
                purchaseFields.append(input);
            }
            index++;
        }

        document.getElementById('purchase-total').textContent = formatPurchaseMoney(total);
        document.getElementById('purchase-count').textContent = `${purchaseCart.size} barang`;
    }

    document.getElementById('add-purchase-item')?.addEventListener('click', () => {
        const option = purchaseSelect.selectedOptions[0];
        const qty = Number(document.getElementById('purchase-qty').value);
        if (!option.value || qty < 1) return;
        const id = option.value;
        const existing = purchaseCart.get(id);
        purchaseCart.set(id, { name: option.dataset.name, price: Number(option.dataset.price), qty: (existing?.qty || 0) + qty });
        document.getElementById('purchase-qty').value = 1;
        renderPurchaseCart();
    });
    document.getElementById('clear-purchase').addEventListener('click', () => { purchaseCart.clear(); renderPurchaseCart(); });
    document.getElementById('purchase-form').addEventListener('submit', (event) => {
        if (!document.getElementById('purchase-supplier')?.value || purchaseCart.size === 0) {
            event.preventDefault();
            alert('Pilih supplier dan tambahkan setidaknya satu barang.');
        }
    });
    renderPurchaseCart();
</script>
@endpush