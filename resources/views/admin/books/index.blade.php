@extends('layouts.admin')
@section('title', 'Data Buku')
@section('content')
<style>
    @media (max-width: 992px) {
        .admin-books-filter-grid { grid-template-columns: 1fr 1fr !important; }
    }
    @media (max-width: 540px) {
        .admin-books-filter-grid { grid-template-columns: 1fr !important; }
    }
</style>
<div class="flex-between mb-4" style="flex-wrap:wrap; gap:12px;">
    <div>
        <h1 style="margin-bottom:4px; font-size:clamp(1.3rem, 4vw, 1.8rem);">Data Buku</h1>
        <p class="text-muted" style="margin:0; font-size:0.88rem;">Kelola katalog buku, stok, harga, dan pantau peminjaman aktif</p>
    </div>
    <div style="display:flex; gap:8px; flex-wrap:wrap;">
        <a href="/admin/books/print?category_id={{ request('category_id') }}" target="_blank" class="btn btn-outline btn-sm" style="font-weight:700; border-color:#2563eb; color:#2563eb; background:#eff6ff;">
            🖨️ Cetak Laporan
        </a>
        <button type="button" onclick="exportBooksCsv()" class="btn btn-outline btn-sm" style="font-weight:700; border-color:#059669; color:#059669; background:#ecfdf5;">
            📥 Export CSV
        </button>
        <a href="/admin/books/create" class="btn btn-primary btn-sm">+ Tambah Buku Baru</a>
    </div>
</div>

<!-- ============ FILTER & SEARCH ============ -->
<div class="panel" style="margin-bottom:20px; padding:18px;">
    <form method="GET" action="/admin/books">
        <div style="display:grid; grid-template-columns: 2fr 1.5fr 1fr 1fr 1.5fr auto; gap:12px; align-items:flex-end;" class="admin-books-filter-grid">
            <div>
                <label class="form-label" style="font-size:0.8rem; margin-bottom:4px;">Cari Buku</label>
                <input type="text" name="q" class="form-control" placeholder="Judul, penulis, ISBN..." value="{{ request('q') }}">
            </div>
            <div>
                <label class="form-label" style="font-size:0.8rem; margin-bottom:4px;">Kategori</label>
                <select name="category_id" class="form-control">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }} ({{ $cat->books_count ?? 0 }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label" style="font-size:0.8rem; margin-bottom:4px;">Status Stok</label>
                <select name="stock_status" class="form-control">
                    <option value="">Semua Stok</option>
                    <option value="sale_available" {{ request('stock_status') == 'sale_available' ? 'selected' : '' }}>Stok Tersedia (>0)</option>
                    <option value="sale_empty" {{ request('stock_status') == 'sale_empty' ? 'selected' : '' }}>Stok Habis (0)</option>
                </select>
            </div>
            <div style="display:flex; gap:6px;">
                <button type="submit" class="btn btn-primary" style="padding:10px 18px;">Filter</button>
                @if(request()->hasAny(['q', 'category_id', 'min_price', 'max_price', 'stock_status']))
                    <a href="/admin/books" class="btn btn-outline" style="padding:10px 14px;">Reset</a>
                @endif
            </div>
        </div>
    </form>
</div>

<!-- ============ TABEL DATA BUKU ============ -->
<div class="panel" style="padding:0; overflow:hidden;">
    <div style="padding:14px 20px; background:#f8fafc; border-bottom:1px solid var(--border); font-size:0.85rem; color:var(--text-muted);">
        Menampilkan <strong>{{ count($books) }}</strong> buku
    </div>
    <div style="overflow-x:auto;">
        <table class="table">
            <thead>
                <tr>
                    <th style="width:70px;">Cover</th>
                    <th>Judul & Penulis</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th style="text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($books as $b)
                <tr>
                    <!-- Preview Cover Buku -->
                    <td style="vertical-align:top;">
                        <div style="width:50px; height:70px; border-radius:6px; overflow:hidden; background:#eee; box-shadow:0 1px 4px rgba(0,0,0,0.1); border:1px solid var(--border);">
                            @if($b->cover)
                                <img src="{{ asset('uploads/covers/' . $b->cover) }}" alt="{{ $b->title }}" style="width:100%; height:100%; object-fit:cover;">
                            @else
                                <div style="display:flex; align-items:center; justify-content:center; height:100%; font-size:1.5rem; color:var(--text-muted);">📚</div>
                            @endif
                        </div>
                    </td>

                    <!-- Judul & Penulis -->
                    <td style="vertical-align:top;">
                        <strong style="font-size:0.95rem; color:var(--text-main);">{{ $b->title }}</strong>
                        <div style="font-size:0.8rem; color:var(--text-muted); margin-top:2px;">Penulis: {{ $b->author }}</div>
                        @if($b->isbn)
                            <div style="font-size:0.75rem; color:var(--text-muted); font-family:monospace;">ISBN: {{ $b->isbn }}</div>
                        @endif
                    </td>

                    <!-- Kategori -->
                    <td style="vertical-align:top;">
                        <span class="status-badge" style="background:#e0e7ff; color:#3730a3;">{{ $b->category->name ?? '-' }}</span>
                    </td>

                    <!-- Harga -->
                    <td style="vertical-align:top; font-weight:700; color:var(--primary);">
                        Rp {{ number_format($b->price, 0, ',', '.') }}
                    </td>

                    <!-- Stok -->
                    <td style="vertical-align:top;">
                        <strong style="color: {{ $b->stock_sale <= 0 ? 'var(--danger)' : '#10b981' }}; font-size:1rem;">
                            {{ $b->stock_sale > 0 ? $b->stock_sale . ' pcs' : 'Habis' }}
                        </strong>
                    </td>

                    <!-- Aksi -->
                    <td style="vertical-align:top; text-align:right;">
                        <div style="display:inline-flex; gap:6px;">
                            <a href="/admin/books/edit/{{ $b->id }}" class="btn btn-outline btn-sm">Edit</a>
                            <form action="/admin/books/delete/{{ $b->id }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:40px; color:var(--text-muted);">
                        Tidak ada data buku yang sesuai dengan pencarian atau filter.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination 10 per halaman -->
    <div style="margin-top: 16px;">
        {{ $books->links() }}
    </div>
</div>

<script>
function exportBooksCsv() {
    let rows = [["No", "Judul Buku", "Penulis", "Kategori", "Harga", "Stok"]];
    let trs = document.querySelectorAll("table tbody tr");
    trs.forEach((tr, i) => {
        let tds = tr.querySelectorAll("td");
        if (tds.length >= 6) {
            let no = i + 1;
            let title = tds[1].querySelector("strong") ? tds[1].querySelector("strong").innerText.trim().replace(/,/g, " ") : tds[1].innerText.trim().replace(/,/g, " ");
            let author = tds[1].querySelector("div") ? tds[1].querySelector("div").innerText.replace('Penulis: ', '').trim().replace(/,/g, " ") : "";
            let cat = tds[2].innerText.trim().replace(/,/g, " ");
            let price = tds[3].innerText.trim().replace(/,/g, " ");
            let sale = tds[4].innerText.trim().replace(/,/g, " ");
            rows.push([no, title, author, cat, price, sale]);
        }
    });
    let csvContent = "data:text/csv;charset=utf-8," + rows.map(e => e.join(",")).join("\n");
    let encodedUri = encodeURI(csvContent);
    let link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "katalog_buku_pustakamart.csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
@endsection
