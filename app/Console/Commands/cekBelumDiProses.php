<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\OrderPenyewaan;
use App\Models\Produk;
use App\Models\User;
use App\Models\Toko;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class cekBelumDiProses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cwd:cek-belum-di-proses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cek order dengan status belum yang melebihi batas waktu tanggal mulai';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Task yang dijalankan:

        $orders = OrderPenyewaan::where('status', 'Menunggu di Proses')->whereDay('tanggal_mulai', '>=', Carbon::now()->subDay(1)->translatedFormat('d'))->orderBy('tanggal_mulai', 'ASC')->get();

        if ($orders) {
            foreach ($orders as $order) {
                $tanggalMulai = Carbon::parse($order->tanggal_mulai)->subDay(1)->setTimezone('Asia/Jakarta')->startOfDay();
                $tanggalSekarang = Carbon::now()->setTimezone('Asia/Jakarta')->startOfDay();
                if ($tanggalSekarang->greaterThan($tanggalMulai)) {
                    $produk = Produk::where('id', $order->id_produk)->first();
                    $pemilik = $produk->toko->user;

                    // Update order penyewaan
                    $order->status = "Dibatalkan Pemilik Sewa";
                    $order->alasan_pembatalan = "Sistem mendeteksi bahwa order ini belum dikirimkan hingga tanggal mulai penyewaan. Sistem otomatis membatalkan order tersebut";
                    $produk->status_produk = "arsip";
                    $order->save();
                    $produk->save();
                    Mail::to($pemilik->email)->send(new \App\Mail\infoDibatalkanOtomatis($pemilik->nama, $order->nomor_order, $order->tanggalFormatted($order->tanggal_mulai), $produk->nama_produk . " ukuran " . $produk->ukuran_produk));
                    $this->info("Nomor order {$order->nomor_order} Telah Dibatalkan karena melebihi batas waktu pengiriman. Email pembatalan telah Dikirimkan ke {$pemilik->email}");
                } elseif ($tanggalSekarang->toDateString() == $tanggalMulai->toDateString()) {
                    $produk = Produk::where('id', $order->id_produk)->first();
                    $pemilik = $produk->toko->user;

                    Mail::to($pemilik->email)->send(new \App\Mail\infoHarapMengirim($pemilik->nama, $order->nomor_order, $order->tanggalFormatted($order->tanggal_mulai), $produk->nama_produk . " ukuran " . $produk->ukuran_produk));
                    $this->info("Email Peringatan dengan nomor order {$order->nomor_order} Telah Dikirimkan ke {$pemilik->email}");
                } else {
                    $this->info("Tidak Menemukan Order dengan tanggal hari ini, dan order yang telat dikirimkan");
                }
            }
        } else {
            $this->info("Tidak Ada Order");
        }
    }
}