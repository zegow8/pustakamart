@extends('layouts.user')

@section('title', 'Dashboard Saya - PustakaMart')

@section('content')
<div class="flex-between mb-4" style="flex-wrap:wrap; gap:16px;">
    <div>
        <h1 style="margin-bottom:4px; font-size:clamp(1.25rem, 4vw, 1.8rem);">Halo, {{ auth()->user()->name }}! 👋</h1>
        <p class="text-muted" style="margin:0; font-size:0.9rem;">Selamat datang di <strong>PustakaMart</strong> • {{ date('l, d F Y') }}</p>
    </div>
    <div style="display:flex; gap:10px; flex-wrap:wrap;">
        <a href="/user/catalog" class="btn btn-primary btn-sm">📚 Belanja Buku Sekarang</a>
        <a href="/user/cart" class="btn btn-outline btn-sm">🛒 Keranjang Belanja</a>
    </div>
</div>

<!-- ============ STATUS PESANAN BELANJA BUKU ============ -->
<div class="panel" style="margin-bottom:20px; padding:18px;">
    <div class="panel-header flex-between mb-3" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px;">
        <h3 class="panel-title" style="margin:0; font-size:1.05rem;">📦 Status Pesanan Anda ({{ $totalOrders }} Pesanan)</h3>
        <a href="/user/transactions" class="btn btn-outline btn-sm" style="font-size:0.75rem;">Lihat Semua</a>
    </div>

    <div class="status-grid-4">
        <a href="/user/transactions?status=Waiting Confirmation" style="background:#ffedd5; padding:14px 8px; border-radius:10px; text-decoration:none; display:block; border:1px solid #fed7aa;">
            <div style="font-size:1.5rem; font-weight:800; color:#c2410c;">{{ $ordWaiting }}</div>
            <div style="font-size:0.72rem; font-weight:700; color:#ea580c; margin-top:4px;">Menunggu ACC</div>
        </a>
        <a href="/user/transactions?status=Processing" style="background:#fef3c7; padding:14px 8px; border-radius:10px; text-decoration:none; display:block; border:1px solid #fde68a;">
            <div style="font-size:1.5rem; font-weight:800; color:#92400e;">{{ $ordProcessing }}</div>
            <div style="font-size:0.72rem; font-weight:700; color:#b45309; margin-top:4px;">Dikemas</div>
        </a>
        <a href="/user/transactions?status=Shipped" style="background:#e0e7ff; padding:14px 8px; border-radius:10px; text-decoration:none; display:block; border:1px solid #c7d2fe;">
            <div style="font-size:1.5rem; font-weight:800; color:#3730a3;">{{ $ordShipped }}</div>
            <div style="font-size:0.72rem; font-weight:700; color:#4338ca; margin-top:4px;">Dikirim</div>
        </a>
        <a href="/user/transactions?status=Completed" style="background:#dcfce7; padding:14px 8px; border-radius:10px; text-decoration:none; display:block; border:1px solid #bbf7d0;">
            <div style="font-size:1.5rem; font-weight:800; color:#15803d;">{{ $ordCompleted }}</div>
            <div style="font-size:0.72rem; font-weight:700; color:#16a34a; margin-top:4px;">Selesai</div>
        </a>
    </div>
</div>

