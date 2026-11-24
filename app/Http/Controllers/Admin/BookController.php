<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;

/**
 * Controller Manajemen Data Buku (Admin PustakaMart)
 * Murni pengelolaan data buku fisik untuk penjualan.
 */
class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with(['category']);

        // Search by Nama / Judul / Penulis
        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('author', 'like', "%{$keyword}%")
                  ->orWhere('isbn', 'like', "%{$keyword}%");
            });
        }

        // Filter by Kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by Harga Min & Max
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter by Stok Penjualan
        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'sale_available') {
                $query->where('stock_sale', '>', 0);
            } elseif ($request->stock_status === 'sale_empty') {
                $query->where('stock_sale', '<=', 0);
            }
        }

        $books = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();
        $categories = Category::withCount('books')->orderBy('name')->get();
        $totalAvailable = Book::where('stock_sale', '>', 0)->count();
        $totalEmpty = Book::where('stock_sale', '<=', 0)->count();

        return view('admin.books.index', compact('books', 'categories', 'totalAvailable', 'totalEmpty'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100', 
            'author' => 'required|string|max:100', 
            'publisher' => 'required|string|max:100',
            'year' => 'required|integer|digits:4|min:1900|max:2099',
            'isbn' => 'required|string|max:30',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0|max:999999999',
            'stock_sale' => 'required|integer|min:0|max:999999', 
            'description' => 'required|string|max:5000',
            'cover' => 'required|file|mimes:jpeg,png,jpg,webp,jfif,avif,gif|max:10240'
        ], [
            'title.required' => 'Judul buku wajib diisi.',
            'author.required' => 'Penulis buku wajib diisi.',
            'publisher.required' => 'Penerbit buku wajib diisi.',
            'year.required' => 'Tahun terbit buku wajib diisi.',
            'year.digits' => 'Tahun terbit harus berupa 4 digit angka.',
            'year.min' => 'Tahun terbit minimal tahun 1900.',
            'year.max' => 'Tahun terbit maksimal tahun 2099.',
            'isbn.required' => 'ISBN buku wajib diisi.',
            'category_id.required' => 'Kategori buku wajib dipilih.',
            'category_id.exists' => 'Kategori buku yang dipilih tidak valid.',
            'price.required' => 'Harga jual buku wajib diisi.',
            'price.numeric' => 'Harga buku harus berupa angka.',
            'price.min' => 'Harga buku tidak boleh kurang dari 0.',
            'stock_sale.required' => 'Stok buku wajib diisi.',
            'stock_sale.integer' => 'Stok buku harus berupa angka bulat.',
            'stock_sale.min' => 'Stok buku tidak boleh kurang dari 0.',
            'description.required' => 'Sinopsis / deskripsi buku wajib diisi.',
            'cover.required' => 'File cover buku wajib diunggah.',
            'cover.file' => 'Cover buku harus berupa file gambar.',
            'cover.mimes' => 'Format file cover harus berupa gambar (JPG, PNG, WEBP).',
            'cover.max' => 'Ukuran file cover maksimal adalah 10 MB.',
        ]);

        // Cek duplikasi judul
        $cleanTitle = trim($request->title);
        $duplicate = Book::whereRaw('LOWER(TRIM(title)) = ?', [strtolower($cleanTitle)])->first();
        if ($duplicate) {
            return back()->withInput()->with('error', "Peringatan: Buku dengan judul '{$cleanTitle}' sudah terdaftar di sistem (ID Buku: #{$duplicate->id}).");
        }

        $data = $request->except(['cover']);
        
        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $uploadDir = public_path('uploads/covers');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $ext = $file->getClientOriginalExtension() ?: 'jpg';
            $rawName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $cleanName = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $rawName);
            $filename = time() . '_' . substr($cleanName, 0, 50) . '.' . strtolower($ext);

            $file->move($uploadDir, $filename);
            $data['cover'] = $filename;
        }

        Book::create($data);
        return redirect('/admin/books')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $book = Book::findOrFail($id);
        $categories = Category::orderBy('name')->get();
        return view('admin.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:100', 
            'author' => 'required|string|max:100', 
            'publisher' => 'nullable|string|max:100',
            'year' => 'nullable|integer|digits:4|min:1900|max:2099',
            'isbn' => 'nullable|string|max:30',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|numeric|min:0|max:999999999',
            'stock_sale' => 'required|integer|min:0|max:999999', 
            'description' => 'nullable|string|max:5000',
            'cover' => 'nullable|file|mimes:jpeg,png,jpg,webp,jfif,avif,gif|max:10240'
        ], [
            'title.required' => 'Judul buku wajib diisi.',
            'author.required' => 'Penulis buku wajib diisi.',
            'price.required' => 'Harga buku wajib diisi.',
            'stock_sale.required' => 'Stok buku wajib diisi.',
        ]);

        $cleanTitle = trim($request->title);
        $duplicate = Book::whereRaw('LOWER(TRIM(title)) = ?', [strtolower($cleanTitle)])
            ->where('id', '!=', $id)
            ->first();
        if ($duplicate) {
            return back()->withInput()->with('error', "Peringatan: Buku dengan judul '{$cleanTitle}' sudah ada pada buku lain (ID Buku: #{$duplicate->id}).");
        }

        $data = $request->except(['cover']);
        
        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $uploadDir = public_path('uploads/covers');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $ext = $file->getClientOriginalExtension() ?: 'jpg';
            $rawName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $cleanName = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $rawName);
            $filename = time() . '_' . substr($cleanName, 0, 50) . '.' . strtolower($ext);

            if ($book->cover && file_exists($uploadDir . '/' . $book->cover)) {
                @unlink($uploadDir . '/' . $book->cover);
            }

            $file->move($uploadDir, $filename);
            $data['cover'] = $filename;
        }

        $book->update($data);
        return redirect('/admin/books')->with('success', 'Buku berhasil diupdate.');
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);

        // Proteksi: Cek apakah ada pesanan yang sedang berjalan
        $activeOrderCount = \App\Models\OrderDetail::where('book_id', $id)
            ->whereHas('order', function($q) {
                $q->whereIn('status', ['Waiting Confirmation', 'Processing', 'Shipped']);
            })->count();

        if ($activeOrderCount > 0) {
            return back()->with('error', "Buku '{$book->title}' tidak dapat dihapus karena masih ada pesanan berjalan yang belum selesai.");
        }

        // Hapus dari keranjang user jika ada
        \App\Models\CartDetail::where('book_id', $id)->delete();

        // Putus relasi di order_details selesai jika memungkinkan, agar riwayat pesanan tetap tercatat
        try {
            \App\Models\OrderDetail::where('book_id', $id)->update(['book_id' => null]);
        } catch (\Throwable $e) {
            // Kolom book_id tidak nullable, lanjutkan
        }

        if ($book->cover && file_exists(public_path('uploads/covers/' . $book->cover))) {
            @unlink(public_path('uploads/covers/' . $book->cover));
        }

        try {
            $book->delete();
        } catch (\Throwable $e) {
            return back()->with('error', "Buku '{$book->title}' tidak dapat dihapus karena masih terkait dengan data transaksi riwayat pesanan.");
        }

        return redirect('/admin/books')->with('success', 'Buku berhasil dihapus.');
    }

    public function printBooks(Request $request)
    {
        $query = Book::with('category')->orderBy('title', 'asc');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $books = $query->get();
        return view('admin.books.print', compact('books'));
    }
}
