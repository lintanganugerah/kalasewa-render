<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Toko;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use App\Models\OrderPenyewaan;
use Carbon\Carbon;
use App\Models\Produk;
use Illuminate\Support\Facades\Mail;
use App\Models\SaldoUser;
use Exception;


class StatusPenyewaanController extends Controller
{

    public function viewBelumDiProses(Request $request)
    {
        $toko = Toko::where('id_user', Auth::id())->first();
        $produk = Produk::where('id_toko', $toko->id)->get()->pluck('id')->toArray();
        $order = OrderPenyewaan::whereIn('id_produk', $produk)->where('status', 'Menunggu di Proses')->orderBy('tanggal_mulai', 'ASC')->get();

        return view('pemilikSewa.iterasi2.pesanan.belumdiproses', compact('order'));
    }

    public function viewDalamPengiriman(Request $request)
    {
        $toko = Toko::where('id_user', Auth::id())->first();
        $produk = Produk::where('id_toko', $toko->id)->get()->pluck('id')->toArray();
        $order = OrderPenyewaan::whereIn('id_produk', $produk)->where('status', 'Dalam Pengiriman')->orderBy('updated_at', 'DESC')->get();

        return view('pemilikSewa.iterasi2.pesanan.dalamPengiriman', compact('order'));
    }

    public function viewSedangBerlangsung(Request $request)
    {
        $toko = Toko::where('id_user', Auth::id())->first();
        $produk = Produk::where('id_toko', $toko->id)->get()->pluck('id')->toArray();
        $order = OrderPenyewaan::whereIn('id_produk', $produk)->where('status', 'Sedang Berlangsung')->orderBy('tanggal_mulai', 'ASC')->get();
        return view('pemilikSewa.iterasi2.pesanan.sedangBerlangsung', compact('order'));
    }

    public function viewTelahKembali(Request $request)
    {
        $toko = Toko::where('id_user', Auth::id())->first();
        $produk = Produk::where('id_toko', $toko->id)->get()->pluck('id')->toArray();
        $order = OrderPenyewaan::whereIn('id_produk', $produk)->where('status', 'Telah Kembali')->orderBy('updated_at', 'ASC')->get();
        return view('pemilikSewa.iterasi2.pesanan.telahKembali', compact('order'));
    }

    public function viewPenyewaanDiretur(Request $request)
    {
        $toko = Toko::where('id_user', Auth::id())->first();
        $produk = Produk::where('id_toko', $toko->id)->get()->pluck('id')->toArray();
        $order = OrderPenyewaan::whereIn('id_produk', $produk)->whereIn('status', ['Retur dalam Pengiriman', 'Retur', 'Retur Dikonfirmasi'])->get();

        return view('pemilikSewa.iterasi2.pesanan.sewaRetur', compact('order'));
    }

    public function viewRiwayatPenyewaan(Request $request)
    {
        $toko = Toko::where('id_user', Auth::id())->first();
        $produk = Produk::where('id_toko', $toko->id)->get()->pluck('id')->toArray();
        $order = OrderPenyewaan::whereIn('id_produk', $produk)->whereIn('status', ['Penyewaan Selesai', 'Retur Selesai', 'Dibatalkan Penyewa', 'Dibatalkan Pemilik Sewa'])->orderBy('updated_at', 'DESC')->get();

        return view('pemilikSewa.iterasi2.pesanan.riwayatPenyewaan', compact('order'));
    }

