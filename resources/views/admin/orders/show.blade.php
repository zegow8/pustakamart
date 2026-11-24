@extends('layouts.admin')

@section('title', 'Detail Pesanan ' . $order->order_code)
@section('page_title', 'Kelola Pesanan: ' . $order->order_code)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div>
        <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#800000] hover:underline mb-2">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Pesanan
        </a>
        <h1 class="text-2xl font-black text-gray-900">Kelola Pesanan: {{ $order->order_code }}</h1>
    </div>

    <!-- PANEL VERIFIKASI STATUS PESANAN -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-200">
        <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4 flex items-center gap-2">
            <i class="fa-solid fa-sliders text-[#800000]"></i> Update Status Pesanan & Verifikasi Pembayaran
        </h2>

        <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="flex flex-col sm:flex-row items-center gap-3">
            @csrf
            @method('PUT')

            <div class="flex-1 w-full">
                <select name="status" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-sm font-bold focus:ring-2 focus:ring-[#800000] focus:outline-none">
                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Menunggu Pembayaran (Pending)</option>
                    <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>Pembayaran Terverifikasi (Paid)</option>
                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Sedang Diproses & Dipacking (Processing)</option>
                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Dalam Pengiriman Kurir (Shipped)</option>
                    <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Pesanan Telah Selesai (Completed)</option>
                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan (Cancelled)</option>
                </select>
            </div>

            <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-[#800000] hover:bg-[#600000] text-white text-xs font-bold rounded-xl shadow transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-check-circle"></i> Perbarui Status
            </button>
        </form>
    </div>

    <!-- INFORMASI PESANAN & BUKTI TRANSFER -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Info Pelanggan & Alamat -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-200 space-y-3 text-xs">
            <h3 class="font-bold text-gray-900 uppercase tracking-wider border-b pb-2">Informasi Pembeli:</h3>
            <div>
                <span class="text-gray-500 block">Nama Akun:</span>
                <span class="font-bold text-gray-900 text-sm">{{ $order->user->name ?? 'User Terhapus' }}</span>
            </div>
            <div>
                <span class="text-gray-500 block">Email Pelanggan:</span>
                <span class="font-semibold text-gray-800">{{ $order->user->email ?? '-' }}</span>
            </div>
            <div>
                <span class="text-gray-500 block">Nomor WhatsApp / HP:</span>
                <span class="font-bold text-gray-900">{{ $order->phone }}</span>
            </div>
            <div>
                <span class="text-gray-500 block">Alamat Pengiriman Buku:</span>
                <p class="font-medium text-gray-800 leading-relaxed bg-gray-50 p-2.5 rounded-xl border mt-1">{{ $order->shipping_address }}</p>
            </div>
            @if($order->notes)
                <div>
                    <span class="text-gray-500 block">Catatan Tambahan:</span>
                    <span class="italic text-gray-700">"{{ $order->notes }}"</span>
                </div>
            @endif
        </div>

        <!-- Bukti Transfer Pembayaran -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-200 space-y-3 text-xs">
            <h3 class="font-bold text-gray-900 uppercase tracking-wider border-b pb-2">Bukti Struk Transfer:</h3>
            @if($order->payment_receipt)
                <div class="rounded-2xl overflow-hidden border border-gray-200 bg-gray-50 p-2">
                    <img src="{{ $order->receipt_url }}" alt="Bukti Transfer" class="w-full max-h-64 object-contain rounded-xl">
                </div>
                <a href="{{ $order->receipt_url }}" target="_blank" class="inline-block text-[#800000] font-bold underline text-xs">
                    <i class="fa-solid fa-arrow-up-right-from-square mr-1"></i> Buka Gambar Bukti Ukuran Penuh
                </a>
            @else
                <div class="p-8 text-center bg-gray-50 rounded-2xl border border-dashed border-gray-300 text-gray-400">
                    <i class="fa-solid fa-receipt text-3xl mb-2 text-gray-300"></i>
                    <p class="font-medium">User belum mengunggah foto bukti pembayaran transfer.</p>
                </div>
            @endif
        </div>

    </div>

    <!-- RINCIAN BUKU YANG DIBELI -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-200">
        <h3 class="font-bold text-gray-900 uppercase tracking-wider text-xs mb-4">Rincian Buku dalam Pesanan Ini</h3>
        
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase font-bold text-[10px] tracking-wider border-b">
                    <tr>
                        <th class="py-2.5 px-3">Buku</th>
                        <th class="py-2.5 px-3">Penulis</th>
                        <th class="py-2.5 px-3 text-center">Jumlah</th>
                        <th class="py-2.5 px-3 text-right">Harga Satuan</th>
                        <th class="py-2.5 px-3 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($order->items as $item)
                        <tr>
                            <td class="py-3 px-3 font-bold text-gray-900 flex items-center gap-2">
                                <img src="{{ $item->book->cover_url }}" alt="{{ $item->book->title }}" class="w-8 h-10 object-cover rounded shadow-sm">
                                <span>{{ $item->book->title }}</span>
                            </td>
                            <td class="py-3 px-3 text-gray-600">{{ $item->book->author }}</td>
                            <td class="py-3 px-3 text-center font-bold">{{ $item->quantity }}</td>
                            <td class="py-3 px-3 text-right">{{ $item->formatted_price }}</td>
                            <td class="py-3 px-3 text-right font-black text-gray-900">{{ $item->formatted_subtotal }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 border-t border-gray-200">
                    <tr>
                        <td colspan="4" class="py-3 px-3 text-right font-bold text-gray-700 uppercase">Total Pesanan:</td>
                        <td class="py-3 px-3 text-right font-black text-sm text-[#800000]">{{ $order->formatted_total }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>
@endsection
