<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoProduk extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_produk',
        'path',
    ];

    public function FotoProduk()
    {
        return $this->belongsTo(Produk::class, 'id')->withDefault();
    }
}