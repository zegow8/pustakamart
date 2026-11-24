<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin PustakaMart</title>
    <link rel="icon" type="image/png" href="{{ asset('images/pustakamart.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <style>
        /* Layout Dasar Admin */
        html, body { max-width: 100vw; overflow-x: hidden; }
        .admin-layout { display: flex; min-height: 100vh; background: #f8fafc; max-width: 100vw; overflow-x: hidden; }
        
        /* Sidebar */
        .sidebar { width: 260px; background: white; border-right: 1px solid #e2e8f0; flex-shrink: 0; display: flex; flex-direction: column; }
        .sidebar-brand { padding: 24px; font-weight: 800; font-size: 1.15rem; color: var(--primary); border-bottom: 1px solid #e2e8f0; letter-spacing: 0.05em; }
        
        .sidebar-menu { list-style: none; padding: 0; margin: 0; flex: 1; overflow-y: auto; }
        .sidebar-menu a { display: block; padding: 11px 24px; color: #475569; font-weight: 600; font-size: 0.9rem; text-decoration: none; border-left: 4px solid transparent; transition: all 0.2s; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: #eff6ff; color: var(--primary); border-left-color: var(--primary); }
        
        /* Pembagi Menu */
        .menu-header { padding: 18px 24px 6px; font-size: 0.7rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.08em; }
        
        /* Konten Utama */
        .main-content { flex: 1; display: flex; flex-direction: column; min-width: 0; }
        .topbar { background: white; padding: 16px 32px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; }
        .content-wrapper { padding: 32px; flex: 1; overflow-y: auto; }
        
        /* Responsive */
        @media (max-width: 768px) {
            .admin-layout { flex-direction: column; }
            .sidebar { width: 100%; border-right: none; border-bottom: 1px solid #e2e8f0; }
            .sidebar-brand { padding: 12px 16px; font-size: 1rem; }
            .sidebar-brand img { height: 26px !important; width: 26px !important; }
            .sidebar-menu { display: flex; overflow-x: auto; white-space: nowrap; -webkit-overflow-scrolling: touch; padding: 2px 8px; gap: 4px; }
            .sidebar-menu a { border-left: none; border-bottom: 2.5px solid transparent; padding: 8px 10px; font-size: 0.78rem; border-radius: 6px; }
            .sidebar-menu a.active { border-left: none; border-bottom-color: var(--primary); background: #FFF0F0; }
            .menu-header { display: none; }
            .topbar { padding: 10px 14px; font-size: 0.85rem; }
            .content-wrapper { padding: 14px 10px; }
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar Admin -->
        <div class="sidebar">
            <div class="sidebar-brand" style="display:flex; align-items:center; gap:10px;">
                <img src="{{ asset('images/pustakamart.png') }}" alt="PustakaMart" style="height:30px; width:30px; object-fit:contain;">
                <span>PUSTAKAMART ADMIN</span>
            </div>
            <ul class="sidebar-menu">
                <li class="menu-header">Utama</li>
                <li><a href="/admin/dashboard" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">Dashboard</a></li>

                <li class="menu-header">Manajemen Buku</li>
                <li><a href="/admin/books" class="{{ request()->is('admin/books*') ? 'active' : '' }}">Data Buku</a></li>
                <li><a href="/admin/categories" class="{{ request()->is('admin/categories*') ? 'active' : '' }}">Kategori Buku</a></li>

                <li class="menu-header">Pesanan & Transaksi</li>
                <li><a href="/admin/orders" class="{{ request()->is('admin/orders*') ? 'active' : '' }}">List Pesanan Buku</a></li>

                <li class="menu-header">Layanan Pelanggan</li>
                <li>
                    <a href="/admin/chats" class="{{ request()->is('admin/chats*') ? 'active' : '' }}" style="display:flex; justify-content:space-between; align-items:center;">
                        <span>💬 LiveChat User</span>
                        @php
                            $unrepliedChatCount = \App\Models\Chat::where('sender', 'user')->where('is_read', 0)->count();
                        @endphp
                        @if($unrepliedChatCount > 0)
                            <span style="background:#ef4444; color:white; font-size:0.65rem; padding:2px 7px; border-radius:10px; font-weight:800;">{{ $unrepliedChatCount }}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="/admin/contacts" class="{{ request()->is('admin/contacts*') ? 'active' : '' }}" style="display:flex; justify-content:space-between; align-items:center;">
                        <span>📬 Pesan Kontak Tamu</span>
                        @php
                            $unreadContactCount = \App\Models\Contact::where('is_read', 0)->count();
                        @endphp
                        @if($unreadContactCount > 0)
                            <span style="background:#800000; color:white; font-size:0.65rem; padding:2px 7px; border-radius:10px; font-weight:800;">{{ $unreadContactCount }}</span>
                        @endif
                    </a>
                </li>

                <li class="menu-header">Sistem</li>
                <li><a href="/admin/users" class="{{ request()->is('admin/users*') ? 'active' : '' }}">List User Terdaftar</a></li>
                <li><a href="/admin/profile" class="{{ request()->is('admin/profile*') ? 'active' : '' }}">Profil Admin</a></li>
                
                <li style="padding: 24px;">
                    <form action="/logout" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin keluar dari sistem Admin PustakaMart?');">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: #ef4444; font-weight: 700; cursor: pointer; font-size: 0.9rem; font-family: inherit;">Logout</button>
                    </form>
                </li>
            </ul>
        </div>

        <!-- Konten Utama -->
        <div class="main-content">
            <div class="topbar">
                <strong style="color: var(--text-main);">Halo, {{ auth()->user()->name }}</strong>
                <a href="/" target="_blank" class="btn btn-outline btn-sm">Lihat Website</a>
            </div>
            
            <div class="content-wrapper">
                @if(session('success'))
                    <div class="alert alert-success">✅ {{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">⚠️ {{ session('error') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger" style="display:block;">
                        <strong>⚠️ Terjadi Kesalahan:</strong>
                        <ul style="margin:4px 0 0 20px;">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>
    
    <script src="{{ asset('assets/js/script.js') }}"></script>
</body>
</html>
