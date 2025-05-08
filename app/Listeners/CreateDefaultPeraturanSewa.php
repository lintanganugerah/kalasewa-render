<?php

namespace App\Listeners;

use App\Events\PemilikSewaCreated;
use App\Models\PeraturanSewa;
use App\Models\Toko;
use App\Events\UserChangeProfile;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateDefaultPeraturanSewa
{
    /**
     * Handle the event.
     *
     * @param \App\Events\PemilikSewaCreated $event
     * @return void
     */
    public function handle(PemilikSewaCreated $event)
    {
        $user = $event->user;

        // Cari toko yang sesuai dengan id_user
        $toko = Toko::where('id_user', $user->id)->first();

        PeraturanSewa::create([
            'nama' => 'Terlambat Mengembalikan Kostum',
            'deskripsi' => 'Penyewa akan dikenakan denda jika pengembalian kostum yang disewa melebihi batas waktu sewa yang telah ditentukan. Denda akan dikenakan per-hari keterlambatan!!',
            'id_toko' => $toko->id,
            'terdapat_denda' => true,
            'denda_pasti' => 50000,
            'denda_kondisional' => null,
        ]);

        event(new UserChangeProfile($user));
    }
}