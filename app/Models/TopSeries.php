<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopSeries extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_series',
        'banyak_dipesan'
    ];

    public function series()
    {
        return $this->belongsTo(Series::class, 'id_series');
    }
}