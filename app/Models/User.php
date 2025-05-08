<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nama',
        'email',
        'password',
        'no_telp',
        'no_darurat',
        'ket_no_darurat',
        'link_sosial_media',
        'kota',
        'kode_pos',
        'role',
        'alamat',
        'provinsi',
        'badge',
        'NIK',
        'foto_identitas',
        'foto_diri',
        'verifyIdentitas',
        'name',
        'avatar'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function isSeller()
    {
        return $this->role === 'pemilik sewa';
    }

    public function isBuyer()
    {
        return $this->role === 'penyewa';
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }
    public function toko()
    {
        return $this->hasOne(Toko::class, 'id_user');
    }

    public function id_penyewa_order()
    {
        return $this->hasMany(OrderPenyewaan::class, 'id_penyewa');
    }

    public function id_review_penyewa()
    {
        return $this->hasMany(Review::class, 'id_penyewa');
    }

    public function avg_nilai_penyewa()
    {
        $nilai = Review::where('id_penyewa', $this->id)->where('tipe', 'review_penyewa')->avg('nilai');

        return $nilai;
    }

    public function avg_nilai_toko()
    {
        $toko = Toko::where('id_user', $this->id)->first();
        $nilai = Review::where('id_toko', $toko->id)->where('tipe', 'review_produk')->avg('nilai');

        return $nilai;
    }

    public function cek_nilai()
    {
        $nilai = Review::where('id_penyewa', $this->id)->where('tipe', 'review_penyewa')->first();

        if ($nilai) {
            return true;
        } else {
            return false;
        }
    }

    public function total_review_penyewa()
    {
        $total = Review::where('id_penyewa', $this->id)->where('tipe', 'review_penyewa')->count();

        return $total;
    }

    public function chatPengirim()
    {
        return $this->hasMany(Toko::class, 'from_id');
    }

    public function chatPenerima()
    {
        return $this->hasMany(Toko::class, 'to_id');
    }

    public function saldo_user()
    {
        return $this->hasOne(SaldoUser::class, 'id_user');
    }

    public function orders()
    {
        return $this->hasMany(OrderPenyewaan::class, 'id_penyewa');
    }
    public function alasanPenolakan()
    {
        return $this->hasMany(AlasanPenolakan::class, 'id_user');
    }

    public function penarikan_saldo()
    {
        return $this->hasMany(PenarikanSaldo::class, 'id_user');
    }

    public function denda_penyewa()
    {
        return $this->hasMany(PenarikanSaldo::class, 'id_penyewa');
    }
}