<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Chatify\Traits\UUID;
use Carbon\Carbon;

class ChMessage extends Model
{
    use UUID;

    public function userPengirim()
    {
        return $this->belongsTo(User::class, 'from_id');
    }

    public function userPenerima()
    {
        return $this->belongsTo(User::class, 'to_id');
    }

    public function time()
    {
        $time = $this->updated_at;
        $time = Carbon::now()->diffInMinutes($time);
        $time = abs(round($time));

        if ($time >= 60) {
            $jam = round($time / 60);
            if ($jam >= 24) {
                $hari = round($jam / 24);
                $formatTime = $hari . ' Hari Lalu';
            } else {
                $formatTime = $jam . ' Jam Lalu';
            }
        } else {
            $formatTime = abs(round($time)) . ' Menit Lalu';
        }
        return $formatTime;
    }
}