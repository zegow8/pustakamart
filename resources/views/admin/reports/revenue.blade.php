@extends('layouts.admin')
@section('title', 'Laporan Pendapatan')
@section('content')
<div class="flex-between mb-4" style="flex-wrap:wrap; gap:12px;">
    <div>
        <h1 style="margin-bottom:4px;">💰 Laporan Pendapatan Toko</h1>
        <p class="text-muted" style="margin:0;">Rekapitulasi omzet penjualan buku fisik yang telah terverifikasi (Dikemas, Dikirim, Selesai)</p>
    </div>
    <div style="display:flex; gap:8px; flex-wrap:wrap;" class="no-print">
        <button type="button" onclick="window.print()" class="btn btn-outline btn-sm" style="font-weight:700; border-color:#2563eb; color:#2563eb; background:#eff6ff;">
            🖨️ Cetak Laporan
        </button>
        <button type="button" onclick="exportRevenueCsv()" class="btn btn-outline btn-sm" style="font-weight:700; border-color:#059669; color:#059669; background:#ecfdf5;">
            📥 Export CSV
        </button>
    </div>
</div>

<!-- Filter Periode -->
<div class="panel mb-4 no-print" style="padding:16px 20px;">
    <form method="GET" action="/admin/reports/revenue" style="display:flex; gap:14px; align-items:flex-end; flex-wrap:wrap;">
        <div>
            <label class="form-label" style="font-size:0.8rem; margin-bottom:4px;">Dari Tanggal</label>
            <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
        </div>
        <div>
            <label class="form-label" style="font-size:0.8rem; margin-bottom:4px;">Sampai Tanggal</label>
            <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
        </div>
        <button type="submit" class="btn btn-primary" style="padding:10px 20px; font-weight:700;">Terapkan Filter</button>
        <a href="/admin/reports/revenue" class="btn btn-outline" style="padding:10px 14px;">Reset</a>
    </form>
</div>

<!-- 4 Kartu Statistik Keuangan -->
<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:16px; margin-bottom:24px;">
    <div class="panel" style="margin:0; padding:20px; border-left:4px solid #800000;">
        <div class="text-muted" style="font-size:0.75rem; font-weight:700; text-transform:uppercase;">TOTAL OMZET BERSIH</div>
        <div style="font-size:1.65rem; font-weight:800; color:#800000; margin-top:6px;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
        <div style="font-size:0.78rem; color:var(--text-muted); margin-top:4px;">Periode {{ date('d M', strtotime($startDate)) }} - {{ date('d M Y', strtotime($endDate)) }}</div>
    </div>
    <div class="panel" style="margin:0; padding:20px; border-left:4px solid #059669;">
        <div class="text-muted" style="font-size:0.75rem; font-weight:700; text-transform:uppercase;">TRANSAKSI TERVERIFIKASI</div>
        <div style="font-size:1.65rem; font-weight:800; color:#059669; margin-top:6px;">{{ $totalOrders }} Pesanan</div>
        <div style="font-size:0.78rem; color:var(--text-muted); margin-top:4px;">Status Processing / Shipped / Completed</div>
    </div>
    <div class="panel" style="margin:0; padding:20px; border-left:4px solid #2563eb;">
        <div class="text-muted" style="font-size:0.75rem; font-weight:700; text-transform:uppercase;">RATA-RATA TRANSAKSI</div>
        <div style="font-size:1.65rem; font-weight:800; color:#2563eb; margin-top:6px;">Rp {{ number_format($avgOrder, 0, ',', '.') }}</div>
        <div style="font-size:0.78rem; color:var(--text-muted); margin-top:4px;">Nilai keranjang rata-rata</div>
    </div>
    <div class="panel" style="margin:0; padding:20px; border-left:4px solid #d97706;">
        <div class="text-muted" style="font-size:0.75rem; font-weight:700; text-transform:uppercase;">METODE TRANSFER</div>
        <div style="font-size:0.88rem; font-weight:700; color:var(--text-main); margin-top:8px;">BCA: <span style="color:#1e40af;">Rp {{ number_format($bcaTotal, 0, ',', '.') }}</span></div>
        <div style="font-size:0.88rem; font-weight:700; color:var(--text-main); margin-top:2px;">Mandiri: <span style="color:#d97706;">Rp {{ number_format($mandiriTotal, 0, ',', '.') }}</span></div>
    </div>
</div>

<!-- Grafik Pendapatan -->
<div class="panel mb-4">
    <h3 class="panel-title mb-3" style="font-size:1.05rem;">📊 Grafik Pendapatan Harian</h3>
    <div style="position:relative; height:280px; width:100%;">
        <canvas id="revenueChart"></canvas>
    </div>
</div>

<!-- Tabel Rincian Pemasukan -->
<div class="panel" style="padding:0; overflow:hidden;">
    <div style="padding:14px 20px; background:#f8fafc; border-bottom:1px solid var(--border); font-weight:700; font-size:0.95rem;">
        📑 Rincian Transaksi Pemasukan ({{ $orders->count() }} Data)
    </div>
    <div style="overflow-x:auto;">
        <table class="table" id="revenueTable" style="margin:0;">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Invoice</th>
                    <th>Pelanggan</th>
                    <th>Metode Bayar</th>
                    <th>Status</th>
                    <th style="text-align:right;">Total Pemasukan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $o)
                <tr>
                    <td style="white-space:nowrap;">{{ $o->created_at->format('d M Y H:i') }}</td>
                    <td><a href="/admin/orders/{{ $o->id }}" style="font-weight:700; color:var(--primary); text-decoration:none;">#{{ $o->invoice_code }}</a></td>
                    <td>{{ $o->receiver_name ?? ($o->user->name ?? '-') }}</td>
                    <td>{{ $o->payment_method }}</td>
                    <td><span class="status-badge" style="background:#dcfce7; color:#166534;">{{ $o->status }}</span></td>
                    <td style="text-align:right; font-weight:700; color:var(--primary);">Rp {{ number_format($o->total, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:32px; color:var(--text-muted);">Tidak ada transaksi lunas pada periode tanggal ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: {!! json_encode($chartData) !!},
                backgroundColor: 'rgba(128, 0, 0, 0.85)',
                borderColor: '#800000',
                borderWidth: 1,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) { return 'Rp ' + value.toLocaleString('id-ID'); }
                    }
                }
            }
        }
    });

    function exportRevenueCsv() {
        let rows = [["Tanggal", "Invoice", "Pelanggan", "Metode Bayar", "Status", "Total"]];
        document.querySelectorAll("#revenueTable tbody tr").forEach(tr => {
            let tds = tr.querySelectorAll("td");
            if (tds.length >= 6) {
                rows.push([
                    tds[0].innerText.trim(),
                    tds[1].innerText.trim(),
                    tds[2].innerText.trim().replace(/,/g, " "),
                    tds[3].innerText.trim(),
                    tds[4].innerText.trim(),
                    tds[5].innerText.trim().replace(/,/g, "")
                ]);
            }
        });
        let csvContent = "data:text/csv;charset=utf-8," + rows.map(e => e.join(",")).join("\n");
        let link = document.createElement("a");
        link.setAttribute("href", encodeURI(csvContent));
        link.setAttribute("download", "laporan_pendapatan_pustakamart.csv");
        document.body.appendChild(link);
        link.click();
        link.remove();
    }
</script>
@endsection
