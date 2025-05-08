<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use HasFactory;

    use SoftDeletes;
    protected $fillable = [
        'nama_prouk',
        'id_toko',
        'id_series',
        'ukuran_produk',
        'status_produk',
        'deskripsi_produk',
        'brand',
        'biaya_cuci',
        'brand_wig',
        'keterangan_wig',
        'additional',
        'harga',
        'gender',
        'metode_kirim'
    ];

    protected $casts = [
        'metode_kirim' => 'array',
    ];

    protected $dates = ['deleted_at'];

    public function FotoProduk()
    {
        return $this->hasOne(FotoProduk::class, 'id_produk')->withDefault();
    }

    public function series()
    {
        return $this->belongsTo(Series::class, 'id_series', 'id')->withDefault();
    }

    public function seriesDetail()
    {
        return $this->belongsTo(Series::class, 'id_series')->withDefault();
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'produk_id');
    }

    public function isInWishlist()
    {
        // Ambil user yang sedang login
        $user = auth()->user();

        // Periksa apakah produk ada dalam wishlist user
        return $user->wishlist()->where('produk_id', $this->id)->exists();
    }

    public function getFotoProdukFirst($id_produk)
    {
        $FotoProduk = FotoProduk::where('id_produk', $id_produk)->first();

        return $FotoProduk;
    }

    public function toko()
    {
        return $this->belongsTo(Toko::class, 'id_toko');
    }

    public function id_produk_order()
    {
        return $this->hasMany(OrderPenyewaan::class, 'id_produk');
    }

    public function id_review_produk()
    {
        return $this->belongsTo(Review::class, 'id_produk');
    }

    public function checkouts()
    {
        return $this->hasMany(Checkout::class, 'id_produk');
    }
    public function avg_nilai_produk($id_produk)
    {
        $nilai = Review::where('id_produk', $id_produk)->where('tipe', 'review_produk')->avg('nilai');

        return $nilai;
    }

    public function cek_nilai($id_produk)
    {
        $nilai = Review::where('id_produk', $id_produk)->where('tipe', 'review_produk')->first();

        if ($nilai) {
            return true;
        } else {
            return false;
        }
    }

    public function total_review_produk($id_produk)
    {
        $total = Review::where('id_produk', $id_produk)->where('tipe', 'review_produk')->count();

        return $total;
    }

    public function alamat()
    {
        return $this->belongsTo(AlamatTambahan::class, 'id_alamat');
    }

    public function getalamatproduk($id_alamat, $id_user)
    {
        if ($id_alamat) {
            $cek = AlamatTambahan::where('id', $id_alamat->id)->first();
        } else {
            $cek = User::where('id', $id_user)->first();
        }
        $alamat = $cek->alamat . ', ' . $cek->kota . ', ' . $cek->kode_pos;
        return $alamat;
    }

    public function getLastOrderAttribute()
    {
        $Order = OrderPenyewaan::where('id_produk', $this->id)->whereNull('ready_status')->latest()->first();
        return $Order;
    }
}