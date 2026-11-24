<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;
use App\Models\Order;
use App\Models\Category;

class DashboardController extends Controller
{
    /**
     * Dashboard Utama Admin PustakaMart
     */
    public function index()
    {
        $totalBooks = Book::count();
        $totalCategories = Category::count();
        $totalUsers = User::where('role', 'user')->count();
        $totalOrders = Order::count();
        $totalStock = Book::sum('stock_sale');

        // Total Pendapatan Penjualan
        $revenueThisMonth = Order::whereIn('status', ['Processing', 'Shipped', 'Completed'])
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->sum('total');

        $revenueAllTime = Order::whereIn('status', ['Processing', 'Shipped', 'Completed'])
            ->sum('total');

        // Statistik Status Pesanan
        $ordWaiting    = Order::where('status', 'Waiting Confirmation')->count();
        $ordProcessing = Order::where('status', 'Processing')->count();
        $ordShipped    = Order::where('status', 'Shipped')->count();
        $ordCompleted  = Order::where('status', 'Completed')->count();
        $ordCancelled  = Order::where('status', 'Cancelled')->count();

        // Pesanan Terbaru yang Masuk
        $recentOrders = Order::with('user')
            ->orderBy('id', 'desc')
            ->take(6)
            ->get();

        // User Baru Terdaftar
        $recentUsers = User::where('role', 'user')
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        // Stok Buku Menipis (< 5 pcs)
        $lowStockBooks = Book::where('stock_sale', '<', 5)
            ->orderBy('stock_sale', 'asc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalBooks', 'totalCategories', 'totalUsers', 'totalOrders', 'totalStock',
            'revenueThisMonth', 'revenueAllTime',
            'ordWaiting', 'ordProcessing', 'ordShipped', 'ordCompleted', 'ordCancelled',
            'recentOrders', 'recentUsers', 'lowStockBooks'
        ));
    }
}
