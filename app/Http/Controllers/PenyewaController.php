<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Events\UserChangeProfile;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Toko;
use App\Models\Penyewa;
use App\Models\Produk;
use App\Models\FotoProduk;
use App\Models\Series;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;

class PenyewaController extends Controller
{

    // MANAJEMEN PROFIL VIEW
    public function viewProfile($id)
    {
        $user = User::findOrFail($id);
        return view('penyewa.profile', compact('user'));
    }

    // MANAJEMEN PROFIL ACTION
    public function updateProfile(Request $request)
    {
        // Ambil user yang sedang login
        $user = auth()->user();

        // Validasi data input
        $request->validate([
            'nomor_telpon' => 'required|numeric|min_digits:10|max_digits:13|unique:users,no_telp,' . $user->id,
            'nomor_telpon_darurat' => 'required|numeric|min_digits:10|max_digits:13',
            'link_sosial_media' => 'required|url',
            'alamat' => 'required|string',
            'provinsi' => 'required|string',
            'kota' => 'required|in:Kota Bandung,Kabupaten Bandung',
            'kodePos' => 'required|numeric|min_digits:5|max_digits:5',
        ]);

        $fotoPath = $user->foto_profil;
        $namaFile = basename($fotoPath);

        if ($request->nomor_telpon == $request->nomor_telpon_darurat) {
            return redirect()->back()->withErrors("Nomor darurat tidak boleh sama dengan nomor telepon pribadi")->withInput();
        }

        if ($request->has('foto')) {
            if ($namaFile !== 'profil_default.jpg') {
                Storage::delete(str_replace('storage/', 'public/', $fotoPath));
            }
            $photoPath = $request->file('foto')->store('public/profiles');
            $photoPath = Str::replaceFirst('public/', 'storage/', $photoPath);
            $user->foto_profil = $photoPath;
            $user->save();
        }

        // Perbarui data user
        $user->no_telp = $request->input('nomor_telpon');
        $user->no_darurat = $request->input('nomor_telpon_darurat');
        $user->alamat = $request->input('alamat');
        $user->provinsi = $request->input('provinsi');
        $user->link_sosial_media = $request->input('link_sosial_media');
        $user->kota = $request->input('kota');
        $user->kode_pos = $request->input('kodePos');
        $user->save();

        $userChange = User::where('id', $user->id)->first(); //Get model User nya untuk di kirim ke event
        event(new UserChangeProfile($userChange)); //Trigger event untuk mengubah kolom name, dan avatar chatify sesuai dengan data user

        session(['profilpath' => $user->foto_profil]);

        // Redirect ke halaman profil dengan pesan sukses
        return redirect()->route('viewProfile', ['id' => $user->id])->with('success', 'Profil berhasil diperbarui');
    }


    // MANAJEMEN PASSWORD VIEW
    public function viewGantiPassword($id)
    {
        $user = User::findOrFail($id);
        return view('penyewa.changepassword', compact('user'));
    }

    // MANAJEMEN PASSWORD ACTION
    public function updatePassword(Request $request)
    {
        // Validasi data input
        $validator = Validator::make($request->all(), [
            'password' => 'required|string',
            'newPassword' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&.,^()]{8,}$/'
            ],
            'confNewPassword' => 'required|string', // Menambahkan aturan untuk memastikan konfirmasi password baru cocok dengan password baru
        ]);

        // Ambil user yang sedang login
        $user = auth()->user();

        // Periksa apakah password lama yang dimasukkan oleh pengguna cocok dengan password yang disimpan dalam basis data
        if (!Hash::check($request->input('password'), $user->password)) {
            // Password lama tidak cocok, kembalikan dengan pesan kesalahan
            return redirect()->route('viewGantiPassword', ['id' => $user->id])->with('error', 'Error! Password Lama Salah!');
        }

        if ($request->input('newPassword') != $request->input('confNewPassword')) {
            return redirect()->route('viewGantiPassword', ['id' => $user->id])->with('error', 'Error! Konfirmasi Password Baru Salah!');
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Jika password lama sesuai, perbarui data user dengan password baru
        $user->password = Hash::make($request->input('newPassword'));

        $user->save();

        // Redirect ke halaman profil dengan pesan sukses
        return redirect()->route('viewHomepage', ['id' => $user->id])->with('success', 'Password Berhasil Diganti!');
    }

}