@extends('layouts.app')

@section('title', 'Toko Buku Online Modern - PustakaMart')

@section('content')
<!-- HERO SECTION -->
<section class="hero" style="background: linear-gradient(135deg, #FFF0F0 0%, #FFD4D4 100%); border: 1px solid #F7C5C5; border-radius: 24px; padding: 48px; margin-bottom: 48px; box-shadow: 0 10px 30px rgba(128,0,0,0.04);">
    <div class="hero-content">
        <div class="hero-eyebrow" style="background: white; color: #800000; border: 1px solid #F0B2B2; box-shadow: 0 2px 8px rgba(128,0,0,0.06); font-weight: 700; letter-spacing: 0.04em;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block; vertical-align:middle; margin-right:4px;"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
            Toko Buku Fisik Original &amp; Terpercaya
        </div>
        <h1 class="hero-title" style="color: #4A0000; font-size: 2.75rem; letter-spacing: -0.03em; line-height: 1.15; margin: 16px 0;">
            Temukan Buku Impianmu<br><span style="color: #800000;">Hanya di PustakaMart.</span>
        </h1>
        <p class="hero-desc" style="color: #634040; font-size: 1.05rem; line-height: 1.6; max-width: 540px;">
            Dapatkan ribuan buku fisik original dari penulis dan penerbit terkemuka nusantara. Belanja mudah, aman, harga bersahabat, dikirim langsung ke depan pintu Anda.
        </p>
        <div class="hero-actions" style="margin-top: 28px; display: flex; gap: 14px; flex-wrap: wrap;">
            <a href="/catalog" class="btn btn-primary btn-lg" style="background: #800000; border-color: #800000; box-shadow: 0 4px 14px rgba(128,0,0,0.25); font-weight: 700; padding: 12px 28px; border-radius: 12px;">
                Jelajahi Katalog Buku &rarr;
            </a>
            @guest
                <a href="/register" class="btn btn-outline btn-lg" style="color: #800000; border-color: #800000; background: white; font-weight: 700; padding: 12px 24px; border-radius: 12px;">
                    Daftar Akun Baru
                </a>
            @else
                <a href="/user/dashboard" class="btn btn-outline btn-lg" style="color: #800000; border-color: #800000; background: white; font-weight: 700; padding: 12px 24px; border-radius: 12px;">
                    Buka Dashboard Saya
                </a>
            @endguest
        </div>
        <div class="hero-features" style="margin-top: 32px; display: flex; gap: 18px; flex-wrap: wrap;">
            <div class="hero-feature-item" style="color: #4A0000; font-size: 0.85rem; font-weight: 600; display: flex; align-items: center; gap: 6px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#800000" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                100% Buku Fisik Original
            </div>
            <div class="hero-feature-item" style="color: #4A0000; font-size: 0.85rem; font-weight: 600; display: flex; align-items: center; gap: 6px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#800000" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                Koleksi Lengkap &amp; Terkini
            </div>
            <div class="hero-feature-item" style="color: #4A0000; font-size: 0.85rem; font-weight: 600; display: flex; align-items: center; gap: 6px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#800000" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                Ekspedisi Cepat &amp; Aman
            </div>
        </div>
    </div>
    <div class="hero-image-wrapper">
        <img src="{{ asset('assets/images/perpusrain.jpg') }}" alt="PustakaMart Toko Buku" 
             style="border-radius: 18px; box-shadow: 0 16px 36px rgba(128,0,0,0.14); border: 4px solid white; object-fit: cover; max-height: 420px; width: 100%;"
             onerror="this.src='https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=500&q=80'">
    </div>
</section>

<!-- STATISTIK UTAMA -->
<section class="stats-section" style="background: white; border: 1px solid #F5D0D0; border-radius: 18px; padding: 28px; margin-bottom: 48px; box-shadow: 0 6px 20px rgba(128,0,0,0.03);">
    <div class="stats-grid">
        <div class="stat-item">
            <div class="stat-number" style="color: #800000;">{{ number_format($totalBooks) }}+</div>
            <div class="stat-label">Koleksi Judul Buku</div>
        </div>
        <div class="stat-divider" style="background: #F7D2D2;"></div>
        <div class="stat-item">
            <div class="stat-number" style="color: #800000;">{{ number_format($totalUsers) }}+</div>
            <div class="stat-label">Pelanggan Terdaftar</div>
        </div>
        <div class="stat-divider" style="background: #F7D2D2;"></div>
        <div class="stat-item">
            <div class="stat-number" style="color: #800000;">{{ number_format($totalCategories) }}+</div>
            <div class="stat-label">Kategori Pilihan</div>
        </div>
        <div class="stat-divider" style="background: #F7D2D2;"></div>
        <div class="stat-item">
            <div class="stat-number" style="color: #800000;">100%</div>
            <div class="stat-label">Jaminan Buku Asli</div>
        </div>
    </div>
