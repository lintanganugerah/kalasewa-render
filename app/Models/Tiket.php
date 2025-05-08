<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tiket extends Model
{
    use HasFactory;
    protected $table = 'tiket';
    protected $guarded = ['id'];
    protected $fillable = ['kategori_tiket_id', 'bukti_tiket', 'deskripsi', 'status', 'user_id'];
    protected $casts = [
        'bukti_tiket' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriTiket::class, 'kategori_tiket_id', 'id')->withTrashed();
    }

}