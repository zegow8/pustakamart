@extends('layouts.app')

@section('title', 'Pembayaran Pesanan ' . $order->order_code)

@section('content')
<div class="max-w-3xl mx-auto space-y-8">

    <div class="text-center">
        <span class="inline-block px-3.5 py-1 bg-yellow-100 text-yellow-800 text-xs font-bold rounded-full mb-2 uppercase tracking-wider">
            Menunggu Pembayaran (Payment Before Delivery)
        </span>
        <h1 class="text-3xl font-black text-[#800000] tracking-tight">Selesaikan Pembayaran Anda</h1>
        <p class="text-xs text-gray-600 font-medium mt-1">
            Pesanan <span class="font-bold text-gray-900">{{ $order->order_code }}</span> telah dibuat. Silakan transfer nominal sesuai tagihan lalu unggah buktinya di bawah ini.
        </p>
    </div>

    <!-- KARTU NOMINAL & NOMOR REKENING -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border-2 border-[#800000]/10 space-y-6">
        
        <div class="p-6 bg-[#FFD4D4]/40 rounded-2xl border border-[#800000]/20 text-center">
            <span class="text-xs text-gray-600 font-bold uppercase tracking-wider block mb-1">Total Tagihan Pembayaran:</span>
            <span class="text-3xl sm:text-4xl font-black text-[#800000] block">{{ $order->formatted_total }}</span>
            <span class="text-[11px] text-gray-500 mt-1 block">Mohon transfer sesuai angka tepat hingga digit terakhir</span>
        </div>

        <!-- Rekening Resmi PustakaMart -->
        <div>
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                <i class="fa-solid fa-building-columns text-[#800000]"></i> Rekening Bank Tujuan Transfer:
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200">
                    <span class="font-black text-blue-800 text-sm block">Bank BCA</span>
                    <span class="font-bold text-gray-900 text-base font-mono block my-1">882-0192-334</span>
                    <span class="text-gray-500">a.n. PT PustakaMart Indonesia</span>
                </div>
                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200">
                    <span class="font-black text-amber-700 text-sm block">Bank Mandiri</span>
                    <span class="font-bold text-gray-900 text-base font-mono block my-1">137-00-9821-445</span>
                    <span class="text-gray-500">a.n. PT PustakaMart Indonesia</span>
                </div>
            </div>
        </div>

        <!-- FORM UNGGAH BUKTI TRANSFER -->
        <div class="pt-4 border-t border-gray-100">
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                <i class="fa-solid fa-upload text-[#800000]"></i> Unggah Bukti Struk Pembayaran
            </h3>

            @if($order->payment_receipt)
                <div class="p-4 bg-green-50 border border-green-200 rounded-2xl mb-4 flex items-center gap-4">
                    <img src="{{ $order->receipt_url }}" alt="Bukti Transfer" class="w-16 h-16 object-cover rounded-xl shadow border">
                    <div>
                        <span class="text-xs font-bold text-green-800 block">Bukti transfer sudah pernah diunggah!</span>
                        <span class="text-[11px] text-gray-600">Status pesanan saat ini: <strong class="text-green-700">{{ $order->status_label }}</strong></span>
                    </div>
                </div>
            @endif

            <form action="{{ route('checkout.receipt', $order->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <input type="file" name="payment_receipt" required accept="image/jpeg,image/png,image/jpg"
                        class="w-full text-xs text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#800000] file:text-white hover:file:bg-[#600000] file:cursor-pointer border border-gray-300 rounded-2xl p-2 bg-gray-50 focus:outline-none">
                    <p class="text-[11px] text-gray-500 mt-1.5">Format file: JPG, JPEG, atau PNG (Maksimal ukuran: 2 MB).</p>
                    @error('payment_receipt')
                        <p class="text-red-600 text-xs mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full py-3.5 px-6 bg-[#800000] hover:bg-[#600000] text-white font-bold rounded-xl shadow-lg transition-all flex items-center justify-center gap-2 text-sm">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Konfirmasi & Kirim Bukti Transfer
                </button>
            </form>
        </div>

    </div>

</div>
@endsection
