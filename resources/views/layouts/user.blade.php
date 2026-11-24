<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - User Area - PustakaMart</title>
    <link rel="icon" type="image/png" href="{{ asset('images/pustakamart.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <style>
        /* Layout User Area */
        *, *::before, *::after { box-sizing: border-box; }
        html, body { width: 100%; max-width: 100vw; overflow-x: hidden; margin: 0; padding: 0; }
        body { display: flex; flex-direction: column; min-height: 100vh; background-color: var(--bg-main); min-width: 0; }
        
        /* NAVBAR DIUBAH JADI TERANG */
        .navbar-user { background: var(--white); border-bottom: 2px solid var(--primary); padding: 0; box-shadow: 0 1px 3px rgba(0,0,0,0.05); width: 100%; max-width: 100%; min-width: 0; }
        .navbar-user .container { width: 100%; max-width: 1200px; min-width: 0; }
        .navbar-user-top { display: flex; justify-content: space-between; align-items: center; padding: 14px 0; width: 100%; min-width: 0; }
        
        .navbar-user .brand { font-size: 1.25rem; font-weight: 800; color: var(--primary); text-decoration: none; display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
        .navbar-user .brand img { height: 36px; width: auto; }
        
        .navbar-user-icons { display: flex; gap: 20px; align-items: center; min-width: 0; }
        .navbar-user-icons a { color: var(--text-main); font-size: 0.9rem; font-weight: 600; text-decoration: none; transition: color 0.2s; white-space: nowrap; }
        .navbar-user-icons a:hover, .navbar-user-icons a.active { color: var(--primary); }
        .navbar-user-icons .logout-link { color: var(--danger) !important; }
        
        .navbar-user-bottom { border-top: 1px solid var(--border); display: flex; gap: 24px; padding: 0; overflow-x: auto; white-space: nowrap; width: 100%; max-width: 100%; min-width: 0; -webkit-overflow-scrolling: touch; }
        .navbar-user-bottom a { color: var(--text-muted); font-weight: 600; font-size: 0.9rem; text-decoration: none; padding: 14px 0; border-bottom: 3px solid transparent; transition: all 0.2s; flex-shrink: 0; }
        .navbar-user-bottom a:hover { color: var(--primary); background: var(--bg-main); }
        .navbar-user-bottom a.active { color: var(--primary); border-bottom-color: var(--primary); }
        
        .badge-notif { background: var(--danger); color: white; font-size: 0.65rem; padding: 2px 6px; border-radius: 10px; font-weight: 700; margin-left: 4px; vertical-align: middle; }
        
        .page-wrap { max-width: 1200px; width: 100%; margin: 0 auto; padding: 24px 16px; flex: 1; min-width: 0; }
        @media (max-width: 768px) {
            .navbar-user-top { flex-direction: row; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px; padding: 8px 0; }
            .navbar-user .brand img { height: 28px !important; width: 28px !important; }
            .navbar-user .brand span { font-size: 1.15rem !important; }
            .navbar-user-icons { width: 100%; justify-content: flex-start; gap: 12px; font-size: 0.78rem; overflow-x: auto; white-space: nowrap; -webkit-overflow-scrolling: touch; padding: 4px 0; }
            .navbar-user-icons a { font-size: 0.78rem; }
            .navbar-user-icons button { font-size: 0.78rem !important; }
            .navbar-user-bottom { gap: 14px; overflow-x: auto; white-space: nowrap; -webkit-overflow-scrolling: touch; padding: 2px 0 6px; }
            .navbar-user-bottom a { font-size: 0.8rem; padding: 8px 2px; }
            .page-wrap { padding: 12px 8px !important; width: 100% !important; max-width: 100vw !important; min-width: 0 !important; }
        }
    </style>
</head>
<body>

    <nav class="navbar-user">
        <div class="container">
            <!-- Baris Atas -->
            <div class="navbar-user-top">
                <a href="/user/dashboard" class="brand" style="display:inline-flex; align-items:center; gap:9px; text-decoration:none;">
                    <img src="{{ asset('images/pustakamart.png') }}" alt="PustakaMart" style="height:34px; width:34px; object-fit:contain;">
                    <span style="font-size:1.4rem; font-weight:800; color:var(--primary); letter-spacing:-0.5px;">PUSTAKAMART</span>
                </a>
                <div class="navbar-user-icons">
                    <a href="/user/catalog" class="{{ request()->is('user/catalog*') ? 'active' : '' }}">📚 Katalog Buku</a>
                    @php
                        $userCartCount = \App\Models\CartDetail::whereHas('cart', function($q) {
                            $q->where('user_id', auth()->id());
                        })->count();
                    @endphp
                    <a href="/user/cart" class="{{ request()->is('user/cart*') ? 'active' : '' }}" style="display:inline-flex; align-items:center; gap:6px;">
                        <span>🛒 Keranjang</span>
                        @if($userCartCount > 0)
                            <span class="badge-cart" style="background:var(--primary); color:white; font-size:0.72rem; font-weight:800; padding:2px 7px; border-radius:12px; line-height:1.2; box-shadow:0 2px 4px rgba(128,0,0,0.25);">
                                {{ $userCartCount }}
                            </span>
                        @endif
                    </a>
                    <a href="/user/profile" class="{{ request()->is('user/profile*') ? 'active' : '' }}">👤 {{ auth()->user()->name }}</a>
                    <form action="/logout" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin keluar dari akun PustakaMart?');">
                        @csrf
                        <button type="submit" class="logout-link" style="background:none;border:none;cursor:pointer;font-weight:600;font-size:0.9rem;font-family:inherit;">Logout</button>
                    </form>
                </div>
            </div>
            <!-- Baris Bawah (Tab) -->
            <div class="navbar-user-bottom">
                <a href="/user/dashboard" class="{{ request()->is('user/dashboard') ? 'active' : '' }}">Beranda</a>
                <a href="/user/catalog" class="{{ request()->is('user/catalog*') ? 'active' : '' }}">Katalog Buku</a>
                <a href="/user/transactions" class="{{ request()->is('user/transactions*') ? 'active' : '' }}">Riwayat Pembelian</a>
                <a href="/user/chat" class="{{ request()->is('user/chat*') ? 'active' : '' }}">
                    💬 Contact to Admin
                    @php
                        $unreadChatCount = \App\Models\Chat::where('user_id', auth()->id())->where('sender', 'admin')->where('is_read', 0)->count();
                    @endphp
                    @if($unreadChatCount > 0)
                        <span class="badge-notif" style="background:#2563eb;">{{ $unreadChatCount }}</span>
                    @endif
                </a>
                <a href="/user/profile" class="{{ request()->is('user/profile') ? 'active' : '' }}">Pengaturan Profil</a>
            </div>
        </div>
    </nav>

    <div class="page-wrap">
        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">⚠️ {{ session('error') }}</div>
        @endif

        @yield('content')
    </div>

    <!-- ============ FOOTER USER AREA (INFO DEVELOPER) ============ -->
    <footer style="background:#0F172A; color:white; border-top:1px solid #1e293b; padding:32px 0 20px; margin-top:auto;">
        <div class="container">
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap:24px; padding-bottom:24px; border-bottom:1px solid rgba(255,255,255,0.1);">
                <div>
                    <div style="font-weight:800; font-size:1.1rem; color:white; margin-bottom:8px; display:flex; align-items:center; gap:8px;">
                        <img src="{{ asset('images/pustakamart.png') }}" alt="PustakaMart" style="height:28px; width:28px; object-fit:contain; background:white; border-radius:6px; padding:2px;">
                        <span>PUSTAKAMART</span>
                    </div>
                    <p style="font-size:0.85rem; color:rgba(255,255,255,0.6); line-height:1.5; margin:0;">
                        Toko buku online terpercaya untuk mencari dan membeli koleksi buku terbaik.
                    </p>
                </div>
                <div>
                    <div style="font-weight:700; font-size:0.85rem; text-transform:uppercase; color:#94a3b8; margin-bottom:8px;">Bantuan & Informasi</div>
                    <ul style="list-style:none; padding:0; margin:0; font-size:0.85rem; color:rgba(255,255,255,0.7); display:flex; flex-direction:column; gap:6px;">
                        <li><a href="/about" style="color:#60a5fa; text-decoration:none; font-weight:600;">ℹ️ Tentang Kami (About Us)</a></li>
                        <li>🚚 Pengiriman ke seluruh Indonesia</li>
                        <li>💳 Pembayaran Transfer Bank Terverifikasi</li>
                        <li>📦 Pengemasan rapi dan aman</li>
                    </ul>
                </div>
                <!-- Developer Info -->
                <div style="background:rgba(255,255,255,0.05); padding:14px 18px; border-radius:8px; border:1px solid rgba(255,255,255,0.1);">
                    <div style="font-weight:700; font-size:0.85rem; text-transform:uppercase; color:#60a5fa; margin-bottom:6px;">
                        💬 Hubungi Developer
                    </div>
                    <div style="font-size:0.8rem; color:rgba(255,255,255,0.7); margin-bottom:8px;">
                        Ada masalah atau saran fitur tambahan?
                    </div>
                    <div style="display:flex; flex-direction:column; gap:6px; font-size:0.825rem;">
                        <a href="https://wa.me/6281547184307" target="_blank" style="color:#d1d5db; text-decoration:none;">
                            📱 WhatsApp: <strong style="color:white;">081547184307</strong>
                        </a>
                        <a href="https://instagram.com/eddlyaa__" target="_blank" style="color:#d1d5db; text-decoration:none;">
                            📸 Instagram: <strong style="color:white;">@eddlyaa__</strong>
                        </a>
                        <a href="https://github.com/zegow8" target="_blank" style="color:#d1d5db; text-decoration:none;">
                            💻 GitHub: <strong style="color:white;">@zegow8</strong>
                        </a>
                    </div>
                </div>
            </div>
            <div style="padding-top:16px; font-size:0.75rem; color:rgba(255,255,255,0.4); text-align:center;">
                &copy; {{ date('Y') }} PustakaMart. All rights reserved.
            </div>
        </div>
    </footer>

    <script src="{{ asset('assets/js/script.js') }}"></script>
</body>
</html>