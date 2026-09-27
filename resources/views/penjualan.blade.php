@extends('layouts.warung')

@section('title', 'Penjualan')
@section('section', 'Kasir penjualan')

@section('content')
<div class="page-heading">
    <div><div class="eyebrow">Kasir</div><h1>Penjualan</h1><p>Catat transaksi dan perbarui stok secara otomatis.</p></div>
    <div class="heading-actions"><a class="button secondary" href="{{ route('laporan') }}"><i class="bi bi-clock-history"></i> Riwayat &amp; laporan</a></div>
</div>

<form method="POST" action="{{ route('penjualan.store') }}" id="sale-form">
    @csrf
    <div class="columns">
        <section class="panel">
            <div class="panel-head"><div><h2>Pilih barang</h2><small>{{ $barangList->count() }} barang tersedia</small></div></div>
            <div class="panel-body">
                @if ($barangList->isEmpty())
                    <div class="empty">Belum ada barang dengan stok tersedia. Tambahkan stok melalui menu Pembelian.</div>
                @else
                    <div class="form-row">
                        <div class="field">
                            <label for="sale-product">Barang</label>
                            <select class="control" id="sale-product">
                                <option value="">Pilih barang</option>
                                @foreach ($barangList as $barang)
                                    <option value="{{ $barang->id }}" data-name="{{ $barang->nama_barang }}" data-code="{{ $barang->kode_barang }}" data-price="{{ $barang->harga_jual }}" data-stock="{{ $barang->stok_barang }}">{{ $barang->nama_barang }} · {{ $barang->kode_barang }} · stok {{ $barang->stok_barang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field" style="max-width:125px"><label for="sale-qty">Jumlah</label><input class="control" id="sale-qty" type="number" min="1" value="1"></div>
                        <button class="button" id="add-sale-item" type="button"><i class="bi bi-plus-lg"></i> Tambah</button>
                    </div>
                @endif
                <div style="margin-top:23px">
                    <div class="panel-head" style="padding:0 0 12px;border:0"><h2>Barang tersedia</h2><span class="muted" style="font-size:11px">Harga jual</span></div>
                    @if ($barangList->isEmpty())
                        <div class="empty">Daftar barang kosong.</div>
                    @else
                        <div class="table-wrap"><table style="min-width:0"><thead><tr><th>Barang</th><th>Stok</th><th class="text-right">Harga</th></tr></thead><tbody>
                            @foreach ($barangList as $barang)
                                <tr><td><span class="table-primary">{{ $barang->nama_barang }}</span><span class="table-sub">{{ $barang->kode_barang }}</span></td><td>{{ $barang->stok_barang }} {{ $barang->satuan }}</td><td class="text-right mono">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</td></tr>
                            @endforeach
                        </tbody></table></div>
                    @endif
                </div>
            </div>
        </section>

        <section class="panel">
            <div class="panel-head"><div><h2>Keranjang transaksi</h2><small id="cart-count">0 barang</small></div><button class="button small secondary" type="button" id="clear-sale"><i class="bi bi-arrow-counterclockwise"></i> Kosongkan</button></div>
            <div class="panel-body">
                <div id="sale-cart" class="cart-list"><div class="empty" style="padding:20px 0">Keranjang masih kosong.</div></div>
                <div id="sale-fields"></div>
                <div class="cart-summary"><span>Total</span><strong id="sale-total">Rp 0</strong></div>
                <div class="field" style="margin-top:18px"><label for="sale-payment">Jumlah pembayaran (Rp)</label><input class="control" id="sale-payment" name="bayar" type="number" min="0" step="1" value="{{ old('bayar') }}" required></div>
                <div class="cart-summary" style="border:0;margin-top:0;padding-top:10px"><span class="muted">Kembalian</span><span id="sale-change" class="mono">Rp 0</span></div>
                <button class="button" type="submit" style="width:100%;margin-top:12px"><i class="bi bi-check2-circle"></i> Proses pembayaran</button>
            </div>
        </section>
    </div>
</form>
@endsection

@push('scripts')
<script>
    const saleCart = new Map();
    const saleSelect = document.getElementById('sale-product');
    const saleCartElement = document.getElementById('sale-cart');
    const saleFields = document.getElementById('sale-fields');
    const salePayment = document.getElementById('sale-payment');
    const rupiah = (value) => `Rp ${Number(value).toLocaleString('id-ID')}`;

    function renderSaleCart() {
        saleCartElement.replaceChildren();
        saleFields.replaceChildren();
        let total = 0;
        let index = 0;

        if (saleCart.size === 0) {
            const empty = document.createElement('div');
            empty.className = 'empty';
            empty.textContent = 'Keranjang masih kosong.';
            saleCartElement.append(empty);
        }

        for (const [id, item] of saleCart) {
            const subtotal = item.price * item.qty;
            total += subtotal;
            const line = document.createElement('div');
            line.className = 'cart-line';
            const description = document.createElement('div');
            description.innerHTML = `<span class="cart-line-name"></span><small></small>`;
            description.querySelector('.cart-line-name').textContent = item.name;
            description.querySelector('small').textContent = `${item.qty} × ${rupiah(item.price)}`;
            const amount = document.createElement('strong');
            amount.className = 'mono';
            amount.textContent = rupiah(subtotal);
            const remove = document.createElement('button');
            remove.type = 'button';
            remove.className = 'button small danger';
            remove.title = `Hapus ${item.name}`;
            remove.setAttribute('aria-label', `Hapus ${item.name}`);
            remove.innerHTML = '<i class="bi bi-x-lg"></i>';
            remove.addEventListener('click', () => { saleCart.delete(id); renderSaleCart(); });
            line.append(description, amount, remove);
            saleCartElement.append(line);

            for (const [key, value] of Object.entries({ barang_id: id, qty: item.qty })) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `items[${index}][${key}]`;
                input.value = value;
                saleFields.append(input);
            }
            index++;
        }

        document.getElementById('sale-total').textContent = rupiah(total);
        document.getElementById('cart-count').textContent = `${saleCart.size} barang`;
        salePayment.min = total;
        const change = Number(salePayment.value || 0) - total;
        document.getElementById('sale-change').textContent = change >= 0 ? rupiah(change) : `Kurang ${rupiah(Math.abs(change))}`;
    }

    document.getElementById('add-sale-item')?.addEventListener('click', () => {
        const option = saleSelect.selectedOptions[0];
        const qty = Number(document.getElementById('sale-qty').value);
        if (!option.value || qty < 1) return;
        const id = option.value;
        const nextQty = (saleCart.get(id)?.qty || 0) + qty;
        if (nextQty > Number(option.dataset.stock)) {
            alert(`Stok ${option.dataset.name} hanya ${option.dataset.stock}.`);
            return;
        }
        saleCart.set(id, { name: option.dataset.name, price: Number(option.dataset.price), qty: nextQty });
        document.getElementById('sale-qty').value = 1;
        renderSaleCart();
    });
    document.getElementById('clear-sale').addEventListener('click', () => { saleCart.clear(); renderSaleCart(); });
    salePayment.addEventListener('input', renderSaleCart);
    document.getElementById('sale-form').addEventListener('submit', (event) => {
        if (saleCart.size === 0) {
            event.preventDefault();
            alert('Tambahkan setidaknya satu barang.');
        }
    });
    renderSaleCart();
</script>
@endpush