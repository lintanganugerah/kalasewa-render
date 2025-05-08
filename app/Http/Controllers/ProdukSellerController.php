<?php

namespace App\Http\Controllers;

use App\Models\AlamatTambahan;
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
use App\Models\FotoProduk;

class ProdukSellerController extends Controller
{
    public function viewTambahProduk()
    {
        $series = Series::all();
        $toko = Toko::where('id_user', Auth::id())->first();
        $alamatTambahan = null;

        if ($toko->isAlamatTambahan) {
            $alamatTambahan = AlamatTambahan::where('id_toko', $toko->id)->get();
            return view('produk.tambahproduk', compact('series', 'alamatTambahan'));
        }

        return view('produk.tambahproduk', compact('series', 'alamatTambahan'));
    }

    public function viewProdukAnda()
    {
        $user = Auth::user();
        $toko = Toko::where('id_user', $user->id)->first();
        $produk = Produk::where('id_toko', $toko->id)->orderByDesc('updated_at')->get();
        $produkIds = $produk->pluck('id');
        $seriesIds = $produk->pluck('id_series');
        $fotoProduk = FotoProduk::whereIn('id_produk', $produkIds)->get();
        $series = Series::whereIn('id', $seriesIds)->get();

        return view('produk.produkanda', compact('produk', 'fotoProduk', 'series'));
    }

    public function viewEditProduk($id)
    {
        $user = Auth::user();
        $toko = Toko::where('id_user', $user->id)->first();
        $produk = Produk::find($id);
        if (!$produk) {
            return redirect()->route('seller.viewProdukAnda')->with('error', 'Produk Invalid');
        }
        $produkIds = $produk->pluck('id');
        $fotoProduk = FotoProduk::whereIn('id_produk', $produkIds)->get();
        session(['id_produk' => $produk->id]);
        $series = Series::all();
        $decodeAdd = json_decode($produk->additional, true); //Menggubah menjadi array key value "nama" => "harga"

        if ($toko->id == $produk->id_toko) {

            $alamatTambahan = null;

            if ($toko->isAlamatTambahan) {
                $alamatTambahan = AlamatTambahan::where('id_toko', $toko->id)->get();
            }
            return view('produk.editproduk', compact('produk', 'fotoProduk', 'series', 'decodeAdd', 'alamatTambahan'));
        } else {
            return redirect()->back()->with('error', 'Produk Invalid');
        }
    }