</section>

<!-- BUKU TERBARU & PILIHAN POPULER -->
<section class="landing-section" style="margin-bottom: 56px;">
    <div class="flex-between mb-4" style="align-items:flex-end;">
        <div>
            <div style="font-size:0.75rem; font-weight:800; color:#800000; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:4px; display:flex; align-items:center; gap:6px;">
                <span style="display:inline-block; width:6px; height:6px; border-radius:50%; background:#800000;"></span>
                Rekomendasi Terkini
            </div>
            <h2 style="margin:0; font-size:1.75rem; font-weight:800; color:#3B0B0B;">Koleksi Buku Terbaru</h2>
            <p class="text-muted" style="font-size:0.9rem; margin:4px 0 0;">Temukan judul-judul pilihan yang baru saja tiba di rak PustakaMart</p>
        </div>
        <a href="/catalog" style="font-weight:700; font-size:0.9rem; white-space:nowrap; color:#800000; text-decoration:none;">Lihat Semua Koleksi &rarr;</a>
    </div>

    <div class="book-grid">
        @foreach ($latestBooks as $b)
            <div class="book-card" style="background: white; border: 1px solid #F5D5D5; border-radius: 16px; padding: 14px; transition: transform 0.2s, box-shadow 0.2s; box-shadow: 0 4px 14px rgba(128,0,0,0.03);">
                <!-- Cover Poster Penuh (Tanpa Ruang Abu-Abu di Bawah) -->
                <a href="/book/{{ $b->id }}" class="book-cover" style="width:100%; aspect-ratio:2/3; border-radius:10px; overflow:hidden; position:relative; background:#FFF0F0; box-shadow:-2px 0 6px rgba(0,0,0,0.06), 2px 4px 10px rgba(0,0,0,0.08); display:block; margin-bottom:12px;">
                    @if ($b->cover)
                        <img src="{{ asset('uploads/covers/' . $b->cover) }}" alt="{{ $b->title }}" style="width:100%; height:100%; object-fit:cover; display:block;" onerror="this.src='https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=500&auto=format&fit=crop&q=80'">
                    @else
                        <div class="book-cover-placeholder" style="height:100%; width:100%; display:flex; align-items:center; justify-content:center; background:#FFF0F0; color:#800000; font-weight:700; text-align:center; padding:12px;">{{ $b->title }}</div>
                    @endif
                    @if ($b->stock_sale <= 0)
                        <span style="position:absolute; top:8px; right:8px; background:#C5221F; color:white; font-size:0.65rem; font-weight:800; padding:3px 8px; border-radius:4px; text-transform:uppercase;">Habis</span>
                    @endif
                </a>
                @if ($b->category)
                    <div style="margin-bottom: 6px;">
                        <span class="book-badge" style="background: #FFD4D4; color: #800000; font-size: 0.7rem; font-weight: 700; padding: 2px 8px; border-radius: 4px;">{{ $b->category->name }}</span>
                    </div>
                @endif
                <a href="/book/{{ $b->id }}" class="book-title" style="color: #2D0A0A; font-weight: 700; font-size: 0.95rem; text-decoration: none; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 2.4em; margin-bottom: 4px;">{{ $b->title }}</a>
                <div class="book-author" style="color: #8C6A6A; font-size: 0.8rem; margin-bottom: 8px;">{{ $b->author }}</div>
                <div class="book-price" style="color: #800000; font-weight: 800; font-size: 1.05rem;">Rp {{ number_format($b->price, 0, ',', '.') }}</div>
            </div>
        @endforeach
    </div>
</section>

