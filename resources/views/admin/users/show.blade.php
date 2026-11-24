@extends('layouts.admin')

@section('title', 'Detail Pelanggan - ' . $user->name)

@section('content')
<div class="page-header flex-between" style="margin-bottom:20px;">
    <div>
        <a href="/admin/users" class="btn btn-outline btn-sm" style="margin-bottom:8px; display:inline-flex; align-items:center; gap:6px;">
            <span>&larr;</span> Kembali ke Daftar Pelanggan
        </a>
        <h1 class="page-title" style="margin:0;">Detail Profil & Transaksi Pelanggan</h1>
        <p class="text-muted" style="margin:4px 0 0;">Informasi akun dan histori pembelian buku di PustakaMart.</p>
    </div>
</div>

<!-- ============ KARTU PROFIL USER ============ -->
<div class="panel" style="margin-bottom:24px; padding:24px;">
    <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:16px;">
        <div style="display:flex; gap:20px; align-items:flex-start;">
            <div style="width:64px; height:64px; border-radius:50%; background:var(--primary); color:white; display:flex; align-items:center; justify-content:center; font-size:1.8rem; font-weight:800; flex-shrink:0;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                    <h2 style="font-size:1.4rem; font-weight:800; margin:0; color:var(--text-main);">{{ $user->name }}</h2>
                    @if($user->is_active)
                        <span class="status-badge" style="background:#ecfdf5; color:#047857;">Aktif</span>
                    @else
                        <span class="status-badge" style="background:#fef2f2; color:#b91c1c;">Nonaktif</span>
                    @endif
                </div>
                <div style="font-size:0.9rem; color:var(--text-muted); margin-top:4px;">
                    Email: <strong>{{ $user->email }}</strong>
                </div>
                <div style="font-size:0.85rem; color:var(--text-muted); margin-top:2px;">
                    No. Handphone / WhatsApp: <strong>{{ $user->phone ?? '-' }}</strong>
                </div>
                <div style="font-size:0.85rem; color:var(--text-muted); margin-top:2px;">
                    NIK KTP: <strong>{{ $user->nik ?? '-' }}</strong>
                </div>
                <div style="font-size:0.85rem; color:var(--text-muted); margin-top:2px;">
                    Alamat Pengiriman: <strong>{{ $user->address ?? '-' }}</strong>
                </div>
            </div>
        </div>

        <div style="display:flex; gap:8px;">
            <form action="/admin/users/reset-password/{{ $user->id }}" method="POST" onsubmit="return confirm('Reset password user ini menjadi password123?')">
                @csrf
                <button type="submit" class="btn btn-outline btn-sm">Reset Password</button>
            </form>
            <form action="/admin/users/toggle-active" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $user->id }}">
                <input type="hidden" name="is_active" value="{{ $user->is_active ? 0 : 1 }}">
                <button type="submit" class="btn {{ $user->is_active ? 'btn-danger' : 'btn-primary' }} btn-sm" onclick="return confirm('Ubah status aktif akun ini?')">
                    {{ $user->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}
                </button>
            </form>
        </div>
    </div>
</div>

<!-- ============ 2 KOTAK RINGKASAN AKTIVITAS ============ -->
<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap:16px; margin-bottom:24px;">
    <!-- Total Belanja -->
    <div class="panel" style="margin:0; padding:20px; border-left:4px solid #10b981;">
        <div class="text-muted" style="font-size:0.8rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em;">TOTAL BELANJA BUKU</div>
        <div style="font-size:1.6rem; font-weight:800; color:#059669; margin-top:6px;">
            Rp {{ number_format($totalSpent, 0, ',', '.') }}
        </div>
        <div style="font-size:0.8rem; color:var(--text-muted); margin-top:4px;">
            Akumulasi transaksi berhasil
        </div>
    </div>

    <!-- Total Pesanan -->
    <div class="panel" style="margin:0; padding:20px; border-left:4px solid var(--primary);">
        <div class="text-muted" style="font-size:0.8rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em;">TOTAL TRANSAKSI PESANAN</div>
        <div style="font-size:1.6rem; font-weight:800; color:var(--primary); margin-top:6px;">
            {{ $totalOrdersCount }} Pesanan
        </div>
        <div style="font-size:0.8rem; color:var(--text-muted); margin-top:4px;">
            Histori pesanan sejak pendaftaran
        </div>
    </div>
</div>

<!-- ============ TABEL RIWAYAT PEMBELIAN BUKU ============ -->
<div class="panel" style="margin-bottom:24px; padding:0; overflow:hidden;">
    <div class="panel-header flex-between" style="padding:16px 20px; border-bottom:1px solid var(--border); background:#f8fafc;">
        <div>
            <h3 class="panel-title" style="margin:0; font-size:1.1rem; font-weight:700;">🛒 Riwayat Pembelian Buku ({{ $user->orders->count() }})</h3>
            <p class="text-muted" style="font-size:0.8rem; margin:2px 0 0;">Semua transaksi pembelian buku fisik oleh pelanggan ini</p>
        </div>
    </div>
    <div style="overflow-x:auto;">
        <table class="table" style="margin:0;">
            <thead>
                <tr>
                    <th>Invoice & Waktu</th>
                    <th>Daftar Buku yang Dibeli</th>
                    <th>Total Bayar</th>
                    <th>Status Pesanan</th>
                    <th style="text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($user->orders as $o)
                <tr>
                    <td style="vertical-align:top; white-space:nowrap;">
                        <a href="/admin/orders/{{ $o->id }}" style="font-weight:700; color:var(--primary); text-decoration:none;">
                            #{{ $o->invoice_code }}
                        </a>
                        <div style="font-size:0.75rem; color:var(--text-muted); margin-top:4px;">
                            {{ $o->created_at->format('d M Y, H:i') }}
                        </div>
                    </td>
                    <td style="vertical-align:top;">
                        @if($o->details && $o->details->count() > 0)
                            <div style="display:flex; flex-direction:column; gap:8px;">
                                @foreach($o->details as $d)
                                    <div style="display:flex; align-items:center; gap:10px;">
                                        <div style="width:32px; height:44px; border-radius:4px; overflow:hidden; background:#eee; flex-shrink:0; border:1px solid var(--border);">
                                            @if($d->book && $d->book->cover)
                                                <img src="{{ asset('uploads/covers/' . $d->book->cover) }}" alt="{{ $d->book->title }}" style="width:100%; height:100%; object-fit:cover;">
                                            @else
                                                <div style="display:flex; align-items:center; justify-content:center; height:100%; font-size:0.9rem;">📚</div>
                                            @endif
                                        </div>
                                        <div style="font-size:0.85rem;">
                                            <strong>{{ $d->title_snapshot ?? $d->book?->title ?? 'Buku Dihapus' }}</strong>
                                            <div style="font-size:0.75rem; color:var(--text-muted);">
                                                {{ $d->qty }} pcs &times; Rp {{ number_format($d->price_snapshot ?? $d->price ?? $d->book?->price ?? 0, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <span class="text-muted" style="font-size:0.85rem;">-</span>
                        @endif
                    </td>
                    <td style="vertical-align:top; font-weight:700; color:#059669; font-size:0.95rem;">
                        Rp {{ number_format($o->total, 0, ',', '.') }}
                    </td>
                    <td style="vertical-align:top;">
                        @if($o->status === 'Waiting Payment')
                            <span class="status-badge" style="background:#fef3c7; color:#92400e;">Menunggu Bayar</span>
                        @elseif($o->status === 'Waiting Confirmation')
                            <span class="status-badge" style="background:#e0e7ff; color:#3730a3;">Menunggu Konfirmasi</span>
                        @elseif($o->status === 'Processing')
                            <span class="status-badge" style="background:#dbeafe; color:#1e40af;">Sedang Dikemas</span>
                        @elseif($o->status === 'Shipped')
                            <span class="status-badge" style="background:#fef9c3; color:#854d0e;">Sedang Dikirim</span>
                        @elseif($o->status === 'Completed' || $o->status === 'Delivered')
                            <span class="status-badge" style="background:#dcfce7; color:#166534; font-weight:700;">Selesai</span>
                        @elseif($o->status === 'Cancelled')
                            <span class="status-badge" style="background:#f1f5f9; color:#475569; border:1px solid #cbd5e1;">Dibatalkan</span>
                        @elseif($o->status === 'Rejected')
                            <span class="status-badge" style="background:#fee2e2; color:#991b1b; border:1px solid #fca5a5;">Ditolak</span>
                        @else
                            <span class="status-badge">{{ $o->status }}</span>
                        @endif
                    </td>
                    <td style="vertical-align:top; text-align:right;">
                        <a href="/admin/orders/{{ $o->id }}" class="btn btn-outline btn-sm">Lihat Order</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center; padding:32px; color:var(--text-muted);">
                        Pelanggan ini belum pernah melakukan pembelian buku.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
