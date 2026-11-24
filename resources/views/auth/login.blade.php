<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PustakaMart</title>
    <link rel="icon" type="image/png" href="{{ asset('images/pustakamart.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>
    <nav class="navbar">
        <div class="container navbar-inner">
            <a href="/" class="navbar-brand" style="display:inline-flex; align-items:center; gap:8px; text-decoration:none;">
                <img src="{{ asset('images/pustakamart.png') }}" alt="PustakaMart" style="height:34px; width:34px; object-fit:contain;">
                <span style="font-size: 1.3rem; font-weight: 800; color: var(--primary);">PUSTAKAMART</span>
            </a>
            <div class="navbar-actions">
                <a href="/" class="btn btn-outline btn-sm">Beranda</a>
            </div>
        </div>
    </nav>

    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-logo" style="display:flex; flex-direction:column; align-items:center; gap:8px; margin-bottom:20px;">
                <img src="{{ asset('images/pustakamart.png') }}" alt="PustakaMart" style="height:64px; width:64px; object-fit:contain;">
                <span style="font-size: 1.5rem; font-weight: 800; color: var(--primary); letter-spacing:-0.5px;">PUSTAKAMART</span>
            </div>
            <h1 class="auth-title">Masuk ke Akun</h1>
            <p class="auth-subtitle">Silakan login untuk memesan dan membeli buku fisik.</p>

            @if(session('error'))
                <div class="alert alert-danger">⚠️ {{ session('error') }}</div>
            @endif
            @if(session('success'))
                <div class="alert alert-success">✅ {{ session('success') }}</div>
            @endif

            <form action="/login" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label class="form-label">Nama Lengkap <span style="color:var(--danger); font-weight:700;">*</span></label>
                    <input type="text" name="name" class="form-control" required value="{{ old('name') }}" placeholder="Contoh: Administrator atau Lyta Sayang">
                    @error('name') <small style="color:var(--danger);">{{ $message }}</small> @enderror
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Alamat Email <span style="color:var(--danger); font-weight:700;">*</span></label>
                    <input type="email" name="email" class="form-control" required value="{{ old('email') }}" placeholder="nama@email.com">
                    @error('email') <small style="color:var(--danger);">{{ $message }}</small> @enderror
                </div>
                <div class="form-group mb-4">
                    <label class="form-label">Password <span style="color:var(--danger); font-weight:700;">*</span></label>
                    <input type="password" name="password" class="form-control" required placeholder="••••••••">
                    @error('password') <small style="color:var(--danger);">{{ $message }}</small> @enderror
                </div>
                <button type="submit" class="btn btn-primary btn-block" style="font-weight:700; padding:12px;">Masuk ke Akun</button>
            </form>

            <div class="auth-footer" style="margin-top:20px;">
                Belum punya akun? <a href="/register" style="font-weight:700;">Daftar di sini</a>
            </div>
        </div>
    </div>
</body>
</html>
