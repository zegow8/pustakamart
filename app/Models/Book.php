<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Book - PustakaMart BookStore
 * Murni data buku untuk penjualan fisik.
 */
class Book extends Model
{
    protected $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Alias stok penjualan (stock_sale) agar konsisten dengan instrumen BNSP.
     */
    public function getStockAttribute()
    {
        return $this->stock_sale ?? 0;
    }

    /**
     * URL Cover Buku.
     */
    public function getCoverUrlAttribute(): string
    {
        if (!$this->cover) {
            return 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=500&auto=format&fit=crop&q=80';
        }

        if (str_starts_with($this->cover, 'http://') || str_starts_with($this->cover, 'https://')) {
            return $this->cover;
        }

        return asset('uploads/covers/' . $this->cover);
    }
}
