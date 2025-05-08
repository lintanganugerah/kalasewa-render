<?php

namespace App\Http\Controllers;

use App\Models\OrderPenyewaan;
use App\Models\Review;
use App\Models\SaldoUser;
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
use App\Models\Series;
use App\Models\Produk;
use Exception;
use App\Models\FotoProduk;
use Carbon\Carbon;
use App\Models\PengajuanDenda;

class PenilaianSisiSellerController extends Controller
{
    public function viewTambahProduk()
    {
        $series = Series::all();
        return view('produk.tambahproduk', compact('series'));
    }

    public function viewpenilaianProduk()
    {
        $toko = Toko::where('id_user', Auth::id())->first();
        $produk = Produk::where('id_toko', $toko->id)->get();

        return view('pemilikSewa.iterasi2.penilaian.penilaianProduk', compact('produk'));
    }

    public function viewdetailPenilaianProduk($id)
    {
        $penilaianProduk = Review::where('id_produk', $id)->where('tipe', 'review_produk')->get();
        $toko = Toko::where('id_user', Auth::id())->first();
        $produk = Produk::where('id', $id)->first();

        if ($produk) {
            if ($toko->id != $produk->id_toko) {
                return redirect()->route('seller.view.penilaian.penilaianProduk')->with('error', 'ID Produk Invalid');
            }
        } else {
            return redirect()->route('seller.view.penilaian.penilaianProduk')->with('error', 'Produk Tidak Ditemukan');
        }

        return view('pemilikSewa.iterasi2.penilaian.detailPenilaianProduk', compact('penilaianProduk', 'produk'));
    }

    public function viewReviewPenyewa($id)
    {
        $penyewa = User::where('id', $id)->first();
        $penilaianPenyewa = Review::where('tipe', 'review_penyewa')->where('id_penyewa', $id)->get();

        if ($penyewa) {
            if ($penyewa->role != "penyewa") {
                return redirect()->route('seller.statuspenyewaan.belumdiproses');
            }
        } else {
            return redirect()->route('seller.statuspenyewaan.belumdiproses');
        }

        return view('pemilikSewa.iterasi2.penilaian.reviewPenyewa', compact('penyewa', 'penilaianPenyewa'));
    }

    public function viewTambahReviewPenyewa($id, $nomor_order)
    {
        $penyewa = User::where('id', $id)->first();
        $order = OrderPenyewaan::where('nomor_order', $nomor_order)->first();

        if (!$order || $order->id_produk_order->id_toko != auth()->user()->toko->id) {
            return redirect()->route('seller.statuspenyewaan.telahkembali')->with('error', "Nomor order tidak ditemukan");
        }

        if ($order->status != "Telah Kembali") {
            return redirect()->route('seller.statuspenyewaan.telahkembali')->with('error', "Nomor Order Invalid! Tidak Ditemukan. Pastikan Order memiliki status Telah Kembali");
        }

        $denda = PengajuanDenda::where('nomor_order', $order->nomor_order)->whereIn('status', ['pending', 'diproses'])->first();

        if ($denda) {
            return redirect()->route('seller.statuspenyewaan.telahkembali')->with('error', "Pesanan tersebut masih memiliki pengajuan denda yang belum lunas atau sedang berlangsung");
        }

        if ($penyewa) {
            if ($penyewa->role != "penyewa" || $penyewa->id != $order->id_penyewa) {
                return redirect()->route('seller.statuspenyewaan.telahkembali')->with('error', "Penyewa tidak valid");
            }
        } else {
            return redirect()->route('seller.statuspenyewaan.telahkembali')->with('error', "Penyewa Tidak Ditemukan!");
        }

        if ($order->jaminan < 0) {
            return redirect()->route('seller.statuspenyewaan.telahkembali')->with('error', 'Nilai Jaminan Penyewa Minus. Penyewa belum melunaskan denda / ongkir nya. Tidak dapat memproses konfirmasi');
        }

        return view('pemilikSewa.iterasi2.penilaian.tambahReviewPenyewa', compact('penyewa', 'order'));
    }

    public function tambahReviewPenyewaAction($id, Request $request, $nomor_order)
    {
        $penyewa = User::where('id', $id)->first();
        $toko = Toko::where('id_user', Auth::id())->first();
        $order = OrderPenyewaan::where('nomor_order', $nomor_order)->first();
        $produk = $order->produk;

        if ($penyewa) {
            if ($penyewa->role != "penyewa") {
                return redirect()->route('seller.statuspenyewaan.telahkembali')->with('error', "ID tujuan bukan Penyewa!");
            }
        } else {
            return redirect()->route('seller.statuspenyewaan.telahkembali')->with('error', "Penyewa Tidak Ditemukan!");
        }

        $validator = Validator::make($request->all(), [
            'komentar' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'foto_produk.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($order->status != "Telah Kembali") {
            return redirect()->route('seller.statuspenyewaan.telahkembali')->with('error', "Nomor Order Invalid! Tidak Ditemukan. Pastikan Order memiliki status Telah Kembali");
        }

        // JANGAN LUPA KASIH UPDATE KE STATUS ORDER!!!!!

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            $fotoReview = NULL;
            $saldoPemilik = SaldoUser::where('id_user', Auth::id())->first();
            $saldoPenyewa = SaldoUser::where('id_user', $order->id_penyewa)->first();

            if ($request->hasFile('foto_produk')) {
                $fotoReview = [];
                foreach ($request->file('foto_produk') as $index => $foto) {
                    // Simpan file foto
                    $path1 = $foto->store('public/review/penyewa');
                    $path = str_replace('public/', 'storage/', $path1);
                    $fotoReview[] = $path;
                }
                $fotoReview = json_encode($fotoReview, JSON_UNESCAPED_SLASHES);
            }

            Review::updateOrCreate([
                "id_penyewa" => $penyewa->id,
                "id_toko" => $toko->id,
                "komentar" => $request->komentar,
                "nilai" => $request->rating,
                "tipe" => "review_penyewa",
                "foto" => $fotoReview
            ]);

            $order->status = "Penyewaan Selesai";
            $produk->status_produk = "arsip";

            $produk->save();
            $order->save();

            if ($saldoPemilik) {
                $totalSaldo = $order->total_penghasilan + $saldoPemilik->saldo;
                $saldoPemilik->saldo = $totalSaldo;
                $saldoPemilik->save();
            } else {
                SaldoUser::create([
                    "id_user" => Auth::id(),
                    'tujuan_rek' => null,
                    'nomor_rekening' => null,
                    'saldo' => $order->total_penghasilan
                ]);
            }

            if ($saldoPenyewa) {
                $totalSaldo = $order->jaminan + $saldoPenyewa->saldo;
                $saldoPenyewa->saldo = $totalSaldo;
                $saldoPenyewa->save();
            } else {
                SaldoUser::create([
                    "id_user" => $order->id_penyewa,
                    'tujuan_rek' => null,
                    'nomor_rekening' => null,
                    'saldo' => $order->jaminan,
                ]);
            }

            DB::commit();

            return redirect()->route('seller.statuspenyewaan.riwayatPenyewaan')->with('success', 'Penyewaan Telah Selesai! Review berhasil ditambahkan');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('seller.statuspenyewaan.telahkembali')->with('error', 'Terjadi kesalahan saat menginputkan rating. Mohon coba lagi');
        }
    }
}