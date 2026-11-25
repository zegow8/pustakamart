<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function revenue(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $orders = Order::with(['user', 'details'])
            ->whereIn('status', ['Processing', 'Shipped', 'Completed'])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalRevenue = $orders->sum('total');
        $totalOrders = $orders->count();
        $avgOrder = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        $bcaTotal = $orders->where('payment_method', 'Transfer BCA')->sum('total');
        $mandiriTotal = $orders->where('payment_method', 'Transfer Mandiri')->sum('total');

        // Kelompokkan pendapatan per tanggal untuk grafik
        $grouped = $orders->groupBy(function($item) {
            return $item->created_at->format('d M');
        })->reverse();

        $chartLabels = $grouped->keys()->values();
        $chartData = $grouped->map(function($dayOrders) {
            return (float) $dayOrders->sum('total');
        })->values();

        return view('admin.reports.revenue', compact(
            'orders', 'startDate', 'endDate',
            'totalRevenue', 'totalOrders', 'avgOrder',
            'bcaTotal', 'mandiriTotal',
            'chartLabels', 'chartData'
        ));
    }
}
