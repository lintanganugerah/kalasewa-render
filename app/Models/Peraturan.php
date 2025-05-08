<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peraturan extends Model
{
    use HasFactory;
    protected $table = 'peraturan_platform';

    public $timestamps = true;

    protected $fillable = [
        'Judul',
        'Peraturan',
        'Audience'
    ];
}