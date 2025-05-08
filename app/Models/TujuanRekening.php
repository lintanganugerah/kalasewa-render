<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TujuanRekening extends Model
{
    use HasFactory;

    protected $table = 'tujuan_rekening';

    protected $fillable = [
        'nama',
    ];

    public function saldoUser()
    {
        return $this->hasOne(SaldoUser::class, 'tujuan_rek');
    }
}