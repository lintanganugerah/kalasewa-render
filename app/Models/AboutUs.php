<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    use HasFactory;
    protected $table = 'aboutUs';

    public $timestamps = true;

    protected $fillable = [
        'id',
        'content'
    ];
}
