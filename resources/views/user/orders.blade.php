@extends('layouts.app')

@section('title', 'Riwayat Pesanan Belanja')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('user.dashboard') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#800000] hover:underline mb-1">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
            </a>
            <h1 class="text-3xl font-black text-[#800000] tracking-tight">Riwayat Pesanan Saya</h1>
            <p class="text-xs text-gray-600 font-medium">Daftar lengkap seluruh transaksi pembelian buku fisik di PustakaMart.</p>
        </div>
        <a href="{{ route('books.index') }}" class="px-4 py-2 bg-[#800000] text-white text-xs font-bold rounded-xl shadow hover:bg-[#600000]">
            <i class="fa-solid fa-plus mr-1"></i> Beli Buku Baru
        </a>
    </div>

    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border-2 border-[#800000]/10 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase font-bold text-[10px] tracking-wider border-b">
                    <tr>
                        <th class="py-3 px-4">Kode Order</th>
                        <th class="py-3 px-4">Tanggal</th>
                        <th class="py-3 px-4">Jumlah Buku</th>
                        <th class="py-3 px-4">Total Biaya</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $ord)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-4 font-bold text-gray-900 font-mono">
                                {{ $ord->order_code }}
                            </td>
                            <td class="py-4 px-4 text-gray-600">
                                {{ $ord->created_at->translatedFormat('d M Y, H:i') }}
                            </td>
                            <td class="py-4 px-4">
                                <span class="font-semibold text-gray-800">{{ $ord->items->sum('quantity') }} Eksemplar</span>
                                <span class="block text-[10px] text-gray-400">({{ $ord->items->count() }} judul buku)</span>
                            </td>
                            <td class="py-4 px-4 font-bold text-[#800000] text-sm">
                                {{ $ord->formatted_total }}
                            </td>
                            <td class="py-4 px-4">
                                @if($ord->status === 'pending')
                                    <span class="px-2.5 py-1 bg-yellow-100 text-yellow-800 rounded-lg text-[10px] font-bold">Menunggu Bayar</span>
                                @elseif($ord->status === 'paid')
                                    <span class="px-2.5 py-1 bg-blue-100 text-blue-800 rounded-lg text-[10px] font-bold">Verifikasi Admin</span>
                                @elseif($ord->status === 'processing')
                                    <span class="px-2.5 py-1 bg-indigo-100 text-indigo-800 rounded-lg text-[10px] font-bold">Diproses Toko</span>
                                @elseif($ord->status === 'shipped')
                                    <span class="px-2.5 py-1 bg-purple-100 text-purple-800 rounded-lg text-[10px] font-bold">Dalam Pengiriman</span>
                                @elseif($ord->status === 'completed')
                                    <span class="px-2.5 py-1 bg-green-100 text-green-800 rounded-lg text-[10px] font-bold">Selesai</span>
                                @else
                                    <span class="px-2.5 py-1 bg-gray-100 text-gray-800 rounded-lg text-[10px] font-bold">{{ ucfirst($ord->status) }}</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    @if($ord->status === 'pending')
                                        <a href="{{ route('checkout.payment', $ord->id) }}" class="px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-xs font-bold shadow-sm">
                                            Bayar
                                        </a>
                                    @endif
                                    <a href="{{ route('user.orders.show', $ord->id) }}" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg text-xs font-bold border border-gray-300">
                                        Rincian
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-400">
                                <i class="fa-solid fa-receipt text-4xl mb-3 text-gray-300"></i>
                                <p class="text-sm font-semibold">Belum ada riwayat pesanan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    </div>

</div>
@endsection
