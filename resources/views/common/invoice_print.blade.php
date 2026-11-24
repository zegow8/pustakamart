<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $order->invoice_code }} - PustakaMart</title>
    <link rel="icon" type="image/png" href="{{ asset('images/pustakamart.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: #1e293b;
            background: #f1f5f9;
            padding: 30px 16px;
            font-size: 14px;
            line-height: 1.5;
        }
        .invoice-card {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.06);
            padding: 40px;
            border: 1px solid #e2e8f0;
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 24px;
            margin-bottom: 24px;
        }
        .brand-title {
            font-size: 24px;
            font-weight: 800;
            color: #2563eb;
            letter-spacing: -0.02em;
        }
        .brand-sub {
            font-size: 12px;
            color: #64748b;
            margin-top: 4px;
            line-height: 1.4;
        }
        .invoice-meta {
            text-align: right;
        }
        .invoice-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 8px;
        }
        .badge-success { background: #dcfce7; color: #15803d; }
        .badge-warning { background: #fef3c7; color: #b45309; }
        .badge-info { background: #e0f2fe; color: #0369a1; }
        .badge-danger { background: #fee2e2; color: #b91c1c; }

        .invoice-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 28px;
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        .info-col h4 {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #64748b;
            margin-bottom: 8px;
            font-weight: 700;
        }
        .info-col p {
            margin-bottom: 4px;
            color: #1e293b;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        .table th {
            background: #f8fafc;
            text-align: left;
            padding: 12px 14px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #475569;
            border-bottom: 2px solid #e2e8f0;
        }
        .table td {
            padding: 14px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        .summary-box {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 32px;
        }
        .summary-table {
            width: 320px;
            border-collapse: collapse;
        }
        .summary-table td {
            padding: 8px 12px;
            font-size: 13px;
        }
        .summary-total {
            border-top: 2px solid #2563eb;
            font-size: 16px !important;
            font-weight: 800;
            color: #1e40af;
        }

        .invoice-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            border-top: 1px dashed #cbd5e1;
            padding-top: 24px;
            margin-top: 20px;
        }
        .signature-box {
            text-align: center;
            width: 200px;
        }
        .signature-line {
            margin-top: 60px;
            border-bottom: 1px solid #64748b;
        }

        .no-print {
            text-align: center;
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
            gap: 12px;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 700;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            border: none;
        }
        .btn-primary { background: #2563eb; color: #fff; }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-secondary { background: #64748b; color: #fff; }

        @media print {
            body { background: #ffffff; padding: 0; }
            .invoice-card { box-shadow: none; border: none; padding: 0; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" class="btn btn-primary">🖨️ Cetak Invoice (Print / Simpan PDF)</button>
        <button onclick="window.history.back()" class="btn btn-secondary">⬅️ Kembali</button>
    </div>

    <div class="invoice-card">
        <!-- HEADER -->
        <div class="invoice-header">
            <div style="display:flex; align-items:center; gap:16px;">
                <img src="{{ asset('images/pustakamart.png') }}" alt="Logo PustakaMart" style="height:60px; width:60px; object-fit:contain;">
                <div>
                    <div class="brand-title">PUSTAKAMART</div>
                    <div class="brand-sub">
                        <strong>Toko Buku Fisik Original &amp; Terpercaya</strong><br>
                        Jl. Ir. H. Juanda No. 84, Kota Sukabumi, Jawa Barat<br>
                        WhatsApp: 0815-4718-4307 | Website: pustakamart.id
                    </div>
                </div>
            </div>
            <div class="invoice-meta">
                <div class="invoice-badge {{ in_array($order->status, ['Processing', 'Shipped', 'Completed']) ? 'badge-success' : ($order->status === 'Waiting Confirmation' ? 'badge-warning' : 'badge-danger') }}">
                    {{ $order->status === 'Waiting Confirmation' ? 'Menunggu Konfirmasi' : ($order->status === 'Processing' ? 'Pembayaran Lunas (Dikemas)' : ($order->status === 'Shipped' ? 'Sedang Dikirim' : ($order->status === 'Completed' ? 'Pesanan Selesai' : $order->status))) }}
                </div>
                <div style="font-size: 16px; font-weight: 800; color: #0f172a;">{{ $order->invoice_code }}</div>
                <div style="font-size: 12px; color: #64748b; margin-top: 2px;">
                    Tanggal: {{ $order->created_at->format('d/m/Y H:i') }} WIB
                </div>
                @if($order->tracking_number)
                    <div style="font-size: 12px; color: #2563eb; font-weight: 700; margin-top: 4px;">
                        No. Resi: {{ $order->tracking_number }}
                    </div>
                @endif
            </div>
        </div>

        <!-- BUYER & SHIPPING INFO -->
        <div class="invoice-info-grid">
            <div class="info-col">
                <h4>Informasi Pembeli</h4>
                <p><strong>Nama:</strong> {{ $order->user->name ?? $order->receiver_name }}</p>
                <p><strong>Email:</strong> {{ $order->user->email ?? '-' }}</p>
                <p><strong>No. Telp / WA:</strong> {{ $order->user->phone ?? $order->receiver_phone ?? '-' }}</p>
                <p><strong>Metode Bayar:</strong> {{ $order->payment_method ?? 'Transfer Bank' }}</p>
            </div>
            <div class="info-col">
                <h4>Informasi Penerima &amp; Pengiriman</h4>
                <p><strong>Penerima:</strong> {{ $order->receiver_name }} ({{ $order->receiver_phone }})</p>
                <p><strong>Alamat Pengiriman:</strong></p>
                <p style="font-size: 12.5px; color: #334155;">{{ $order->shipping_address }}</p>
                @if($order->note)
                    <p style="margin-top: 6px; font-size: 12px; color: #1e40af; background: #eff6ff; padding: 4px 8px; border-radius: 4px;">
                        <strong>Pesan Kurir:</strong> {{ $order->note }}
                    </p>
                @endif
            </div>
        </div>

        <!-- ITEMS TABLE -->
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;">No</th>
                    <th>Judul Buku</th>
                    <th style="text-align: right; width: 130px;">Harga Satuan</th>
                    <th style="text-align: center; width: 80px;">Qty</th>
                    <th style="text-align: right; width: 140px;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->details as $idx => $detail)
                    <tr>
                        <td style="text-align: center; color: #64748b;">{{ $idx + 1 }}</td>
                        <td>
                            <div style="font-weight: 700; color: #0f172a;">{{ $detail->title_snapshot ?? ($detail->book->title ?? 'Buku') }}</div>
                            <div style="font-size: 12px; color: #64748b;">{{ $detail->book->author ?? 'Penerbit Resmi' }}</div>
                        </td>
                        <td style="text-align: right;">Rp {{ number_format($detail->price_snapshot, 0, ',', '.') }}</td>
                        <td style="text-align: center; font-weight: 700;">{{ $detail->qty }} pcs</td>
                        <td style="text-align: right; font-weight: 700;">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- SUMMARY -->
        <div class="summary-box">
            <table class="summary-table">
                <tr>
                    <td style="color: #64748b;">Subtotal Buku</td>
                    <td style="text-align: right; font-weight: 600;">Rp {{ number_format($order->details->sum('subtotal'), 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="color: #64748b;">Ongkos Kirim (Flat)</td>
                    <td style="text-align: right; font-weight: 600;">Rp 10.000</td>
                </tr>
                <tr class="summary-total">
                    <td>Total Tagihan</td>
                    <td style="text-align: right;">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <!-- FOOTER & SIGNATURE -->
        <div class="invoice-footer">
            <div style="font-size: 11px; color: #64748b; max-width: 420px; line-height: 1.5;">
                * Struk/Invoice ini merupakan bukti transaksi sah yang diterbitkan secara elektronik oleh sistem PustakaMart.<br>
                * Simpan nomor resi pengiriman untuk melacak status paket buku Anda.<br>
                * Terima kasih telah berbelanja buku original dan mendukung literasi Indonesia.
            </div>
            <div class="signature-box">
                <div style="font-size: 12px; color: #475569;">Sukabumi, {{ date('d F Y') }}</div>
                <div style="font-size: 12px; font-weight: 700; color: #0f172a; margin-top: 4px;">Pustakawan / Kasir,</div>
                <div class="signature-line"></div>
                <div style="font-size: 12px; font-weight: 700; color: #0f172a; margin-top: 4px;">PustakaMart Official</div>
            </div>
        </div>
    </div>

</body>
</html>