<!-- ALUR LENGKAP PEMBELIAN BUKU (MASTER CRAFTSMANSHIP) -->
<section class="landing-section-alt" id="alur-pembelian" style="background: white; border: 1px solid #F5D0D0; border-radius: 24px; padding: 48px 36px; margin-bottom: 48px; box-shadow: 0 8px 30px rgba(128,0,0,0.03);">
    <div class="text-center" style="max-width:680px; margin:0 auto 40px;">
        <span style="background:#FFD4D4; color:#800000; font-size:0.75rem; font-weight:800; padding:4px 14px; border-radius:999px; text-transform:uppercase; letter-spacing:0.06em; display:inline-block; margin-bottom:10px;">
            Alur Pemesanan
        </span>
        <h2 style="margin:0 0 10px; font-size:1.95rem; font-weight:800; color:#3B0B0B;">Cara Belanja Mudah di PustakaMart</h2>
        <p class="text-muted" style="margin:0; font-size:0.95rem;">5 langkah transparan dan terstandarisasi mulai dari pemilihan buku hingga paket sampai ke rumah Anda</p>
    </div>

    <div class="flow-card-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 18px;">
        <!-- Langkah 1 -->
        <div class="flow-step-card" style="background: #FFF8F8; border: 1px solid #F5D5D5; border-radius: 16px; padding: 24px 20px; position: relative;">
            <div style="width: 32px; height: 32px; border-radius: 50%; background: #800000; color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem; margin-bottom: 16px;">
                01
            </div>
            <h3 style="font-size:1rem; font-weight:700; color:#3B0B0B; margin-bottom:8px;">Pilih Buku</h3>
            <p class="text-muted" style="font-size:0.85rem; margin:0; line-height:1.5;">
                Eksplorasi ribuan judul buku original di katalog dan klik <strong>Tambah ke Keranjang</strong>.
            </p>
        </div>

        <!-- Langkah 2 -->
        <div class="flow-step-card" style="background: #FFF8F8; border: 1px solid #F5D5D5; border-radius: 16px; padding: 24px 20px; position: relative;">
            <div style="width: 32px; height: 32px; border-radius: 50%; background: #800000; color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem; margin-bottom: 16px;">
                02
            </div>
            <h3 style="font-size:1rem; font-weight:700; color:#3B0B0B; margin-bottom:8px;">Checkout &amp; Alamat</h3>
            <p class="text-muted" style="font-size:0.85rem; margin:0; line-height:1.5;">
                Isi alamat tujuan pengiriman lengkap beserta catatan instruksi kurir ekspedisi.
            </p>
        </div>

        <!-- Langkah 3 -->
        <div class="flow-step-card" style="background: #FFF8F8; border: 1px solid #F5D5D5; border-radius: 16px; padding: 24px 20px; position: relative;">
            <div style="width: 32px; height: 32px; border-radius: 50%; background: #800000; color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem; margin-bottom: 16px;">
                03
            </div>
            <h3 style="font-size:1rem; font-weight:700; color:#3B0B0B; margin-bottom:8px;">Transfer Bank</h3>
            <p class="text-muted" style="font-size:0.85rem; margin:0; line-height:1.5;">
                Lakukan transfer ke rekening resmi (BCA, BRI, Mandiri) dan unggah foto bukti bayar.
            </p>
        </div>

        <!-- Langkah 4 -->
        <div class="flow-step-card" style="background: #FFF8F8; border: 1px solid #F5D5D5; border-radius: 16px; padding: 24px 20px; position: relative;">
            <div style="width: 32px; height: 32px; border-radius: 50%; background: #800000; color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem; margin-bottom: 16px;">
                04
            </div>
            <h3 style="font-size:1rem; font-weight:700; color:#3B0B0B; margin-bottom:8px;">Verifikasi &amp; Packing</h3>
            <p class="text-muted" style="font-size:0.85rem; margin:0; line-height:1.5;">
                Admin memverifikasi pembayaran dan buku dikemas ekstra aman berlapis bubble wrap tebal.
            </p>
        </div>

        <!-- Langkah 5 -->
        <div class="flow-step-card" style="background: #FFF8F8; border: 1px solid #F5D5D5; border-radius: 16px; padding: 24px 20px; position: relative;">
            <div style="width: 32px; height: 32px; border-radius: 50%; background: #800000; color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem; margin-bottom: 16px;">
                05
            </div>
            <h3 style="font-size:1rem; font-weight:700; color:#3B0B0B; margin-bottom:8px;">Pengiriman Paket</h3>
            <p class="text-muted" style="font-size:0.85rem; margin:0; line-height:1.5;">
                Kurir mengantarkan buku fisik original ke rumah Anda dalam kondisi mulus dan siap dibaca.
            </p>
        </div>
    </div>
</section>

