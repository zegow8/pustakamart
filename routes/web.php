<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\ProfileController;

// ================= PUBLIC =================
Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/catalog', [PageController::class, 'catalog'])->name('catalog');
Route::get('/book/{id}', [PageController::class, 'bookDetail'])->name('book.detail');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.store');

// ================= AUTH =================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ================= USER AREA (DILINDUNGI MIDDLEWARE USER) =================
Route::middleware(['user'])->group(function () {
    Route::get('/user', fn() => redirect('/user/dashboard'));
    Route::get('/user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::get('/user/catalog', [PageController::class, 'userCatalog'])->name('user.catalog');
    
    // Chat ke Admin
    Route::get('/user/chat', [App\Http\Controllers\User\ChatController::class, 'index'])->name('user.chat');
    Route::post('/user/chat/send', [App\Http\Controllers\User\ChatController::class, 'send']);
    
    // Keranjang
    Route::get('/user/cart', [CartController::class, 'index']);
    Route::post('/user/cart/add', [CartController::class, 'add']);
    Route::post('/user/cart/update/{id}', [CartController::class, 'update']);
    Route::post('/user/cart/remove/{id}', [CartController::class, 'remove']);

    // Checkout
    Route::get('/user/checkout', [CheckoutController::class, 'index']);
    Route::post('/user/checkout', [CheckoutController::class, 'process']);

    // Transaksi Pesanan
    Route::get('/user/transactions', [OrderController::class, 'index']);
    Route::get('/user/transactions/{id}', [OrderController::class, 'show']);
    Route::get('/user/transactions/{id}/print', [OrderController::class, 'printInvoice']);
    Route::post('/user/transactions/{id}/upload-receipt', [OrderController::class, 'uploadReceipt']);
    Route::post('/user/transactions/cancel', [OrderController::class, 'cancel']);

    // Notifikasi
    Route::get('/user/notifications', [App\Http\Controllers\User\NotificationController::class, 'index']);
    Route::get('/user/notifications/{id}/read', [App\Http\Controllers\User\NotificationController::class, 'markRead']);
    Route::post('/user/notifications/read-all', [App\Http\Controllers\User\NotificationController::class, 'markAllRead']);

    // Profil
    Route::get('/user/profile', [ProfileController::class, 'index']);
    Route::post('/user/profile', [ProfileController::class, 'update']);
    Route::post('/user/profile/password', [ProfileController::class, 'changePassword']);
});

// ================= ADMIN AREA (DILINDUNGI MIDDLEWARE ADMIN) =================
Route::middleware(['admin'])->group(function () {
    Route::get('/admin', fn() => redirect('/admin/dashboard'));
    
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index']);

        // Chat Pelanggan / User
        Route::get('/chats', [App\Http\Controllers\Admin\ChatController::class, 'index'])->name('admin.chats');
        Route::post('/chats/send', [App\Http\Controllers\Admin\ChatController::class, 'send']);

        // Pesan Kontak Masuk (Contact to Admin)
        Route::get('/contacts', [App\Http\Controllers\Admin\ContactController::class, 'index'])->name('admin.contacts.index');
        Route::post('/contacts/{id}/toggle-read', [App\Http\Controllers\Admin\ContactController::class, 'toggleRead'])->name('admin.contacts.toggle');
        Route::get('/contacts/{id}/toggle-read', [App\Http\Controllers\Admin\ContactController::class, 'toggleRead']);
        Route::post('/contacts/{id}/read', [App\Http\Controllers\Admin\ContactController::class, 'markAsRead'])->name('admin.contacts.read');
        Route::put('/contacts/{id}/read', [App\Http\Controllers\Admin\ContactController::class, 'markAsRead']);
        Route::post('/contacts/{id}/delete', [App\Http\Controllers\Admin\ContactController::class, 'destroy'])->name('admin.contacts.destroy');
        Route::delete('/contacts/{id}', [App\Http\Controllers\Admin\ContactController::class, 'destroy']);

        // Buku & Kategori
    Route::get('/books', [App\Http\Controllers\Admin\BookController::class, 'index']);
    Route::get('/books/print', [App\Http\Controllers\Admin\BookController::class, 'printBooks']);
    Route::get('/books/create', [App\Http\Controllers\Admin\BookController::class, 'create']);
    Route::post('/books', [App\Http\Controllers\Admin\BookController::class, 'store']);
    Route::get('/books/edit/{id}', [App\Http\Controllers\Admin\BookController::class, 'edit']);
    Route::post('/books/update/{id}', [App\Http\Controllers\Admin\BookController::class, 'update']);
    Route::post('/books/delete/{id}', [App\Http\Controllers\Admin\BookController::class, 'destroy']);

    Route::get('/categories', [App\Http\Controllers\Admin\CategoryController::class, 'index']);
    Route::post('/categories', [App\Http\Controllers\Admin\CategoryController::class, 'store']);
    Route::post('/categories/update/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'update']);
    Route::post('/categories/delete/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'destroy']);

    // List Pesanan Buku dari Berbagai User (Sesuai PDF BNSP)
    Route::get('/orders', [App\Http\Controllers\Admin\OrderController::class, 'index']);
    Route::get('/orders/print', [App\Http\Controllers\Admin\OrderController::class, 'printOrders']);
    Route::get('/orders/{id}/print', [App\Http\Controllers\Admin\OrderController::class, 'printInvoice']);
    Route::get('/orders/{id}', [App\Http\Controllers\Admin\OrderController::class, 'show']);
    Route::post('/orders/update/{id}', [App\Http\Controllers\Admin\OrderController::class, 'update']);

    // Laporan Pendapatan
    Route::get('/reports/revenue', [App\Http\Controllers\Admin\ReportController::class, 'revenue']);

    // List User Terdaftar (Sesuai PDF BNSP)
    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index']);
    Route::match(['get', 'post'], '/users/reset-password/{id}', [App\Http\Controllers\Admin\UserController::class, 'resetPassword'])->whereNumber('id');
    Route::match(['get', 'post'], '/users/{id}/reset-password', [App\Http\Controllers\Admin\UserController::class, 'resetPassword'])->whereNumber('id');
    Route::post('/users/toggle', [App\Http\Controllers\Admin\UserController::class, 'toggleActive']);
    Route::get('/users/{id}', [App\Http\Controllers\Admin\UserController::class, 'show'])->whereNumber('id');
    
    // Profil Admin
    Route::get('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'index']);
    Route::post('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'update']);
    Route::post('/profile/password', [App\Http\Controllers\Admin\ProfileController::class, 'changePassword']);
    });
});
