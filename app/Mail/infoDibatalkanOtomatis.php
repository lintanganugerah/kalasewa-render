<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class infoDibatalkanOtomatis extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    //EMAIL UNTUK PEMILIK SEWA UNTUK SEGERA MENGIRIMKAN PRODUK MAKSIMAL HARI INI

    public $jumlahHari;
    public $nomorOrder;
    public $namaPemilik;
    public $tanggalMulai;
    public $namaProduk;

    public $tries = 5;

    public function __construct($namaPemilik, $nomorOrder, $tanggalMulai, $namaProduk)
    {
        $this->nomorOrder = $nomorOrder;
        $this->tanggalMulai = $tanggalMulai;
        $this->namaPemilik = $namaPemilik;
        $this->namaProduk = $namaProduk;
    }

    public function build()
    {
        return $this->subject('Pesanan dengan nomor order ' . $this->nomorOrder . ' Telah Dibatalkan Otomatis!')->view('emails.infoDibatalkanOtomatis')
            ->with([
                'nomorOrder' => $this->nomorOrder,
                'tanggalMulai' => $this->tanggalMulai,
                'namaPemilik' => $this->namaPemilik,
                'namaProduk' => $this->namaProduk,
            ]);
    }
}