<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class peringatanBacaChat extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $totalChat;
    public $namaPengirim;

    public $tries = 5;

    public function __construct($totalChat, $namaPengirim)
    {
        $this->totalChat = $totalChat;
        $this->namaPengirim = $namaPengirim;
    }

    public function build()
    {
        return $this->subject('Anda Memiliki Pesan yang belum dibaca dari ' . $this->namaPengirim . '!')->view('emails.peringatanBelumDibacaChat')
            ->with(['totalChat' => $this->totalChat, 'namaPengirim' => $this->namaPengirim]);
    }
}