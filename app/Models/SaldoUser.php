<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaldoUser extends Model
{
    use HasFactory;

    protected $table = 'saldo_user';

    protected $fillable = [
        'id_user',
        'tujuan_rek',
        'nama_rekening',
        'nomor_rekening',
        'saldo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function tujuanRekening()
    {
        return $this->belongsTo(TujuanRekening::class, 'tujuan_rek');
    }
}