<!-- KEUNGGULAN PUSTAKAMART: 3-KOLOM COMPACT GRID (TIDAK MEMANJANG VERTIKAL) -->
<section class="landing-section" style="margin-bottom: 56px;">
    <div class="text-center" style="max-width:680px; margin:0 auto 36px;">
        <span style="background:#FFD4D4; color:#800000; font-size:0.75rem; font-weight:800; padding:4px 14px; border-radius:999px; text-transform:uppercase; letter-spacing:0.06em; display:inline-block; margin-bottom:10px;">
            Keunggulan Layanan
        </span>
        <h2 style="margin:0 0 8px; font-size:1.85rem; font-weight:800; color:#3B0B0B;">Kenapa Belanja di PustakaMart?</h2>
        <p class="text-muted" style="margin:0; font-size:0.95rem;">Standar kualitas tinggi dan kemudahan berbelanja buku fisik secara digital</p>
    </div>

    <div class="feature-grid-modern">
        <div class="feature-card-modern">
            <div class="feature-icon-wrapper" style="background:#FFD4D4; color:#800000;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
            </div>
            <h3 style="font-size:1.05rem; font-weight:700; margin-bottom:6px; color:#3B0B0B;">Katalog Buku Lengkap</h3>
            <p class="text-muted" style="font-size:0.85rem; line-height:1.55; margin:0;">
                Menyediakan beragam genre mulai dari sastra klasik, novel best-seller, teknologi komputer, bisnis, hingga sains akademik.
            </p>
        </div>

        <div class="feature-card-modern">
            <div class="feature-icon-wrapper" style="background:#E6F4EA; color:#0D8050;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            <h3 style="font-size:1.05rem; font-weight:700; margin-bottom:6px; color:#3B0B0B;">Jaminan 100% Buku Asli</h3>
            <p class="text-muted" style="font-size:0.85rem; line-height:1.55; margin:0;">
                Seluruh buku berasal dari penerbit resmi terpercaya. Bebas dari buku bajakan demi menghargai hak cipta penulis.
            </p>
        </div>

        <div class="feature-card-modern">
            <div class="feature-icon-wrapper" style="background:#FCE8E6; color:#C5221F;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
            </div>
            <h3 style="font-size:1.05rem; font-weight:700; margin-bottom:6px; color:#3B0B0B;">Stok Akurat &amp; Real-Time</h3>
            <p class="text-muted" style="font-size:0.85rem; line-height:1.55; margin:0;">
                Ketersediaan stok buku diperbarui seketika dan transparan setiap kali ada transaksi pembelian baru.
            </p>
        </div>

        <div class="feature-card-modern">
            <div class="feature-icon-wrapper" style="background:#FFD4D4; color:#800000;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            </div>
            <h3 style="font-size:1.05rem; font-weight:700; margin-bottom:6px; color:#3B0B0B;">LiveChat Customer Service</h3>
            <p class="text-muted" style="font-size:0.85rem; line-height:1.55; margin:0;">
                Butuh rekomendasi buku atau konfirmasi pesanan? Tanya langsung admin secara real-time via LiveChat terintegrasi.
            </p>
        </div>

        <div class="feature-card-modern">
            <div class="feature-icon-wrapper" style="background:#FEF3C7; color:#B45309;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/></svg>
            </div>
            <h3 style="font-size:1.05rem; font-weight:700; margin-bottom:6px; color:#3B0B0B;">Proteksi Pengemasan Prima</h3>
            <p class="text-muted" style="font-size:0.85rem; line-height:1.55; margin:0;">
                Setiap paket buku dibungkus bubble wrap tebal dan karton kokoh demi memastikan buku tiba dalam kondisi mulus.
            </p>
        </div>

        <div class="feature-card-modern">
            <div class="feature-icon-wrapper" style="background:#EDE9FE; color:#6D28D9;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
            </div>
            <h3 style="font-size:1.05rem; font-weight:700; margin-bottom:6px; color:#3B0B0B;">Dashboard Terintegrasi</h3>
            <p class="text-muted" style="font-size:0.85rem; line-height:1.55; margin:0;">
                Pantau pesanan, cetak nota invoice, dan kelola profil akun Anda dalam dashboard yang simpel dan responsif.
            </p>
        </div>
    </div>
</section>

