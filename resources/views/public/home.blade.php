@extends('layouts.app')

@section('title', 'Beranda - Toko Buku Terlengkap & Terpercaya')

@section('content')
<div class="space-y-12">

    <!-- HERO SECTION (MAROON GRADIENT + BLUSH HIGHLIGHTS) -->
    <section class="bg-[#800000] text-white rounded-3xl p-8 sm:p-12 shadow-2xl relative overflow-hidden border-4 border-white/20">
        <div class="absolute -right-10 -bottom-10 w-96 h-96 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
        <div class="relative z-10 max-w-3xl">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 text-[#FFD4D4] text-xs font-bold uppercase tracking-wider mb-4 border border-white/20">
                <i class="fa-solid fa-sparkles text-yellow-300"></i> PustakaMart Resmi Hadir Untuk Anda
            </span>
            <h1 class="text-3xl sm:text-5xl font-black tracking-tight leading-tight mb-4">
                Jelajahi Dunia Literasi dengan Buku Berkualitas.
            </h1>
            <p class="text-[#FFD4D4] text-base sm:text-lg mb-8 leading-relaxed">
                Temukan ribuan judul buku fiksi, teknologi, bisnis, dan akademik pilihan. Transaksi mudah, aman, dan pesanan langsung dikirim ke alamat rumah Anda.
            </p>

            <!-- Search Bar di Hero -->
            <form action="{{ route('books.index') }}" method="GET" class="flex flex-col sm:flex-row gap-2 max-w-xl bg-white p-2 rounded-2xl shadow-xl">
                <div class="flex-1 flex items-center px-3 text-gray-400">
                    <i class="fa-solid fa-magnifying-glass mr-2 text-[#800000]"></i>
                    <input type="text" name="q" placeholder="Cari judul buku, penulis, atau penerbit..." class="w-full text-gray-800 text-sm focus:outline-none">
                </div>
                <button type="submit" class="py-3 px-6 bg-[#800000] hover:bg-[#600000] text-white font-bold rounded-xl text-sm transition-all shadow-md flex items-center justify-center gap-2">
                    <span>Cari Buku</span>
                </button>
            </form>
        </div>
    </section>

    <!-- KATEGORI BUKU CEPAT -->
    <section>
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-black text-[#800000] tracking-tight">Kategori Pilihan</h2>
                <p class="text-xs text-gray-600 font-medium">Temukan buku berdasarkan topik yang Anda gemari</p>
            </div>
            <a href="{{ route('books.index') }}" class="text-xs font-bold text-[#800000] hover:underline flex items-center gap-1">
                <span>Lihat Semua</span> <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach($categories as $category)
                <a href="{{ route('books.index', ['category' => $category->slug]) }}" class="bg-white p-5 rounded-2xl shadow-sm hover:shadow-md border-2 border-[#800000]/10 hover:border-[#800000] transition-all group flex flex-col items-center text-center">
                    <div class="w-12 h-12 rounded-xl bg-[#FFD4D4] text-[#800000] flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform shadow-inner">
                        <i class="fa-solid fa-bookmark"></i>
                    </div>
                    <h3 class="font-bold text-sm text-gray-800 group-hover:text-[#800000] transition-colors line-clamp-1">
                        {{ $category->name }}
                    </h3>
                    <span class="text-xs text-gray-500 mt-1 font-medium">{{ $category->books_count }} Buku</span>
                </a>
            @endforeach
        </div>
    </section>

    <!-- BUKU PILIHAN / TERBARU -->
    <section>
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-black text-[#800000] tracking-tight">Koleksi Buku Terbaru</h2>
                <p class="text-xs text-gray-600 font-medium">Pilihan buku terbaik dengan harga terjangkau</p>
            </div>
            <a href="{{ route('books.index') }}" class="text-xs font-bold text-[#800000] hover:underline flex items-center gap-1">
                <span>Buka Katalog Lengkap</span> <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($featuredBooks as $book)
                <div class="bg-white rounded-3xl shadow-sm hover:shadow-xl border-2 border-[#800000]/10 overflow-hidden flex flex-col justify-between transition-all group">
                    <div>
                        <!-- Cover Buku -->
                        <div class="relative h-64 overflow-hidden bg-gray-100">
                            <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute top-3 left-3 bg-[#800000] text-white text-[11px] font-bold px-3 py-1 rounded-full shadow">
                                {{ $book->category->name ?? 'Umum' }}
                            </div>
                            @if($book->stock > 0)
                                <div class="absolute bottom-3 right-3 bg-black/60 backdrop-blur-sm text-white text-[10px] font-semibold px-2.5 py-1 rounded-lg">
                                    Stok: {{ $book->stock }}
                                </div>
                            @else
                                <div class="absolute bottom-3 right-3 bg-red-600 text-white text-[10px] font-bold px-2.5 py-1 rounded-lg shadow">
                                    Stok Habis
                                </div>
                            @endif
                        </div>

                        <!-- Info Buku -->
                        <div class="p-5">
                            <span class="text-xs text-gray-500 font-medium block mb-1">Oleh: {{ $book->author }} ({{ $book->year }})</span>
                            <h3 class="font-black text-lg text-gray-900 group-hover:text-[#800000] transition-colors line-clamp-1 mb-2">
                                <a href="{{ route('books.show', $book->id) }}">{{ $book->title }}</a>
                            </h3>
                            <p class="text-xs text-gray-600 line-clamp-2 leading-relaxed mb-4">
                                {{ $book->description ?: 'Buku berkualitas tinggi terbitan ' . $book->publisher . ' siap melengkapi koleksi bacaan Anda.' }}
                            </p>
                        </div>
                    </div>

                    <!-- Footer Kartu: Harga & Tombol Beli -->
                    <div class="px-5 pb-5 pt-0 flex items-center justify-between border-t border-gray-100 pt-4">
                        <div>
                            <span class="text-[10px] text-gray-500 uppercase font-bold block">Harga:</span>
                            <span class="text-lg font-black text-[#800000]">{{ $book->formatted_price }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('books.show', $book->id) }}" class="p-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition-all" title="Lihat Detail">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            @if($book->stock > 0)
                                @auth
                                    <form action="/user/cart/add" method="POST">
                                        @csrf
                                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                                        <button type="submit" class="px-3.5 py-2.5 bg-[#800000] hover:bg-[#600000] text-white rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 shadow">
                                            <i class="fa-solid fa-cart-plus"></i> Beli
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="px-3.5 py-2.5 bg-[#800000] hover:bg-[#600000] text-white rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 shadow">
                                        <i class="fa-solid fa-cart-plus"></i> Beli
                                    </a>
                                @endauth
                            @else
                                <button type="button" disabled class="px-3.5 py-2.5 bg-gray-200 text-gray-400 rounded-xl text-xs font-bold cursor-not-allowed">
                                    Habis
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 bg-white rounded-3xl p-8 border border-dashed border-gray-300">
                    <i class="fa-solid fa-book-open text-gray-300 text-5xl mb-3"></i>
                    <p class="text-gray-500 font-semibold text-sm">Belum ada buku yang tersedia saat ini.</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- VALUE PROPOSITION (KENAPA PILIH PUSTAKAMART) -->
    <section class="bg-white rounded-3xl p-8 sm:p-10 shadow-sm border-2 border-[#800000]/10">
        <div class="text-center max-w-xl mx-auto mb-8">
            <h2 class="text-2xl font-black text-[#800000] tracking-tight">Keunggulan Berbelanja di PustakaMart</h2>
            <p class="text-xs text-gray-600 font-medium mt-1">Kami menjamin kepuasan dan keamanan transaksi setiap pembeli buku</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
            <div class="p-5 rounded-2xl bg-[#FFD4D4]/30 border border-[#800000]/10">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-[#800000] text-white flex items-center justify-center text-2xl mb-4 shadow">
                    <i class="fa-solid fa-certificate"></i>
                </div>
                <h3 class="font-black text-gray-900 mb-1">100% Buku Asli</h3>
                <p class="text-xs text-gray-600 leading-relaxed">Seluruh buku didatangkan langsung dari penerbit terpercaya dengan kualitas cetak terbaik.</p>
            </div>

            <div class="p-5 rounded-2xl bg-[#FFD4D4]/30 border border-[#800000]/10">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-[#800000] text-white flex items-center justify-center text-2xl mb-4 shadow">
                    <i class="fa-solid fa-shield-check"></i>
                </div>
                <h3 class="font-black text-gray-900 mb-1">Payment Before Delivery</h3>
                <p class="text-xs text-gray-600 leading-relaxed">Pembayaran via transfer bank terverifikasi otomatis sebelum pesanan dikirimkan ke rumah.</p>
            </div>

            <div class="p-5 rounded-2xl bg-[#FFD4D4]/30 border border-[#800000]/10">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-[#800000] text-white flex items-center justify-center text-2xl mb-4 shadow">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
                <h3 class="font-black text-gray-900 mb-1">Pengemasan Ekstra Aman</h3>
                <p class="text-xs text-gray-600 leading-relaxed">Setiap buku dilapisi bubble wrap tebal dan kardus khusus agar tiba tanpa lipatan atau cacat.</p>
            </div>
        </div>
    </section>

</div>
@endsection
