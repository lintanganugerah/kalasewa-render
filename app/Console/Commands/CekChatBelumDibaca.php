<?php

namespace App\Console\Commands;

use App\Models\ChMessage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CekChatBelumDibaca extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cwd:cek-chat-belum-dibaca';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cek chat belum dibaca, lalu kirimkan email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $listChat = ChMessage::where('seen', 0)->where('reminder', 0)->get()->groupBy('to_id');
        
        foreach ($listChat as $chat) {
            // Hitung jumlah pesan yang belum dibaca untuk pengguna ini (hanya pesan dengan reminder == 0 yang diproses)
            $totalUnread = ChMessage::where('to_id', $chat[0]->to_id)->where('seen', 0)->count();
        
            if ($totalUnread > 0) {
                // Kirim email peringatan jika ada pesan yang belum dibaca
                Mail::to($chat[0]->userPenerima->email)->send(new \App\Mail\peringatanBacaChat($totalUnread, $chat[0]->userPenerima->nama));
                $this->info("Email Peringatan telah dikirimkan ke user: {$chat[0]->userPenerima->nama}");
        
                // Update kolom reminder menjadi 1 (true) setelah email dikirim
                ChMessage::whereIn('id', $chat->pluck('id'))->update(['reminder' => 1]);
            }
        }
    }
}