    public function inputResi(Request $request, $nomor_order)
    {
        $order = OrderPenyewaan::where('nomor_order', $nomor_order)->first();
        $toko = Toko::where('id_user', Auth::id())->first();

        $validator = Validator::make($request->all(), [
            'nomor_resi' => 'required|string',
            'biaya_pengiriman' => 'required|numeric',
            'foto_bukti_resi' => 'required|file|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if (!$order) {
            return redirect()->back()->with('error', "Order Tidak ditemukan/Sudah dihapus");
        }

        if ($toko->id != $order->id_produk_order->id_toko) {
            return redirect()->back()->with('error', "Produk Invalid. Silahkan Refresh Halaman Anda");
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        $photoPath = $request->file('foto_bukti_resi')->store('public/bukti_resi');
        $photoPath = Str::replaceFirst('public/', 'storage/', $photoPath);
        $ongkir = (int) str_replace('.', '', $request->biaya_pengiriman);
        $ongkirTerpotong = $order->ongkir_default - $ongkir;

        if ($ongkirTerpotong < 0) { //Ongkir_default kurang dari ongkir yang di inputkan
            $order->ongkir_default = 0; //Bikin ongkir_default tidak bersisa
            $order->jaminan += $ongkirTerpotong; //Potong jaminan
        } else {
            $order->ongkir_default -= $ongkir; //Kalau ongkir default ga kurang, langsung potong aja ongkir_default dengan ongkir_pengiriman yang di inputkan
        }

        $order->total_harga += $ongkir; //Tambahin ongkir_pengiriman yang di input ke total_harga

        if ($order->ongkir_default == 0 && $order->jaminan < 0) {
            $order->total_penghasilan += $ongkir - abs($order->jaminan); //Tambahin ongkir_pengiriman yang di input ke penghasilan pemilik sewa
        } else {
            $order->total_penghasilan += $ongkir; //Tambahin ongkir_pengiriman yang di input ke penghasilan pemilik sewa
        }

        $order->nomor_resi = $request->nomor_resi;
        $order->ongkir_pengiriman = $ongkir;
        $order->bukti_resi = $photoPath;
        $order->status = "Dalam Pengiriman";
        $order->save();

        return redirect()->route('seller.statuspenyewaan.dalampengiriman')->with('success', 'Status Produk berhasil diubah! Produk menjadi dalam pengiriman');
    }

    public function returSelesai($nomor_order)
    {
        $order = OrderPenyewaan::where('nomor_order', $nomor_order)->where('status', 'Retur dalam Pengiriman')->first();
        $toko = Toko::where('id_user', Auth::id())->first();

        if (!$order) {
            return redirect()->back()->with('error', "Order Tidak ditemukan/Sudah dihapus");
        }
        $produk = $order->produk;

        if ($toko->id != $order->id_produk_order->id_toko) {
            return redirect()->back()->with('error', "Produk Invalid. Silahkan Refresh Halaman Anda");
        }

        try {
            if ($order->jaminan < 0) {
                return redirect()->route('seller.statuspenyewaan.penyewaandiretur')->with('error', 'Nilai Jaminan Penyewa Minus. Penyewa belum melunaskan denda nya. Tidak dapat memproses');
            }

            $order->status = "Retur Selesai";
            $produk->status_produk = "arsip";

            $produk->save();
            $order->save();

            SaldoUser::updateOrCreate(
                ['id_user' => $order->id_penyewa],
                ["saldo" => $order->grand_total - $order->fee_admin]
            );
        } catch (Exception $e) {
            return redirect()->route('seller.statuspenyewaan.penyewaandiretur')->with('error', 'Terjadi kesalahan. Mohon coba lagi nanti');
        }

        return redirect()->route('seller.statuspenyewaan.riwayatPenyewaan')->with('success', 'Status Produk berhasil diubah! Penyewaan yang diretur telah diterima anda!');
    }

    public function dibatalkanPemilikSewa(Request $request, $nomor_order)
    {
        $order = OrderPenyewaan::where('nomor_order', $nomor_order)->first();
        $toko = Toko::where('id_user', Auth::id())->first();
        $produk = $order->produk;

        $validator = Validator::make($request->all(), [
            'alasan_batal' => 'required|string'
        ]);

        if (!$order) {
            return redirect()->back()->with('error', "Order Tidak ditemukan/Sudah dihapus");
        }

        if ($toko->id != $order->id_produk_order->id_toko) {
            return redirect()->back()->with('error', "Order Invalid. Silahkan Refresh Halaman Anda");
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $order->status = "Dibatalkan Pemilik Sewa";
            $order->alasan_pembatalan = $request->alasan_batal;

            $produk->status_produk = "arsip";

            $produk->save();
            $order->save();

            $saldo = SaldoUser::where("id_user", $order->id_penyewa)->first();
            $saldo_yang_ada = $saldo->saldo ?? 0;
            $totalDikembalikan = $order->grand_total + $saldo_yang_ada;

            SaldoUser::updateOrCreate(
                ['id_user' => $order->id_penyewa],
                ["saldo" => $totalDikembalikan]
            );

            DB::commit();

            return redirect()->route('seller.statuspenyewaan.riwayatPenyewaan')->with('success', 'Penyewaan telah anda Batalkan!');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('seller.statuspenyewaan.belumdiproses')->with('error', 'Terjadi kesalahan. Mohon coba lagi');
        }
    }

    public function ingatkanPenyewa($nomor_order, $id_penyewa)
    {
        $order = OrderPenyewaan::where('nomor_order', $nomor_order)->where('status', 'Sedang Berlangsung')->first();
        $penyewa = User::where('role', 'penyewa')->where('id', $id_penyewa)->first();
        $toko = Toko::where('id_user', Auth::id())->first();


        if (!$order) {
            return redirect()->back()->with('error', "Order Tidak ditemukan");
        }

        if ($order->id_penyewa != $penyewa->id) {
            return redirect()->back()->with('error', "Produk Invalid. Silahkan Refresh Halaman Anda");
        }

        if ($toko->id != $order->id_produk_order->id_toko) {
            return redirect()->back()->with('error', "Produk Invalid. Silahkan Refresh Halaman Anda");
        }


        $tanggalSelesaiBatasMax = Carbon::parse($order->tanggal_selesai)->addDays()->format('d-m-Y');
        $tanggalSelesai = Carbon::parse($order->tanggal_selesai)->format('d-m-Y');

        //BIKIN MAIL DISINI
        Mail::to($penyewa->email)->send(new \App\Mail\ingatkanPenyewa($order->nomor_order, $tanggalSelesai, $tanggalSelesaiBatasMax, $penyewa->nama, $toko->nama_toko, $order->id_produk_order->nama_produk));

        return redirect()->route('seller.statuspenyewaan.sedangberlangsung')->with('success', 'Berhasil mengirimkan email pengingat untuk mengembalikan kostum kepada penyewa');
    }

}