<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMailPenarikan extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $otp;

    public $tries = 5;

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function build()
    {
        return $this->subject('Kode OTP Penarikan Saldo Penghasilan')->view('emails.otpPenarikan')
            ->with(['otp' => $this->otp]);
    }
}