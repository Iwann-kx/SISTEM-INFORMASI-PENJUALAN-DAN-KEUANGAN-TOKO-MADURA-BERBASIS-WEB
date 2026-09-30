<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk | Warung Sembako</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root{font-family:'DM Sans',sans-serif;color:#192a25;background:#f3f5f0;font-synthesis:none;text-rendering:optimizeLegibility;font-size:14px;--green:#17664f;--dark:#104c3b;--muted:#697972;--line:#dfe6df}
        *{box-sizing:border-box}body{margin:0;min-height:100vh;display:grid;place-items:center;padding:28px}.login-shell{width:min(100%,1040px);min-height:610px;display:grid;grid-template-columns:1fr 1fr;background:#fff;border:1px solid var(--line);box-shadow:0 24px 70px rgba(25,42,37,.12);overflow:hidden}.welcome-panel{position:relative;isolation:isolate;display:flex;flex-direction:column;justify-content:space-between;padding:42px;background:#104c3b;color:#f3f7f2;overflow:hidden}.welcome-panel:before{content:'';position:absolute;inset:0;z-index:-1;opacity:.17;background:repeating-linear-gradient(135deg,transparent 0 27px,#b8d38f 28px 29px,transparent 30px 54px)}.brand{display:flex;align-items:center;gap:12px}.brand-mark{width:42px;height:42px;display:grid;place-items:center;background:#d9ed9b;color:#104c3b;font-size:20px;border-radius:9px}.brand strong{display:block;font:700 13px 'Space Grotesk',sans-serif;letter-spacing:.5px}.brand small{display:block;margin-top:3px;color:#bdd0c4;font-size:11px}.welcome-copy{max-width:390px;padding:38px 0}.eyebrow{display:flex;align-items:center;gap:9px;color:#d9ed9b;font-size:10px;font-weight:700;letter-spacing:1.4px;text-transform:uppercase}.eyebrow:before{content:'';width:24px;height:1px;background:#d9ed9b}.welcome-copy h1{margin:18px 0 12px;font:600 38px/1.12 'Space Grotesk',sans-serif;letter-spacing:0}.welcome-copy p{max-width:330px;margin:0;color:#d0dfd5;font-size:14px;line-height:1.8}.panel-foot{color:#b4c9bd;font-size:11px}.login-panel{display:grid;place-items:center;padding:48px}.login-form{width:min(100%,340px)}.login-form h2{margin:0;font:600 26px 'Space Grotesk',sans-serif;letter-spacing:0}.form-intro{margin:8px 0 30px;color:var(--muted);line-height:1.6}.error{padding:11px 12px;margin-bottom:18px;background:#fae8e3;border:1px solid #f1d2ca;border-radius:5px;color:#923e2e;font-size:12px;line-height:1.5}.field{display:grid;gap:7px;margin-top:17px}.field label{color:#52635b;font-size:11px;font-weight:700}.input-wrap{position:relative}.input-wrap i{position:absolute;top:50%;left:13px;transform:translateY(-50%);color:#84948b;font-size:15px}.input-wrap input{width:100%;height:46px;padding:0 13px 0 40px;border:1px solid #d7e0d8;border-radius:5px;background:#fff;color:#192a25;font:inherit;outline:none}.input-wrap input:focus{border-color:var(--green);box-shadow:0 0 0 3px rgba(23,102,79,.11)}.input-wrap input::placeholder{color:#9aa69f}.submit{width:100%;height:46px;margin-top:25px;display:flex;align-items:center;justify-content:center;gap:9px;border:0;border-radius:5px;background:var(--green);color:#fff;font:700 13px 'DM Sans',sans-serif;cursor:pointer;transition:background .15s}.submit:hover{background:var(--dark)}.form-note{margin:19px 0 0;text-align:center;color:var(--muted);font-size:11px}.mobile-brand{display:none}
        @media(max-width:720px){body{padding:0;background:#fff}.login-shell{min-height:100vh;grid-template-columns:1fr;border:0;box-shadow:none}.welcome-panel{min-height:210px;padding:24px 25px}.welcome-copy{padding:28px 0 4px}.welcome-copy h1{font-size:29px;margin:12px 0 7px}.welcome-copy p,.panel-foot{display:none}.login-panel{align-items:start;padding:42px 25px}.login-form{width:min(100%,400px)}.login-form h2{font-size:24px}.form-intro{margin-bottom:22px}}
        @media(prefers-reduced-motion:reduce){*,*:before,*:after{scroll-behavior:auto!important;transition:none!important}}
    </style>
</head>
<body>
<main class="login-shell">
    <section class="welcome-panel" aria-label="Warung Sembako">
        <a class="brand" href="{{ route('login') }}">
            <span class="brand-mark"><i class="bi bi-basket2-fill"></i></span>
            <span><strong>WARUNG SEMBAKO</strong><small>Penjualan &amp; keuangan</small></span>
        </a>
        <div class="welcome-copy">
            <div class="eyebrow">Sistem kasir toko</div>
            <h1>Semua urusan toko, lebih tertata.</h1>
            <p>Catat transaksi, pantau persediaan, dan lihat ringkasan usaha dalam satu tempat.</p>
        </div>
        <div class="panel-foot">Akses khusus untuk staf toko</div>
    </section>
    <section class="login-panel">
        <form class="login-form" method="POST" action="{{ route('login.store') }}">
            @csrf
            <h2>Masuk ke toko</h2>
            <p class="form-intro">Gunakan akun kasir untuk melanjutkan ke sistem.</p>
            @if ($errors->any())
                <div class="error" role="alert">{{ $errors->first() }}</div>
            @endif
            <div class="field">
                <label for="username">Username</label>
                <div class="input-wrap">
                    <i class="bi bi-person" aria-hidden="true"></i>
                    <input id="username" name="username" type="text" value="{{ old('username') }}" autocomplete="username" placeholder="Masukkan username" required autofocus>
                </div>
            </div>
            <div class="field">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <i class="bi bi-lock" aria-hidden="true"></i>
                    <input id="password" name="password" type="password" autocomplete="current-password" placeholder="Masukkan password" required>
                </div>
            </div>
            <button class="submit" type="submit">Masuk <i class="bi bi-arrow-right" aria-hidden="true"></i></button>
            <p class="form-note">Sistem Informasi Penjualan dan Keuangan</p>
        </form>
    </section>
</main>
</body>
</html>