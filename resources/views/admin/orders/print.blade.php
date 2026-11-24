<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rekapitulasi Pesanan - PustakaMart</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: #1e293b;
            background: #f1f5f9;
            padding: 24px;
            font-size: 13px;
        }
        .report-card {
            max-width: 1000px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            padding: 32px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.06);
            border: 1px solid #e2e8f0;
        }
        .report-header {
            text-align: center;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }
        .report-title {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .report-sub {
            font-size: 13px;
            color: #64748b;
            margin-top: 4px;
        }

        .meta-strip {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 12px;
            color: #475569;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        .table th, .table td {
            border: 1px solid #cbd5e1;
            padding: 10px 12px;
            text-align: left;
        }
        .table th {
            background: #f8fafc;
            font-weight: 700;
            color: #1e293b;
            font-size: 12px;
            text-transform: uppercase;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
        }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-info { background: #e0f2fe; color: #075985; }
        .badge-danger { background: #fee2e2; color: #991b1b; }

        .summary-box {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 30px;
        }
        .summary-table {
            width: 340px;
            border-collapse: collapse;
        }
        .summary-table td {
            padding: 6px 12px;
            border: 1px solid #cbd5e1;
            font-size: 13px;
        }

        .report-footer {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }
        .sig-box {
            text-align: center;
            width: 200px;
            font-size: 12px;
        }
        .sig-line {
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
        .btn-secondary { background: #64748b; color: #fff; }

        @media print {
            body { background: #ffffff; padding: 0; }
            .report-card { box-shadow: none; border: none; padding: 0; max-width: 100%; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" class="btn btn-primary">🖨️ Cetak Laporan (Print / PDF)</button>
        <button onclick="window.history.back()" class="btn btn-secondary">⬅️ Kembali ke Dashboard</button>
    </div>

    <div class="report-card">
        <div class="report-header">
            <div class="report-title">Laporan Rekapitulasi Pesanan Pembelian Buku</div>
            <div class="report-sub">
                PUSTAKAMART - Toko Buku &amp; Perpustakaan Digital<br>
                Jl. Ir. H. Juanda No. 84, Kota Sukabumi, Jawa Barat
            </div>
        </div>

        <div class="meta-strip">
            <div>
                <strong>Periode:</strong> {{ $period }} | 
                <strong>Status Filter:</strong> {{ request('status') ? request('status') : 'Semua Status' }}
            </div>
            <div>
                <strong>Waktu Cetak:</strong> {{ date('d F Y, H:i') }} WIB
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th style="width: 35px; text-align: center;">No</th>
                    <th style="width: 130px;">No. Invoice</th>
                    <th style="width: 110px;">Tanggal</th>
                    <th>Nama Pembeli / Penerima</th>
                    <th>Detail Buku yang Dipesan</th>
                    <th style="width: 100px;">Metode</th>
                    <th style="width: 110px;">Status</th>
                    <th style="width: 120px; text-align: right;">Total Tagihan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $idx => $o)
                    <tr>
                        <td style="text-align: center;">{{ $idx + 1 }}</td>
                        <td style="font-weight: 700;">{{ $o->invoice_code }}</td>
                        <td>{{ $o->created_at->format('d/m/Y') }}</td>
                        <td>
                            <strong>{{ $o->receiver_name }}</strong><br>
                            <span style="font-size: 11px; color: #64748b;">{{ $o->receiver_phone }}</span>
                        </td>
                        <td>
                            @foreach($o->details as $d)
                                <div style="font-size: 12px;">• {{ $d->title_snapshot }} ({{ $d->qty }}x)</div>
                            @endforeach
                        </td>
                        <td>{{ $o->payment_method }}</td>
                        <td>
                            <span class="badge {{ in_array($o->status, ['Processing', 'Shipped', 'Completed']) ? 'badge-success' : ($o->status === 'Waiting Confirmation' ? 'badge-warning' : 'badge-danger') }}">
                                {{ $o->status }}
                            </span>
                        </td>
                        <td style="text-align: right; font-weight: 700;">Rp {{ number_format($o->total, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: #94a3b8; padding: 20px;">Tidak ada transaksi pesanan yang sesuai dengan filter.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="summary-box">
            <table class="summary-table">
                <tr>
                    <td style="background: #f8fafc; font-weight: 600;">Total Pesanan</td>
                    <td style="text-align: right; font-weight: 700;">{{ $orders->count() }} Transaksi</td>
                </tr>
                <tr>
                    <td style="background: #f8fafc; font-weight: 600;">Pesanan Berhasil / Lunas</td>
                    <td style="text-align: right; font-weight: 700; color: #166534;">
                        {{ $orders->whereIn('status', ['Processing', 'Shipped', 'Completed'])->count() }} Pesanan
                    </td>
                </tr>
                <tr>
                    <td style="background: #f8fafc; font-weight: 700; color: #1e40af;">Total Pendapatan Valid</td>
                    <td style="text-align: right; font-weight: 800; font-size: 15px; color: #1e40af;">
                        Rp {{ number_format($totalSales, 0, ',', '.') }}
                    </td>
                </tr>
            </table>
        </div>

        <div class="report-footer">
            <div class="sig-box">
                <div>Sukabumi, {{ date('d F Y') }}</div>
                <div style="margin-top: 4px;">Kepala Bagian Penjualan,</div>
                <div class="sig-line"></div>
                <div style="font-weight: 700; margin-top: 4px;">Admin PustakaMart</div>
            </div>
        </div>
    </div>

</body>
</html>