    public function editProdukAction(Request $request, $id)
    {
        $user = Auth::user();
        $produk = Produk::where('id', $id)->first();
        $toko = Toko::where('id_user', $user->id)->first();

        $validator = Validator::make($request->all(), [
            'namaProduk' => 'required|string',
            'deskripsiProduk' => 'required|string',
            'series' => 'required',
            'ukuran' => 'required',
            'harga' => 'required|numeric|min:100',
            'brand' => 'required|string', //UBAH JADI STRING, HABIS CEK OLD DD
            'gender' => 'required|in:Pria,Wanita,Semua Gender',
            'foto_produk' => 'nullable|max:5120',
            'beratProduk' => 'required|numeric|min:10',
            'metode_kirim' => 'required',
            'grade' => 'required|String|in:Grade 1,Grade 2,Grade 3',
            'cuci' => 'required',
            'wig_opsi' => 'required',
            'biaya_cuci' => 'required_if:cuci,ya|numeric|min:100',
            'brand_wig' => 'required_if:wig_opsi,ya|String',
            'ket_wig' => 'required_if:wig_opsi,ya|String',
        ]);


        if ($toko->id != $produk->id_toko) {
            return redirect()->back()->with('error', "Produk Invalid");
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->has("additional")) {
            $additionalData = [];
            $additional = $request->additional;
            $count = count($additional);

            //Cek modulus/modolu kalau sisa total data itu 0 jika dibagi 2 artinya jumlah genap. Kalo ganjil berarti ada field yang kosong.
            if ($count % 2 != 0) {
                //Hasil additional selalu genap. Nama Additional di ganjil, Harga nya di genap (Jumlah 2 tiap additional, genap)
                // Kalo hasil ganjil, maka ini di eksekusi, dan berarti ada satu field yang ga ke isi
                return redirect()->back()->withErrors("Ada kesalahan data pada Barang Additional, Mohon Refresh halaman")->withInput();
            }

            // Lakukan perulangan setiap jarak 2, karena tiap additional pake 2 indeks array
            for ($i = 0; $i < $count; $i += 2) {
                $nama = $additional[$i]; //Simpan isi value index ganjil array $request->additional sebagai nama (key nya) 
                $harga = $additional[$i + 1]; //Simpan isi value index genap array $request->additionalsebagai harga (value nya)
                $harga = str_replace('.', '', $harga);

                // Cek apakah isi string harga itu numeric. Jika tidak maka balik kan ke halaman tambah produk
                if (!is_numeric($harga)) {
                    return redirect()->back()->withErrors(["add" . $i + 1 => "Tipe data Harga pada form additional tidak valid"])->withInput();
                }

                // Cek apakah ada nama additional yang sama dengan sebelumnya
                if (isset($additionalData[$nama])) {
                    return redirect()->back()->withErrors(["add" . $i => "Nama Additional Tidak Boleh Saling sama, ganti dengan nama yang lain"])->withInput();
                }

                // Akhirnya masukin key => value_harga
                $additionalData[$nama] = abs((int) $harga);
            }

            $produk->additional = json_encode($additionalData);
        } else {
            $produk->additional = null;
        }

        if ($request->has('biaya_cuci')) {
            $produk->biaya_cuci = str_replace('.', '', $request->biaya_cuci);
        } else {
            $produk->biaya_cuci = null;
        }

        if ($request->has('brand_wig')) {
            $produk->brand_wig = $request->brand_wig;
            $produk->keterangan_wig = $request->ket_wig;
        } else {
            $produk->brand_wig = null;
            $produk->keterangan_wig = null;
        }

        if ($request->alamat_opsi) {
            if ($request->alamat_opsi != 'default') {
                $alamat = AlamatTambahan::where('id', $request->alamat_opsi)->first();
                if (!$alamat) {
                    return redirect()->back()->withErrors(["alamat_opsi" => "Alamat Invalid. Tidak Ditemukan"])->withInput();
                } elseif ($alamat->id_toko != $toko->id) {
                    return redirect()->back()->withErrors(["alamat_opsi" => "Alamat Invalid"])->withInput();
                }

                $produk->id_alamat = $request->alamat_opsi;
            } else {
                $produk->id_alamat = null;
            }
        }

        $series = Series::firstOrCreate(['series' => ucwords($request->series)]);

        $produk->grade = $request->grade;
        $produk->nama_produk = $request->namaProduk;
        $produk->deskripsi_produk = $request->deskripsiProduk;
        $produk->id_series = $series->id; // Menggunakan series yang ada atau buat baru
        $produk->brand = $request->brand;
        $produk->harga = abs($request->harga);
        $produk->gender = $request->gender;
        $produk->berat_produk = abs($request->beratProduk);
        $produk->metode_kirim = $request->metode_kirim;
        $produk->ukuran_produk = $request->ukuran;

        if ($request->has("foto_produk")) {
            //CEK EKSTENSI FOTO TERLEBIH DAHULU
            foreach ($request->foto_produk as $file) {
                // Ambil ekstensi file
                $extension = $file->getClientOriginalExtension();

                // Periksa ekstensi file sesuai
                if (!in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp'])) {
                    return redirect()->back()->with('error', 'Foto harus berupa file dengan format jpg, jpeg, png, atau webp.')->withInput();
                }
            }
            foreach ($request->foto_produk as $foto) {
                $pathSebelum = $foto->store('public/produk/foto_produk');
                $path = str_replace('public/', 'storage/', $pathSebelum);

                // Buat instance model FotoProduk
                $fotoProduk = new FotoProduk();
                $fotoProduk->id_produk = $id;
                $fotoProduk->path = $path;
                $fotoProduk->save();
            }
        }

        $produk->save();

        return redirect()->route('seller.viewProdukAnda')->with('success', 'Perubahan Produk Berhasil Disimpan');
    }

