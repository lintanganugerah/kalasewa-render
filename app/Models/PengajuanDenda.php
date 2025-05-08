<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanDenda extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_denda';

    protected $fillable = ['id_penyewa', 'id_toko', 'nomor_order', 'id_peraturan', 'penjelasan', 'nominal_denda', 'sisa_denda', 'foto_bukti', 'status', 'alasan_penolakan'];
    protected $casts = [
        'foto_bukti' => 'array',
    ];

    public function penyewa()
    {
        return $this->belongsTo(User::class, 'id_penyewa');
    }

    public function toko()
    {
        return $this->belongsTo(Toko::class, 'id_toko');
    }

    public function nomor_order()
    {
        return $this->belongsTo(OrderPenyewaan::class, 'nomor_order', 'nomor_order');
    }

    public function peraturan()
    {
        return $this->belongsTo(PeraturanSewa::class, 'id_peraturan');
    }

    public function pemilikSewa()
    {
        return $this->toko->user;
    }
}