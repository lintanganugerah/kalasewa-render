<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlamatTambahan extends Model
{
    use HasFactory;

    protected $table = 'alamat_tambahan';

    protected $primaryKey = 'id'; //Karena kita di skema tidak memakai id() jadi kita tetapkan ini adalah primary

    public $incrementing = false; //id tidak auto increment karena menggunakan unix time sebagai Unique nya

    protected $keyType = 'bigInteger'; //Kita tetapkan juga keyType nya sebagai bigInt. Sebenarnya tidak masalah, karena secara default laravel akan menghandle primary key sebagai int. Tapi jaga-jaga aja
    protected $fillable = [
        'id',
        'id_toko',
        'nama',
        'alamat',
        'kota',
        'kode_pos',
        'provinsi',
    ];

    public function toko()
    {
        return $this->belongsTo(Toko::class, 'id_toko');
    }

    public function produk()
    {
        return $this->hasMany(Produk::class, 'id');
    }

}