@extends('layouts.warung')

@section('title', 'Laporan')
@section('section', 'Laporan keuangan')

@section('content')
<div class="page-heading">
    <div><div class="eyebrow">Analisis transaksi</div><h1>Laporan</h1><p>Rekap penjualan dan pembelian sesuai rentang tanggal.</p></div>
    <div class="heading-actions"><button class="button secondary" type="button" onclick="window.print()"><i class="bi bi-printer"></i> Cetak</button></div>
</div>

<section class="panel" style="margin-bottom:16px">
    <div class="panel-body">
        <form class="filter-bar" method="GET" action="{{ route('laporan') }}">
            <div class="field"><label for="start-date">Tanggal mulai</label><input class="control" id="start-date" name="start_date" type="date" value="{{ $startDate }}" required></div>
            <div class="field"><label for="end-date">Tanggal akhir</label><input class="control" id="end-date" name="end_date" type="date" value="{{ $endDate }}" required></div>
            <button class="button" type="submit"><i class="bi bi-funnel"></i> Terapkan</button>
        </form>
    </div>
</section>

<section class="stats" aria-label="Ringkasan laporan">
    <div class="stat" style="--accent:var(--green)"><i class="bi bi-receipt stat-icon"></i><div class="stat-label">Total penjualan</div><div class="stat-value">Rp {{ number_format($salesTotal, 0, ',', '.') }}</div><div class="stat-foot">{{ $sales->count() }} transaksi</div></div>
    <div class="stat" style="--accent:var(--amber)"><i class="bi bi-box-seam stat-icon"></i><div class="stat-label">Total pembelian</div><div class="stat-value">Rp {{ number_format($purchasesTotal, 0, ',', '.') }}</div><div class="stat-foot">{{ $purchases->count() }} faktur</div></div>
    <div class="stat" style="--accent:var(--coral)"><i class="bi bi-calculator stat-icon"></i><div class="stat-label">Selisih periode</div><div class="stat-value">Rp {{ number_format($salesTotal - $purchasesTotal, 0, ',', '.') }}</div><div class="stat-foot">Penjualan dikurangi pembelian tercatat</div></div>
    <div class="stat" style="--accent:#7c9638"><i class="bi bi-calendar3 stat-icon"></i><div class="stat-label">Periode laporan</div><div class="stat-value" style="font-size:17px">{{ \Illuminate\Support\Carbon::parse($startDate)->format('d M Y') }}</div><div class="stat-foot">sampai {{ \Illuminate\Support\Carbon::parse($endDate)->format('d M Y') }}</div></div>
</section>

<div class="two-tables">
    <section class="panel">
        <div class="panel-head"><div><h2>Penjualan</h2><small>{{ $sales->count() }} transaksi dalam periode ini</small></div><span class="badge green">Rp {{ number_format($salesTotal, 0, ',', '.') }}</span></div>
        @if ($sales->isEmpty())
            <div class="empty">Tidak ada transaksi penjualan pada periode ini.</div>
        @else
            <div class="table-wrap"><table><thead><tr><th>No. transaksi</th><th>Tanggal</th><th class="text-right">Total</th></tr></thead><tbody>
                @foreach ($sales as $sale)
                    <tr><td class="table-primary mono">{{ $sale->no_transaksi }}</td><td class="muted">{{ $sale->tanggal?->format('d/m/Y H:i') }}</td><td class="text-right table-primary">Rp {{ number_format($sale->total, 0, ',', '.') }}</td></tr>
                @endforeach
            </tbody></table></div>
        @endif
    </section>
    <section class="panel">
        <div class="panel-head"><div><h2>Pembelian</h2><small>{{ $purchases->count() }} faktur dalam periode ini</small></div><span class="badge amber">Rp {{ number_format($purchasesTotal, 0, ',', '.') }}</span></div>
        @if ($purchases->isEmpty())
            <div class="empty">Tidak ada transaksi pembelian pada periode ini.</div>
        @else
            <div class="table-wrap"><table><thead><tr><th>No. faktur</th><th>Supplier / tanggal</th><th class="text-right">Total</th></tr></thead><tbody>
                @foreach ($purchases as $purchase)
                    <tr><td class="table-primary mono">{{ $purchase->no_faktur }}</td><td><span>{{ $purchase->supplier->nama_supplier }}</span><span class="table-sub">{{ $purchase->tanggal?->format('d/m/Y H:i') }}</span></td><td class="text-right table-primary">Rp {{ number_format($purchase->total, 0, ',', '.') }}</td></tr>
                @endforeach
            </tbody></table></div>
        @endif
    </section>
</div>
@endsection