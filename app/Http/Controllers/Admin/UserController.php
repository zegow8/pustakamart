<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'user')
            ->withCount(['orders'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%")
                  ->orWhere('nik', 'like', "%{$keyword}%")
                  ->orWhere('phone', 'like', "%{$keyword}%");
            });
        }

        $users = $query->paginate(10)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    public function resetPassword(Request $request, $id)
    {
        $user = User::where('role', 'user')->findOrFail($id);
        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make('password123')
        ]);

        return redirect('/admin/users')->with('success', "Password akun '{$user->name}' berhasil direset menjadi: password123");
    }

    public function show($id)
    {
        $user = User::where('role', 'user')
            ->with([
                'orders' => function($q) {
                    $q->orderBy('created_at', 'desc')->with('details.book');
                }
            ])
            ->findOrFail($id);

        $totalSpent = $user->orders->whereIn('status', ['Processing', 'Shipped', 'Completed'])->sum('total');
        $totalOrdersCount = $user->orders->count();

        return view('admin.users.show', compact(
            'user',
            'totalSpent',
            'totalOrdersCount'
        ));
    }

    public function toggleActive(Request $request)
    {
        $user = User::where('role', 'user')->findOrFail($request->id);
        $user->update(['is_active' => $request->is_active]);
        
        $status = $request->is_active == 1 ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Akun user {$user->name} berhasil {$status}.");
    }
}
