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

class CekPengembalianTerlambat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cwd:cek-pengembalian-terlambat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Command Untuk Mengecek Mana Order yang sudah melebihi batas waktu tanggal selesai';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Task yang dijalankan:

        $orders = OrderPenyewaan::where('status', 'Sedang Berlangsung')->get();
        $orders = json_decode($orders);

        // dd($orders[0]->tanggal_selesai);

        if ($orders) {
            foreach ($orders as $order) {
                $tanggalSelesaiBatasMax = Carbon::parse($order->tanggal_selesai)->setTimezone('Asia/Jakarta')->addDays()->startOfDay();
                $tanggalSekarang = Carbon::now()->setTimezone('Asia/Jakarta')->startOfDay();
                if ($tanggalSekarang->greaterThan($tanggalSelesaiBatasMax)) {
                    $hariKeterlambatan = ((int) round(Carbon::now()->startOfDay()->diffInDays($tanggalSelesaiBatasMax))) * -1;
                    $produk = Produk::where('id', $order->id_produk)->first();
                    $penyewa = User::where('id', $order->id_penyewa)->first();
                    $dendaDitetapkan = $produk->toko->peraturanSewaToko[0]->denda_pasti;
                    $dendaTerlambat = $hariKeterlambatan * $dendaDitetapkan; // Denda Berdasarkan Nilai Yang ditetapkan oleh pemilik
                    $tanggalSelesaiBatasMax = Carbon::parse($tanggalSelesaiBatasMax)->format('d-m-Y');
                    $tanggalSelesai = Carbon::parse($order->tanggal_selesai)->format('d-m-Y');

                    $dendaSebelumnya = $order->denda_keterlambatan ?? 0;
                    $dendaTerlambatTotal = $dendaSebelumnya + $dendaTerlambat;
                    $sisaJaminan = $order->jaminan - $dendaTerlambat;

                    if ($sisaJaminan >= 0) {
                        $totalPenghasilan = $order->total_penghasilan + $dendaTerlambat;
                    } else {
                        if ($order->jaminan >= 0) {
                            $totalPenghasilan = $order->total_penghasilan + $order->jaminan;
                        } else {
                            $totalPenghasilan = $order->total_penghasilan;
                        }
                    }

                    // Update denda_keterlambatan dan simpan perubahan
                    OrderPenyewaan::where('nomor_order', $order->nomor_order)->update(['denda_keterlambatan' => $dendaTerlambatTotal, 'jaminan' => $sisaJaminan, 'total_penghasilan' => $totalPenghasilan]);
                    Mail::to($penyewa->email)->send(new \App\Mail\infoTerlambatMengembalikan($dendaTerlambatTotal, $dendaDitetapkan, $hariKeterlambatan, $order->nomor_order, $tanggalSelesai, $tanggalSelesaiBatasMax, $penyewa->nama));
                    $this->info("Denda keterlambatan ditambahkan untuk pesanan dengan nomor order: {$order->nomor_order}");
                } elseif ($tanggalSekarang->toDateString() == $tanggalSelesaiBatasMax->toDateString()) {
                    $produk = Produk::where('id', $order->id_produk)->first();
                    $penyewa = User::where('id', $order->id_penyewa)->first();
                    $dendaDitetapkan = $produk->toko->peraturanSewaToko[0]->denda_pasti;
                    $tanggalSelesaiBatasMax = Carbon::parse($tanggalSelesaiBatasMax)->toDateString();
                    $tanggalSelesai = Carbon::parse($order->tanggal_selesai)->toDateString();

                    Mail::to($penyewa->email)->send(new \App\Mail\infoBatasAkhirPengembalian($dendaDitetapkan, $order->nomor_order, $tanggalSelesai, $tanggalSelesaiBatasMax, $penyewa->nama));
                    $this->info("Email Peringatan dengan nomor order {$order->nomor_order} Telah Dikirimkan ke {$penyewa->email}");
                } else {
                    $this->info("Tidak Menemukan Order yang telat");
                }
            }
        } else {
            $this->info("Tidak Ada Order");
        }
    }
}