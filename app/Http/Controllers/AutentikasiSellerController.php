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


class AutentikasiSellerController extends Controller
{

    public function registerView(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === "pemilik_sewa") {
                return redirect()->route('seller.dashboardtoko');
            }
        }
        return view('autentikasi-seller.daftar-seller');
    }

    public function registerActionBuyer(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === "pemilik_sewa") {
                return redirect()->route('seller.dashboardtoko');
            }
        }
        return view('autentikasi-seller.daftar-buyer');
    }

    public function registerViewBuyer(Request $request)
    {
        return view('autentikasi-seller.daftar-buyer');
    }

    public function registerInformationView(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if (isset($user->nama)) {
                return redirect()->route('seller.dashboardtoko');
            }
            session(['profilpath' => $user->foto_profil]);
        } else if (session()->has('regis')) {
            return view('autentikasi-seller.daftar-informasi-seller');
        } else if (session('Invalid_Identitas') === TRUE) {
            $user = User::where('id', session('user_ID'))->first();
            $toko = Toko::where('id_user', session('user_ID'))->first();

            // Perbaikan: Mengubah compact() agar nama variabel diberikan sebagai string
            return view('autentikasi-seller.daftar-informasi-seller', compact('user', 'toko'));
        } else {
            return redirect()->back();
        }
    }

    public function loginView(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === "pemilik_sewa") {
                return redirect()->route('seller.dashboardtoko');
            }
        }
        return view('autentikasi-seller.loginView');
    }

    public function verifikasiView(Request $request)
    {
        if (!session()->has('verify')) {
            return redirect()->route('loginView');
        } elseif (session()->has('verified')) {
            return redirect()->route('seller.verifiedView');
        }
        return view('autentikasi-seller.daftar-kode-verifikasi');
    }

    public function verifiedView(Request $request)
    {
        if (!session()->has('verified')) {
            return redirect()->route('loginView');
        }
        Session::forget('verified');
        return view('autentikasi-seller.daftar-verified-view');
    }

    public function viewForgotPass(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === "pemilik_sewa") {
                return redirect()->route('seller.dashboardtoko');
            }
        }
        return view('autentikasi-seller.resetPassRequestEmail');
    }

    public function ForgotPassAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT ? back()->with(['success' => __($status)]) : back()->withErrors(['email' => __($status)]);
    }

    public function viewresetPass(Request $request, $token)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === "pemilik_sewa") {
                return redirect()->route('seller.dashboardtoko');
            }
        }
        return view('autentikasi-seller.resetPass')->with(['token' => $token, 'email' => $request->email]);
    }

    public function resetPassAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min_digits:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET ? redirect()->route('loginView')->with('success', __($status)) :
            back()->withErrors(['email' => [__($status)]]);
    }

    public function registerInformationActionSeller(Request $request)
    {
        //SIMPAN INFORMASI AKUN BARU SAAT REGISTER
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min_digits:8,',
            'nama' => 'alpha|string',
            'namaToko' => 'required|string|unique:tokos,nama_toko',
            'link_sosial_media' => 'required|url',
            'nomor_telpon' => 'required|numeric|min_digits:10|max_digits:13|unique:users,no_telp',
            'AlamatToko' => 'required|string',
            'provinsi' => 'required|string',
            'kota' => 'required|in:Kota Bandung,Kabupaten Bandung',
            'kodePos' => 'required|numeric|min_digits:5|max_digits:5',
            'metode_kirim' => 'required',
            'nomor_identitas' => 'required|numeric|min_digits:16|max_digits:16|unique:users,NIK',
            'foto_identitas' => 'file|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if (session()->has('email')) {
            $email = Session::get('email');
        } else {
            return redirect()->route('seller.registerView')->with('error', 'Silahkan coba daftar ulang');
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $cekToko = DB::table('tokos')->where('nama_toko', $request->namaToko)->first();
        if ($cekToko !== null) {
            return redirect()->back()->withErrors(['msg' => 'Nama Toko telah ada, coba nama toko lain']);
        }

        $photoPath = $request->file('foto_identitas')->store('public/identitas');
        $photoPath = Str::replaceFirst('public/', 'storage/', $photoPath);

        $user = new User;
        $toko = new Toko;
        $user->email = $email;
        $user->nama = $request->nama;
        $user->password = Hash::make($request->password);
        $user->no_telp = $request->nomor_telpon;
        $user->alamat = $request->AlamatToko;
        $user->provinsi = $request->provinsi;
        $user->link_sosial_media = $request->link_sosial_media;
        $user->kota = $request->kota;
        $user->kode_pos = $request->kodePos;
        $user->nik = $request->nomor_identitas;
        $user->foto_identitas = $photoPath;
        $user->role = "pemilik_sewa";
        $user->save();
        $toko->nama_toko = $request->namaToko;
        $toko->id_user = $user->id;
        $toko->metode_kirim = json_encode($request->metode_kirim);
        $toko->save();
        $user->sendEmailVerificationNotification();
        session(['verify' => TRUE]);

        return redirect()->route('seller.verifikasiView');
    }

    public function checkEmailSeller(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'setuju_syarat_dan_ketentuan' => 'accepted',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $email = strtolower($request->email);
        $u = DB::table('users')->where('email', $email)->first();
        if ($u) {
            if ($u->verifyIdentitas == "Sudah" || $u->verifyIdentitas == "Tidak") {
                return redirect()->back()->with('error', 'Alamat Email sudah terdaftar, silahkan login atau gunakan email lain!');
            }
        }

        session(['email' => $email]);
        session(['regis' => TRUE]);

        return redirect()->route('seller.registerInformationView');
    }

    public function checkEmailBuyer(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        $email = strtolower($request->email);
        $u = DB::table('users')->where('email', $email)->first();
        if ($u) {
            return redirect()->back()->with('error', 'Alamat Email sudah terdaftar, silahkan login atau gunakan email lain!');
        }

        return redirect()->route('seller.verifikasiView');
    }

    public function verify(Request $request, $user_id, $hash)
    {
        if (!$request->hasValidSignature()) {
            return response()->json(["msg" => "Expired url provided. Silahkan Login untuk mengirimkan ulang link verifikasi"], 401);
        }

        Session::forget('verify');
        session(['verified' => TRUE]);
        Session::forget('email');
        Session::forget('regis');

        if (!Auth::check()) {
            $user = User::findOrFail($user_id);
        } else {
            $user = Auth::user();
        }

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        } else {
            return redirect()->route('seller.registerView')->with("error", "User Telah terverifikasi, Silahkan Login");
        }

        Auth::logout();

        return redirect()->route('seller.verifiedView');
    }

    public function loginAction(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $user_id = $user->id;
            $toko = Toko::where('id_user', $user->id)->first();

            if (!isset($user->email_verified_at)) {
                session(['verify' => TRUE]);
                session(['email' => $user->email]);
                $user->sendEmailVerificationNotification();
                Auth::logout();
                return redirect()->route('seller.verifikasiView');
            } else if ($user->verifyIdentitas === "Tidak") {
                Auth::logout();
                Session::flush();
                Session::regenerate(true);
                return redirect()->route('loginView')->with('error', 'Mohon Tunggu Konfirmasi admin 1x24 jam');
            } else if ($user->verifyIdentitas === "Ditolak") {
                Auth::logout();
                Session::flush();
                Session::regenerate(true);
                return redirect()->route('loginView')->with('error', 'Informasi anda ditolak oleh admin! Silahkan daftar ulang');
            }

            session(['uid' => $user->id]);
            session(['profilpath' => $user->foto_profil]);


            if ($user->role == "pemilik_sewa") {
                session(['namatoko' => $toko->nama_toko]);
                return redirect()->route('seller.dashboardtoko');
            } else if ($user->role == "pemilik_sewa") {
                return redirect()->route('');
            }
        } else {
            return redirect()->route('loginView')->with(['error' => 'Email atau password salah!']);
        }
    }

    public function logout()
    {
        if (Auth::user()) {
            Auth::logout();
            Session::flush();
            Session::regenerate(true);
            return redirect()->route('loginView');
        }
    }
}