<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ingatkanPenyewa extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $nomorOrder;
    public $namaPenyewa;
    public $tanggalSelesai;
    public $tanggalSelesaiMax;
    public $namaToko;
    public $namaProduk;

    public $tries = 5;

    public function __construct($nomorOrder, $tanggalSelesai, $tanggalSelesaiMax, $namaPenyewa, $namaToko, $namaProduk)
    {
        $this->nomorOrder = $nomorOrder;
        $this->tanggalSelesai = $tanggalSelesai;
        $this->tanggalSelesaiMax = $tanggalSelesaiMax;
        $this->namaPenyewa = $namaPenyewa;
        $this->namaToko = $namaToko;
        $this->namaProduk = $namaProduk;
    }

    public function build()
    {
        return $this->subject('Reminder mengembalikan kostum!')->view('emails.ingatkanPenyewa')
            ->with([
                'nomorOrder' => $this->nomorOrder,
                'tanggalSelesai' => $this->tanggalSelesai,
                'tanggalSelesaiMax' => $this->tanggalSelesaiMax,
                'namaPenyewa' => $this->namaPenyewa,
                'namaToko' => $this->namaToko,
                'namaProduk' => $this->namaProduk,
            ]);
    }
}