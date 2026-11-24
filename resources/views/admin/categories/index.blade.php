@extends('layouts.admin')
@section('title', 'Kategori Buku')
@section('content')
<style>
    @media (max-width: 900px) {
        .admin-category-grid { grid-template-columns: 1fr !important; }
    }
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(15, 23, 42, 0.65);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(3px);
        padding: 16px;
    }
    .category-stat-card {
        background: white;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 16px 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .mini-cover-thumb {
        width: 38px;
        height: 54px;
        border-radius: 4px;
        overflow: hidden;
        border: 1px solid #cbd5e1;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12);
        cursor: pointer;
        flex-shrink: 0;
        background: #f8fafc;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .mini-cover-thumb:hover {
        transform: translateY(-2px) scale(1.06);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    .book-collection-item {
        display: flex;
        gap: 14px;
        padding: 12px;
        border-radius: 8px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        align-items: center;
        transition: background 0.15s ease;
    }
    .book-collection-item:hover {
        background: #f1f5f9;
    }
</style>

<!-- Header & Subtitle -->
<div class="flex-between mb-4">
    <div>
        <h1 style="margin-bottom:4px;">Kategori Buku</h1>
        <p class="text-muted" style="margin:0;">Kelola klasifikasi genre, jumlah buku, dan intip koleksi buku di setiap kategori</p>
    </div>
</div>

<!-- Statistik Ringkas Kategori -->
@php
    $totalCategories = count($categories);
    $totalBooksCount = $categories->sum('books_count');
    $topCategory = $categories->sortByDesc('books_count')->first();
@endphp
<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:16px; margin-bottom:24px;">
    <div class="category-stat-card">
        <div style="font-size:0.8rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.05em;">Total Kategori</div>
        <div style="font-size:1.8rem; font-weight:800; color:var(--primary); margin-top:4px;">{{ $totalCategories }}</div>
        <div style="font-size:0.8rem; color:var(--text-muted); margin-top:2px;">Kategori genre aktif</div>
    </div>
    <div class="category-stat-card">
        <div style="font-size:0.8rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.05em;">Total Koleksi Buku</div>
        <div style="font-size:1.8rem; font-weight:800; color:#059669; margin-top:4px;">{{ $totalBooksCount }}</div>
        <div style="font-size:0.8rem; color:var(--text-muted); margin-top:2px;">Buku terklasifikasi</div>
    </div>
    <div class="category-stat-card">
        <div style="font-size:0.8rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.05em;">Kategori Terbanyak</div>
        <div style="font-size:1.2rem; font-weight:800; color:var(--text-main); margin-top:8px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
            {{ $topCategory ? $topCategory->name : '-' }}
        </div>
        <div style="font-size:0.8rem; color:var(--text-muted); margin-top:2px;">
            {{ $topCategory ? $topCategory->books_count . ' Judul Buku' : 'Belum ada data' }}
        </div>
    </div>
</div>

<div style="display:grid; grid-template-columns:320px 1fr; gap:24px;" class="admin-category-grid">
    <!-- Form Tambah Kategori -->
    <div>
        <div class="panel" style="margin-bottom:18px;">
            <h3 style="margin-top:0; margin-bottom:12px; font-size:1.1rem;">Tambah Kategori Baru</h3>
            <form action="/admin/categories" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label class="form-label">Nama Kategori <span style="color:var(--danger);">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="Contoh: Biografi, Komik, Fiksi" required maxlength="50" value="{{ old('name') }}">
                    <small class="text-muted" style="display:block; margin-top:4px; font-size:0.75rem;">Maksimal 50 karakter</small>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Deskripsi Kategori</label>
                    <input type="text" name="description" class="form-control" placeholder="Contoh: Koleksi karya sastra & imajinatif" maxlength="50" value="{{ old('description') }}">
                    <small class="text-muted" style="display:block; margin-top:4px; font-size:0.75rem;">Maksimal 50 karakter</small>
                </div>
                <button type="submit" class="btn btn-primary btn-block" style="font-weight:700;">+ Simpan Kategori</button>
            </form>
        </div>

        <div class="panel" style="background:#eff6ff; border-color:#bfdbfe;">
            <h4 style="margin-top:0; margin-bottom:6px; font-size:0.95rem; color:#1e40af;">💡 Tips Pengelolaan</h4>
            <p style="font-size:0.8rem; color:#1e3a8a; margin:0; line-height:1.4;">
                Kategori yang sudah terhubung dengan koleksi buku tidak dapat dihapus secara langsung demi melindungi integritas data katalog perpustakaan.
            </p>
        </div>
    </div>

    <!-- Tabel Daftar Kategori & Ringkasan Koleksi -->
    <div>
        <!-- Search & Filter Bar -->
        <div class="panel" style="padding:14px 18px; margin-bottom:16px;">
            <form method="GET" action="/admin/categories" style="display:flex; gap:10px; align-items:center;">
                <div style="flex:1;">
                    <input type="text" name="q" class="form-control" placeholder="Cari nama kategori..." value="{{ request('q') }}">
                </div>
                <button type="submit" class="btn btn-primary" style="padding:9px 18px;">Cari</button>
                @if(request('q'))
                    <a href="/admin/categories" class="btn btn-outline" style="padding:9px 14px;">Reset</a>
                @endif
            </form>
        </div>

        <div class="panel" style="padding:0; overflow:hidden;">
            <div style="padding:14px 20px; background:#f8fafc; border-bottom:1px solid var(--border); font-size:0.85rem; color:var(--text-muted); display:flex; justify-content:space-between; align-items:center;">
                <div>
                    Menampilkan <strong>{{ count($categories) }}</strong> kategori
                    @if(request('q'))
                        (filter: <em>"{{ request('q') }}"</em>)
                    @endif
                </div>
            </div>
            
            <div style="overflow-x:auto;">
                <table class="table" style="margin:0;">
                    <thead>
                        <tr>
                            <th style="width:40px; text-align:center;">No</th>
                            <th style="width:160px;">Nama Kategori</th>
                            <th style="width:180px;">Deskripsi</th>
                            <th style="width:120px; text-align:center;">Jumlah Buku</th>
                            <th>Pratinjau Koleksi Buku (Sampul & Judul)</th>
                            <th style="text-align:right; width:170px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $idx => $c)
                        <tr>
                            <td style="text-align:center; color:var(--text-muted); vertical-align:middle;">{{ $idx + 1 }}</td>
                            
                            <!-- Nama & Slug Kategori -->
                            <td style="vertical-align:middle;">
                                <strong style="color:var(--text-main); font-size:0.95rem; display:block;">{{ $c->name }}</strong>
                                <span style="font-size:0.75rem; color:var(--text-muted); font-family:monospace;">/{{ $c->slug }}</span>
                            </td>

                            <!-- Deskripsi Kategori (Maks 50 Karakter) -->
                            <td style="vertical-align:middle; font-size:0.85rem; color:var(--text-main);">
                                @if($c->description)
                                    <span>{{ $c->description }}</span>
                                @else
                                    <span style="color:var(--text-muted); font-style:italic;">-</span>
                                @endif
                            </td>

                            <!-- Jumlah Buku (Badge) -->
                            <td style="text-align:center; vertical-align:middle;">
                                @if($c->books_count > 0)
                                    <span class="status-badge" style="background:#e0f2fe; color:#0369a1; font-weight:700; padding:4px 10px; border-radius:20px;">
                                        📚 {{ $c->books_count }} Buku
                                    </span>
                                @else
                                    <span class="status-badge" style="background:#f1f5f9; color:#64748b; font-weight:600; padding:4px 10px; border-radius:20px;">
                                        0 Buku
                                    </span>
                                @endif
                            </td>

                            <!-- Pratinjau Buku: Cover & Judul -->
                            <td style="vertical-align:middle;">
                                @if($c->books_count > 0)
                                    <div style="display:flex; flex-direction:column; gap:6px;">
                                        <!-- Row Sampul Buku -->
                                        <div style="display:flex; align-items:center; gap:6px; flex-wrap:wrap;">
                                            @foreach($c->books->take(4) as $book)
                                                <div class="mini-cover-thumb" onclick="openCollectionModal({{ $c->id }})" title="{{ $book->title }} ({{ $book->author }})">
                                                    @if($book->cover)
                                                        <img src="{{ asset('uploads/covers/' . $book->cover) }}" alt="{{ $book->title }}" style="width:100%; height:100%; object-fit:cover;">
                                                    @else
                                                        <div style="display:flex; align-items:center; justify-content:center; height:100%; font-size:1.1rem; color:var(--text-muted); background:#f1f5f9;">📖</div>
                                                    @endif
                                                </div>
                                            @endforeach
                                            @if($c->books_count > 4)
                                                <span onclick="openCollectionModal({{ $c->id }})" style="cursor:pointer; background:#e2e8f0; color:#334155; font-size:0.75rem; font-weight:700; padding:4px 7px; border-radius:4px; border:1px solid #cbd5e1;" title="Klik untuk melihat semua {{ $c->books_count }} buku">
                                                    +{{ $c->books_count - 4 }} lagi
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Ringkasan Judul Buku -->
                                        <div style="font-size:0.8rem; color:var(--text-muted); line-height:1.3; max-width:380px;">
                                            <span style="font-weight:600; color:var(--text-main);">Contoh:</span>
                                            {{ $c->books->pluck('title')->take(3)->implode(', ') }}@if($c->books_count > 3)...@endif
                                        </div>

                                        <div>
                                            <button type="button" class="btn btn-outline btn-sm" style="font-size:0.75rem; padding:3px 8px; border-radius:5px;" onclick="openCollectionModal({{ $c->id }})">
                                                👁️ Intip Semua Koleksi ({{ $c->books_count }})
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <div style="color:var(--text-muted); font-size:0.85rem; font-style:italic;">
                                        📭 Belum ada buku di kategori ini
                                    </div>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td style="text-align:right; vertical-align:middle;">
                                <div style="display:flex; justify-content:flex-end; gap:4px; align-items:center; flex-wrap:wrap;">
                                    <a href="/admin/books?category_id={{ $c->id }}" class="btn btn-outline btn-sm" style="font-size:0.8rem; padding:5px 8px;" title="Lihat di Halaman Data Buku">
                                        🔍 Buku
                                    </a>
                                    <button type="button" class="btn btn-outline btn-sm" style="font-size:0.8rem; padding:5px 8px; font-weight:600;" onclick="openEditModal({{ $c->id }}, '{{ addslashes($c->name) }}', '{{ addslashes($c->description ?? '') }}')">
                                        ✏️ Edit
                                    </button>
                                    <form action="/admin/categories/delete/{{ $c->id }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori \'{{ addslashes($c->name) }}\'?')" style="display:inline;">
                                        @csrf 
                                        <button type="submit" class="btn btn-danger btn-sm" style="font-size:0.8rem; padding:5px 8px;">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align:center; padding:32px; color:var(--text-muted);">
                                Belum ada kategori yang ditambahkan{{ request('q') ? ' yang cocok dengan pencarian Anda.' : '.' }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ============ MODAL INTIP KOLEKSI BUKU ============ -->
<div id="collectionModal" class="modal-overlay">
    <div style="background:white; border-radius:14px; width:95%; max-width:680px; max-height:85vh; display:flex; flex-direction:column; box-shadow:0 20px 40px rgba(0,0,0,0.3); overflow:hidden;">
        <!-- Modal Header -->
        <div style="padding:18px 24px; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; background:#f8fafc;">
            <div>
                <h3 style="margin:0; font-size:1.15rem; color:var(--text-main);">
                    📚 Koleksi Buku: <span id="modalCatTitle" style="color:var(--primary);"></span>
                </h3>
                <p id="modalCatSubtitle" class="text-muted" style="margin:2px 0 0; font-size:0.85rem;"></p>
            </div>
            <button type="button" onclick="closeCollectionModal()" style="border:none; background:transparent; font-size:1.4rem; color:var(--text-muted); cursor:pointer; line-height:1;">&times;</button>
        </div>

        <!-- Modal Body (Scrollable List) -->
        <div id="modalBooksList" style="padding:20px 24px; overflow-y:auto; flex:1; display:flex; flex-direction:column; gap:12px;">
            <!-- Dynamically populated by JavaScript -->
        </div>

        <!-- Modal Footer -->
        <div style="padding:14px 24px; border-top:1px solid var(--border); background:#f8fafc; display:flex; justify-content:space-between; align-items:center;">
            <a id="modalCatFilterLink" href="#" class="btn btn-primary btn-sm" style="font-weight:700;">
                🔍 Buka &amp; Kelola di Halaman Data Buku
            </a>
            <button type="button" class="btn btn-outline btn-sm" onclick="closeCollectionModal()">Tutup</button>
        </div>
    </div>
</div>

<!-- ============ MODAL EDIT KATEGORI ============ -->
<div id="editCategoryModal" class="modal-overlay">
    <div style="background:white; border-radius:12px; padding:24px; width:90%; max-width:440px; box-shadow:0 15px 35px rgba(0,0,0,0.25);">
        <h3 style="margin-top:0; margin-bottom:8px; font-size:1.2rem;">Edit Kategori</h3>
        <p class="text-muted" style="font-size:0.85rem; margin-bottom:18px;">Ubah nama dan deskripsi kategori yang akan ditampilkan pada filter katalog buku.</p>
        
        <form id="editCategoryForm" method="POST" action="">
            @csrf
            <div class="form-group mb-3">
                <label class="form-label" style="font-weight:700;">Nama Kategori <span style="color:var(--danger);">*</span></label>
                <input type="text" name="name" id="editCategoryName" class="form-control" required placeholder="Nama kategori" maxlength="50">
            </div>
            <div class="form-group mb-4">
                <label class="form-label" style="font-weight:700;">Deskripsi Kategori</label>
                <input type="text" name="description" id="editCategoryDescription" class="form-control" placeholder="Contoh: Koleksi karya sastra & imajinatif" maxlength="50">
                <small class="text-muted" style="display:block; margin-top:4px; font-size:0.75rem;">Maksimal 50 karakter</small>
            </div>
            <div style="display:flex; justify-content:flex-end; gap:8px;">
                <button type="button" class="btn btn-outline" onclick="closeEditModal()">Batal</button>
                <button type="submit" class="btn btn-primary" style="font-weight:700;">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Data koleksi buku dari backend
    const categoriesData = @json($categoriesJson);

    function openCollectionModal(categoryId) {
        const cat = categoriesData[categoryId];
        if (!cat) return;

        document.getElementById('modalCatTitle').textContent = cat.name;
        document.getElementById('modalCatSubtitle').textContent = `Total ${cat.count} buku terdaftar di dalam kategori ini`;
        document.getElementById('modalCatFilterLink').href = `/admin/books?category_id=${cat.id}`;

        const container = document.getElementById('modalBooksList');
        container.innerHTML = '';

        if (!cat.books || cat.books.length === 0) {
            container.innerHTML = `
                <div style="text-align:center; padding:32px 16px; color:var(--text-muted);">
                    <div style="font-size:2.5rem; margin-bottom:8px;">📭</div>
                    <strong>Belum Ada Buku</strong>
                    <p style="margin:4px 0 0; font-size:0.85rem;">Belum ada judul buku yang dimasukkan ke dalam kategori ini.</p>
                </div>
            `;
        } else {
            cat.books.forEach((b, index) => {
                const item = document.createElement('div');
                item.className = 'book-collection-item';
                
                const coverHtml = b.cover_url 
                    ? `<img src="${b.cover_url}" alt="${b.title}" style="width:100%; height:100%; object-fit:cover;">`
                    : `<div style="display:flex; align-items:center; justify-content:center; height:100%; font-size:1.4rem; color:var(--text-muted);">📚</div>`;

                item.innerHTML = `
                    <div style="width:48px; height:68px; border-radius:5px; overflow:hidden; border:1px solid #cbd5e1; background:#f1f5f9; flex-shrink:0;">
                        ${coverHtml}
                    </div>
                    <div style="flex:1; min-width:0;">
                        <strong style="color:var(--text-main); font-size:0.95rem; display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">${b.title}</strong>
                        <div style="font-size:0.8rem; color:var(--text-muted); margin-top:2px;">
                            Penulis: <strong>${b.author || '-'}</strong> ${b.year ? `(${b.year})` : ''}
                        </div>
                        <div style="font-size:0.8rem; margin-top:4px; display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                            <span style="font-weight:700; color:var(--primary);">Rp ${b.price}</span>
                            <span class="status-badge" style="background:#ecfdf5; color:#065f46; font-size:0.75rem; padding:2px 8px;">Stok: ${b.stock_sale}</span>
                        </div>
                    </div>
                `;
                container.appendChild(item);
            });
        }

        document.getElementById('collectionModal').style.display = 'flex';
    }

    function closeCollectionModal() {
        document.getElementById('collectionModal').style.display = 'none';
    }

    function openEditModal(id, name, description) {
        const modal = document.getElementById('editCategoryModal');
        const form = document.getElementById('editCategoryForm');
        const inputName = document.getElementById('editCategoryName');
        const inputDesc = document.getElementById('editCategoryDescription');
        form.action = '/admin/categories/update/' + id;
        inputName.value = name;
        inputDesc.value = description || '';
        modal.style.display = 'flex';
        inputName.focus();
    }

    function closeEditModal() {
        document.getElementById('editCategoryModal').style.display = 'none';
    }

    // Tutup modal jika klik background gelap
    window.addEventListener('click', function(e) {
        const editModal = document.getElementById('editCategoryModal');
        const colModal = document.getElementById('collectionModal');
        if (e.target === editModal) closeEditModal();
        if (e.target === colModal) closeCollectionModal();
    });
</script>
@endsection
