@extends('layouts.user')

@section('title', 'Detail Transaksi')

@section('content')
<style>
    .order-detail-header h1 {
        font-size: clamp(1.2rem, 4.5vw, 1.75rem);
        font-weight: 800;
        color: var(--text-main);
        word-break: break-all;
        line-height: 1.3;
        margin: 6px 0 4px 0;
    }

    @media (max-width: 868px) {
        .user-order-detail-grid { 
            grid-template-columns: 1fr !important; 
            gap: 16px !important; 
        }
    }

    /* Tampilan Desktop vs Mobile untuk Rincian Buku */
    .order-table-desktop {
        display: block;
        width: 100%;
        overflow-x: auto;
    }
    .order-cards-mobile {
        display: none;
    }

    @media (max-width: 640px) {
        .order-table-desktop {
            display: none !important;
        }
        .order-cards-mobile {
            display: flex !important;
            flex-direction: column;
        }
    }
</style>

<div class="mb-4 order-detail-header">
    <a href="/user/transactions" style="font-weight:600; font-size:0.88rem; text-decoration:none; color:var(--primary); display:inline-flex; align-items:center; gap:6px; margin-bottom:4px;">
        &larr; Kembali ke Riwayat Pembelian
    </a>
    <div style="display:flex; justify-content:space-between; align-items:flex-end; flex-wrap:wrap; gap:12px;">
        <div>
            <h1>Detail Pesanan: {{ $order->invoice_code }}</h1>
            
        </div>
        <a href="/user/transactions/{{ $order->id }}/print" target="_blank" class="btn btn-outline" style="font-weight:700; display:inline-flex; align-items:center; gap:8px; border-color:#2563eb; color:#2563eb; background:#eff6ff;">
            <span style="font-size:1.1rem;">🖨️</span> Cetak Invoice / Struk
        </a>
    </div>
    
</div>

