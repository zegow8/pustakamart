<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller Dashboard User
 * Sesuai instruksi: Dashboard Admin dan User berbeda.
 * Menampilkan ringkasan status belanjaan, riwayat pesanan, dan profil user.
 */
class UserDashboardController extends Controller
{
    /**
     * Halaman Dashboard Utama Pengguna/Pembeli.
     */
    public function index()
    {
        $user = Auth::user();
        
        $orders = Order::where('user_id', $user->id)
            ->with('details.book')
            ->latest()
            ->take(5)
            ->get();

        $totalOrders = Order::where('user_id', $user->id)->count();
        $pendingOrders = Order::where('user_id', $user->id)->whereIn('status', ['Waiting Confirmation', 'Processing', 'Shipped'])->count();
        $completedOrders = Order::where('user_id', $user->id)->where('status', 'Completed')->count();
        $totalSpent = Order::where('user_id', $user->id)->whereIn('status', ['Processing', 'Shipped', 'Completed'])->sum('total');

        return view('user.dashboard', compact('user', 'orders', 'totalOrders', 'pendingOrders', 'completedOrders', 'totalSpent'));
    }

    /**
     * Menampilkan seluruh riwayat pesanan buku user.
     */
    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('items.book')
            ->latest()
            ->paginate(10);

        return view('user.orders', compact('orders'));
    }

    /**
     * Menampilkan rincian spesifik satu pesanan buku.
     */
    public function orderDetail($id)
    {
        $order = Order::where('user_id', Auth::id())
            ->with(['items.book.category', 'user'])
            ->findOrFail($id);

        return view('user.order-detail', compact('order'));
    }

    /**
     * Memperbarui profil data pengiriman user.
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return back()->with('success', 'Profil dan alamat pengiriman Anda berhasil diperbarui.');
    }
}
