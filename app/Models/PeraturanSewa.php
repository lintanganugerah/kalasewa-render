<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PeraturanSewa extends Model
{
    use HasFactory;

    use SoftDeletes;
    protected $table = 'peraturan_sewa_toko';

    protected $fillable = [
        'nama',
        'deskripsi',
        'id_toko',
        'terdapat_denda',
        'denda_pasti',
        'denda_kondisional',
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'terdapat_denda' => 'boolean',
    ];

    public function peraturanSewaToko()
    {
        return $this->belongsTo(Toko::class, 'id');
    }

    public function pengajuan_denda()
    {
        return $this->hasMany(PengajuanDenda::class, 'id_praturan');
    }
}