<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Book;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'details.book'])->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function($q) use ($keyword) {
                $q->where('invoice_code', 'like', "%{$keyword}%")
                  ->orWhere('receiver_name', 'like', "%{$keyword}%")
                  ->orWhere('receiver_phone', 'like', "%{$keyword}%")
                  ->orWhere('receiver_nik', 'like', "%{$keyword}%")
                  ->orWhereHas('user', function($uq) use ($keyword) {
                      $uq->where('name', 'like', "%{$keyword}%")
                         ->orWhere('email', 'like', "%{$keyword}%");
                  });
            });
        }

        $orders = $query->paginate(10)->withQueryString();

        // Hitung count untuk tab
        $counts = [
            'all' => Order::count(),
            'Waiting Confirmation' => Order::where('status', 'Waiting Confirmation')->count(),
            'Processing' => Order::where('status', 'Processing')->count(),
            'Shipped' => Order::where('status', 'Shipped')->count(),
            'Completed' => Order::where('status', 'Completed')->count(),
            'Cancelled' => Order::where('status', 'Cancelled')->count(),
            'Rejected' => Order::where('status', 'Rejected')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'counts'));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'details.book'])->findOrFail($id);
        return view('admin.orders.detail', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::with('details')->findOrFail($id);
        $newStatus = $request->status;

        if (!$newStatus) {
            return back()->with('error', 'Silakan tentukan status tindakan pesanan.');
        }

        // VALIDASI KETAT ALUR STATUS SESUAI ATURAN:
        // 1. Menunggu Konfirmasi -> Hanya bisa ke Dikemas (Processing) atau Ditolak (Rejected)
        if ($order->status === 'Waiting Confirmation') {
            if (!in_array($newStatus, ['Processing', 'Rejected'])) {
                return back()->with('error', 'Dari status Menunggu Konfirmasi, pilihan hanya Dikemas atau Tolak.');
            }
        }
        // 2. Dikemas -> Hanya bisa ke Dikirim (Shipped)
        elseif ($order->status === 'Processing') {
            if ($newStatus !== 'Shipped') {
                return back()->with('error', 'Dari status Dikemas, pilihan hanya Dikirim.');
            }
        }
        // 3. Dikirim -> Hanya bisa ke Selesai (Completed)
        elseif ($order->status === 'Shipped') {
            if ($newStatus !== 'Completed') {
                return back()->with('error', 'Dari status Dikirim, pilihan hanya Selesai.');
            }
        }
        // 4. Status Final (Completed, Rejected, Cancelled)
        elseif (in_array($order->status, ['Completed', 'Rejected', 'Cancelled'])) {
            return back()->with('error', 'Status pesanan sudah final dan tidak dapat diubah lagi.');
        }

        // Jika pesanan ditolak, kembalikan stok buku secara otomatis
        if ($newStatus === 'Rejected' && $order->status !== 'Rejected') {
            foreach ($order->details as $d) {
                Book::where('id', $d->book_id)->increment('stock_sale', $d->qty);
            }
        }

        $trackingNumber = $order->tracking_number;
        if ($request->filled('tracking_number')) {
            $trackingNumber = $request->tracking_number;
        }

        $order->update([
            'status' => $newStatus, 
            'tracking_number' => $trackingNumber
        ]);

        // Kirim Notifikasi Otomatis ke User
        $notifTitle = 'Update Status Pesanan #' . $order->invoice_code;
        $notifMsg = "Status pesanan #{$order->invoice_code} diperbarui menjadi: {$newStatus}.";
        if ($newStatus === 'Processing') {
            $notifTitle = 'Pembayaran Diterima! 📦';
            $notifMsg = "Pembayaran untuk pesanan #{$order->invoice_code} telah di-ACC admin. Buku sedang dikemas rapi.";
        } elseif ($newStatus === 'Shipped') {
            $notifTitle = 'Pesanan Sedang Dikirim! 🚚';
            $notifMsg = "Pesanan #{$order->invoice_code} telah diserahkan ke kurir ekspedisi. No. Resi: {$trackingNumber}";
        } elseif ($newStatus === 'Completed') {
            $notifTitle = 'Pesanan Selesai! ✅';
            $notifMsg = "Terima kasih telah berbelanja di PustakaMart! Pesanan #{$order->invoice_code} telah selesai diterima.";
        } elseif ($newStatus === 'Rejected') {
            $notifTitle = 'Pesanan Ditolak ❌';
            $notifMsg = "Mohon maaf, pesanan #{$order->invoice_code} ditolak karena bukti pembayaran tidak sesuai. Stok telah dikembalikan.";
        }

        \App\Models\Notification::create([
            'user_id' => $order->user_id,
            'title' => $notifTitle,
            'message' => $notifMsg,
            'link' => '/user/transactions/' . $order->id
        ]);

        return back()->with('success', 'Status pesanan berhasil diperbarui menjadi: ' . $newStatus . '.');
    }

    public function printInvoice($id)
    {
        $order = Order::with(['details.book', 'user'])->findOrFail($id);
        return view('common.invoice_print', compact('order'));
    }

    public function printOrders(Request $request)
    {
        $query = Order::with(['user', 'details.book'])->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $orders = $query->get();
        $totalSales = $orders->whereIn('status', ['Processing', 'Shipped', 'Completed'])->sum('total');

        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $period = 'Semua Periode';
        if ($startDate && $endDate) {
            $period = date('d F Y', strtotime($startDate)) . ' s/d ' . date('d F Y', strtotime($endDate));
        } elseif ($startDate) {
            $period = 'Mulai ' . date('d F Y', strtotime($startDate));
        } elseif ($endDate) {
            $period = 'Sampai ' . date('d F Y', strtotime($endDate));
        }

        return view('admin.orders.print', compact('orders', 'totalSales', 'period'));
    }
}