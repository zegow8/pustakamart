<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;
use App\Models\User;

class PageController extends Controller
{
    /**
     * Halaman Landing Page (Beranda)
     */
    public function index()
    {
        $latestBooks = Book::where('is_active', 1)
            ->orderByRaw('CASE WHEN stock_sale <= 0 THEN 1 ELSE 0 END ASC')
            ->orderBy('id', 'desc')
            ->take(8)
            ->get();
        
        $totalBooks = Book::where('is_active', 1)->count();
        $totalUsers = User::where('role', 'user')->count();
        $totalCategories = Category::count();

        return view('public.index', compact('latestBooks', 'totalBooks', 'totalUsers', 'totalCategories'));
    }

    /**
     * Halaman Katalog Buku Public
     */
    public function catalog(Request $request)
    {
        $query = Book::with('category')->where('is_active', 1);

        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('author', 'like', "%{$keyword}%")
                  ->orWhere('isbn', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $sort = $request->get('sort', 'newest');
        if ($sort === 'out_of_stock') {
            // Khusus filter stok habis: hanya tampilkan buku yang stoknya <= 0
            $query->where('stock_sale', '<=', 0)->orderBy('id', 'desc');
        } else {
            // Buku yang stoknya habis selalu ditaruh di paling bawah
            $query->orderByRaw('CASE WHEN stock_sale <= 0 THEN 1 ELSE 0 END ASC');

            switch ($sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'title':
                    $query->orderBy('title', 'asc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        }

        $books = $query->paginate(10)->withQueryString();
        $categories = Category::withCount(['books' => function($q) {
            $q->where('is_active', 1);
        }])->orderBy('name')->get();

        return view('public.catalog', compact('books', 'categories'));
    }

    /**
     * Halaman Katalog Buku Khusus User Area (10 buku per halaman sesuai instrumen JWD)
     */
    public function userCatalog(Request $request)
    {
        $query = Book::with('category')->where('is_active', 1);

        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('author', 'like', "%{$keyword}%")
                  ->orWhere('isbn', 'like', "%{$keyword}%")
                  ->orWhereHas('category', function($cq) use ($keyword) {
                      $cq->where('name', 'like', "%{$keyword}%");
                  });
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $sort = $request->get('sort', 'newest');
        if ($sort === 'out_of_stock') {
            // Khusus filter stok habis: hanya tampilkan buku yang stoknya <= 0
            $query->where('stock_sale', '<=', 0)->orderBy('id', 'desc');
        } else {
            // Buku yang stoknya habis selalu ditaruh di paling bawah
            $query->orderByRaw('CASE WHEN stock_sale <= 0 THEN 1 ELSE 0 END ASC');

            switch ($sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'title':
                    $query->orderBy('title', 'asc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        }

        // 10 buku per halaman sesuai ketentuan challenge JWD
        $books = $query->paginate(10)->withQueryString();
        $categories = Category::withCount(['books' => function($q) {
            $q->where('is_active', 1);
        }])->orderBy('name')->get();

        return view('user.catalog', compact('books', 'categories'));
    }

    /**
     * Halaman Detail Buku
     */
    public function bookDetail($id)
    {
        $book = Book::with('category')->where('is_active', 1)->findOrFail($id);

        $relatedBooks = [];
        if ($book->category_id) {
            $relatedBooks = Book::where('category_id', $book->category_id)
                                ->where('id', '!=', $id)
                                ->where('is_active', 1)
                                ->inRandomOrder()
                                ->take(4)
                                ->get();
        }

        return view('public.book', compact('book', 'relatedBooks'));
    }

    /**
     * Halaman Tentang Kami
     */
    public function about()
    {
        return view('public.about');
    }

    /**
     * Halaman Hubungi Kami (Contact Form)
     */
    public function contact()
    {
        return view('public.contact');
    }

    /**
     * Submit Pesan Hubungi Kami
     */
    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'subject' => 'required|string|max:150',
            'message' => 'required|string|max:2000',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'subject.required' => 'Topik pesan wajib diisi.',
            'message.required' => 'Isi pesan wajib diisi.',
        ]);

        // Simpan pesan ke database agar masuk ke dashboard admin
        \App\Models\Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'is_read' => false,
        ]);

        if (\Illuminate\Support\Facades\Auth::check()) {
            $user = \Illuminate\Support\Facades\Auth::user();
            \App\Models\Chat::create([
                'user_id' => $user->id,
                'sender' => 'user',
                'message' => "[Formulir Kontak: {$request->subject}]\n" . $request->message,
                'is_read' => 0,
            ]);

            return redirect('/user/chat')->with('success', 'Pesan Anda berhasil dikirim ke LiveChat & Kotak Masuk Admin!');
        }

        return back()->with('success', 'Pesan Anda berhasil terkirim ke Administrator! Tim Customer Service PustakaMart akan segera memproses pesan Anda.');
    }
}
