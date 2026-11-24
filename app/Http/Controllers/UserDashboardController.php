<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    /**
     * Dashboard Utama Pengguna PustakaMart (Toko Buku)
     */
    public function index()
    {
        $userId = Auth::id();

        // 1. STATISTIK PESANAN BELANJA BUKU
        $ordWaiting    = Order::where('user_id', $userId)->where('status', 'Waiting Confirmation')->count();
        $ordProcessing = Order::where('user_id', $userId)->where('status', 'Processing')->count();
        $ordShipped    = Order::where('user_id', $userId)->where('status', 'Shipped')->count();
        $ordCompleted  = Order::where('user_id', $userId)->where('status', 'Completed')->count();
        $ordCancelled  = Order::where('user_id', $userId)->where('status', 'Cancelled')->count();
        $totalOrders   = Order::where('user_id', $userId)->count();

        // 2. PESANAN TERBARU USER
        $recentOrders = Order::where('user_id', $userId)
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        // 3. REKOMENDASI BUKU TERBARU UNTUK DIBELI
        $recommendedBooks = Book::with('category')
            ->where('stock_sale', '>', 0)
            ->orderBy('id', 'desc')
            ->take(4)
            ->get();

        return view('user.dashboard', compact(
            'ordWaiting', 'ordProcessing', 'ordShipped', 'ordCompleted', 'ordCancelled', 'totalOrders',
            'recentOrders', 'recommendedBooks'
        ));
    }
}
