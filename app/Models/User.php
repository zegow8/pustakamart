<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Model User - PustakaMart BookStore
 * Pengelolaan autentikasi Pembeli & Administrator (Murni Penjualan Buku).
 */
class User extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password', 'nik', 'phone', 'address', 'role', 'is_active'];
    protected $hidden = ['password'];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }
}
