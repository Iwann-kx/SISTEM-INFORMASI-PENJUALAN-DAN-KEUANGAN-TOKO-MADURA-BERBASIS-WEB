@extends('layouts.warung')

@section('title', 'Ringkasan')
@section('section', 'Ringkasan operasional')

@section('content')
<div class="page-heading">
    <div>
        <div class="eyebrow">Toko Madura · Ikhtisar harian</div>
        <h1>Ringkasan operasional</h1>
        <p>Pantau penjualan, pembelian, dan ketersediaan barang hari ini.</p>
    </div>
    <div class="heading-actions">
        <a class="button secondary" href="{{ route('pembelian') }}"><i class="bi bi-box-seam"></i> Catat pembelian</a>
        <a class="button" href="{{ route('penjualan') }}"><i class="bi bi-plus-lg"></i> Transaksi baru</a>
    </div>
</div>

<section class="stats" aria-label="Statistik hari ini">
    <div class="stat" style="--accent:var(--green)"><i class="bi bi-arrow-up-right stat-icon"></i><div class="stat-label">Penjualan hari ini</div><div class="stat-value">Rp {{ number_format($salesToday, 0, ',', '.') }}</div><div class="stat-foot">Nilai transaksi tercatat hari ini</div></div>
    <div class="stat" style="--accent:var(--amber)"><i class="bi bi-arrow-down-left stat-icon"></i><div class="stat-label">Pembelian hari ini</div><div class="stat-value">Rp {{ number_format($purchasesToday, 0, ',', '.') }}</div><div class="stat-foot">Belanja barang dari supplier</div></div>
    <div class="stat" style="--accent:var(--coral)"><i class="bi bi-graph-up-arrow stat-icon"></i><div class="stat-label">Selisih hari ini</div><div class="stat-value">Rp {{ number_format($profitToday, 0, ',', '.') }}</div><div class="stat-foot">Penjualan dikurangi pembelian</div></div>
    <div class="stat" style="--accent:#7c9638"><i class="bi bi-boxes stat-icon"></i><div class="stat-label">Barang terdaftar</div><div class="stat-value">{{ number_format($productCount, 0, ',', '.') }}</div><div class="stat-foot">{{ $lowStockCount }} barang dengan stok 5 atau kurang</div></div>
</section>

<div class="columns">
    <section class="panel">
        <div class="panel-head">
            <div><h2>Aktivitas 7 hari</h2><small>Nilai penjualan dan pembelian harian</small></div>
            <div class="chart-legend"><span><i class="legend-dot"></i>Penjualan</span><span><i class="legend-dot purchase"></i>Pembelian</span></div>
        </div>
        <div class="panel-body">
            <div class="chart" role="img" aria-label="Grafik penjualan dan pembelian tujuh hari terakhir">
                @foreach ($summary as $day)
                    <div class="chart-day">
                        <div class="bars">
                            <span class="bar" title="Penjualan Rp {{ number_format($day['sales'], 0, ',', '.') }}" style="height:{{ max(3, ($day['sales'] / $maxSummary) * 100) }}%"></span>
                            <span class="bar purchase" title="Pembelian Rp {{ number_format($day['purchases'], 0, ',', '.') }}" style="height:{{ max(3, ($day['purchases'] / $maxSummary) * 100) }}%"></span>
                        </div>
                        <small>{{ $day['label'] }}</small>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <section class="panel">
        <div class="panel-head"><div><h2>Stok perlu diperhatikan</h2><small>Ambang pemantauan: 10 unit</small></div><a class="panel-link" href="{{ route('stok') }}">Semua barang <i class="bi bi-arrow-right"></i></a></div>
        @if ($lowStockItems->isEmpty())
            <div class="empty"><i class="bi bi-check2-circle"></i><br>Semua stok barang aman.</div>
        @else
            <div class="table-wrap"><table style="min-width:0"><thead><tr><th>Barang</th><th class="text-right">Sisa</th></tr></thead><tbody>
                @foreach ($lowStockItems as $barang)
                    <tr><td><span class="table-primary">{{ $barang->nama_barang }}</span><span class="table-sub">{{ $barang->kode_barang }}</span></td><td class="text-right"><span class="badge {{ $barang->stok_barang <= 3 ? 'red' : 'amber' }}">{{ $barang->stok_barang }} {{ $barang->satuan }}</span></td></tr>
                @endforeach
            </tbody></table></div>
        @endif
    </section>
</div>

<section class="panel">
    <div class="panel-head"><div><h2>Transaksi terbaru</h2><small>Aktivitas penjualan dan pembelian terakhir</small></div><a class="panel-link" href="{{ route('laporan') }}">Lihat laporan <i class="bi bi-arrow-right"></i></a></div>
    @if ($recentTransactions->isEmpty())
        <div class="empty">Belum ada transaksi. Mulai dengan mencatat penjualan atau pembelian.</div>
    @else
        <div class="table-wrap"><table><thead><tr><th>Jenis</th><th>Nomor</th><th>Tanggal</th><th class="text-right">Total</th></tr></thead><tbody>
            @foreach ($recentTransactions as $transaction)
                <tr><td><span class="badge {{ $transaction['type'] === 'Penjualan' ? 'green' : 'amber' }}">{{ $transaction['type'] }}</span></td><td class="table-primary mono">{{ $transaction['number'] }}</td><td class="muted">{{ $transaction['date']?->format('d/m/Y H:i') }}</td><td class="text-right table-primary">Rp {{ number_format($transaction['total'], 0, ',', '.') }}</td></tr>
            @endforeach
        </tbody></table></div>
    @endif
</section>
@endsection