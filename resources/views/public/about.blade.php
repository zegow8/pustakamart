@extends(auth()->check() ? (auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user') : 'layouts.app')

@section('title', 'About Us - Tentang PustakaMart')

@section('content')
<div class="about-hero">
    <h1>Tentang PustakaMart (About Us)</h1>
    <p>Toko buku online independen terpercaya untuk semua pembaca di Indonesia.</p>
</div>

<div style="max-width: 860px; margin: 0 auto;">

    <div class="panel" style="margin-bottom:24px;">
        <h2 style="font-size:1.3rem;margin-bottom:12px;">Siapa Kami?</h2>
        <p style="line-height:1.6; color:var(--text-main); font-size:1rem;">
            <strong>PustakaMart</strong> adalah platform toko buku online yang hadir untuk mempermudah masyarakat dalam menemukan, memesan, dan membeli berbagai macam buku berkualitas. Mulai dari karya fiksi, sastra, non-fiksi, komik, hingga buku referensi akademik, kami berkomitmen menyajikan koleksi terlengkap dengan harga yang ramah dan terjangkau.
        </p>
    </div>

    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap:20px; margin-bottom:24px;">
        <div class="panel" style="margin-bottom:0;border-top:4px solid var(--primary);">
            <div style="font-size:2rem;margin-bottom:12px;">📚</div>
            <h3 style="font-size:1.1rem;margin-bottom:8px;">Koleksi Buku Lengkap</h3>
            <p style="font-size:0.95rem;color:var(--text-muted);margin:0;">Menyediakan ribuan judul buku pilihan dari penerbit dan penulis terkemuka nasional maupun internasional.</p>
        </div>
        <div class="panel" style="margin-bottom:0;border-top:4px solid var(--success);">
            <div style="font-size:2rem;margin-bottom:12px;">🚚</div>
            <h3 style="font-size:1.1rem;margin-bottom:8px;">Pengiriman Cepat & Aman</h3>
            <p style="font-size:0.95rem;color:var(--text-muted);margin:0;">Buku dikemas dengan rapi dan aman menggunakan bubble wrap tebal, siap dikirim ke seluruh pelosok Nusantara.</p>
        </div>
        <div class="panel" style="margin-bottom:0;border-top:4px solid #f59e0b;">
            <div style="font-size:2rem;margin-bottom:12px;">💳</div>
            <h3 style="font-size:1.1rem;margin-bottom:8px;">Pembayaran Mudah</h3>
            <p style="font-size:0.95rem;color:var(--text-muted);margin:0;">Mendukung transfer bank dengan verifikasi cepat sebelum buku dikirimkan ke alamat Anda.</p>
        </div>
    </div>

    <div class="panel" style="margin-bottom:24px;background:#f8fafc;">
        <h2 style="font-size:1.3rem;margin-bottom:20px;">💡 Kenapa Memilih PustakaMart?</h2>
        <div style="display:grid;gap:16px;">
            <div style="display:flex;gap:16px;align-items:flex-start;">
                <div style="width:36px;height:36px;background:var(--primary);color:white;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:800;flex-shrink:0;">1</div>
                <div>
                    <strong>Buku 100% Original</strong>
                    <p style="margin:4px 0 0;font-size:0.9rem;color:var(--text-muted);">Kami hanya menyediakan buku-buku asli ber-ISBN resmi langsung dari penerbit tepercaya.</p>
                </div>
            </div>
            <div style="display:flex;gap:16px;align-items:flex-start;">
                <div style="width:36px;height:36px;background:var(--primary);color:white;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:800;flex-shrink:0;">2</div>
                <div>
                    <strong>Layanan Pelanggan Responsif (Contact Admin)</strong>
                    <p style="margin:4px 0 0;font-size:0.9rem;color:var(--text-muted);">Tim kami siap melayani pertanyaan seputar buku dan status pesanan melalui fitur Contact to Admin.</p>
                </div>
            </div>
            <div style="display:flex;gap:16px;align-items:flex-start;">
                <div style="width:36px;height:36px;background:var(--primary);color:white;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:800;flex-shrink:0;">3</div>
                <div>
                    <strong>Proses Pemesanan Praktis</strong>
                    <p style="margin:4px 0 0;font-size:0.9rem;color:var(--text-muted);">Cukup pilih buku, tambahkan ke keranjang, dan lakukan pembayaran dengan mudah.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="panel" style="text-align:center;background:linear-gradient(135deg,var(--primary),var(--primary-dark));color:white;border:none;">
        <h2 style="color:white;margin-bottom:12px;">Temukan Buku Impianmu Sekarang</h2>
        <p style="color:rgba(255,255,255,0.85);margin-bottom:24px;">Daftar akun gratis dan nikmati pengalaman berbelanja buku yang menyenangkan di PustakaMart.</p>
        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
            @guest
                <a href="/register" class="btn" style="background:white;color:var(--primary);font-weight:700;">Daftar Sekarang</a>
                <a href="/catalog" class="btn" style="background:transparent;color:white;border-color:rgba(255,255,255,0.5);">Lihat Katalog Buku</a>
            @else
                <a href="/user/catalog" class="btn" style="background:white;color:var(--primary);font-weight:700;">Jelajahi Katalog Buku</a>
            @endguest
        </div>
    </div>

</div>
@endsection
