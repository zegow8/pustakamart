<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Inventaris Buku - PustakaMart</title>
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
        <button onclick="window.history.back()" class="btn btn-secondary">⬅️ Kembali ke Data Buku</button>
    </div>

    <div class="report-card">
        <div class="report-header">
            <div class="report-title">Laporan Inventaris Koleksi Buku &amp; Stok</div>
            <div class="report-sub">
                PUSTAKAMART - Toko Buku &amp; Perpustakaan Digital<br>
                Jl. Ir. H. Juanda No. 84, Kota Sukabumi, Jawa Barat
            </div>
        </div>

        <div class="meta-strip">
            <div>
                <strong>Total Judul:</strong> {{ $books->count() }} Judul | 
                <strong>Kategori Filter:</strong> {{ request('category_id') ? ($books->first()->category->name ?? 'Terpilih') : 'Semua Kategori' }}
            </div>
            <div>
                <strong>Waktu Cetak:</strong> {{ date('d F Y, H:i') }} WIB
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th style="width: 35px; text-align: center;">No</th>
                    <th>Judul Buku</th>
                    <th style="width: 160px;">Penulis &amp; ISBN</th>
                    <th style="width: 130px;">Kategori</th>
                    <th style="width: 120px; text-align: right;">Harga Satuan</th>
                    <th style="width: 100px; text-align: center;">Stok Tersedia</th>
                </tr>
            </thead>
            <tbody>
                @forelse($books as $idx => $b)
                    <tr>
                        <td style="text-align: center;">{{ $idx + 1 }}</td>
                        <td>
                            <strong style="color: #0f172a;">{{ $b->title }}</strong>
                        </td>
                        <td>
                            <div>{{ $b->author ?? '-' }}</div>
                            <div style="font-size: 11px; color: #64748b;">ISBN: {{ $b->isbn ?? '-' }}</div>
                        </td>
                        <td>{{ $b->category->name ?? 'Tanpa Kategori' }}</td>
                        <td style="text-align: right; font-weight: 600;">Rp {{ number_format($b->price, 0, ',', '.') }}</td>
                        <td style="text-align: center; font-weight: 700; color: {{ $b->stock_sale > 0 ? '#166534' : '#dc2626' }};">
                            {{ $b->stock_sale }} pcs
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #94a3b8; padding: 20px;">Tidak ada data buku yang sesuai.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="summary-box">
            <table class="summary-table">
                <tr>
                    <td style="background: #f8fafc; font-weight: 600;">Total Judul Buku</td>
                    <td style="text-align: right; font-weight: 700;">{{ $books->count() }} Judul</td>
                </tr>
                <tr>
                    <td style="background: #f8fafc; font-weight: 600;">Total Unit Stok Buku</td>
                    <td style="text-align: right; font-weight: 700; color: #166534;">
                        {{ $books->sum('stock_sale') }} Eksemplar
                    </td>
                </tr>
            </table>
        </div>

        <div class="report-footer">
            <div class="sig-box">
                <div>Jakarta, {{ date('d F Y') }}</div>
                <div style="margin-top: 4px;">Bagian Logistik & Inventaris,</div>
                <div class="sig-line"></div>
                <div style="font-weight: 700; margin-top: 4px;">Staff PustakaMart</div>
            </div>
        </div>
    </div>

</body>
</html>