<!-- FAQ KHUSUS PEMBELIAN BUKU PUSTAKAMART: INTERACTIVE ACCORDION (100% DAPAT DIKLIK) -->
<section class="landing-section-alt" id="faq" style="background: white; border: 1px solid #F5D0D0; border-radius: 24px; padding: 48px 36px; margin-bottom: 32px; box-shadow: 0 8px 30px rgba(128,0,0,0.03);">
    <div class="text-center" style="max-width:680px; margin:0 auto 36px;">
        <span style="background:#FFD4D4; color:#800000; font-size:0.75rem; font-weight:800; padding:4px 14px; border-radius:999px; text-transform:uppercase; letter-spacing:0.06em; display:inline-block; margin-bottom:10px;">
            Tanya Jawab
        </span>
        <h2 style="margin:0 0 8px; font-size:1.85rem; font-weight:800; color:#3B0B0B;">Pertanyaan yang Sering Diajukan</h2>
        <p class="text-muted" style="margin:0; font-size:0.95rem;">Informasi penting seputar pemesanan dan pengiriman buku di PustakaMart</p>
    </div>

    <div class="faq-container">
        <!-- FAQ 1 -->
        <div class="faq-item" onclick="toggleFaq(this)">
            <div class="faq-question">
                <span>Bagaimana cara memesan buku di PustakaMart?</span>
                <div class="faq-chevron">&darr;</div>
            </div>
            <div class="faq-answer">
                Cukup pilih buku yang Anda inginkan dari katalog, klik "Tambah ke Keranjang", masuk ke halaman Checkout, isi alamat pengiriman, dan selesaikan pembayaran via transfer bank resmi.
            </div>
        </div>

        <!-- FAQ 2 -->
        <div class="faq-item" onclick="toggleFaq(this)">
            <div class="faq-question">
                <span>Apakah buku yang dijual di PustakaMart 100% original?</span>
                <div class="faq-chevron">&darr;</div>
            </div>
            <div class="faq-answer">
                Ya! Kami menjamin 100% keaslian setiap buku. Seluruh buku didatangkan langsung dari penerbit resmi dan dikirim dalam kondisi bersegel baru.
            </div>
        </div>

        <!-- FAQ 3 -->
        <div class="faq-item" onclick="toggleFaq(this)">
            <div class="faq-question">
                <span>Apa metode pembayaran yang didukung?</span>
                <div class="faq-chevron">&darr;</div>
            </div>
            <div class="faq-answer">
                Kami mendukung metode <em>Payment Before Delivery</em> melalui transfer rekening bank resmi (BCA, Mandiri, BRI). Setelah transfer, Anda cukup mengunggah foto bukti struk pembayaran pada halaman pesanan.
            </div>
        </div>

        <!-- FAQ 4 -->
        <div class="faq-item" onclick="toggleFaq(this)">
            <div class="faq-question">
                <span>Berapa lama estimasi pengiriman paket buku?</span>
                <div class="faq-chevron">&darr;</div>
            </div>
            <div class="faq-answer">
                Pesanan yang pembayarannya terverifikasi sebelum pukul 15.00 WIB akan dikemas dan dikirim pada hari yang sama. Estimasi waktu tiba adalah 1-3 hari kerja tergantung wilayah tujuan.
            </div>
        </div>

        <!-- FAQ 5: Lupa Password & Verifikasi Keamanan -->
        <div class="faq-item" onclick="toggleFaq(this)">
            <div class="faq-question">
                <span>Bagaimana jika saya lupa password akun PustakaMart?</span>
                <div class="faq-chevron">&darr;</div>
            </div>
            <div class="faq-answer">
                Demi perlindungan keamanan akun pengguna (<em>cyber protection</em>), silakan kirimkan permohonan pemulihan akun melalui menu <strong>Hubungi Kami</strong>. Anda wajib mencantumkan <strong>Alamat Email</strong>, <strong>NIK KTP</strong>, dan <strong>Nomor HP</strong> yang terdaftar saat registrasi untuk verifikasi kepemilikan oleh Administrator.<br><br>
                Setelah data dicocokkan dan diverifikasi valid oleh Admin, akun akan diberikan kata sandi sementara default: <code>password123</code>. Begitu berhasil masuk, Anda diwajibkan untuk langsung mengganti kata sandi tersebut melalui menu <strong>Pengaturan Profil</strong> demi privasi dan keamanan akun Anda.
            </div>
        </div>
    </div>
</section>

<script>
function toggleFaq(card) {
    const isCurrentlyActive = card.classList.contains('active');
    
    // Tutup item lain agar rapi
    document.querySelectorAll('.faq-item').forEach(item => {
        item.classList.remove('active');
    });

    // Toggle klik sekarang
    if (!isCurrentlyActive) {
        card.classList.add('active');
    }
}
</script>
@endsection
