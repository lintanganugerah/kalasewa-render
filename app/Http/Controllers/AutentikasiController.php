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
use App\Events\PemilikSewaCreated;
use App\Events\UserChangeProfile;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Toko;
use App\Models\KodeOTP;
use App\Models\Penyewa;
use App\Models\Produk;
use App\Models\FotoProduk;
use App\Models\Series;
use App\Models\Peraturan;
use App\Models\PeraturanSewa;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;
use Exception;
use Throwable;

class AutentikasiController extends Controller
{
    // REGISTER
    public function verifikasiViewOTP(Request $request)
    {
        if (!session()->has('regis')) {
            return redirect()->route('loginView');
        }

        return view('authentication.verifyFormOTP');
    }

    public function verifyOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = KodeOTP::where('email', session('email'))->where('kode', $request->kode)->first();

        if ($user) {
            if (session('role') == 'penyewa') {
                return redirect()->route('registerInformationViewPenyewa');
            } elseif (session('role') == 'pemilik_sewa') {
                return redirect()->route('registerInformationViewPemilikSewa');
            } else {
                return redirect()->back()->with('error', "Role anda tidak valid");
            }
        } else {
            return redirect()->back()->with('error', 'Kode OTP Salah.');
        }
    }

    // PENYEWA

    //Masuk Halaman Registrasi tab Penyewa
    public function registerViewPenyewa(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === "pemilik_sewa") {
                return redirect()->route('pemiliksewa.berandaView');
            } else if ($user->role === "penyewa") {
                return redirect()->route('viewHomepage');
            }
        }

        $peraturans = Peraturan::where('audience', 'penyewa')
            ->orWhere(function ($query) {
                $query->where('audience', 'umum')
                    ->whereNotIn('Judul', function ($subQuery) {
                        $subQuery->select('Judul')
                            ->from('peraturan_platform')
                            ->where('audience', 'penyewa');
                    });
            })
            ->get();

        return view('authentication.register-penyewa', compact('peraturans'));
    }

    //Check Email Penyewa saat Regis
    public function checkEmailPenyewa(Request $request)
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
        session(['role' => "penyewa"]);
        session(['regis' => TRUE]);

        $kode_otp = mt_rand(100000, 999999);
        while (true) {
            $cekOTP = KodeOTP::where('kode', $kode_otp)->first();
            if ($cekOTP != null) {
                $kode_otp = mt_rand(100000, 999999);
            } else {
                break;
            }
        }
        // cek kode
        $cek = KodeOTP::where('email', session('email'));
        if ($cek->count() > 0) {
            $cek->update([
                'kode' => $kode_otp
            ]);
        } else {
            // create otp
            KodeOTP::create([
                'email' => session('email'),
                'kode' => $kode_otp
            ]);
        }

        try {
            Mail::to(session('email'))->send(new \App\Mail\OtpMail($kode_otp));
        } catch (Exception $e) {
            return redirect()->back()->withErrors("Kode OTP Gagal Terkirim ke Email");
        }

        return redirect()->route('verifikasiViewOTP');
    }

    //Masuk ke Halaman Daftar Penyewa Bagian Informasi
    public function registerInformationViewPenyewa(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if (isset($user->nama)) {
                return redirect()->route('viewHomepage');
            }
            session(['profilpath' => $user->foto_profil]);
        } else if (session()->has('regis')) {
            $enumOptions = ['Teman', 'Kerabat', 'Orang Tua'];
            return view('authentication.register-penyewa-informasi', compact('enumOptions'));
        } else {
            return redirect()->back();
        }
    }

    //Action dari Halaman Daftar Informasi Penyewa
    public function registerInformationActionPenyewa(Request $request)
    {
        //SIMPAN INFORMASI AKUN BARU SAAT REGISTER
        $validator = Validator::make($request->all(), [
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&.,^()]{8,}$/'
            ],
            'confPassword' => 'required|string',
            'nama' => 'required|string',
            'nomor_identitas' => 'required|numeric|min_digits:16|max_digits:16|unique:users,NIK',
            'link_sosial_media' => 'required|url',
            'nomor_telpon' => 'required|numeric|min_digits:10|max_digits:13|unique:users,no_telp',
            'ket_no_darurat' => 'required|in:Teman,Kerabat,Orang Tua',
            'nomor_telpon_darurat' => 'required|numeric|min_digits:10|max_digits:13',
            'alamat' => 'required|string',
            'provinsi' => 'required|string',
            'kota' => 'required|in:Kota Bandung,Kabupaten Bandung',
            'kodePos' => 'required|numeric|min_digits:5|max_digits:5',
            'foto_identitas' => 'file|mimes:jpg,jpeg,png,webp|max:20480',
            'foto_diri' => 'file|mimes:jpg,jpeg,png,webp|max:20480',
        ]);

        if (session()->has('email')) {
            $email = Session::get('email');
        } else {
            return redirect()->route('registerViewPenyewa')->with('error', 'Error! Silahkan coba daftar ulang');
        }

        if ($request->nomor_telpon == $request->nomor_telpon_darurat) {
            return redirect()->back()->withErrors("Nomor darurat tidak boleh sama dengan nomor telepon pribadi")->withInput();
        }

        if ($request->input('confPassword') != $request->input('password')) {
            return redirect()->back()->withErrors("Konfirmasi password anda salah!")->withInput();
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }



        $photoPath = $request->file('foto_identitas')->store('public/identitas');
        $photoPath = Str::replaceFirst('public/', 'storage/', $photoPath);

        $photoPathDiri = $request->file('foto_diri')->store('public/foto_diri');
        $photoPathDiri = Str::replaceFirst('public/', 'storage/', $photoPathDiri);

        $u = User::where('email', $email)->first();
        if ($u) {
            if ($u->verifyIdentitas == "Ditolak") {
                $user = $u;
                $user->verifyIdentitas = "Tidak";
            }
        } else {
            $user = new User;
        }
        $user->email = $email;
        $user->nama = $request->nama;
        $user->password = Hash::make($request->password);
        $user->no_telp = $request->nomor_telpon;
        $user->ket_no_darurat = $request->ket_no_darurat;
        $user->no_darurat = $request->nomor_telpon_darurat;
        $user->alamat = $request->alamat;
        $user->provinsi = $request->provinsi;
        $user->link_sosial_media = $request->link_sosial_media;
        $user->kota = $request->kota;
        $user->kode_pos = $request->kodePos;
        $user->nik = $request->nomor_identitas;
        $user->foto_identitas = $photoPath;
        $user->foto_diri = $photoPathDiri;
        $user->role = "penyewa";
        $user->save();

        $KODEOTP = KodeOTP::where('email', session('email'))->first();
        if ($KODEOTP) {
            $KODEOTP->delete();
        }

        event(new UserChangeProfile($user)); //Trigger event untuk mengubah kolom name, dan avatar chatify sesuai dengan data user

        Session::forget('verify');
        Session::forget('email');
        Session::forget('regis');
        Session::forget('role');

        return redirect()->route('loginView')->with('success', 'Registrasi berhasil dilakukan. Mohon Tunggu Konfirmasi Verifikasi Identitas 1x24 jam');
    }

    // PEMILIK SEWA

    //Masuk ke Tab Daftar Pemilik Sewa
    public function registerViewPemilikSewa(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === "pemilik_sewa") {
                return redirect()->route('pemiliksewa.berandaView');
            } else if ($user->role === "penyewa") {
                return redirect()->route('viewHomepage');
            }
        }
        // Mengambil peraturan dengan Audience 'pemilik sewa' dan menampilkan 'umum' jika tidak ada judul yang sama
        $peraturans = Peraturan::where('audience', 'pemilik sewa')
            ->orWhere(function ($query) {
                $query->where('audience', 'umum')
                    ->whereNotIn('Judul', function ($subQuery) {
                        $subQuery->select('Judul')
                            ->from('peraturan_platform')
                            ->where('audience', 'pemilik sewa');
                    });
            })
            ->get();


        return view('authentication.register-pemiliksewa', compact('peraturans'));
    }

    //Check Email Pemilik Sewa saat Regis
    public function checkEmailPemilikSewa(Request $request)
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
        session(['role' => "pemilik_sewa"]);
        session(['regis' => TRUE]);

        $kode_otp = mt_rand(100000, 999999);
        while (true) {
            $cekOTP = KodeOTP::where('kode', $kode_otp)->first();
            if ($cekOTP != null) {
                $kode_otp = mt_rand(100000, 999999);
            } else {
                break;
            }
        }
        // cek kode
        $cek = KodeOTP::where('email', session('email'));
        if ($cek->count() > 0) {
            $cek->update([
                'kode' => $kode_otp
            ]);
        } else {
            // create otp
            KodeOTP::create([
                'email' => session('email'),
                'kode' => $kode_otp
            ]);
        }

        try {
            Mail::to(session('email'))->send(new \App\Mail\OtpMail($kode_otp));
        } catch (Throwable $e) {
            return redirect()->back()->withErrors("Kode OTP Gagal Terkirim ke Email");
        }

        return redirect()->route('verifikasiViewOTP');
    }

    //Masuk ke Halaman Daftar Pemilik Sewa Bagian Informasi
    public function registerInformationViewPemilikSewa(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if (isset($user->nama)) {
                return redirect()->route('seller.dashboardtoko');
            }
            session(['profilpath' => $user->foto_profil]);
        } else if (session()->has('regis')) {
            return view('authentication.register-pemiliksewa-informasi');
        } else {
            return redirect()->back();
        }
    }

    //Action dari Halaman Daftar Informasi Pemilik Sewa
    public function registerInformationActionPemilikSewa(Request $request)
    {
        //SIMPAN INFORMASI AKUN BARU SAAT REGISTER
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'confirmed', 'string', 'min:8', 'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[ -\/:-@\[-`{-~])[A-Za-z\d -\/:-@\[-`{-~]{8,}$/'],
            'nama' => 'required|string',
            'namaToko' => 'required|string',
            'link_sosial_media' => 'required|url',
            'nomor_telpon' => 'required|numeric|min_digits:10|max_digits:13|unique:users,no_telp',
            'AlamatToko' => 'required|string',
            'provinsi' => 'required|string',
            'kota' => 'required|in:Kota Bandung,Kabupaten Bandung',
            'kodePos' => 'required|numeric|min_digits:5|max_digits:5',
            'nomor_identitas' => 'required|numeric|min_digits:16|max_digits:16|unique:users,NIK',
            'foto_identitas' => 'required|image|mimes:jpg,jpeg,png,webp|max:20480',
        ], [
            'password.regex' => 'Password harus 8 karakter, memiliki huruf kapital, angka, dan simbol.',
            'password.confirmed' => 'Konfirmasi password tidak sama dengan password',
        ]);

        if (session()->has('email')) {
            $email = Session::get('email');
        } else {
            return redirect()->route('registerViewPemilikSewa')->with('error', 'Email Telah Terdaftar! Silahkan Login');
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $u = User::where('email', $email)->first();
        $cekToko = Toko::where('nama_toko', $request->namaToko)->first();
        if ($cekToko !== null && optional($u)->verifyIdentitas != "Ditolak") {
            return redirect()->back()->withErrors(['namaToko' => 'Nama Toko telah ada, coba nama toko lain'])->withinput();
        }

        $photoPath = $request->file('foto_identitas')->store('public/identitas');
        $photoPath = Str::replaceFirst('public/', 'storage/', $photoPath);

        if ($u) {
            if ($u->verifyIdentitas == "Ditolak") {
                $ditolak = true;
                $user = $u;
                $toko = Toko::where('id_user', $user->id)->first() ?? new Toko;
                $user->verifyIdentitas = "Tidak";
            }
        } else {
            $ditolak = false;
            $user = new User;
            $toko = new Toko;
        }
        
         DB::beginTransaction();

        if ($ditolak) {
            // Update existing user
            $user->nama = $request->nama;
            $user->password = Hash::make($request->password);
            $user->no_telp = $request->nomor_telpon;
            $user->alamat = $request->AlamatToko;
            $user->provinsi = $request->provinsi;
            $user->link_sosial_media = $request->link_sosial_media;
            $user->kota = $request->kota;
            $user->kode_pos = $request->kodePos;
            $user->NIK = $request->nomor_identitas;
            $user->foto_identitas = $photoPath;
            $user->role = 'pemilik_sewa';

            $toko->nama_toko = $request->namaToko;
            $toko->id_user = $user->id;
            $user->save();
            $toko->save();
        } else {
            // Create new user
            $user = User::create([
                'email' => $email,
                'nama' => $request->nama,
                'password' => Hash::make($request->password),
                'no_telp' => $request->nomor_telpon,
                'alamat' => $request->AlamatToko,
                'provinsi' => $request->provinsi,
                'link_sosial_media' => $request->link_sosial_media,
                'kota' => $request->kota,
                'kode_pos' => $request->kodePos,
                'NIK' => $request->nomor_identitas,
                'foto_identitas' => $photoPath,
                'role' => 'pemilik_sewa',
            ]);
            $toko->nama_toko = $request->namaToko;
            $toko->id_user = $user->id;
            $toko->save();
        }


        $KODEOTP = KodeOTP::where('email', session('email'))->first();
        if ($KODEOTP) {
            $KODEOTP->delete();
        }
        
        $cekPeraturanDefault = PeraturanSewa::where('nama', 'Terlambat Mengembalikan Kostum')->where('id_toko', $toko->id)->first();
        
        if(!$cekPeraturanDefault) {
            event(new PemilikSewaCreated($user));
        }

        DB::commit();

        Session::forget('verify');
        Session::forget('email');
        Session::forget('regis');
        Session::forget('role');

        return redirect()->route('loginView')->with('success', 'Registrasi berhasil dilakukan. Mohon Tunggu Konfirmasi Verifikasi Identitas 1x24 jam');
    }


    // VERIFIKASI

    // LOGIN
    //Masuk ke Halaman Login
    public function loginView(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === "pemilik_sewa") {
                return redirect()->route('seller.dashboardtoko');
            } else if ($user->role === "penyewa") {
                return redirect()->route('viewHomepage');
            } else if ($user->role === "admin" || $user->role === "super_admin") {
                return redirect()->route('admin.dashboard');
            }
        }
        return view('authentication.login');
    }

    //Menekan Tombol Login
    public function loginAction(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $toko = Toko::where('id_user', $user->id)->first();

            if ($user->verifyIdentitas === "Tidak") {
                Auth::logout();
                Session::flush();
                Session::regenerate(true);
                return redirect()->route('loginView')->with('error', 'Mohon Tunggu Konfirmasi admin 1x24 jam');
            } else if ($user->verifyIdentitas === "Ditolak") {
                Auth::logout();
                Session::flush();
                Session::regenerate(true);
                return redirect()->route('loginView')->with('error', 'Informasi anda ditolak oleh admin! Silahkan daftar ulang');
            } else {
                session(['uid' => $user->id]);
                session(['profilpath' => $user->foto_profil]);

                if ($user->role == "pemilik_sewa") {
                    session(['namatoko' => $toko->nama_toko]);
                    return redirect()->route('seller.dashboardtoko');
                } else if ($user->role == "penyewa") {
                    return redirect()->route('viewHomepage');
                } else if ($user->role == "admin" || $user->role == "super_admin") {
                    session(['nama' => $user->nama]);
                    return redirect()->route('admin.dashboard');
                }
            }

        } else {
            return redirect()->route('loginView')->with(['error' => 'Email atau password salah!']);
        }
    }

    // LOGOUT
    //Logout dari sesi yang sedang berlangsung
    public function logout()
    {
        if (Auth::user()) {
            Auth::logout();
            Session::flush();
            Session::regenerate(true);
            return redirect()->route('loginView');
        }
    }

    // RESET PASSWORD
    public function viewForgotPass(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === "pemilik_sewa") {
                return redirect()->route('seller.dashboardtoko');
            } else if ($user->role === "penyewa") {
                return redirect()->route('viewHomepage');
            }
        }
        return view('authentication.resetPassRequestEmail');
    }

    public function ForgotPassAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()->withErrors("Email tidak ditemukan");
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT ? back()->with(['success' => __($status)]) : back()->withErrors(['email' => __($status)]);
    }

    public function viewResetPass(Request $request, $token)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === "pemilik_sewa") {
                return redirect()->route('seller.dashboardtoko');
            } else if ($user->role === "penyewa") {
                return redirect()->route('viewHomepage');
            }
        }

        $user = User::where('email', $request->email)->first();
        $tokenExist = Password::tokenExists($user, $token);

        if ($tokenExist) {
            return view('authentication.resetPass')->with(['token' => $token, 'email' => $request->email]);
        } else {
            return redirect()->route('viewForgotPass')->with('error', 'Token Expired/Tidak Ditemukan. Silahkan Request Ulang! Jika sudah mengirimkan request sebelumnya, Mohon gunakan link terbaru dari email');
        }
    }

    public function resetPassAction(Request $request, $token)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', 'string', 'min:8', 'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[ -\/:-@\[-`{-~])[A-Za-z\d -\/:-@\[-`{-~]{8,}$/'],
        ], [
            'password.regex' => 'Password harus 8 karakter, memiliki huruf kapital, angka, dan simbol.',
            'password.confirmed' => 'Konfirmasi password tidak sama dengan password',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

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

    // REJECTED
    public function viewRejected()
    {
        return view('authentication.infoRejected');
    }
    public function viewBanned()
    {
        return view('authentication.infoBanned');
    }

    // UBAH SANDI
    public function ubahSandi()
    {
        $user = Auth::user();
        return view('admin.ubahSandi', compact('user'));
    }

    public function updateSandi(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).+$/',
                'confirmed',
            ],
            'password_confirmation' => 'required',
        ], [
            'password.regex' => 'Field Password harus setidaknya lebih dari 8 karakter, mengandung huruf besar, huruf kecil, angka, dan karakter khusus.',

        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi lama tidak sesuai.']);
        }

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('admin.ubahSandi')->with('success', 'Kata sandi berhasil diubah');
    }
}