    public function tambahProdukAction(Request $request)
    {
        $user = Auth::user();
        $toko = Toko::where('id_user', $user->id)->first();

        // dd($request->foto_produk);
        $validator = Validator::make($request->all(), [
            'namaProduk' => 'required|string',
            'deskripsiProduk' => 'required|string',
            'series' => 'required|string',
            'ukuran' => 'required|in:XS,S,M,L,XL,XXL,All_Size',
            'harga' => 'required|numeric|min:100',
            'brand' => 'required|string',
            'gender' => 'required|string|in:Pria,Wanita,Semua Gender',
            'foto_produk' => 'required|max:5120',
            'beratProduk' => 'required|numeric|min:50',
            'metode_kirim' => 'required',
            'grade' => 'required|String|in:Grade 1,Grade 2,Grade 3',
            'cuci' => 'required',
            'wig_opsi' => 'required',
            'biaya_cuci' => 'required_if:cuci,ya|numeric|min:100',
            'brand_wig' => 'required_if:wig_opsi,ya|String',
            'ket_wig' => 'required_if:wig_opsi,ya|String',
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        foreach ($request->foto_produk as $file) {
            // Ambil ekstensi file
            $extension = $file->getClientOriginalExtension();

            // Periksa ekstensi file sesuai
            if (!in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp'])) {
                return redirect()->back()->with('error', 'Foto harus berupa file dengan format jpg, jpeg, png, atau webp.')->withInput();
            }
        }

        // Cek dan buat series baru jika perlu
        $series = Series::firstOrCreate(['series' => ucwords($request->series)]);

        $produk = new Produk;

        if ($request->has("additional")) {
            $additionalData = [];
            $additional = $request->additional;
            $count = count($additional);

            //Cek modulus/modolu kalau sisa total data itu 0 jika dibagi 2 artinya jumlah genap. Kalo ganjil berarti ada field yang kosong.
            if ($count % 2 != 0) {
                //Hasil additional selalu genap. Nama Additional di ganjil, Harga nya di genap (Jumlah 2 tiap additional, genap)
                // Kalo hasil ganjil, maka ini di eksekusi, dan berarti ada satu field yang ga ke isi
                return redirect()->back()->withErrors("Ada kesalahan data pada Barang Additional, Mohon Refresh halaman")->withInput();
            }

            // Lakukan perulangan setiap jarak 2, karena tiap additional pake 2 indeks array
            for ($i = 0; $i < $count; $i += 2) {
                $nama = $additional[$i]; //Simpan isi value index ganjil array $request->additional sebagai nama (key nya) 
                $harga = $additional[$i + 1]; //Simpan isi value index genap array $request->additionalsebagai harga (value nya)
                $harga = str_replace('.', '', $harga);

                // Cek apakah isi string harga itu numeric. Jika tidak maka balik kan ke halaman tambah produk
                if (!is_numeric($harga)) {
                    return redirect()->back()->withErrors(["add" . $i + 1 => "Tipe data Harga pada form additional tidak valid"])->withInput();
                }

                // Cek apakah ada nama additional yang sama dengan sebelumnya
                if (isset($additionalData[$nama])) {
                    return redirect()->back()->withErrors(["add" . $i => "Nama Additional Tidak Boleh Saling sama, ganti dengan nama yang lain"])->withInput();
                }

                // Akhirnya masukin key => value_harga
                $additionalData[$nama] = abs((int) $harga);
            }

            $produk->additional = json_encode($additionalData);
        }

        if ($request->alamat_opsi) {
            if ($request->alamat_opsi != 'default') {
                $alamat = AlamatTambahan::where('id', $request->alamat_opsi)->first();
                if (!$alamat) {
                    return redirect()->back()->withErrors(["alamat_opsi" => "Alamat Invalid. Tidak Ditemukan"])->withInput();
                } elseif ($alamat->id_toko != $toko->id) {
                    return redirect()->back()->withErrors(["alamat_opsi" => "Alamat Invalid"])->withInput();
                }

                $produk->id_alamat = $request->alamat_opsi;
            }
        }

        $produk->grade = $request->grade;
        $produk->nama_produk = $request->namaProduk;
        $produk->deskripsi_produk = $request->deskripsiProduk;
        $produk->id_series = $series->id; // Menggunakan ID series yang ditemukan atau baru dibuat
        $produk->brand = $request->brand;
        $produk->harga = abs($request->harga);
        $produk->gender = $request->gender;
        $produk->berat_produk = abs($request->beratProduk);
        $produk->metode_kirim = $request->metode_kirim;
        $produk->ukuran_produk = $request->ukuran;
        $produk->id_toko = $toko->id;

        $produk->save();
        $id_produk = $produk->getKey();
        if ($request->has('biaya_cuci')) {
            $produk->biaya_cuci = str_replace('.', '', $request->biaya_cuci);
        }

        if ($request->has('brand_wig')) {
            $produk->brand_wig = $request->brand_wig;
            $produk->keterangan_wig = $request->ket_wig;
        }

        foreach ($request->foto_produk as $foto) {
            $pathSebelum = $foto->store('public/produk/foto_produk');
            $path = str_replace('public/', 'storage/', $pathSebelum);

            $produk->save();
            $id_produk = $produk->getKey();

            // Buat instance model FotoProduk
            $fotoProduk = new FotoProduk();
            $fotoProduk->id_produk = $id_produk;
            $fotoProduk->path = $path;
            $fotoProduk->save();
        }
        return redirect()->route('seller.viewProdukAnda')->with('success', 'Produk Berhasil Ditambahkan');
    }

    public function arsipProduk($id)
    {
        $user = Auth::user();
        $toko = Toko::where('id_user', $user->id)->first();
        $produk = Produk::find($id);
        if (!$produk) {
            return redirect()->back()->with('error', 'Produk Invalid');
        }
        if ($toko->id == $produk->id_toko) {
            $produk->update(['status_produk' => 'arsip']);
            return redirect()->back()->with('success', 'Produk berhasil diarsipkan.');
        } else {
            return redirect()->back()->with('error', 'Produk Invalid');
        }
    }

    public function aktifkanProduk($id)
    {
        $user = Auth::user();
        $toko = Toko::where('id_user', $user->id)->first();
        $produk = Produk::find($id);
        if (!$produk) {
            return redirect()->back()->with('error', 'Produk Invalid');
        }
        if ($toko->id == $produk->id_toko) {
            if ($produk->LastOrder) {
                if (!$produk->LastOrder->ready_status) {
                    $produk->LastOrder->update(['ready_status' => 'done']);
                }
            }
            $produk->update(['status_produk' => 'aktif']);
            return redirect()->back()->with('success', 'Produk berhasil diaktifkan untuk ditampilkan pada marketplace.');
        } else {
            return redirect()->back()->with('error', 'Produk Invalid');
        }
    }

    public function hapusProduk($id)
    {
        $user = Auth::user();
        $toko = Toko::where('id_user', $user->id)->first();
        $produk = Produk::find($id);
        $lastOrder = $produk->lastOrder;
        if (!$produk) {
            return redirect()->back()->with('error', 'Produk Invalid');
        }

        if ($lastOrder) {
            if (!in_array($produk->lastOrder->status, ['Penyewaan Selesai', 'Retur Selesai', 'Dibatalkan Pemilik Sewa'])) {
                // Jika tidak termasuk, kembalikan response atau lakukan tindakan yang sesuai
                return redirect()->back()->with('error', 'Tidak bisa menghapus produk yang sedang disewa');
            }
        }

        if ($toko->id == $produk->id_toko) {
            $fotoProduk = FotoProduk::where('id_produk', $produk->id)->get();
            if ($fotoProduk) {
                foreach ($fotoProduk as $foto) {
                    $path = $foto->path; // Path foto di storage
                    Storage::delete(str_replace('storage/', 'public/', $path));
                    $foto->delete(); // Hapus entri foto dari database
                }
            }
            $produk->delete();
            return redirect()->back()->with('success', 'Produk berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'Produk Invalid');
        }
    }

    public function hapusFoto($id)
    {
        $user = Auth::user();
        $toko = Toko::where('id_user', $user->id)->first();
        $foto = FotoProduk::find($id);

        if (!$foto) {
            return redirect()->back()->with('error', 'Foto Invalid');
        }

        $produk = Produk::where('id', session('id_produk'))->first();

        if ($foto->id_produk == $produk->id) {
            // Cek jumlah foto yang tersisa
            $jumlahFoto = FotoProduk::where('id_produk', $produk->id)->count();

            if ($jumlahFoto == 1) {
                return redirect()->back()->with('error', 'Tidak dapat menghapus foto terakhir. Harap tambahkan foto pengganti nya lalu klik simpan perubahan');
            }

            // Hapus foto dari storage
            $path = $foto->path;
            Storage::delete(str_replace('storage/', 'public/', $path));

            // Hapus foto dari database
            $foto->delete();

            return redirect()->back()->with('success', 'Foto berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'Terjadi kesalahan, silahkan refresh browser anda');
        }
    }
}