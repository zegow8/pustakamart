@extends('layouts.admin')
@section('title', 'Detail Pesanan')
@section('content')
<style>
    .order-detail-header h1 {
        font-size: clamp(1.2rem, 4.5vw, 1.75rem);
        font-weight: 800;
        color: #1e293b;
        word-break: break-all;
        line-height: 1.3;
        margin: 6px 0 4px 0;
    }

    @media (max-width: 868px) {
        .admin-order-detail-grid { grid-template-columns: 1fr !important; gap: 16px !important; }
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
    <a href="/admin/orders" style="font-weight:600; font-size:0.88rem; text-decoration:none; color:var(--primary); display:inline-flex; align-items:center; gap:6px; margin-bottom:4px;">
        &larr; Kembali ke Daftar Pembelian
    </a>
    <div style="display:flex; justify-content:space-between; align-items:flex-end; flex-wrap:wrap; gap:12px;">
        <div>
            <h1>Pesanan: {{ $order->invoice_code }}</h1>
            
        </div>
        <a href="/admin/orders/{{ $order->id }}/print" target="_blank" class="btn btn-outline" style="font-weight:700; display:inline-flex; align-items:center; gap:8px; border-color:#2563eb; color:#2563eb; background:#eff6ff;">
            <span style="font-size:1.1rem;">🖨️</span> Cetak Invoice / Struk
        </a>
    </div>
    <p class="text-muted" style="margin:0; font-size:0.85rem;">Tanggal Transaksi: {{ date('d M Y H:i', strtotime($order->created_at)) }} WIB</p>
</div>

<div style="display:grid; grid-template-columns: 2fr 1fr; gap:24px;" class="admin-order-detail-grid">
    <!-- DAFTAR ITEM DENGAN PREVIEW COVER BUKU -->
    <div>
        <div class="panel" style="padding:0; overflow:hidden; margin-bottom:20px;">
            <div style="padding:14px 18px; background:#f8fafc; border-bottom:1px solid var(--border); font-weight:700; font-size:0.95rem;">
                📚 Rincian Buku yang Dipesan
            </div>

            <!-- 1. TAMPILAN DESKTOP (TABEL) -->
            <div class="order-table-desktop">
                <table class="table" style="margin:0;">
                    <thead>
                        <tr>
                            <th style="width:70px;">Cover</th>
                            <th>Buku</th>
                            <th>Harga Satuan</th>
                            <th style="text-align:center;">Qty</th>
                            <th style="text-align:right;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->details as $d)
                        <tr>
                            <td>
                                <div style="width:50px; height:70px; border-radius:6px; overflow:hidden; background:#eee; border:1px solid var(--border); box-shadow:0 1px 3px rgba(0,0,0,0.08);">
                                    @if($d->book && $d->book->cover)
                                        <img src="{{ asset('uploads/covers/' . $d->book->cover) }}" alt="{{ $d->title_snapshot }}" style="width:100%; height:100%; object-fit:cover;">
                                    @else
                                        <div style="display:flex; align-items:center; justify-content:center; height:100%; font-size:1.5rem;">📖</div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <strong style="font-size:0.95rem;">{{ $d->title_snapshot }}</strong>
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

            <!-- 2. TAMPILAN MOBILE (KARTU PRODUK) -->
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
                $adminSubtotal = $order->details->sum('subtotal');
                $adminOngkir = max(0, $order->total - $adminSubtotal);
            @endphp
            <div style="padding:18px 24px; background:#fafafa; border-top:1px solid var(--border); display:flex; flex-direction:column; gap:10px;">
                <div style="display:flex; justify-content:space-between; font-size:0.9rem; color:var(--text-muted);">
                    <span>Subtotal Produk:</span>
                    <strong style="color:var(--text-main);">Rp {{ number_format($adminSubtotal, 0, ',', '.') }}</strong>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:0.9rem; color:var(--text-muted);">
                    <span>Biaya Ongkos Kirim:</span>
                    <strong style="color:var(--text-main);">Rp {{ number_format($adminOngkir, 0, ',', '.') }}</strong>
                </div>
                <div style="display:flex; justify-content:space-between; align-items:center; padding-top:14px; margin-top:4px; border-top:2px dashed #cbd5e1;">
                    <span style="font-size:1.05rem; font-weight:800; color:var(--text-main);">Total Pembayaran :</span>
                    <span style="font-size:1.4rem; font-weight:900; color:#1e40af; letter-spacing:-0.02em;">
                        Rp {{ number_format($order->total, 0, ',', '.') }}
                    </span>
                </div>
            </div>

        </div>

        <!-- INFORMASI PENGIRIMAN & PEMBELI -->
        <div class="panel">
            <h3 class="panel-title mb-4" style="font-size:1rem;">📍 Informasi Pengiriman & Pembeli</h3>
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:16px;">
                <div>
                    <div style="font-size:0.75rem; text-transform:uppercase; font-weight:700; color:var(--text-muted);">Nama Penerima</div>
                    <div style="font-size:0.95rem; font-weight:700; margin-top:2px;">{{ $order->receiver_name ?? ($order->user->name ?? '-') }}</div>
                </div>
                <div>
                    <div style="font-size:0.75rem; text-transform:uppercase; font-weight:700; color:var(--text-muted);">Email & No. HP</div>
                    <div style="font-size:0.88rem; margin-top:2px; word-break:break-all;">{{ $order->user->email ?? '-' }} | {{ $order->receiver_phone ?? ($order->user->phone ?? '-') }}</div>
                </div>
                <div>
                    <div style="font-size:0.75rem; text-transform:uppercase; font-weight:700; color:var(--text-muted);">Metode Pembayaran</div>
                    <div style="font-size:0.92rem; font-weight:700; color:var(--primary); margin-top:2px;">{{ $order->payment_method ?? 'Transfer' }}</div>
                </div>
                <div style="grid-column: 1 / -1;">
                    <div style="font-size:0.75rem; text-transform:uppercase; font-weight:700; color:var(--text-muted);">Alamat Pengiriman</div>
                    <div style="font-size:0.9rem; margin-top:2px; background:#f8fafc; padding:12px; border-radius:6px; border:1px solid var(--border); line-height:1.5; word-break:break-word;">
                        {{ $order->shipping_address ?? 'Tidak ada alamat tercatat' }}
                    </div>
                </div>
                <div style="grid-column: 1 / -1;">
                    <div style="font-size:0.75rem; text-transform:uppercase; font-weight:700; color:var(--text-muted);">📝 Pesan / Catatan untuk Kurir</div>
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

    <!-- UPDATE STATUS & RESI -->
    <div>
        <div class="panel">
            <h3 class="panel-title mb-4" style="font-size:1rem;">⚙️ Kelola Status Pembayaran &amp; Pesanan</h3>
            
            <div style="margin-bottom:16px; padding:12px; background:#f8fafc; border-radius:8px; border:1px solid var(--border);">
                <div style="font-size:0.72rem; color:var(--text-muted); font-weight:700; text-transform:uppercase;">Status Saat Ini</div>
                <div style="margin-top:4px;">
                    @if($order->status === 'Waiting Confirmation')
                        <span class="status-badge" style="background:#ffedd5; color:#c2410c; font-size:0.85rem; font-weight:800; border:1px solid #fdba74;">
                            ⏳ Menunggu Konfirmasi Admin
                        </span>
                    @elseif($order->status === 'Processing')
                        <span class="status-badge" style="background:#fef3c7; color:#92400e;">Dikemas (Disiapkan)</span>
                    @elseif($order->status === 'Shipped')
                        <span class="status-badge" style="background:#e0e7ff; color:#3730a3;">Sedang Dikirim</span>
                    @elseif($order->status === 'Completed')
                        <span class="status-badge" style="background:#dcfce7; color:#166534;">Selesai Diterima</span>
                    @elseif($order->status === 'Cancelled')
                        <span class="status-badge" style="background:#f1f5f9; color:#475569;">Dibatalkan Pembeli</span>
                    @elseif($order->status === 'Rejected')
                        <span class="status-badge" style="background:#fee2e2; color:#991b1b;">Ditolak Admin</span>
                    @endif
                </div>
            </div>

            <!-- BUKTI TRANSFER PEMBAYARAN -->
            <div style="margin-bottom:18px; padding:14px; background:#f8fafc; border-radius:8px; border:1px solid var(--border);">
                <div style="font-size:0.75rem; color:var(--text-muted); font-weight:700; text-transform:uppercase; margin-bottom:8px; display:flex; justify-content:space-between; align-items:center;">
                    <span>📸 Bukti Transfer Pembayaran</span>
                    @if($order->payment_receipt)
                        <span style="font-size:0.7rem; background:#dcfce7; color:#166534; padding:2px 8px; border-radius:4px; font-weight:700;">Terunggah</span>
                    @else
                        <span style="font-size:0.7rem; background:#fee2e2; color:#991b1b; padding:2px 8px; border-radius:4px; font-weight:700;">Belum Ada</span>
                    @endif
                </div>
                @if($order->payment_receipt)
                    @php
                        $ext = pathinfo($order->payment_receipt, PATHINFO_EXTENSION);
                        $isPdf = strtolower($ext) === 'pdf';
                    @endphp
                    @if($isPdf)
                        <div style="padding:10px 0;">
                            <a href="{{ asset('uploads/receipts/' . $order->payment_receipt) }}" target="_blank" class="btn btn-outline btn-sm" style="font-weight:700; color:#2563eb; border-color:#2563eb; background:#eff6ff; display:inline-flex; align-items:center; gap:6px;">
                                📄 Buka Dokumen Bukti Transfer (PDF) &rarr;
                            </a>
                        </div>
                    @else
                        <div style="border-radius:6px; overflow:hidden; border:1px solid var(--border); background:white; margin-bottom:8px; text-align:center; padding:4px;">
                            <a href="{{ asset('uploads/receipts/' . $order->payment_receipt) }}" target="_blank" title="Klik untuk memperbesar bukti transfer">
                                <img src="{{ asset('uploads/receipts/' . $order->payment_receipt) }}" alt="Bukti Transfer" style="max-width:100%; max-height:220px; object-fit:contain; border-radius:4px; display:block; margin:0 auto;">
                            </a>
                        </div>
                        <div style="text-align:center;">
                            <a href="{{ asset('uploads/receipts/' . $order->payment_receipt) }}" target="_blank" style="font-size:0.8rem; font-weight:700; color:var(--primary); text-decoration:none; display:inline-flex; align-items:center; gap:4px;">
                                🔍 Klik untuk Lihat Ukuran Penuh &rarr;
                            </a>
                        </div>
                    @endif
                @else
                    <div style="font-size:0.82rem; color:#94a3b8; font-style:italic; padding:6px 0;">
                        Tidak ada file bukti transfer yang dilampirkan.
                    </div>
                @endif
            </div>

            <!-- Form Update Status Sesuai Alur Pasti -->
            @if(!in_array($order->status, ['Completed', 'Rejected', 'Cancelled']))
                <form action="/admin/orders/update/{{ $order->id }}" method="POST">
                    @csrf

                    @if($order->status === 'Waiting Confirmation')
                        <!-- TAHAP 1: Menunggu Konfirmasi -> Pilihan cuma 2: Dikemas atau Tolak -->
                        <div class="form-group mb-3">
                            <label class="form-label" style="font-size:0.85rem; font-weight:700;">Tindakan Konfirmasi Pesanan</label>
                            <select name="status" class="form-control" style="font-size:0.9rem;" required>
                                <option value="" disabled selected>-- Pilih: Dikemas atau Ditolak --</option>
                                <option value="Processing">📦 Dikemas (ACC Pembayaran & Siapkan Buku)</option>
                                <option value="Rejected">❌ Tolak Pesanan / Pembayaran Tidak Valid</option>
                            </select>
                            <small class="text-muted" style="display:block; margin-top:4px; font-size:0.75rem;">
                                Pilih <strong>Dikemas</strong> untuk menyetujui pesanan, atau <strong>Tolak</strong> jika bukti pembayaran tidak sesuai.
                            </small>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block" style="font-weight:700; height:42px;">
                            Simpan Keputusan Admin
                        </button>

                    @elseif($order->status === 'Processing')
                        <!-- TAHAP 2: Sedang Dikemas -> Pilihan cuma 1: Dikirim -->
                        <div class="form-group mb-3">
                            <label class="form-label" style="font-size:0.85rem; font-weight:700;">Tindakan Selanjutnya</label>
                            <select name="status" class="form-control" style="font-size:0.9rem;" required>
                                <option value="Shipped" selected>🚚 Dikirim (Serahkan ke Kurir & Input Resi)</option>
                            </select>
                            <small class="text-muted" style="display:block; margin-top:4px; font-size:0.75rem;">
                                Buku telah selesai dipacking rapi dan siap dikirimkan kepada pembeli.
                            </small>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label" style="font-size:0.85rem; font-weight:700;">Nomor Resi Pengiriman <span style="color:var(--danger);">*</span></label>
                            <input type="text" name="tracking_number" class="form-control" style="font-family:monospace; font-weight:700;" value="{{ $order->tracking_number }}" placeholder="Contoh: JNE8291048290" required>
                            <small class="text-muted" style="display:block; margin-top:4px; font-size:0.75rem;">Wajib memasukkan nomor resi ekspedisi kurir.</small>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block" style="font-weight:700; height:42px; background:#2563eb; border-color:#2563eb;">
                            🚚 Konfirmasi Paket Telah Dikirim
                        </button>

                    @elseif($order->status === 'Shipped')
                        <!-- TAHAP 3: Sedang Dikirim -> Pilihan cuma 1: Selesai -->
                        <div class="form-group mb-3">
                            <label class="form-label" style="font-size:0.85rem; font-weight:700;">Tindakan Akhir</label>
                            <select name="status" class="form-control" style="font-size:0.9rem;" required>
                                <option value="Completed" selected>✅ Selesai (Pesanan Telah Sampai & Diterima)</option>
                            </select>
                            <small class="text-muted" style="display:block; margin-top:4px; font-size:0.75rem;">
                                Nomor Resi: <strong>{{ $order->tracking_number ?? '-' }}</strong>. Tandai selesai jika barang sudah diterima pembeli.
                            </small>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block" style="font-weight:700; height:42px; background:#16a34a; border-color:#16a34a;">
                            ✅ Tandai Pesanan Selesai
                        </button>
                    @endif
                </form>
            @else
                <!-- STATUS FINAL (Selesai, Ditolak, atau Dibatalkan) -->
                <div style="font-size:0.85rem; color:var(--text-muted); text-align:center; padding:18px 14px; background:#f8fafc; border-radius:8px; border:1px solid var(--border);">
                    @if($order->status === 'Completed')
                        <div style="font-size:1.6rem; margin-bottom:6px;">🎉</div>
                        <strong style="color:#166534; font-size:0.95rem; display:block;">Pesanan Telah Selesai</strong>
                        <p style="margin:4px 0 0; font-size:0.8rem; color:#475569;">Buku telah berhasil diterima oleh pembeli. Transaksi ini selesai dan status sudah final.</p>
                    @elseif($order->status === 'Rejected')
                        <div style="font-size:1.6rem; margin-bottom:6px;">❌</div>
                        <strong style="color:#991b1b; font-size:0.95rem; display:block;">Pesanan Telah Ditolak</strong>
                        <p style="margin:4px 0 0; font-size:0.8rem; color:#475569;">Pesanan ditolak oleh admin. Stok buku telah otomatis dikembalikan ke katalog toko.</p>
                    @elseif($order->status === 'Cancelled')
                        <div style="font-size:1.6rem; margin-bottom:6px;">⚠️</div>
                        <strong style="color:#64748b; font-size:0.95rem; display:block;">Pesanan Dibatalkan</strong>
                        <p style="margin:4px 0 0; font-size:0.8rem; color:#475569;">Pesanan dibatalkan oleh pembeli sebelum diproses.</p>
                    @endif
                    <div style="margin-top:12px; font-size:0.75rem; color:#94a3b8; font-weight:700;">🔒 Alur status pesanan telah selesai dan ditutup.</div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
