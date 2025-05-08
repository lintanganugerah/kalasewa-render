<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Series extends Model
{
    use HasFactory;

    use SoftDeletes;
    public $timestamps = true;
    protected $fillable = [
        'series'
    ];
    protected $dates = ['deleted_at'];

    public function series()
    {
        return $this->hasOne(Produk::class, 'id_series')->withDefault();
    }

    public function top_series()
    {
        return $this->hasMany(TopSeries::class, 'id_series');
    }
}