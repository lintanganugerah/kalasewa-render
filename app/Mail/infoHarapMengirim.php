<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class infoHarapMengirim extends Mailable implements ShouldQueue
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
        return $this->subject('Harap Segera Mengirimkan Kostum dengan nomor order ' . $this->nomorOrder . ' !')->view('emails.infoHarapKirim')
            ->with([
                'nomorOrder' => $this->nomorOrder,
                'tanggalMulai' => $this->tanggalMulai,
                'namaPemilik' => $this->namaPemilik,
                'namaProduk' => $this->namaProduk,
            ]);
    }
}