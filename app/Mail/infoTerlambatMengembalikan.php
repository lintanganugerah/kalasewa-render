<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class infoTerlambatMengembalikan extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $totalDenda;
    public $jumlahHari;
    public $nomorOrder;
    public $namaPenyewa;
    public $tanggalSelesai;
    public $tanggalSelesaiMax;
    public $dendaPerHari;

    public $tries = 5;

    public function __construct($totalDenda, $dendaPerHari, $jumlahHari, $nomorOrder, $tanggalSelesai, $tanggalSelesaiMax, $namaPenyewa)
    {
        $this->totalDenda = $totalDenda;
        $this->jumlahHari = $jumlahHari;
        $this->nomorOrder = $nomorOrder;
        $this->tanggalSelesai = $tanggalSelesai;
        $this->tanggalSelesaiMax = $tanggalSelesaiMax;
        $this->namaPenyewa = $namaPenyewa;
        $this->dendaPerHari = $dendaPerHari;
    }

    public function build()
    {
        return $this->subject('Anda Terlambat Mengembalikan Kostum!')->view('emails.infoTerlambatMengembalikan')
            ->with([
                'totalDenda' => $this->totalDenda,
                'dendaPerHari' => $this->dendaPerHari,
                'jumlahHari' => $this->jumlahHari,
                'nomorOrder' => $this->nomorOrder,
                'tanggalSelesai' => $this->tanggalSelesai,
                'tanggalSelesaiMax' => $this->tanggalSelesaiMax,
                'namaPenyewa' => $this->namaPenyewa,
            ]);
    }
}