<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    use HasFactory;

    protected $table = 'checkout';

    protected $fillable = [
        'id_penyewa',
        'id_produk',
        'total_harga',
        'status',
        'snapToken',
    ];

    public function penyewa()
    {
        return $this->belongsTo(User::class, 'id_penyewa');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }


}