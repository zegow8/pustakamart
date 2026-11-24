@extends('layouts.admin')

@section('title', 'Dashboard Admin - PustakaMart')

@section('content')
<!-- Header Dashboard -->
<div class="flex-between mb-4" style="flex-wrap:wrap; gap:16px;">
    <div>
        <h1 style="margin-bottom:4px; font-size:1.6rem;">Dashboard Toko Buku PustakaMart</h1>
        <p class="text-muted" style="margin:0; font-size:0.9rem;">Ringkasan inventaris buku, monitor pesanan pembeli, dan aktivitas pengguna</p>
    </div>
    <div style="display:flex; gap:10px; flex-wrap:wrap;">
        <a href="/admin/books/create" class="btn btn-primary btn-sm">+ Tambah Buku Baru</a>
        <a href="/admin/orders" class="btn btn-outline btn-sm">📦 Kelola Pesanan</a>
    </div>
</div>

<!-- ============ 4 KARTU STATISTIK UTAMA ============ -->
<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap:16px; margin-bottom:24px;">
    <div class="panel" style="margin:0; padding:18px 20px; border-left:4px solid var(--primary);">
        <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted); text-transform:uppercase;">TOTAL KOLEKSI BUKU</div>
        <div style="font-size:1.8rem; font-weight:800; color:var(--primary); margin-top:4px;">{{ $totalBooks }}</div>
        <div style="font-size:0.8rem; color:var(--text-muted); margin-top:2px;">{{ $totalStock }} eksemplar tersedia</div>
    </div>
    <div class="panel" style="margin:0; padding:18px 20px; border-left:4px solid #059669;">
        <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted); text-transform:uppercase;">TOTAL PESANAN BUKU</div>
        <div style="font-size:1.8rem; font-weight:800; color:#059669; margin-top:4px;">{{ $totalOrders }}</div>
        <div style="font-size:0.8rem; color:var(--text-muted); margin-top:2px;">Dari seluruh pengguna</div>
    </div>
    <div class="panel" style="margin:0; padding:18px 20px; border-left:4px solid #d97706;">
        <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted); text-transform:uppercase;">USER TERDAFTAR</div>
        <div style="font-size:1.8rem; font-weight:800; color:#d97706; margin-top:4px;">{{ $totalUsers }}</div>
        <div style="font-size:0.8rem; color:var(--text-muted); margin-top:2px;">Akun pembeli aktif</div>
    </div>
    <div class="panel" style="margin:0; padding:18px 20px; border-left:4px solid #7c3aed;">
        <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted); text-transform:uppercase;">TOTAL PENDAPATAN</div>
        <div style="font-size:1.4rem; font-weight:800; color:#7c3aed; margin-top:6px;">Rp {{ number_format($revenueAllTime, 0, ',', '.') }}</div>
        <div style="font-size:0.8rem; color:var(--text-muted); margin-top:2px;">Bulan ini: Rp {{ number_format($revenueThisMonth, 0, ',', '.') }}</div>
    </div>
</div>

<!-- ============ KOTAK STATUS PESANAN (KONTROL PENJUALAN) ============ -->
<div class="panel" style="margin-bottom:24px; padding:20px;">
    <div class="panel-header flex-between mb-3">
        <h3 class="panel-title" style="margin:0;">📦 Monitor Status Pesanan Pembeli</h3>
        <a href="/admin/orders" class="btn btn-outline btn-sm">Lihat Semua Pesanan &rarr;</a>
    </div>

    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap:12px; text-align:center;">
        <a href="/admin/orders?status=Waiting Confirmation" style="background:#ffedd5; padding:16px 10px; border-radius:10px; text-decoration:none; display:block; border:1px solid #fed7aa;">
            <div style="font-size:1.6rem; font-weight:800; color:#c2410c;">{{ $ordWaiting }}</div>
            <div style="font-size:0.75rem; font-weight:700; color:#ea580c; margin-top:4px;">Menunggu ACC Admin</div>
        </a>
        <a href="/admin/orders?status=Processing" style="background:#fef3c7; padding:16px 10px; border-radius:10px; text-decoration:none; display:block; border:1px solid #fde68a;">
            <div style="font-size:1.6rem; font-weight:800; color:#92400e;">{{ $ordProcessing }}</div>
            <div style="font-size:0.75rem; font-weight:700; color:#b45309; margin-top:4px;">Sedang Dikemas</div>
        </a>
        <a href="/admin/orders?status=Shipped" style="background:#e0e7ff; padding:16px 10px; border-radius:10px; text-decoration:none; display:block; border:1px solid #c7d2fe;">
            <div style="font-size:1.6rem; font-weight:800; color:#3730a3;">{{ $ordShipped }}</div>
            <div style="font-size:0.75rem; font-weight:700; color:#4338ca; margin-top:4px;">Dalam Pengiriman</div>
        </a>
        <a href="/admin/orders?status=Completed" style="background:#dcfce7; padding:16px 10px; border-radius:10px; text-decoration:none; display:block; border:1px solid #bbf7d0;">
            <div style="font-size:1.6rem; font-weight:800; color:#15803d;">{{ $ordCompleted }}</div>
            <div style="font-size:0.75rem; font-weight:700; color:#16a34a; margin-top:4px;">Pesanan Selesai</div>
        </a>
    </div>
