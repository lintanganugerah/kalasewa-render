<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Review extends Model
{
    use HasFactory;

    protected $table = 'review';

    protected $fillable = [
        'id_penyewa',
        'id_toko',
        'id_produk',
        'komentar',
        'nilai',
        'foto',
        'tipe',
    ];

    protected $casts = [
        'nilai' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_penyewa');
    }


    public function id_review_penyewa()
    {
        return $this->belongsTo(User::class, 'id_penyewa', 'id');
    }

    public function id_review_toko()
    {
        return $this->belongsTo(Toko::class, 'id_toko', 'id');
    }

    public function id_review_produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id');
    }

    public function produk_series($id_series)
    {
        $series = Series::where('id', $id_series)->first();

        return $series;
    }

    public function foto_produk_review($id_produk)
    {
        $foto = FotoProduk::where('id_produk', $id_produk)->first();

        return $foto;
    }

    public function getFotoProfilToko($id_toko)
    {
        $toko = Toko::where('id', $id_toko)->first();
        $foto = User::where('id', $toko->id_user)->first();

        return $foto;
    }
}