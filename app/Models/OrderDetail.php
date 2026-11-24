<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model {
    protected $guarded = ['id'];
    public $timestamps = false; // WAJIB: Gak ada created_at/updated_at
    
    public function book() { 
        return $this->belongsTo(Book::class); 
    }

    public function getPriceAttribute($val)
    {
        return $val ?? $this->price_snapshot ?? $this->book?->price ?? 0;
    }

    public function getTitleAttribute($val)
    {
        return $val ?? $this->title_snapshot ?? $this->book?->title ?? '-';
    }
}