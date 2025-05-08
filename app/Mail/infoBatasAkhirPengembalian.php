<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class infoBatasAkhirPengembalian extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $jumlahHari;
    public $nomorOrder;
    public $namaPenyewa;
    public $tanggalSelesai;
    public $tanggalSelesaiMax;
    public $dendaPerHari;

    public $tries = 5;

    public function __construct($dendaPerHari, $nomorOrder, $tanggalSelesai, $tanggalSelesaiMax, $namaPenyewa)
    {
        $this->nomorOrder = $nomorOrder;
        $this->tanggalSelesai = $tanggalSelesai;
        $this->tanggalSelesaiMax = $tanggalSelesaiMax;
        $this->namaPenyewa = $namaPenyewa;
        $this->dendaPerHari = $dendaPerHari;
    }

    public function build()
    {
        return $this->subject('Peringatan Batas Akhir Pengembalian Kostum!')->view('emails.infoBatasAkhirPengembalian')
            ->with([
                'dendaPerHari' => $this->dendaPerHari,
                'nomorOrder' => $this->nomorOrder,
                'tanggalSelesai' => $this->tanggalSelesai,
                'tanggalSelesaiMax' => $this->tanggalSelesaiMax,
                'namaPenyewa' => $this->namaPenyewa,
            ]);
    }
}