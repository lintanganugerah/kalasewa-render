<?php

namespace App\Listeners;

use App\Events\UserChangeProfile;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class updateNameAvatarChat
{
    /**
     * Handle the event.
     *
     * @param \App\Events\UserChangeProfile $event
     * @return void
     */
    public function handle(UserChangeProfile $event)
    {
        //Ganti name dan avatar chatify
        $user = $event->user;

        if ($user->role == "pemilik_sewa") {
            $user->name = $user->toko->nama_toko;
        } else {
            $user->name = $user->nama;
        }

        $user->avatar = $user->foto_profil;
        $user->save();
    }
}