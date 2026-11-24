@extends('layouts.admin')
@section('title', 'Edit Buku')
@section('content')
<div class="mb-4">
    <a href="/admin/books" style="font-weight:600; font-size:0.88rem; text-decoration:none; color:var(--primary); display:inline-flex; align-items:center; gap:6px; margin-bottom:8px;">
        &larr; Kembali ke Data Buku
    </a>
    <h1 style="margin:0;">Edit Buku: {{ $book->title }}</h1>
    <p class="text-muted" style="margin:4px 0 0; font-size:0.88rem;">Perbarui data judul, stok, kategori, atau cover buku</p>
</div>

<div class="panel">
    <form action="/admin/books/update/{{ $book->id }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:24px;" class="admin-book-form-grid">
            <div>
                <div class="form-group mb-3">
                    <label class="form-label">Judul Buku <span style="color:var(--danger); font-weight:700;">*</span></label>
                    <input type="text" name="title" class="form-control" required maxlength="50" placeholder="Maksimal 50 karakter" value="{{ old('title', $book->title) }}">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Penulis / Pengarang <span style="color:var(--danger); font-weight:700;">*</span></label>
                    <input type="text" name="author" class="form-control" required maxlength="50" placeholder="Maksimal 50 karakter" value="{{ old('author', $book->author) }}">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Penerbit</label>
                    <input type="text" name="publisher" class="form-control" maxlength="50" placeholder="Maksimal 50 karakter" value="{{ old('publisher', $book->publisher) }}">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Tahun Terbit</label>
                    <input type="number" name="year" class="form-control" min="1900" max="2099" placeholder="Contoh: 2024" oninput="if(this.value.length > 4) this.value = this.value.slice(0, 4);" value="{{ old('year', $book->year) }}">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">ISBN</label>
                    <input type="text" name="isbn" class="form-control" maxlength="30" placeholder="Contoh: 978-602-03-8591-4" value="{{ old('isbn', $book->isbn) }}">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Kategori Buku</label>
                    <select name="category_id" class="form-control">
                        <option value="">- Pilih Kategori -</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" {{ old('category_id', $book->category_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <div class="form-group mb-3">
                    <label class="form-label">Harga Jual (Rp) <span style="color:var(--danger); font-weight:700;">*</span></label>
                    <input type="number" name="price" class="form-control" required min="0" max="999999999" value="{{ old('price', $book->price) }}">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Stok Buku (Pcs) <span style="color:var(--danger); font-weight:700;">*</span></label>
                    <div style="display:flex; align-items:center; max-width:240px;">
                        <button type="button" class="btn btn-outline" style="border-top-right-radius:0; border-bottom-right-radius:0; padding:8px 14px; font-weight:bold; font-size:1.1rem; user-select:none;" onclick="stepStock('input_stock_sale', -1)">−</button>
                        <input type="number" id="input_stock_sale" name="stock_sale" class="form-control" required min="0" max="999999" value="{{ old('stock_sale', $book->stock_sale) }}" style="border-radius:0; text-align:center; font-weight:600;">
                        <button type="button" class="btn btn-outline" style="border-top-left-radius:0; border-bottom-left-radius:0; padding:8px 14px; font-weight:bold; font-size:1.1rem; user-select:none;" onclick="stepStock('input_stock_sale', 1)">+</button>
                    </div>
                    <small class="text-muted" style="display:block; margin-top:4px; font-size:0.75rem;">Bisa diketik langsung atau tekan tombol - / +</small>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Ganti Cover Buku (Biarkan kosong jika tidak diganti)</label>
                    <input type="file" name="cover" class="form-control" accept="image/*">
                    @if($book->cover)
                        <div style="margin-top:10px; display:flex; align-items:center; gap:12px;">
                            <img src="{{ asset('uploads/covers/' . $book->cover) }}" style="height:80px; border-radius:6px; border:1px solid var(--border); object-fit:cover;">
                            <span class="text-muted" style="font-size:0.8rem;">Cover aktif saat ini: <code>{{ $book->cover }}</code></span>
                        </div>
                    @endif
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Sinopsis &amp; Deskripsi Buku</label>
                    <textarea name="description" class="form-control" maxlength="5000" style="min-height:120px;">{{ old('description', $book->description) }}</textarea>
                </div>
            </div>
        </div>
        <div style="margin-top:20px; padding-top:16px; border-top:1px solid var(--border); display:flex; justify-content:flex-end; gap:10px;">
            <a href="/admin/books" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary" style="font-weight:700; padding:10px 24px;">Simpan Perubahan Buku</button>
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