</div>

<!-- ============ 2 KOLOM: PESANAN TERBARU & STOK RENDAH ============ -->
<div style="display:grid; grid-template-columns: 1.4fr 1fr; gap:24px;" class="admin-dash-grid">
    <!-- Kolom Kiri: Pesanan Masuk Terbaru -->
    <div class="panel" style="margin:0; padding:20px;">
        <div class="panel-header flex-between mb-3">
            <h3 class="panel-title" style="margin:0;">🛒 Pesanan Masuk Terbaru</h3>
            <a href="/admin/orders" style="font-size:0.85rem; color:var(--primary); font-weight:600; text-decoration:none;">Buka Data Pesanan &rarr;</a>
        </div>

        @if($recentOrders->isEmpty())
            <div style="text-align:center; padding:32px; color:var(--text-muted);">
                Belum ada transaksi pesanan yang masuk.
            </div>
        @else
            <div style="overflow-x:auto;">
                <table class="table" style="margin:0; font-size:0.85rem;">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Pemesan</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th style="text-align:right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $ro)
                        <tr>
                            <td>
                                <strong style="font-family:monospace; font-size:0.85rem; color:var(--text-main);">
                                    {{ $ro->invoice_code ?? $ro->invoice_number }}
                                </strong>
                                <div style="font-size:0.75rem; color:var(--text-muted);">{{ $ro->payment_method }}</div>
                            </td>
                            <td>
                                <div>{{ $ro->receiver_name }}</div>
                                <div style="font-size:0.75rem; color:var(--text-muted);">{{ $ro->receiver_phone }}</div>
                            </td>
                            <td style="font-weight:700; color:var(--primary);">
                                Rp {{ number_format($ro->total ?? $ro->total_amount, 0, ',', '.') }}
                            </td>
                            <td>
                                <span class="status-badge" style="font-size:0.7rem; font-weight:700;
                                    @if($ro->status === 'Waiting Confirmation') background:#ffedd5; color:#c2410c;
                                    @elseif($ro->status === 'Processing') background:#fef3c7; color:#92400e;
                                    @elseif($ro->status === 'Shipped') background:#e0e7ff; color:#3730a3;
                                    @elseif($ro->status === 'Completed') background:#dcfce7; color:#15803d;
                                    @else background:#fee2e2; color:#991b1b; @endif">
                                    {{ $ro->status }}
                                </span>
                            </td>
                            <td style="text-align:right;">
                                <a href="/admin/orders/{{ $ro->id }}" class="btn btn-outline btn-sm" style="font-size:0.75rem; padding:3px 8px;">Kelola</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Kolom Kanan: Peringatan Stok Rendah & User Baru -->
    <div style="display:flex; flex-direction:column; gap:20px;">
        <!-- Peringatan Stok Menipis -->
        <div class="panel" style="margin:0; padding:20px;">
            <div class="panel-header flex-between mb-2">
                <h3 class="panel-title" style="margin:0; font-size:1rem; color:#dc2626;">⚠️ Stok Buku Menipis (&lt; 5 Pcs)</h3>
                <a href="/admin/books" style="font-size:0.8rem; color:var(--primary); text-decoration:none;">Data Buku &rarr;</a>
            </div>
            
            @if($lowStockBooks->isEmpty())
                <div style="font-size:0.85rem; color:#15803d; padding:12px 0;">
                    ✅ Semua stok buku dalam kondisi aman.
                </div>
            @else
                <div style="display:flex; flex-direction:column; gap:8px;">
                    @foreach($lowStockBooks as $lb)
                    <div style="display:flex; justify-content:space-between; align-items:center; background:#fef2f2; border:1px solid #fecaca; border-radius:6px; padding:8px 12px; font-size:0.825rem;">
                        <div style="min-width:0; flex:1;">
                            <strong style="color:var(--text-main); display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $lb->title }}</strong>
                            <span style="font-size:0.75rem; color:var(--text-muted);">Rp {{ number_format($lb->price, 0, ',', '.') }}</span>
                        </div>
                        <div style="font-weight:800; color:#dc2626; margin-left:10px;">
                            Sisa: {{ $lb->stock_sale }} pcs
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- User Baru Bergabung -->
        <div class="panel" style="margin:0; padding:20px;">
            <div class="panel-header flex-between mb-2">
                <h3 class="panel-title" style="margin:0; font-size:1rem;">👥 User Baru Terdaftar</h3>
                <a href="/admin/users" style="font-size:0.8rem; color:var(--primary); text-decoration:none;">Semua User &rarr;</a>
            </div>

            <div style="display:flex; flex-direction:column; gap:8px;">
                @foreach($recentUsers as $ru)
                <div style="display:flex; justify-content:space-between; align-items:center; background:#f8fafc; border:1px solid var(--border); border-radius:6px; padding:8px 12px; font-size:0.825rem;">
                    <div>
                        <strong style="color:var(--text-main);">{{ $ru->name }}</strong>
                        <div style="font-size:0.75rem; color:var(--text-muted);">{{ $ru->email }}</div>
                    </div>
                    <div style="font-size:0.75rem; color:var(--text-muted);">
                        {{ date('d M Y', strtotime($ru->created_at)) }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 900px) {
    .admin-dash-grid { grid-template-columns: 1fr !important; }
}
</style>
@endsection
