@extends('layouts.warung')

@section('title', 'Stok Barang')
@section('section', 'Persediaan barang')

@section('content')
<div class="page-heading">
    <div><div class="eyebrow">Persediaan</div><h1>Stok barang</h1><p>Kelola harga, satuan, dan jumlah persediaan.</p></div>
    <div class="heading-actions"><a class="button secondary" href="{{ route('pembelian') }}"><i class="bi bi-box-seam"></i> Catat stok masuk</a></div>
</div>

<section class="panel">
    <div class="panel-head">
        <div><h2>Daftar barang</h2><small>{{ $barangList->count() }} barang terdaftar</small></div>
        <details class="inline-details">
            <summary class="button"><i class="bi bi-plus-lg"></i> Tambah barang</summary>
            <div class="details-form" style="position:absolute;right:0;top:42px;width:min(520px,calc(100vw - 34px));padding:18px;background:#fff;border:1px solid var(--line);border-radius:8px;box-shadow:var(--shadow);z-index:4">
                <form method="POST" action="{{ route('barang.store') }}">
                    @csrf
                    <div class="field-grid">
                        <div class="field"><label for="kode_barang">Kode barang</label><input class="control" id="kode_barang" name="kode_barang" value="{{ old('kode_barang') }}" required maxlength="50"></div>
                        <div class="field"><label for="nama_barang">Nama barang</label><input class="control" id="nama_barang" name="nama_barang" value="{{ old('nama_barang') }}" required maxlength="255"></div>
                        <div class="field"><label for="harga_beli">Harga beli (Rp)</label><input class="control" id="harga_beli" name="harga_beli" type="number" min="0" step="1" required></div>
                        <div class="field"><label for="harga_jual">Harga jual (Rp)</label><input class="control" id="harga_jual" name="harga_jual" type="number" min="0" step="1" required></div>
                        <div class="field"><label for="stok_barang">Stok awal</label><input class="control" id="stok_barang" name="stok_barang" type="number" min="0" step="1" value="0" required></div>
                        <div class="field"><label for="satuan">Satuan</label><input class="control" id="satuan" name="satuan" placeholder="Kg, pcs, box" maxlength="50" required></div>
                    </div>
                    <div class="form-actions"><button class="button" type="submit"><i class="bi bi-check-lg"></i> Simpan barang</button></div>
                </form>
            </div>
        </details>
    </div>
    <div class="panel-body" style="padding-bottom:10px">
        <div class="field" style="max-width:350px"><label for="search-stock">Cari barang</label><input class="control" id="search-stock" type="search" placeholder="Nama atau kode barang"></div>
    </div>
    @if ($barangList->isEmpty())
        <div class="empty">Belum ada barang terdaftar.</div>
    @else
        <div class="table-wrap"><table id="stock-table"><thead><tr><th>Kode</th><th>Nama barang</th><th>Harga beli</th><th>Harga jual</th><th class="text-right">Stok</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
            @foreach ($barangList as $barang)
                <tr data-search="{{ strtolower($barang->kode_barang.' '.$barang->nama_barang) }}">
                    <td class="mono">{{ $barang->kode_barang }}</td>
                    <td><span class="table-primary">{{ $barang->nama_barang }}</span><span class="table-sub">{{ $barang->satuan }}</span></td>
                    <td class="mono">Rp {{ number_format($barang->harga_beli, 0, ',', '.') }}</td>
                    <td class="mono">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</td>
                    <td class="text-right table-primary">{{ number_format($barang->stok_barang, 0, ',', '.') }}</td>
                    <td>@if ($barang->stok_barang <= 3)<span class="badge red">Kritis</span>@elseif ($barang->stok_barang <= 10)<span class="badge amber">Menipis</span>@else<span class="badge green">Aman</span>@endif</td>
                    <td><div style="display:flex;gap:6px;align-items:center">
                        <details class="inline-details">
                            <summary class="button small secondary" title="Edit barang" aria-label="Edit {{ $barang->nama_barang }}"><i class="bi bi-pencil"></i></summary>
                            <div class="details-form" style="position:absolute;right:0;top:34px;width:min(420px,calc(100vw - 34px));padding:16px;background:#fff;border:1px solid var(--line);border-radius:8px;box-shadow:var(--shadow);z-index:3">
                                <form method="POST" action="{{ route('barang.update', $barang) }}">
                                    @csrf @method('PUT')
                                    <div class="field-grid">
                                        <div class="field"><label>Kode barang</label><input class="control" name="kode_barang" value="{{ $barang->kode_barang }}" required maxlength="50"></div>
                                        <div class="field"><label>Nama barang</label><input class="control" name="nama_barang" value="{{ $barang->nama_barang }}" required maxlength="255"></div>
                                        <div class="field"><label>Harga beli (Rp)</label><input class="control" name="harga_beli" type="number" min="0" value="{{ $barang->harga_beli }}" required></div>
                                        <div class="field"><label>Harga jual (Rp)</label><input class="control" name="harga_jual" type="number" min="0" value="{{ $barang->harga_jual }}" required></div>
                                        <div class="field"><label>Stok</label><input class="control" name="stok_barang" type="number" min="0" value="{{ $barang->stok_barang }}" required></div>
                                        <div class="field"><label>Satuan</label><input class="control" name="satuan" value="{{ $barang->satuan }}" required maxlength="50"></div>
                                    </div>
                                    <div class="form-actions"><button class="button small" type="submit"><i class="bi bi-check-lg"></i> Simpan perubahan</button></div>
                                </form>
                            </div>
                        </details>
                        <form method="POST" action="{{ route('barang.destroy', $barang) }}" onsubmit="return confirm('Hapus barang {{ $barang->nama_barang }}?')">
                            @csrf @method('DELETE')
                            <button class="button small danger" type="submit" title="Hapus barang" aria-label="Hapus {{ $barang->nama_barang }}"><i class="bi bi-trash3"></i></button>
                        </form>
                    </div></td>
                </tr>
            @endforeach
        </tbody></table></div>
    @endif
</section>
@endsection

@push('scripts')
<script>
    document.getElementById('search-stock')?.addEventListener('input', (event) => {
        const query = event.target.value.trim().toLocaleLowerCase('id');
        document.querySelectorAll('#stock-table tbody tr').forEach((row) => {
            row.hidden = !row.dataset.search.includes(query);
        });
    });
</script>
@endpush