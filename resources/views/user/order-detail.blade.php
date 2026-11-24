@extends('layouts.app')

@section('title', 'Rincian Pesanan ' . $order->order_code)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Tombol Navigasi & Cetak Nota -->
    <div class="flex items-center justify-between">
        <a href="{{ route('user.orders') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#800000] hover:underline bg-white px-4 py-2 rounded-xl shadow-sm border border-[#800000]/10">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Pesanan
        </a>
        <button onclick="window.print()" class="px-4 py-2 bg-gray-900 text-white text-xs font-bold rounded-xl shadow hover:bg-black transition-all flex items-center gap-1.5">
            <i class="fa-solid fa-print"></i> Cetak Invoice
        </button>
    </div>

    <!-- KARTU INVOICE RESMI -->
    <div class="bg-white rounded-3xl p-6 sm:p-10 shadow-sm border-2 border-[#800000]/10 space-y-8" id="printableInvoice">
        
        <!-- Header Invoice -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-6 border-b border-gray-100 gap-4">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest block">Invoice Pesanan</span>
                <h1 class="text-2xl font-black text-[#800000] font-mono">{{ $order->order_code }}</h1>
                <span class="text-xs text-gray-500">Tanggal Transaksi: {{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB</span>
            </div>
            <div>
                <span class="px-3 py-1.5 bg-[#FFD4D4] text-[#800000] rounded-xl text-xs font-black uppercase tracking-wider border border-[#800000]/20">
                    Status: {{ $order->status_label }}
                </span>
            </div>
        </div>

        <!-- Info Penerima & Pembayaran -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200">
                <span class="font-bold text-gray-500 uppercase block mb-1.5">Penerima & Alamat Pengiriman:</span>
                <span class="font-bold text-gray-900 text-sm block mb-1">{{ $order->user->name }}</span>
                <p class="text-gray-700 leading-relaxed mb-2">{{ $order->shipping_address }}</p>
                <span class="text-gray-600 font-medium"><i class="fa-solid fa-phone mr-1"></i> {{ $order->phone }}</span>
                @if($order->notes)
                    <div class="mt-2 pt-2 border-t border-gray-200 text-gray-500 italic">
                        Catatan: "{{ $order->notes }}"
                    </div>
                @endif
            </div>

            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200">
                <span class="font-bold text-gray-500 uppercase block mb-1.5">Metode Pembayaran:</span>
                <span class="font-bold text-gray-900 text-sm block mb-1">Transfer Bank (Payment Before Delivery)</span>
                <p class="text-gray-600 mb-3">Pesanan diproses dan dikirim setelah pembayaran terverifikasi oleh Administrator.</p>

                @if($order->payment_receipt)
                    <div class="pt-2 border-t border-gray-200 flex items-center gap-3">
                        <img src="{{ $order->receipt_url }}" alt="Bukti Transfer" class="w-12 h-12 object-cover rounded-lg shadow border">
                        <div>
                            <span class="font-bold text-green-700 block">Bukti transfer terunggah</span>
                            <a href="{{ $order->receipt_url }}" target="_blank" class="text-[#800000] underline text-[11px]">Lihat Gambar Penuh</a>
                        </div>
                    </div>
                @else
                    <div class="pt-2 border-t border-gray-200">
                        <a href="{{ route('checkout.payment', $order->id) }}" class="inline-block px-3 py-1.5 bg-yellow-500 text-white font-bold rounded-lg text-xs shadow">
                            <i class="fa-solid fa-upload mr-1"></i> Unggah Bukti Bayar Sekarang
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Tabel Rincian Buku -->
        <div>
            <h3 class="font-black text-sm text-gray-900 uppercase tracking-wider mb-3">Daftar Buku yang Dibeli</h3>
            
            <div class="overflow-x-auto border border-gray-200 rounded-2xl overflow-hidden">
                <table class="w-full text-xs text-left">
                    <thead class="bg-gray-50 text-gray-700 uppercase font-bold text-[10px] tracking-wider border-b">
                        <tr>
                            <th class="py-3 px-4">No</th>
                            <th class="py-3 px-4">Judul Buku</th>
                            <th class="py-3 px-4">Kategori</th>
                            <th class="py-3 px-4 text-center">Jumlah</th>
                            <th class="py-3 px-4 text-right">Harga Satuan</th>
                            <th class="py-3 px-4 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($order->items as $idx => $item)
                            <tr>
                                <td class="py-3 px-4 font-bold text-gray-500">{{ $idx + 1 }}</td>
                                <td class="py-3 px-4 font-bold text-gray-900">{{ $item->book->title }}</td>
                                <td class="py-3 px-4 text-gray-600">{{ $item->book->category->name ?? '-' }}</td>
                                <td class="py-3 px-4 text-center font-bold">{{ $item->quantity }}</td>
                                <td class="py-3 px-4 text-right">{{ $item->formatted_price }}</td>
                                <td class="py-3 px-4 text-right font-bold text-gray-900">{{ $item->formatted_subtotal }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 border-t border-gray-200 text-xs">
                        <tr>
                            <td colspan="5" class="py-3 px-4 text-right font-bold text-gray-600 uppercase">Total Tagihan:</td>
                            <td class="py-3 px-4 text-right font-black text-base text-[#800000]">{{ $order->formatted_total }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection
