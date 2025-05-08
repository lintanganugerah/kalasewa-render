<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Toko;
use App\Models\Penyewa;
use App\Models\Produk;
use App\Models\FotoProduk;
use App\Models\Series;
use App\Models\OrderPenyewaan;
use App\Models\Review;
use App\Models\TopSeries;
use App\Models\Checkout;
use App\Models\TujuanRekening;
use App\Models\SaldoUser;
use App\Models\PenarikanSaldo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

class KeuanganPenyewaController extends Controller
{
    //
    public function setRekening(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomor_rekening' => 'required|numeric|min_digits:10',
            'nama_rekening' => 'required|string',
            'tujuan_rek' => 'required|exists:tujuan_rekening,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        SaldoUser::updateOrCreate(
            ['id_user' => Auth::id()],
            $data
        );

        return redirect()->route('viewPenarikan')->with('success', 'Rekening berhasil di set');
    }

    public function tarikSaldo(Request $request)
    {

        $saldos = SaldoUser::where('id_user', Auth()->user()->id)->first();

        if (!$saldos || $saldos->saldo == 0) {
            return redirect()->route('viewPenarikan')->withErrors('Maaf, tidak ada saldo yang dapat ditarik');
        }

        if ($saldos->saldo < 10000) {
            return redirect()->route('viewPenarikan')->withErrors('Maaf, minimal penarikan adalah Rp10.000');
        }

        $penarikan = new PenarikanSaldo();
        $penarikan->id_user = Auth()->user()->id;
        $penarikan->nominal = $saldos->saldo;
        $penarikan->bank = $saldos->tujuanRekening->nama;
        $penarikan->nomor_rekening = $saldos->nomor_rekening;
        $penarikan->nama_rekening = $saldos->nama_rekening;
        $penarikan->status = "Menunggu Konfirmasi";

        $saldos->saldo = 0;

        $saldos->save();
        $penarikan->save();

        return redirect()->route('viewPenarikan')->with('success', 'Penarikan Saldo Berhasil, Silahkan Menunggu 1x24 Jam');
    }


    public function viewPenarikan()
    {
        $saldos = SaldoUser::where('id_user', Auth()->user()->id)->first();
        $penarikans = PenarikanSaldo::where('id_user', Auth()->user()->id)->get();

        return view('penyewa.penarikan.penarikan', compact('saldos', 'penarikans'));
    }

    public function viewUbahRekening()
    {
        $saldos = SaldoUser::where('id_user', Auth()->user()->id)->first();
        $rekenings = TujuanRekening::all();

        return view('penyewa.penarikan.ubahRekening', compact('saldos', 'rekenings'));
    }
    public function viewTarikRekening()
    {
        $saldos = SaldoUser::where('id_user', Auth()->user()->id)->first();

        if ($saldos->nomor_rekening == null){
            return redirect()->route('viewUbahRekening')->withErrors('Silahkan isi terlebih dahulu informasi rekening anda!');
        }

        return view('penyewa.penarikan.tarikRekening', compact('saldos'));
    }
}
