<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KodeOTP extends Model
{
    use HasFactory;
    protected $table = 'otp_codes';
    protected $fillable = ['email', 'kode'];
}
