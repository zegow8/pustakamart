@extends('layouts.admin')

@section('title', 'Kelola Pengguna - PustakaMart')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Kelola Pelanggan</h1>
        <p class="text-muted">Daftar akun pelanggan toko buku online PustakaMart.</p>
    </div>
</div>

<div class="panel">
    <!-- FILTER & PENCARIAN -->
    <form method="GET" action="/admin/users" style="margin-bottom: 20px;">
        <div style="display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end;">
            <div style="flex:1; min-width:220px;">
                <label class="form-label" style="font-size:0.8rem; margin-bottom:4px;">Cari Pelanggan</label>
                <input type="text" name="q" class="form-control" placeholder="Ketik nama, email, NIK, atau nomor HP..." value="{{ request('q') }}">
            </div>
            <div style="display:flex; gap:6px;">
                <button type="submit" class="btn btn-primary" style="padding:10px 18px;">Cari</button>
                @if(request('q'))
                    <a href="/admin/users" class="btn btn-outline" style="padding:10px 14px;">Reset</a>
                @endif
            </div>
        </div>
    </form>

    <div style="margin-bottom:12px; font-size:0.85rem; color:var(--text-muted);">
        Total: <strong>{{ $users->total() }}</strong> pengguna terdaftar
    </div>
    <div style="overflow-x:auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>Pelanggan & Kontak</th>
                    <th>Alamat Pengiriman</th>
                    <th>Aktivitas Pesanan</th>
                    <th>Status Akun</th>
                    <th style="text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                <tr>
                    <td style="vertical-align:top;">
                        <strong style="font-size:0.95rem; color:var(--text-main);">{{ $u->name }}</strong>
                        <div style="font-size:0.8rem; color:var(--text-muted); margin-top:2px;">Email: {{ $u->email }}</div>
                        @if($u->nik)
                            <div style="font-size:0.75rem; color:var(--text-muted); font-family:monospace;">NIK: {{ $u->nik }}</div>
                        @endif
                        <div style="font-size:0.75rem; color:var(--text-muted);">HP: {{ $u->phone ?? '-' }}</div>
                        <div style="font-size:0.7rem; color:#94a3b8; margin-top:4px;">Terdaftar: {{ date('d M Y', strtotime($u->created_at)) }}</div>
                    </td>

                    <td style="vertical-align:top; max-width:220px;">
                        <div style="font-size:0.85rem; color:var(--text-muted); line-height: 1.4;">
                            {{ $u->address ?? '-' }}
                        </div>
                    </td>

                    <td style="vertical-align:top;">
                        <span style="font-size:0.78rem; background:#eff6ff; color:#1e40af; font-weight:700; padding:4px 10px; border-radius:12px; border:1px solid #bfdbfe; display:inline-block;">
                            🛍️ {{ $u->orders_count ?? 0 }} Transaksi Pesanan
                        </span>
                    </td>

                    <!-- STATUS USER -->
                    <td style="vertical-align:top;">
                        @if($u->is_active)
                            <span class="status-badge" style="background:#ecfdf5; color:#047857;">Aktif</span>
                        @else
                            <span class="status-badge" style="background:#fef2f2; color:#b91c1c;">Nonaktif</span>
                        @endif
                    </td>

                    <!-- AKSI -->
                    <td style="vertical-align:top; text-align:right;">
                        <div style="display:inline-flex; gap:6px;">
                            <a href="/admin/users/{{ $u->id }}" class="btn btn-outline btn-sm">Detail</a>
                            <form action="/admin/users/reset-password/{{ $u->id }}" method="POST" style="display:inline;" onsubmit="return confirm('Reset password pengguna ini menjadi password123?')">
                                @csrf
                                <button type="submit" class="btn btn-outline btn-sm" title="Reset Password">Reset Pass</button>
                            </form>
                            <form action="/admin/users/toggle-active" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="id" value="{{ $u->id }}">
                                <input type="hidden" name="is_active" value="{{ $u->is_active ? 0 : 1 }}">
                                <button type="submit" class="btn {{ $u->is_active ? 'btn-danger' : 'btn-primary' }} btn-sm" onclick="return confirm('Ubah status aktif akun ini?')">
                                    {{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted" style="padding:32px 0;">Tidak ada pengguna ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $users->links() }}
    </div>
</div>
@endsection
