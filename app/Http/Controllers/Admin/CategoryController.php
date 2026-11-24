<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::withCount('books')
            ->with(['books' => function($q) {
                $q->select('id', 'category_id', 'title', 'author', 'publisher', 'year', 'cover', 'price', 'stock_sale')
                  ->orderBy('id', 'desc');
            }]);

        if ($request->filled('q')) {
            $keyword = trim($request->q);
            $query->where('name', 'like', "%{$keyword}%");
        }

        $categories = $query->orderBy('name')->get();

        $categoriesJson = $categories->mapWithKeys(function($c) {
            return [$c->id => [
                'id' => $c->id,
                'name' => $c->name,
                'description' => $c->description,
                'count' => $c->books_count,
                'books' => $c->books->map(function($b) {
                    return [
                        'id' => $b->id,
                        'title' => $b->title,
                        'author' => $b->author,
                        'publisher' => $b->publisher,
                        'year' => $b->year,
                        'price' => number_format($b->price, 0, ',', '.'),
                        'stock_sale' => $b->stock_sale,
                        'cover_url' => $b->cover ? asset('uploads/covers/' . $b->cover) : null,
                    ];
                })
            ]];
        });

        return view('admin.categories.index', compact('categories', 'categoriesJson'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:categories,name',
            'description' => 'nullable|string|max:50'
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.max' => 'Nama kategori maksimal 50 karakter.',
            'name.unique' => 'Nama kategori sudah terdaftar.',
            'description.max' => 'Deskripsi kategori maksimal 50 karakter.'
        ]);

        $name = trim($request->name);
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        $slug = substr($slug, 0, 50);

        Category::create([
            'name' => $name,
            'slug' => $slug,
            'description' => $request->filled('description') ? trim($request->description) : null
        ]);

        return back()->with('success', "Kategori '{$name}' berhasil ditambahkan.");
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:50|unique:categories,name,' . $id,
            'description' => 'nullable|string|max:50'
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.max' => 'Nama kategori maksimal 50 karakter.',
            'name.unique' => 'Nama kategori sudah digunakan.',
            'description.max' => 'Deskripsi kategori maksimal 50 karakter.'
        ]);

        $name = trim($request->name);
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        $slug = substr($slug, 0, 50);

        $category->update([
            'name' => $name,
            'slug' => $slug,
            'description' => $request->filled('description') ? trim($request->description) : null
        ]);

        return back()->with('success', "Kategori berhasil diperbarui menjadi '{$name}'.");
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        $bookCount = \App\Models\Book::where('category_id', $id)->count();
        if ($bookCount > 0) {
            return back()->with('error', "Kategori '{$category->name}' tidak dapat dihapus karena masih digunakan oleh {$bookCount} buku.");
        }

        try {
            $category->delete();
            return back()->with('success', 'Kategori berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', "Kategori '{$category->name}' tidak dapat dihapus karena masih terhubung dengan data lain.");
        }
    }
}