<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlasanPenolakan extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'id_user',
        'penolakan',
        'alasan_penolakan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

}
