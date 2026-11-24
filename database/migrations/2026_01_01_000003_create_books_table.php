<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migrasi pembuatan tabel books.
     * Sesuai butir FR.IA.02: Admin CRUD Data Buku & User Pencarian/Pembelian Buku.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('title');
            $table->string('author');
            $table->string('publisher');
            $table->integer('year');
            $table->decimal('price', 12, 2);
            $table->integer('stock')->default(0);
            $table->string('cover_image')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Membalikkan migrasi (drop tabel).
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
