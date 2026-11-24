<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    protected $guarded = ['id'];

    public function user() { 
        return $this->belongsTo(User::class); 
    }

    public function details() { 
        return $this->hasMany(OrderDetail::class); 
    }

    public function items() { 
        return $this->hasMany(OrderDetail::class); 
    }

    public function getInvoiceNumberAttribute()
    {
        return $this->invoice_code ?? $this->order_code ?? '-';
    }

    public function getTotalAmountAttribute()
    {
        return $this->total ?? 0;
    }
}