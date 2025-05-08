<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\PenarikanSaldo;
use Illuminate\Http\Request;
use App\Models\SaldoUser;
use App\Models\KodeOTP;
use App\Models\TujuanRekening;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Exception;

class PenarikanSaldoController extends Controller
{

    public function viewTarikSaldo()
    {
        $rekening = SaldoUser::where('id_user', auth()->user()->id)->first();
        if (!$rekening || !$rekening->nomor_rekening || !$rekening->nama_rekening || !$rekening->tujuan_rek) {
            return redirect()->route('seller.rekening.viewSetRekening')->with('error', 'Silahkan Masukan Informasi Rekening anda terlebih dahulu sebelum melakukan penarikan');
        } else if (!$rekening->saldo || $rekening->saldo = 0) {
            return redirect()->route('seller.keuangan.dashboardKeuangan')->with('error', 'Anda tidak memiliki saldo yang dapat ditarik');
        }
        $list_bank = TujuanRekening::all();
        return view('pemilikSewa.iterasi3.penarikan.create', compact('rekening', 'list_bank'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nominal' => 'required|numeric|min:10000',
            'kode_otp' => 'required|numeric|min_digits:6'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }


        $kode = KodeOTP::where('email', auth()->user()->email)->first();
        if ($request->kode_otp != $kode->kode) {
            return redirect()->back()->with('error', 'Kode OTP tidak valid. Pastikan anda memasukkan kode OTP terbaru')->withInput();
        }

        if (now()->diffInMinutes($kode->updated_at) <= -10) {
            return redirect()->back()->with('error', 'Kode OTP telah kedaluwarsa. Silahkan klik kembali tombol ajukan penarikan')->withInput();
        }

        if ($request->nominal > auth()->user()->saldo_user->saldo) {
            return redirect()->back()->withErrors(['nominal' => 'Saldo anda tidak mencukupi']);
        }

        $rekening = SaldoUser::where('id_user', auth()->user()->id)->first();

        if (!$rekening) {
            return redirect()->back()->withErrors(['bank' => 'Error Tujuan Transfer Tidak Ditemukan']);
        }

        DB::beginTransaction();
        try {
            PenarikanSaldo::create([
                'id_user' => auth()->user()->id,
                'nominal' => $request->nominal,
                'bank' => $rekening->tujuanRekening->nama,
                'nomor_rekening' => $rekening->nomor_rekening,
                'nama_rekening' => $rekening->nama_rekening,
                'status' => 'Menunggu Konfirmasi',
            ]);
            $rekening->saldo -= $request->nominal;
            $rekening->save();
            $kode->delete();
            DB::commit();
            return redirect()->route('seller.penarikan.viewRiwayatPenarikan')->with('success', 'Anda berhasil mengajukan penarikan saldo.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors("Terjadi Kesalahan");
        }
    }

    public function sendOTPpenarikan()
    {
        $kode_otp = mt_rand(100000, 999999);
        while (true) {
            $cekOTP = KodeOTP::where('kode', $kode_otp)->first();
            if ($cekOTP != null) {
                $kode_otp = mt_rand(100000, 999999) + rand(1, 100);
            } else {
                break;
            }
        }

        $kode = KodeOTP::where('email', auth()->user()->email)->first();

        if ($kode) {
            if (now()->diffInMinutes($kode->updated_at) <= -10) {
                KodeOTP::updateOrCreate(
                    ['email' => auth()->user()->email],
                    ['kode' => $kode_otp]
                );
            } else {
                $kode_otp = $kode->kode;
            }
        } else {
            KodeOTP::updateOrCreate(
                ['email' => auth()->user()->email],
                ['kode' => $kode_otp]
            );
        }

        try {
            Mail::to(auth()->user()->email)->send(new \App\Mail\OtpMailPenarikan($kode_otp));
            return response()->json(['success' => true, 'message' => 'Kode OTP terbaru berhasil dikirimkan ke email Anda ']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Kode OTP gagal dikirimkan ke email Anda. Mohon Request Ulang']);
        }
    }
}