<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migrasi pembuatan tabel orders.
     * Sesuai butir FR.IA.02: 
     * - Admin: List pesanan buku dari user yang berbeda-beda
     * - User: Payment at Delivery (Pembayaran buku sebelum buku dikirim)
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('total_amount', 12, 2);
            $table->enum('status', ['pending', 'paid', 'processing', 'shipped', 'completed', 'cancelled'])->default('pending');
            $table->string('payment_method')->default('transfer_bank');
            $table->string('payment_receipt')->nullable();
            $table->text('shipping_address');
            $table->string('phone');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Membalikkan migrasi (drop tabel).
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
