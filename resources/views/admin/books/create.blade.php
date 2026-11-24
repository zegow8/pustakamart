@extends('layouts.admin')
@section('title', 'Tambah Buku Baru')
@section('content')
<div class="mb-4">
    <a href="/admin/books" style="font-weight:600; font-size:0.88rem; text-decoration:none; color:var(--primary); display:inline-flex; align-items:center; gap:6px; margin-bottom:8px;">
        &larr; Kembali ke Data Buku
    </a>
    <h1 style="margin:0;">Tambah Buku Baru</h1>
    <p class="text-muted" style="margin:4px 0 0; font-size:0.88rem;">Tambahkan buku fisik original baru ke katalog PustakaMart</p>
</div>

<div class="panel">
    <form action="/admin/books" method="POST" enctype="multipart/form-data">
        @csrf
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:24px;" class="admin-book-form-grid">
            <div>
                <div class="form-group mb-3">
                    <label class="form-label">Judul Buku <span style="color:var(--danger); font-weight:700;">*</span></label>
                    <input type="text" name="title" class="form-control" required maxlength="50" placeholder="Maksimal 50 karakter" value="{{ old('title') }}">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Penulis / Pengarang <span style="color:var(--danger); font-weight:700;">*</span></label>
                    <input type="text" name="author" class="form-control" required maxlength="50" placeholder="Maksimal 50 karakter" value="{{ old('author') }}">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Penerbit <span style="color:var(--danger); font-weight:700;">*</span></label>
                    <input type="text" name="publisher" class="form-control" required maxlength="50" placeholder="Maksimal 50 karakter" value="{{ old('publisher') }}">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Tahun Terbit <span style="color:var(--danger); font-weight:700;">*</span></label>
                    <input type="number" name="year" class="form-control" required min="1900" max="2099" placeholder="Contoh: 2024 (4 digit)" oninput="if(this.value.length > 4) this.value = this.value.slice(0, 4);" value="{{ old('year') }}">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">ISBN <span style="color:var(--danger); font-weight:700;">*</span></label>
                    <input type="text" name="isbn" class="form-control" required maxlength="30" placeholder="Contoh: 978-602-03-8591-4" value="{{ old('isbn') }}">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Kategori Buku <span style="color:var(--danger); font-weight:700;">*</span></label>
                    <select name="category_id" class="form-control" required>
                        <option value="">- Pilih Kategori -</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" {{ old('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <div class="form-group mb-3">
                    <label class="form-label">Harga Jual (Rp) <span style="color:var(--danger); font-weight:700;">*</span></label>
                    <input type="number" name="price" class="form-control" required min="0" max="999999999" placeholder="Contoh: 125000" value="{{ old('price') }}">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Stok Buku (Pcs) <span style="color:var(--danger); font-weight:700;">*</span></label>
                    <div style="display:flex; align-items:center; max-width:240px;">
                        <button type="button" class="btn btn-outline" style="border-top-right-radius:0; border-bottom-right-radius:0; padding:8px 14px; font-weight:bold; font-size:1.1rem; user-select:none;" onclick="stepStock('input_stock_sale', -1)">−</button>
                        <input type="number" id="input_stock_sale" name="stock_sale" class="form-control" required min="0" max="999999" placeholder="0" value="{{ old('stock_sale', 0) }}" style="border-radius:0; text-align:center; font-weight:600;">
                        <button type="button" class="btn btn-outline" style="border-top-left-radius:0; border-bottom-left-radius:0; padding:8px 14px; font-weight:bold; font-size:1.1rem; user-select:none;" onclick="stepStock('input_stock_sale', 1)">+</button>
                    </div>
                    <small class="text-muted" style="display:block; margin-top:4px; font-size:0.75rem;">Bisa diketik langsung atau tekan tombol - / +</small>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Cover Buku (JPG / PNG / WEBP maks 10MB) <span style="color:var(--danger); font-weight:700;">*</span></label>
                    <input type="file" name="cover" class="form-control" required accept="image/*">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Sinopsis &amp; Deskripsi Buku <span style="color:var(--danger); font-weight:700;">*</span></label>
                    <textarea name="description" class="form-control" required maxlength="5000" style="min-height:120px;" placeholder="Tuliskan ringkasan sinopsis cerita atau isi buku...">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>
        <div style="margin-top:20px; padding-top:16px; border-top:1px solid var(--border); display:flex; justify-content:flex-end; gap:10px;">
            <a href="/admin/books" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary" style="font-weight:700; padding:10px 24px;">Simpan Buku Baru</button>
        </div>
    </form>
</div>

<script>
    function stepStock(inputId, delta) {
        const el = document.getElementById(inputId);
        if (!el) return;
        let val = parseInt(el.value, 10);
        if (isNaN(val)) val = 0;
        val = Math.max(0, val + delta);
        el.value = val;
    }
</script>
@endsection