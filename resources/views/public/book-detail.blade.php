@extends('layouts.app')

@section('title', $book->title . ' - Detail Buku')

@section('content')
<div class="space-y-10">

    <!-- Tombol Kembali -->
    <div>
        <a href="{{ route('books.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-[#800000] hover:underline bg-white px-4 py-2 rounded-xl shadow-sm border border-[#800000]/10">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Katalog Buku
        </a>
    </div>

    <!-- KARTU UTAMA DETAIL BUKU -->
    <div class="bg-white rounded-3xl p-6 sm:p-10 shadow-sm border-2 border-[#800000]/10">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
            
            <!-- Kolom Kiri: Cover Buku -->
            <div class="md:col-span-4">
                <div class="rounded-2xl overflow-hidden shadow-xl border-4 border-[#FFD4D4] bg-gray-50 sticky top-28">
                    <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="w-full h-auto object-cover max-h-[480px]">
                </div>
            </div>

            <!-- Kolom Kanan: Detail & Form Beli -->
            <div class="md:col-span-8 space-y-6">
                
                <div>
                    <span class="inline-block px-3.5 py-1 bg-[#FFD4D4] text-[#800000] text-xs font-bold rounded-full mb-3 uppercase tracking-wider">
                        {{ $book->category->name ?? 'Kategori Umum' }}
                    </span>
                    <h1 class="text-2xl sm:text-4xl font-black text-gray-900 leading-tight mb-2">
                        {{ $book->title }}
                    </h1>
                    <p class="text-sm text-gray-600 font-medium">
                        Penulis: <span class="text-gray-900 font-bold">{{ $book->author }}</span>
                    </p>
                </div>

                <!-- Harga & Status Stok -->
                <div class="p-5 rounded-2xl bg-[#FFD4D4]/30 border border-[#800000]/10 flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <span class="text-xs text-gray-500 font-bold uppercase block">Harga Buku:</span>
                        <span class="text-3xl font-black text-[#800000]">{{ $book->formatted_price }}</span>
                    </div>

                    <div>
                        <span class="text-xs text-gray-500 font-bold uppercase block mb-1">Ketersediaan Stok:</span>
                        @if($book->stock > 0)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-lg border border-green-200">
                                <i class="fa-solid fa-circle-check"></i> Siap Kirim ({{ $book->stock }} tersisa)
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-100 text-red-800 text-xs font-bold rounded-lg border border-red-200">
                                <i class="fa-solid fa-circle-xmark"></i> Stok Habis
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Spesifikasi Buku -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-200">
                        <span class="text-[10px] text-gray-500 uppercase font-bold block">Penerbit</span>
                        <span class="text-xs font-bold text-gray-800 block truncate">{{ $book->publisher }}</span>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-200">
                        <span class="text-[10px] text-gray-500 uppercase font-bold block">Tahun Terbit</span>
                        <span class="text-xs font-bold text-gray-800 block">{{ $book->year }}</span>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-200 col-span-2 sm:col-span-1">
                        <span class="text-[10px] text-gray-500 uppercase font-bold block">Kondisi</span>
                        <span class="text-xs font-bold text-gray-800 block">Baru & Segel Original</span>
                    </div>
                </div>

                <!-- Deskripsi Buku -->
                <div>
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-2">Sinopsis & Deskripsi Buku</h3>
                    <div class="text-sm text-gray-700 leading-relaxed bg-gray-50 p-4 rounded-2xl border border-gray-200 whitespace-pre-line">
                        {{ $book->description ?: 'Buku berkualitas tinggi yang dirancang untuk memperluas wawasan dan pengetahuan pembaca.' }}
                    </div>
                </div>

                <!-- Formulir Tambah ke Keranjang (Add to Cart) -->
                @if($book->stock > 0)
                    @auth
                        <form action="{{ route('cart.add', $book->id) }}" method="POST" class="pt-2 flex flex-col sm:flex-row items-center gap-3">
                            @csrf
                            <div class="w-full sm:w-36 flex items-center border-2 border-gray-300 rounded-xl overflow-hidden bg-white">
                                <button type="button" onclick="decrementQty()" class="px-3.5 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold transition-all">-</button>
                                <input type="number" name="quantity" id="quantityInput" value="1" min="1" max="{{ $book->stock }}"
                                    class="w-full text-center text-sm font-bold focus:outline-none border-none">
                                <button type="button" onclick="incrementQty()" class="px-3.5 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold transition-all">+</button>
                            </div>

                            <button type="submit" class="w-full sm:flex-1 py-3.5 px-6 bg-[#800000] hover:bg-[#600000] text-white font-bold rounded-xl shadow-lg transition-all flex items-center justify-center gap-2">
                                <i class="fa-solid fa-cart-shopping"></i> Tambahkan ke Keranjang
                            </button>
                        </form>
                    @else
                        <div class="p-4 bg-[#FFD4D4]/40 rounded-2xl border border-[#800000]/20 flex items-center justify-between">
                            <span class="text-xs text-gray-700 font-medium">Masuk akun untuk memesan buku ini:</span>
                            <a href="{{ route('login') }}" class="py-2 px-4 bg-[#800000] text-white text-xs font-bold rounded-xl shadow">
                                Login Sekarang
                            </a>
                        </div>
                    @endauth
                @else
                    <div class="p-4 bg-gray-100 text-gray-500 rounded-xl text-center text-xs font-bold">
                        Buku ini sementara sedang tidak tersedia (Stok Habis).
                    </div>
                @endif

            </div>
        </div>
    </div>

    <!-- BUKU TERKAIT -->
    @if($relatedBooks->isNotEmpty())
        <div>
            <h2 class="text-xl font-black text-[#800000] mb-4">Buku Terkait dalam Kategori Serupa</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                @foreach($relatedBooks as $related)
                    <div class="bg-white rounded-2xl p-4 shadow-sm border border-[#800000]/10 flex gap-4 items-center">
                        <img src="{{ $related->cover_url }}" alt="{{ $related->title }}" class="w-16 h-20 object-cover rounded-xl shadow">
                        <div class="flex-1 truncate">
                            <h4 class="font-bold text-sm text-gray-900 truncate">
                                <a href="{{ route('books.show', $related->id) }}" class="hover:text-[#800000]">{{ $related->title }}</a>
                            </h4>
                            <span class="text-xs text-gray-500 block">{{ $related->author }}</span>
                            <span class="text-xs font-black text-[#800000] mt-1 block">{{ $related->formatted_price }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>

<script>
    function incrementQty() {
        const input = document.getElementById('quantityInput');
        const max = parseInt(input.getAttribute('max')) || 999;
        let val = parseInt(input.value) || 1;
        if (val < max) {
            input.value = val + 1;
        }
    }
    function decrementQty() {
        const input = document.getElementById('quantityInput');
        let val = parseInt(input.value) || 1;
        if (val > 1) {
            input.value = val - 1;
        }
    }
</script>
@endsection
