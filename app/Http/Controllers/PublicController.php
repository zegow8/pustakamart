<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Contact;
use Illuminate\Http\Request;

/**
 * Controller Halaman Publik PustakaMart
 * Sesuai butir FR.IA.02:
 * - Landing Page
 * - Pencarian Buku & Filter Kategori
 * - Detail Buku
 * - Halaman About Us
 * - Contact to Admin (Form kirim pesan ke admin)
 */
class PublicController extends Controller
{
    /**
     * Halaman Utama (Landing Page PustakaMart).
     */
    public function index()
    {
        $categories = Category::withCount('books')->get();
        $featuredBooks = Book::with('category')->latest()->take(6)->get();
        $totalBooks = Book::count();
        $totalCategories = Category::count();

        return view('public.home', compact('categories', 'featuredBooks', 'totalBooks', 'totalCategories'));
    }

    /**
     * Halaman Katalog & Pencarian Buku.
     */
    public function catalog(Request $request)
    {
        $query = Book::with('category');

        // Fitur Pencarian berdasarkan Judul atau Penulis
        if ($request->filled('q')) {
            $searchTerm = '%' . $request->q . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', $searchTerm)
                  ->orWhere('author', 'like', $searchTerm)
                  ->orWhere('publisher', 'like', $searchTerm);
            });
        }

        // Filter berdasarkan Kategori
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Pengurutan (Sorting)
        if ($request->sort === 'price_low') {
            $query->orderBy('price', 'asc');
        } elseif ($request->sort === 'price_high') {
            $query->orderBy('price', 'desc');
        } elseif ($request->sort === 'year') {
            $query->orderBy('year', 'desc');
        } else {
            $query->latest();
        }

        $books = $query->paginate(9)->withQueryString();
        $categories = Category::withCount('books')->get();

        return view('public.catalog', compact('books', 'categories'));
    }

    /**
     * Halaman Detail Buku.
     */
    public function bookDetail($id)
    {
        $book = Book::with('category')->findOrFail($id);
        $relatedBooks = Book::where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->take(3)
            ->get();

        return view('public.book-detail', compact('book', 'relatedBooks'));
    }

    /**
     * Halaman About Us (Profil Toko & Informasi Platform).
     */
    public function about()
    {
        return view('public.about');
    }

    /**
     * Halaman Contact to Admin (Formulir Kontak).
     */
    public function contact()
    {
        return view('public.contact');
    }

    /**
     * Memproses pengiriman pesan ke Admin.
     */
    public function storeContact(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10'],
        ], [
            'name.required' => 'Nama pengirim wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'subject.required' => 'Subjek pesan wajib diisi.',
            'message.required' => 'Isi pesan wajib diisi.',
            'message.min' => 'Isi pesan minimal 10 karakter.',
        ]);

        Contact::create($validated);

        return back()->with('success', 'Terima kasih! Pesan Anda telah berhasil dikirimkan ke tim Administrator PustakaMart.');
    }
}