<div style="display:grid; grid-template-columns: 2fr 1fr; gap:24px;" class="user-order-detail-grid">
    <!-- RINCIAN BUKU YANG DIBELI -->
    <div>
        <div class="panel" style="padding:0; overflow:hidden; margin-bottom:20px;">
            <div style="padding:14px 18px; background:#f8fafc; border-bottom:1px solid var(--border); font-weight:700; font-size:0.95rem;">
                📚 Daftar Buku yang Dipesan
            </div>

            <!-- 1. TAMPILAN DESKTOP (TABEL) -->
            <div class="order-table-desktop">
                <table class="table" style="margin:0;">
                    <thead>
                        <tr>
                            <th style="width:70px;">Cover</th>
                            <th>Buku</th>
                            <th>Harga</th>
                            <th style="text-align:center;">Qty</th>
                            <th style="text-align:right;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->details as $d)
                        <tr>
                            <td>
                                <div style="width:50px; height:70px; border-radius:6px; overflow:hidden; background:#eee; border:1px solid var(--border);">
                                    @if($d->book && $d->book->cover)
                                        <img src="{{ asset('uploads/covers/' . $d->book->cover) }}" alt="{{ $d->title_snapshot }}" style="width:100%; height:100%; object-fit:cover;">
                                    @else
                                        <div style="display:flex; align-items:center; justify-content:center; height:100%; font-size:1.5rem;">📖</div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <strong style="font-size:0.95rem; color:var(--text-main);">{{ $d->title_snapshot }}</strong>
                                @if($d->book)
                                    <div style="font-size:0.8rem; color:var(--text-muted); margin-top:2px;">Penulis: {{ $d->book->author }}</div>
                                @endif
                            </td>
                            <td style="white-space:nowrap;">Rp {{ number_format($d->price_snapshot, 0, ',', '.') }}</td>
                            <td style="text-align:center; font-weight:700;">{{ $d->qty }} pcs</td>
                            <td style="text-align:right; font-weight:700; color:var(--primary); white-space:nowrap;">
                                Rp {{ number_format($d->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- 2. TAMPILAN MOBILE (KARTU PRODUK FLEKSIBEL) -->
            <div class="order-cards-mobile">
                @foreach($order->details as $d)
                    <div style="display:flex; gap:12px; padding:14px; border-bottom:1px solid var(--border); align-items:flex-start;">
                        <div style="width:55px; height:78px; border-radius:6px; overflow:hidden; background:#f1f5f9; flex-shrink:0; border:1px solid var(--border);">
                            @if($d->book && $d->book->cover)
                                <img src="{{ asset('uploads/covers/' . $d->book->cover) }}" alt="{{ $d->title_snapshot }}" style="width:100%; height:100%; object-fit:cover;">
                            @else
                                <div style="display:flex; align-items:center; justify-content:center; height:100%; font-size:1.4rem;">📖</div>
                            @endif
                        </div>
                        <div style="flex:1; min-width:0;">
                            <div style="font-weight:700; font-size:0.92rem; color:var(--text-main); line-height:1.35; margin-bottom:2px;">
                                {{ $d->title_snapshot }}
                            </div>
                            @if($d->book)
                                <div style="font-size:0.75rem; color:var(--text-muted); margin-bottom:6px;">
                                    Penulis: {{ $d->book->author }}
                                </div>
                            @endif
                            <div style="display:flex; justify-content:space-between; align-items:flex-end; flex-wrap:wrap; gap:6px; margin-top:6px;">
                                <div style="font-size:0.8rem; color:var(--text-muted);">
                                    Rp {{ number_format($d->price_snapshot, 0, ',', '.') }} &times; <strong>{{ $d->qty }} pcs</strong>
                                </div>
                                <div style="font-size:0.95rem; font-weight:800; color:var(--primary);">
                                    Rp {{ number_format($d->subtotal, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- RINCIAN PEMBAYARAN JELAS & ELEGAN (DESKTOP & MOBILE) -->
            @php
                $subtotalProduk = $order->details->sum('subtotal');
                $biayaOngkir = max(0, $order->total - $subtotalProduk);
            @endphp
            <div style="padding:18px 24px; background:#fafafa; border-top:1px solid var(--border); display:flex; flex-direction:column; gap:10px;">
                <div style="display:flex; justify-content:space-between; font-size:0.9rem; color:var(--text-muted);">
                    <span>Subtotal Produk:</span>
                    <strong style="color:var(--text-main);">Rp {{ number_format($subtotalProduk, 0, ',', '.') }}</strong>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:0.9rem; color:var(--text-muted);">
                    <span>Biaya Ongkos Kirim:</span>
                    <strong style="color:var(--text-main);">Rp {{ number_format($biayaOngkir, 0, ',', '.') }}</strong>
                </div>
                <div style="display:flex; justify-content:space-between; align-items:center; padding-top:14px; margin-top:4px; border-top:2px dashed #cbd5e1;">
                    <span style="font-size:1.05rem; font-weight:800; color:var(--text-main);">Total Tagihan :</span>
                    <span style="font-size:1.4rem; font-weight:900; color:#1e40af; letter-spacing:-0.02em;">
                        Rp {{ number_format($order->total, 0, ',', '.') }}
                    </span>
                </div>
            </div>

        </div>

        <!-- INFORMASI PENGIRIMAN & PESAN UNTUK KURIR -->
        <div class="panel">
            <div class="panel-header flex-between mb-3" style="padding-bottom:12px; border-bottom:1px solid var(--border);">
                <h3 class="panel-title" style="font-size:1rem; display:flex; align-items:center; gap:8px;">
                    📍 Informasi Pengiriman
                </h3>
                <span style="font-size:0.75rem; background:#eff6ff; color:#1e40af; font-weight:700; padding:3px 10px; border-radius:12px; border:1px solid #bfdbfe;">
                    📦 Jasa Ekspedisi
                </span>
            </div>

            <div style="display:flex; flex-direction:column; gap:14px;">
                <!-- Penerima -->
                <div>
                    <div style="font-size:0.75rem; text-transform:uppercase; font-weight:700; color:var(--text-muted); letter-spacing:0.04em;">Nama Penerima &amp; Kontak</div>
                    <div style="font-size:0.95rem; font-weight:700; color:var(--text-main); margin-top:3px;">
                        {{ $order->receiver_name ?? ($order->user->name ?? 'User') }}
                        <span style="font-weight:500; color:var(--text-muted); font-size:0.85rem; margin-left:6px;">
                            ({{ $order->receiver_phone ?? ($order->user->phone ?? '-') }})
                        </span>
                    </div>
                </div>

                <!-- Alamat -->
                <div>
                    <div style="font-size:0.75rem; text-transform:uppercase; font-weight:700; color:var(--text-muted); letter-spacing:0.04em;">Alamat Lengkap Pengiriman</div>
                    <p style="margin:4px 0 0; font-size:0.9rem; color:var(--text-main); background:#f8fafc; padding:12px 14px; border-radius:8px; border:1px solid var(--border); line-height:1.6; word-break:break-word;">
                        {{ $order->shipping_address ?? 'Alamat tidak tercantum' }}
                    </p>
                </div>

                <!-- Catatan / Pesan untuk Kurir -->
                <div>
                    <div style="font-size:0.75rem; text-transform:uppercase; font-weight:700; color:var(--text-muted); letter-spacing:0.04em; display:flex; align-items:center; gap:6px;">
                        <span>📝 Pesan / Catatan untuk Kurir</span>
                    </div>
                    @if(!empty($order->note))
                        <div style="margin-top:4px; font-size:0.9rem; background:#eff6ff; color:#1e40af; padding:12px 14px; border-radius:8px; border:1px solid #bfdbfe; font-weight:600; line-height:1.5;">
                            "{{ $order->note }}"
                        </div>
                    @else
                        <div style="margin-top:4px; font-size:0.85rem; color:#94a3b8; background:#f8fafc; padding:10px 14px; border-radius:8px; border:1px dashed #cbd5e1; font-style:italic;">
                            Tidak ada catatan khusus untuk kurir.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- STATUS & TRACKING -->
    <div>
        <div class="panel">
            <h3 class="panel-title mb-4" style="font-size:1rem;">📦 Status Pesanan</h3>
            
            <div style="margin-bottom:16px;">
                <div style="font-size:0.75rem; text-transform:uppercase; font-weight:700; color:var(--text-muted); margin-bottom:6px;">Status Saat Ini</div>
                <div>
                    @if($order->status === 'Waiting Confirmation')
                        <span class="status-badge" style="background:#ffedd5; color:#c2410c; font-size:0.82rem; font-weight:800; border:1px solid #fdba74; display:inline-block; padding:5px 10px;">
                            ⏳ Menunggu Konfirmasi Admin
                        </span>
                        <p style="font-size:0.8rem; color:var(--text-muted); margin-top:8px; line-height:1.4;">
                            Pembayaran Anda via <strong>{{ $order->payment_method }}</strong> sedang diperiksa oleh admin. Buku akan segera dikemas setelah pembayaran di-ACC.
                        </p>
                    @elseif($order->status === 'Processing')
                        <span class="status-badge" style="background:#fef3c7; color:#92400e; font-size:0.82rem;">Dikemas (Sedang Disiapkan)</span>
                    @elseif($order->status === 'Shipped')
                        <span class="status-badge" style="background:#e0e7ff; color:#3730a3; font-size:0.82rem;">Sedang Dikirim</span>
                    @elseif($order->status === 'Completed')
                        <span class="status-badge" style="background:#dcfce7; color:#166534; font-size:0.82rem;">Selesai Diterima</span>
                    @elseif($order->status === 'Cancelled')
                        <span class="status-badge" style="background:#f1f5f9; color:#475569; font-size:0.82rem;">Pesanan Dibatalkan</span>
                    @elseif($order->status === 'Rejected')
                        <span class="status-badge" style="background:#fee2e2; color:#991b1b; font-size:0.82rem;">Pesanan Ditolak</span>
                    @endif
                </div>
            </div>

            @if($order->tracking_number && in_array($order->status, ['Shipped', 'Completed']))
                <div style="margin-bottom:16px; background:#eff6ff; padding:12px; border-radius:8px; border:1px solid #bfdbfe;">
                    <div style="font-size:0.72rem; text-transform:uppercase; font-weight:700; color:var(--primary-dark);">Nomor Resi Pengiriman</div>
                    <div style="font-family:monospace; font-size:1.05rem; font-weight:800; color:var(--primary); margin-top:4px; word-break:break-all;">
                        {{ $order->tracking_number }}
                    </div>
                </div>
            @endif

            <div style="margin-bottom:16px;">
                <div style="font-size:0.75rem; text-transform:uppercase; font-weight:700; color:var(--text-muted); margin-bottom:4px;">Metode Pembayaran</div>
                <div style="font-weight:700; color:var(--primary); font-size:0.92rem;">{{ $order->payment_method ?? 'Transfer Bank' }}</div>
            </div>

            @if(in_array($order->status, ['Waiting Confirmation', 'Processing']))
                <div style="margin-top:20px; padding-top:16px; border-top:1px solid var(--border);">
                    <form action="/user/transactions/cancel" method="POST">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                        <button type="submit" class="btn btn-outline btn-block" style="color:var(--danger); border-color:var(--danger); font-weight:700; font-size:0.88rem;" onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                            Batalkan Pesanan
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
