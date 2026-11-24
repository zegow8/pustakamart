<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - PustakaMart</title>
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

    <div class="auth-wrapper" style="padding: 40px 16px;">
        <div class="auth-card" style="max-width: 480px;">
            <div class="auth-logo" style="display:flex; flex-direction:column; align-items:center; gap:8px; margin-bottom:20px;">
                <img src="{{ asset('images/pustakamart.png') }}" alt="PustakaMart" style="height:64px; width:64px; object-fit:contain;">
                <span style="font-size: 1.5rem; font-weight: 800; color: var(--primary); letter-spacing:-0.5px;">PUSTAKAMART</span>
            </div>
            <h1 class="auth-title">Buat Akun Baru</h1>
            <p class="auth-subtitle">Daftar untuk mulai memesan buku fisik original favorit Anda di PustakaMart.</p>

            @if($errors->any())
                <div class="alert alert-danger" style="margin-bottom: 20px;">
                    <ul style="margin:0; padding-left:20px; font-size:0.875rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="/register" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span style="color:var(--danger); font-weight:700;">*</span></label>
                    <input type="text" name="name" class="form-control" required placeholder="Contoh: Budi Santoso" value="{{ old('name') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Alamat Email <span style="color:var(--danger); font-weight:700;">*</span></label>
                    <input type="email" name="email" class="form-control" required placeholder="nama@email.com" value="{{ old('email') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Nomor Telepon / WhatsApp <span style="color:var(--danger); font-weight:700;">*</span></label>
                    <input type="tel" name="phone" class="form-control" required minlength="10" maxlength="15" placeholder="Contoh: 081234567890" value="{{ old('phone') }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 15);">
                    <small class="text-muted" style="font-size:0.8rem; display:block; margin-top:4px;">Dibutuhkan untuk konfirmasi pesanan &amp; koordinasi kurir ekspedisi (10-15 digit angka).</small>
                </div>

                <div class="form-group">
                    <label class="form-label">NIK (Nomor Induk Kependudukan) <span style="color:var(--danger); font-weight:700;">*</span></label>
                    <input type="text" name="nik" class="form-control" required maxlength="16" minlength="16" placeholder="Contoh: 3171012345670001 (16 digit)" value="{{ old('nik') }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16);">
                    <small class="text-muted" style="font-size:0.8rem; display:block; margin-top:4px;">Wajib 16 digit angka unik sesuai data KTP.</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Alamat Lengkap Pengiriman <span style="color:var(--danger); font-weight:700;">*</span></label>
                    <textarea name="address" class="form-control" rows="3" required placeholder="Tuliskan nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan, kota/kabupaten, dan kode pos...">{{ old('address') }}</textarea>
                    <small class="text-muted" style="font-size:0.8rem; display:block; margin-top:4px;">Alamat otomatis tersimpan sebagai alamat pengiriman pesanan buku Anda.</small>
                </div>
                <div class="form-group">
                    <label class="form-label">Password (min. 6 karakter) <span style="color:var(--danger); font-weight:700;">*</span></label>
                    <input type="password" name="password" class="form-control" required minlength="6" placeholder="Masukkan kata sandi">
                </div>
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password <span style="color:var(--danger); font-weight:700;">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control" required minlength="6" placeholder="Ulangi kata sandi">
                </div>
                <button type="submit" class="btn btn-primary btn-block" style="margin-top:12px; font-weight:700; padding:12px;">Daftar Sekarang</button>
            </form>

            <div class="auth-footer" style="margin-top:20px;">
                Sudah punya akun? <a href="/login" style="font-weight:700;">Login di sini</a>
            </div>
        </div>
    </div>
</body>
</html>