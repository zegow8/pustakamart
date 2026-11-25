<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Beranda') - PustakaMart</title>
    <link rel="icon" type="image/png" href="{{ asset('images/pustakamart.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <style>
        html, body { max-width: 100vw; overflow-x: hidden; }
    </style>
</head>
<body>
    <!-- NAVBAR PUBLIC -->
    <nav class="navbar">
        <div class="container navbar-inner">
            <a href="/" class="navbar-brand" style="display:inline-flex; align-items:center; gap:9px; text-decoration:none;">
                <img src="{{ asset('images/pustakamart.png') }}" alt="PustakaMart" style="height:34px; width:34px; object-fit:contain;">
                <span style="font-size:1.4rem; font-weight:800; color:var(--primary); letter-spacing:-0.5px;">PUSTAKAMART</span>
            </a>
            <ul class="navbar-nav">
                <li><a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Beranda</a></li>
                <li><a href="/catalog" class="{{ request()->is('catalog') ? 'active' : '' }}">Katalog Buku</a></li>
                <li><a href="/about" class="{{ request()->is('about') ? 'active' : '' }}">About Us</a></li>
                <li><a href="/contact" class="{{ request()->is('contact*') ? 'active' : '' }}">Hubungi Kami</a></li>
                @auth
                    @if(auth()->user()->role === 'user')
                        <li><a href="/user/chat" class="{{ request()->is('user/chat*') ? 'active' : '' }}">💬 LiveChat CS</a></li>
                    @elseif(auth()->user()->role === 'admin')
                        <li><a href="/admin/chats" class="{{ request()->is('admin/chats*') ? 'active' : '' }}">💬 Chat Pelanggan</a></li>
                    @endif
                @endauth
            </ul>
            <div class="navbar-actions">
                @auth
                    <a href="{{ auth()->user()->role === 'admin' ? '/admin/dashboard' : '/user/dashboard' }}" class="btn btn-outline btn-sm">Dashboard</a>
                @else
                    <a href="/login" class="btn btn-outline btn-sm">Login</a>
                    <a href="/register" class="btn btn-primary btn-sm">Daftar</a>
                @endauth
                <button class="navbar-hamburger" id="navbar-hamburger" aria-label="Menu" aria-expanded="false">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
        <!-- Mobile Menu -->
        <div class="navbar-mobile-menu" id="navbar-mobile-menu">
            <a href="/">Beranda</a>
            <a href="/catalog">Katalog Buku</a>
            <a href="/about">About Us</a>
            <a href="/contact">Hubungi Kami</a>
            @auth
                @if(auth()->user()->role === 'user')
                    <a href="/user/chat">💬 LiveChat CS</a>
                @elseif(auth()->user()->role === 'admin')
                    <a href="/admin/chats">💬 Chat Pelanggan</a>
                @endif
                <a href="{{ auth()->user()->role === 'admin' ? '/admin/dashboard' : '/user/dashboard' }}">Dashboard</a>
            @else
                <a href="/login">Login</a>
                <a href="/register" style="color:var(--primary);">Daftar Gratis</a>
            @endauth
        </div>
    </nav>

    <main class="main-content">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success">✅ {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">⚠️ {{ session('error') }}</div>
            @endif
            
            @yield('content')
        </div>
    </main>

    <!-- FOOTER PUBLIC HARMONIS -->
    <footer class="footer">
        <div class="container">
            <div class="footer-top" style="grid-template-columns: 1.4fr 1fr 1fr 1.4fr; gap: 36px;">
                <!-- KOLOM 1: BRAND & DESKRIPSI -->
                <div class="footer-col">
                    <div class="footer-brand" style="margin-bottom:12px; display:flex; align-items:center; gap:10px;">
                        <img src="{{ asset('images/pustakamart.png') }}" alt="PustakaMart" style="height:36px; width:36px; object-fit:contain; background:white; border-radius:8px; padding:2px;">
                        <span style="font-size:1.3rem; font-weight:800; color:white; letter-spacing:0.04em;">PUSTAKAMART</span>
                    </div>
                    <p class="footer-tagline" style="font-size:0.875rem; color:rgba(255,255,255,0.75); line-height:1.6; margin-bottom:16px;">
                        Toko Buku Online Modern &amp; Terpercaya. Menghadirkan ribuan koleksi buku fisik original berkualitas dengan harga terbaik dan pengiriman cepat ke seluruh Indonesia.
                    </p>
                    <div style="display:flex; flex-wrap:wrap; gap:6px;">
                        <span style="background:rgba(255,255,255,0.08); color:#93c5fd; font-size:0.75rem; font-weight:600; padding:3px 8px; border-radius:6px; border:1px solid rgba(255,255,255,0.12);">📚 100% Original</span>
                        <span style="background:rgba(255,255,255,0.08); color:#a7f3d0; font-size:0.75rem; font-weight:600; padding:3px 8px; border-radius:6px; border:1px solid rgba(255,255,255,0.12);">✨ Koleksi Lengkap</span>
                        <span style="background:rgba(255,255,255,0.08); color:#fde68a; font-size:0.75rem; font-weight:600; padding:3px 8px; border-radius:6px; border:1px solid rgba(255,255,255,0.12);">⚡ Pengiriman Cepat</span>
                    </div>
                </div>

                <!-- KOLOM 2: NAVIGASI CEPAT -->
                <div class="footer-col">
                    <h4 class="footer-col-title" style="color:white; font-size:1rem; font-weight:700; margin-bottom:16px;">Navigasi</h4>
                    <ul class="footer-links" style="display:flex; flex-direction:column; gap:10px; font-size:0.875rem;">
                        <li><a href="/" style="color:rgba(255,255,255,0.75); text-decoration:none;">Beranda</a></li>
                        <li><a href="/catalog" style="color:rgba(255,255,255,0.75); text-decoration:none;">Katalog Buku Lengkap</a></li>
                        <li><a href="/#alur-pembelian" style="color:rgba(255,255,255,0.75); text-decoration:none;">Alur Pembelian</a></li>
                        <li><a href="/#faq" style="color:rgba(255,255,255,0.75); text-decoration:none;">FAQ &amp; Bantuan</a></li>
                        <li><a href="/about" style="color:rgba(255,255,255,0.75); text-decoration:none;">About Us (Tentang Kami)</a></li>
                    </ul>
                </div>

                <!-- KOLOM 3: LAYANAN & AKUN -->
                <div class="footer-col">
                    <h4 class="footer-col-title" style="color:white; font-size:1rem; font-weight:700; margin-bottom:16px;">Layanan &amp; Akun</h4>
                    <ul class="footer-links" style="display:flex; flex-direction:column; gap:10px; font-size:0.875rem;">
                        @auth
                            <li><a href="{{ auth()->user()->role === 'admin' ? '/admin/dashboard' : '/user/dashboard' }}" style="color:rgba(255,255,255,0.75); text-decoration:none;">Dashboard Akun</a></li>
                            @if(auth()->user()->role === 'user')
                                <li><a href="/user/cart" style="color:rgba(255,255,255,0.75); text-decoration:none;">Keranjang Belanja</a></li>
                                <li><a href="/user/transactions" style="color:rgba(255,255,255,0.75); text-decoration:none;">Riwayat Pesanan Buku</a></li>
                                <li><a href="/user/chat" style="color:rgba(255,255,255,0.75); text-decoration:none;">Tanya Admin (Live Chat)</a></li>
                            @endif
                        @else
                            <li><a href="/login" style="color:rgba(255,255,255,0.75); text-decoration:none;">Masuk ke Akun</a></li>
                            <li><a href="/register" style="color:rgba(255,255,255,0.75); text-decoration:none;">Daftar Akun Baru (Gratis)</a></li>
                        @endauth
                    </ul>
                </div>

                <!-- KOLOM 4: KONTAK & INFORMASI DEVELOPER -->
                <div class="footer-col" style="background:rgba(255,255,255,0.04); padding:20px; border-radius:12px; border:1px solid rgba(255,255,255,0.08);">
                    <h4 class="footer-col-title" style="color:#60a5fa; font-size:0.98rem; font-weight:700; margin-bottom:12px;">💬 Hubungi Developer</h4>
                    <p style="font-size:0.825rem; color:rgba(255,255,255,0.72); margin-bottom:14px; line-height:1.55;">
                        Ada pertanyaan seputar sistem, saran perbaikan, atau kolaborasi perpustakaan? Hubungi kami langsung:
                    </p>
                    <ul class="footer-links" style="display:flex; flex-direction:column; gap:10px; font-size:0.85rem;">
                        <li>
                            <a href="https://wa.me/6281547184307" target="_blank" style="display:flex; align-items:center; gap:8px; color:rgba(255,255,255,0.85); text-decoration:none;">
                                <span>📱 WhatsApp:</span> <strong style="color:white;">081547184307</strong>
                            </a>
                        </li>
                        <li>
                            <a href="https://instagram.com/eddlyaa__" target="_blank" style="display:flex; align-items:center; gap:8px; color:rgba(255,255,255,0.85); text-decoration:none;">
                                <span>📸 Instagram:</span> <strong style="color:white;">@eddlyaa__</strong>
                            </a>
                        </li>
                        <li>
                            <a href="https://github.com/zegow8" target="_blank" style="display:flex; align-items:center; gap:8px; color:rgba(255,255,255,0.85); text-decoration:none;">
                                <span>💻 GitHub:</span> <strong style="color:white;">@zegow8</strong>
                            </a>
                        </li>
                    </ul>
                    <div style="margin-top:14px; padding-top:12px; border-top:1px solid rgba(255,255,255,0.08); font-size:0.78rem; color:rgba(255,255,255,0.6);">
                        🕒 <strong>Operasional:</strong> Senin – Sabtu (08.00 – 20.00 WIB)
                    </div>
                </div>
            </div>

            <!-- FOOTER BOTTOM -->
            <div class="footer-bottom" style="margin-top:40px; padding-top:20px; border-top:1px solid rgba(255,255,255,0.1); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; font-size:0.85rem; color:rgba(255,255,255,0.65);">
                <span>&copy; {{ date('Y') }} <strong>PustakaMart</strong>. All rights reserved.</span>
                <span>Dibangun dengan Laravel 12 &amp; MySQL. Developer: <strong>@eddlyaa__ / @zegow8</strong></span>
            </div>
        </div>
    </footer>
    <script src="{{ asset('assets/js/script.js') }}"></script>
</body>
</html>