<!-- ============ 2 KOLOM: PESANAN TERBARU & REKOMENDASI BUKU ============ -->
<div class="dashboard-2col">
    <!-- Kolom Kiri: Pesanan Terakhir -->
    <div class="panel" style="margin:0;">
        <div class="panel-header flex-between mb-3" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px;">
            <h3 class="panel-title" style="margin:0; font-size:1.05rem;">🧾 Pesanan Terakhir</h3>
            <a href="/user/transactions" style="font-size:0.8rem; color:var(--primary); font-weight:600; text-decoration:none; white-space:nowrap;">Selengkapnya &rarr;</a>
        </div>

        @if($recentOrders->isEmpty())
            <div style="text-align:center; padding:32px 16px; color:var(--text-muted);">
                <div style="font-size:2.5rem; margin-bottom:8px;">🛍️</div>
                <strong style="color:var(--text-main);">Belum Ada Pesanan</strong>
                <p style="font-size:0.85rem; margin:4px 0 16px;">Anda belum melakukan pembelian buku di PustakaMart.</p>
                <a href="/user/catalog" class="btn btn-primary btn-sm">Mulai Belanja</a>
            </div>
        @else
            <div style="display:flex; flex-direction:column; gap:12px;">
                @foreach($recentOrders as $ro)
                    <div style="background:#f8fafc; border:1px solid var(--border); border-radius:8px; padding:12px; display:flex; justify-content:space-between; align-items:center; gap:8px; width:100%; box-sizing:border-box;">
                        <div style="min-width:0; flex:1;">
                            <strong style="font-size:0.85rem; color:var(--text-main); font-family:monospace; display:block; word-break:break-all;">
                                {{ $ro->invoice_code ?? $ro->invoice_number }}
                            </strong>
                            <div style="font-size:0.72rem; color:var(--text-muted); margin-top:2px;">
                                {{ date('d M Y, H:i', strtotime($ro->created_at)) }} • {{ $ro->payment_method }}
                            </div>
                            <div style="font-size:0.85rem; font-weight:800; color:var(--primary); margin-top:3px;">
                                Rp {{ number_format($ro->total ?? $ro->total_amount, 0, ',', '.') }}
                            </div>
                        </div>
                        <div style="display:flex; flex-direction:column; align-items:flex-end; gap:6px; flex-shrink:0;">
                            <span class="status-badge" style="font-size:0.72rem; font-weight:700; padding:3px 8px; border-radius:14px; white-space:nowrap;
                                @if($ro->status === 'Waiting Confirmation') background:#ffedd5; color:#c2410c;
                                @elseif($ro->status === 'Processing') background:#fef3c7; color:#92400e;
                                @elseif($ro->status === 'Shipped') background:#e0e7ff; color:#3730a3;
                                @elseif($ro->status === 'Completed') background:#dcfce7; color:#15803d;
                                @elseif($ro->status === 'Cancelled') background:#fee2e2; color:#991b1b;
                                @else background:#fee2e2; color:#991b1b; @endif">
                                {{ $ro->status }}
                            </span>
                            <a href="/user/transactions/{{ $ro->id }}" class="btn btn-outline btn-sm" style="font-size:0.72rem; padding:3px 8px; white-space:nowrap;">Detail &rarr;</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Kolom Kanan: Rekomendasi Buku Siap Beli -->
    <div class="panel" style="margin:0;">
        <div class="panel-header flex-between mb-3">
            <h3 class="panel-title" style="margin:0;">✨ Rekomendasi Buku</h3>
            <a href="/user/catalog" style="font-size:0.85rem; color:var(--primary); font-weight:600; text-decoration:none;">Katalog &rarr;</a>
        </div>

        <div style="display:flex; flex-direction:column; gap:12px;">
            @foreach($recommendedBooks as $rb)
                <div style="display:flex; gap:12px; align-items:center; background:#f8fafc; border:1px solid var(--border); border-radius:8px; padding:10px;">
                    <div style="width:48px; height:68px; border-radius:4px; overflow:hidden; background:#eee; flex-shrink:0; border:1px solid var(--border);">
                        @if($rb->cover)
                            <img src="{{ asset('uploads/covers/' . $rb->cover) }}" alt="{{ $rb->title }}" style="width:100%; height:100%; object-fit:cover;">
                        @else
                            <div style="display:flex; align-items:center; justify-content:center; height:100%; font-size:1.2rem;">📖</div>
                        @endif
                    </div>
                    <div style="flex:1; min-width:0;">
                        <strong style="font-size:0.88rem; color:var(--text-main); display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            {{ $rb->title }}
                        </strong>
                        <div style="font-size:0.75rem; color:var(--text-muted); margin-top:2px;">{{ $rb->author }}</div>
                        <div style="font-size:0.85rem; font-weight:800; color:var(--primary); margin-top:4px;">
                            Rp {{ number_format($rb->price, 0, ',', '.') }}
                        </div>
                    </div>
                    <div>
                        <a href="/book/{{ $rb->id }}" class="btn btn-outline btn-sm" style="font-size:0.75rem; padding:4px 8px;">Lihat</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
.status-grid-4 {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    text-align: center;
    width: 100%;
}
.dashboard-2col {
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    gap: 20px;
    width: 100%;
}
@media (max-width: 860px) {
    .dashboard-2col {
        grid-template-columns: 100% !important;
        gap: 16px !important;
    }
    .status-grid-4 {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 8px !important;
    }
}
</style>
@endsection
