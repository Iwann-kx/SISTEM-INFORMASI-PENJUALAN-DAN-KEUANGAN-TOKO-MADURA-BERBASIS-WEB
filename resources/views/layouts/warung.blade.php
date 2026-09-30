<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Ringkasan') | {{ config('app.name', 'Warung Sembako') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root{--ink:#192a25;--muted:#697972;--paper:#f3f5f0;--surface:#fff;--line:#dfe6df;--green:#17664f;--green-dark:#104c3b;--mint:#e5f1e8;--lime:#d9ed9b;--coral:#c75f48;--amber:#e9b64b;--shadow:0 12px 30px rgba(25,42,37,.06)}
        *{box-sizing:border-box}body{margin:0;background:var(--paper);color:var(--ink);font-family:'DM Sans',sans-serif;font-size:14px}a{color:inherit;text-decoration:none}button,input,select,textarea{font:inherit}button{cursor:pointer}
        .shell{min-height:100vh;display:grid;grid-template-columns:236px minmax(0,1fr)}.sidebar{position:sticky;top:0;height:100vh;background:var(--green-dark);color:#eff7ef;padding:25px 16px;display:flex;flex-direction:column;z-index:10}.brand{display:flex;align-items:center;gap:11px;padding:0 10px 27px;border-bottom:1px solid rgba(255,255,255,.14)}.brand-mark{width:38px;height:38px;background:var(--lime);color:var(--green-dark);display:grid;place-items:center;border-radius:10px;font-size:19px}.brand strong{font-family:'Space Grotesk',sans-serif;font-size:14px;display:block}.brand small{color:#b9cec3;font-size:11px}.nav-label{color:#9bb7a8;text-transform:uppercase;font-size:10px;font-weight:700;letter-spacing:1px;padding:25px 11px 9px}.nav-links{display:grid;gap:5px}.nav-link{display:flex;align-items:center;gap:12px;padding:11px 12px;border-radius:7px;color:#cfdfd4;font-weight:600;transition:background .18s,color .18s}.nav-link i{font-size:17px;width:20px;text-align:center}.nav-link:hover,.nav-link.active{background:#236f56;color:#fff}.nav-link.active{box-shadow:inset 3px 0 var(--lime)}.side-note{margin-top:auto;padding:14px 12px;border-top:1px solid rgba(255,255,255,.14);color:#b9cec3;font-size:11px;line-height:1.6}
        .main{min-width:0}.topbar{height:68px;background:rgba(255,255,255,.88);border-bottom:1px solid var(--line);display:flex;justify-content:space-between;align-items:center;padding:0 clamp(18px,3vw,42px);position:sticky;top:0;z-index:5;backdrop-filter:blur(12px)}.topbar-title{font-family:'Space Grotesk',sans-serif;font-weight:600;font-size:14px}.topbar-date{color:var(--muted);font-size:12px}.content{max-width:1440px;margin:0 auto;padding:30px clamp(18px,3vw,42px) 50px}.page-heading{display:flex;justify-content:space-between;align-items:flex-end;gap:18px;margin-bottom:24px}.eyebrow{color:var(--green);font-size:11px;text-transform:uppercase;letter-spacing:1px;font-weight:700;margin-bottom:6px}.page-heading h1{font:600 25px 'Space Grotesk',sans-serif;margin:0}.page-heading p{color:var(--muted);margin:6px 0 0}.heading-actions{display:flex;gap:8px;flex-wrap:wrap}
        .stats{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px;margin-bottom:20px}.stat{background:var(--surface);border:1px solid var(--line);border-radius:8px;padding:18px 19px;min-width:0;position:relative;overflow:hidden}.stat:before{content:'';position:absolute;left:0;top:0;bottom:0;width:4px;background:var(--accent,var(--green))}.stat-label{color:var(--muted);font-size:12px;font-weight:600}.stat-value{font:600 23px 'Space Grotesk',sans-serif;margin:10px 0 4px;overflow-wrap:anywhere}.stat-foot{font-size:11px;color:var(--muted)}.stat-icon{position:absolute;right:16px;top:16px;color:var(--accent,var(--green));font-size:18px}
        .columns{display:grid;grid-template-columns:minmax(0,1.5fr) minmax(280px,1fr);gap:16px;margin-bottom:16px}.columns.equal{grid-template-columns:repeat(2,minmax(0,1fr))}.panel{background:var(--surface);border:1px solid var(--line);border-radius:8px;min-width:0}.panel-head{display:flex;align-items:center;justify-content:space-between;padding:17px 19px;border-bottom:1px solid var(--line);gap:10px}.panel-head h2{font:600 15px 'Space Grotesk',sans-serif;margin:0}.panel-head small{color:var(--muted)}.panel-body{padding:18px 19px}.panel-link{color:var(--green);font-size:12px;font-weight:700}.chart-legend{display:flex;gap:16px;color:var(--muted);font-size:11px}.legend-dot{display:inline-block;width:8px;height:8px;border-radius:50%;margin-right:5px;background:var(--green)}.legend-dot.purchase{background:var(--amber)}.chart{height:190px;display:grid;grid-template-columns:repeat(7,minmax(0,1fr));gap:12px;align-items:end;padding-top:15px}.chart-day{height:100%;display:flex;flex-direction:column;justify-content:flex-end;align-items:center;gap:7px}.bars{height:145px;width:100%;display:flex;gap:4px;align-items:flex-end;justify-content:center}.bar{width:min(23px,42%);min-height:3px;border-radius:4px 4px 0 0;background:var(--green)}.bar.purchase{background:var(--amber)}.chart-day small{font-size:10px;color:var(--muted)}
        .table-wrap{overflow:auto}table{width:100%;border-collapse:collapse;min-width:580px}th{text-align:left;color:var(--muted);font-size:10px;text-transform:uppercase;letter-spacing:.6px;font-weight:700;padding:11px 13px;background:#f8faf7;white-space:nowrap}td{padding:12px 13px;border-top:1px solid #edf0ec;vertical-align:middle}tbody tr:hover{background:#fafcf9}.text-right{text-align:right}.text-center{text-align:center}.muted{color:var(--muted)}.mono{font-variant-numeric:tabular-nums}.table-primary{font-weight:700}.table-sub{display:block;color:var(--muted);font-size:11px;margin-top:3px}.badge{display:inline-flex;align-items:center;gap:5px;border-radius:4px;padding:4px 7px;font-size:10px;font-weight:700;background:#edf1ed;color:#53645b}.badge.green{background:var(--mint);color:var(--green-dark)}.badge.amber{background:#fff3d6;color:#805d11}.badge.red{background:#fae8e3;color:#a54531}.empty{text-align:center;color:var(--muted);padding:28px 10px}
        .button{border:0;border-radius:5px;display:inline-flex;align-items:center;justify-content:center;gap:7px;padding:9px 13px;background:var(--green);color:#fff;font-weight:700;font-size:12px;transition:background .15s}.button:hover{background:var(--green-dark);color:#fff}.button.secondary{background:#edf2ed;color:var(--ink)}.button.secondary:hover{background:#e0e8e0}.button.danger{background:#fae8e3;color:#9e3f2d}.button.danger:hover{background:#f3d3ca}.button.small{padding:6px 9px;font-size:11px}.field-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:13px}.field{display:grid;gap:6px}.field label{font-size:11px;color:#52635b;font-weight:700}.control{width:100%;border:1px solid #d7e0d8;border-radius:5px;padding:9px 10px;background:#fff;color:var(--ink);outline:none}.control:focus{border-color:var(--green);box-shadow:0 0 0 3px rgba(23,102,79,.1)}.form-row{display:flex;gap:9px;align-items:flex-end}.form-row>.field{flex:1}.form-actions{display:flex;gap:8px;align-items:center;margin-top:14px}.form-section{padding:16px 0;border-bottom:1px solid #edf0ec}.form-section:last-child{border-bottom:0;padding-bottom:0}.form-section h3{font:600 13px 'Space Grotesk',sans-serif;margin:0 0 12px}.notice{padding:11px 13px;border-radius:5px;background:var(--mint);color:var(--green-dark);margin-bottom:16px;font-size:12px}.notice.error{background:#fae8e3;color:#923e2e}.notice ul{margin:5px 0 0;padding-left:18px}.details-form{padding-top:8px}.details-form form{min-width:310px}.supplier-bar{display:grid;grid-template-columns:minmax(160px,1fr) minmax(220px,1.3fr) auto;gap:10px;align-items:end}.cart-list{display:grid;gap:8px}.cart-line{display:grid;grid-template-columns:1fr auto auto;align-items:center;gap:10px;padding:10px;border:1px solid var(--line);border-radius:5px}.cart-line-name{font-weight:700}.cart-line small{display:block;color:var(--muted);margin-top:3px}.cart-summary{display:flex;justify-content:space-between;align-items:center;border-top:1px solid var(--line);margin-top:15px;padding-top:15px;font-weight:700}.cart-summary strong{font:600 19px 'Space Grotesk',sans-serif;color:var(--green)}.inline-details{position:relative}.inline-details summary{cursor:pointer;list-style:none}.inline-details summary::-webkit-details-marker{display:none}.filter-bar{display:flex;align-items:flex-end;gap:10px;flex-wrap:wrap}.filter-bar .field{min-width:160px}.two-tables{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}.footer-note{color:#829087;font-size:11px;padding:18px 0}
        @media(max-width:1050px){.stats{grid-template-columns:repeat(2,minmax(0,1fr))}.columns{grid-template-columns:1fr}.two-tables{grid-template-columns:1fr}}
        @media(max-width:700px){.shell{grid-template-columns:minmax(0,1fr)}.sidebar{position:relative;min-width:0;height:auto;padding:12px 14px}.brand{min-width:0;padding:0 4px 11px;border:0}.brand-mark{width:33px;height:33px}.nav-label,.side-note{display:none}.nav-links{display:flex;min-width:0;max-width:100%;overflow:auto;margin:8px -14px -12px;padding:7px 14px 12px}.nav-link{white-space:nowrap;padding:8px 10px;font-size:11px}.nav-link i{font-size:15px}.topbar{height:48px;position:static;padding:0 17px}.content{padding:22px 15px 36px}.page-heading{align-items:flex-start;flex-direction:column;margin-bottom:18px}.page-heading h1{font-size:22px}.stats{gap:9px}.stat{padding:14px 14px}.stat-value{font-size:18px}.stat-icon{right:12px;top:12px}.panel-head,.panel-body{padding-left:14px;padding-right:14px}.chart{gap:4px}.bars{gap:3px}.columns.equal{grid-template-columns:1fr}.field-grid{grid-template-columns:1fr}.supplier-bar{grid-template-columns:1fr}.form-row{align-items:stretch;flex-direction:column}.form-row .button{width:100%}.filter-bar{align-items:stretch;flex-direction:column}.filter-bar .field{width:100%}.cart-line{grid-template-columns:1fr auto}.cart-line .button{grid-column:2;grid-row:1/3}.page-heading .heading-actions{width:100%}.page-heading .heading-actions .button{flex:1}}
        @media print{.sidebar,.topbar,.heading-actions,.filter-bar,.footer-note{display:none!important}.shell{display:block}.content{max-width:none;padding:0}.panel,.stat{break-inside:avoid;box-shadow:none}}
    </style>
</head>
<body>
<div class="shell">
    <aside class="sidebar">
        <a href="{{ route('dashboard') }}" class="brand">
            <span class="brand-mark"><i class="bi bi-basket2-fill"></i></span>
            <span><strong>WARUNG SEMBAKO</strong><small>Penjualan &amp; keuangan</small></span>
        </a>
        <div class="nav-label">Menu utama</div>
        <nav class="nav-links" aria-label="Navigasi utama">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="bi bi-grid-1x2"></i> Ringkasan</a>
            <a class="nav-link {{ request()->routeIs('penjualan*') ? 'active' : '' }}" href="{{ route('penjualan') }}"><i class="bi bi-receipt"></i> Penjualan</a>
            <a class="nav-link {{ request()->routeIs('pembelian*') ? 'active' : '' }}" href="{{ route('pembelian') }}"><i class="bi bi-box-seam"></i> Pembelian</a>
            <a class="nav-link {{ request()->routeIs('stok', 'barang.*') ? 'active' : '' }}" href="{{ route('stok') }}"><i class="bi bi-list-check"></i> Stok barang</a>
            <a class="nav-link {{ request()->routeIs('laporan') ? 'active' : '' }}" href="{{ route('laporan') }}"><i class="bi bi-bar-chart-line"></i> Laporan</a>
        </nav>
        <div class="side-note">Sistem informasi penjualan<br>dan keuangan toko</div>
    </aside>
    <div class="main">
        <header class="topbar">
            <span class="topbar-title">@yield('section', 'Ringkasan operasional')</span>
            <div style="display:flex;align-items:center;gap:12px">
                <span class="topbar-date">{{ now()->translatedFormat('l, d F Y') }}</span>
                <form method="POST" action="{{ route('logout') }}" style="margin:0">
                    @csrf
                    <button type="submit" style="border:1px solid var(--line);border-radius:5px;padding:7px 10px;background:var(--surface);color:var(--ink);font-size:11px;font-weight:700"><i class="bi bi-box-arrow-right"></i> Keluar</button>
                </form>
            </div>
        </header>
        <main class="content">
            @if (session('success'))
                <div class="notice" role="status"><i class="bi bi-check-circle"></i> {{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="notice error" role="alert"><strong>Periksa kembali data yang dimasukkan.</strong><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
            @endif
            @yield('content')
            <div class="footer-note">Warung Sembako <span aria-hidden="true">/</span> Sistem Informasi Penjualan dan Keuangan</div>
        </main>
    </div>
</div>
@stack('scripts')
</body>
</html>