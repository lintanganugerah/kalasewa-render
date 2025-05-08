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
use App\Models\PengajuanDenda;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function viewOrder($id)
    {

        $produk = Produk::with('toko.user')->findOrFail($id);
        $fotoproduk = FotoProduk::where('ID_produk', $id)->get();
        $series = Series::all();
        $brand = Produk::select('brand')->distinct()->get();
        $toko = Toko::all();

        if ($produk->status_produk == "tidak ready") {
            return redirect()->route('viewHomepage')->with('error', 'Produk Sudah Dipesan Oleh Orang Lain');
        }

        return view('penyewa.pemesanan.informasiPemesanan', compact('produk', 'fotoproduk', 'series', 'brand', 'toko'));
    }

    public function createOrder(Request $request, $id)
    {
        // Validasi data
        $validator = Validator::make($request->all(), [
            'mulaisewa' => 'required|date',
            'akhirsewa' => 'required|date',
            'size' => 'required|string',
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'metodekirim' => 'required|string',
            'additional_items' => 'required|string' // validasi untuk hidden input additional_items
        ]);

        // Jika validasi gagal, kembali dengan pesan kesalahan
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        // Ambil data yang sudah divalidasi
        $validatedData = $validator->validated();

        // Decode JSON additional items
        $additionalItems = json_decode($validatedData['additional_items'], true);

        // Ambil produk berdasarkan id
        $produk = Produk::findOrFail($id);

        if ($produk->status_produk == "tidak ready") {
            return redirect()->route('viewHomepage')->with('error', 'Produk Sudah Dipesan Oleh Orang Lain');
        }

        // Hitung total harga
        $hargaKatalog = $produk->harga;
        $hargaCuci = $produk->biaya_cuci ? $produk->biaya_cuci : 0;
        $totalHargaAdditional = collect($additionalItems)->sum('harga');
        $biayaAdmin = ($hargaKatalog + $totalHargaAdditional) * 0.05;
        $totalKatalog = $hargaKatalog + $totalHargaAdditional + $hargaCuci;
        $biayaJaminan = 50000;
        $biayaOngkir = 30000;
        $totalHarga = $hargaKatalog + $totalHargaAdditional + $hargaCuci + $biayaAdmin + $biayaJaminan + $biayaOngkir;

        // Midtrans
        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $nomorOrder = "ORS" . time() . auth()->user()->id . $produk->id . mt_rand(1, 100);

        $params = array(
            'transaction_details' => array(
                'order_id' => $nomorOrder,
                'gross_amount' => $totalHarga,
            ),
            'expiry' => [
                'start_time' => Carbon::now()->format('Y-m-d H:i:s T'), // waktu mulai
                'unit' => 'minute', // satuan waktu
                'duration' => 10 // durasi dalam satuan yang telah ditentukan
            ],
        );

        $snapToken = \Midtrans\Snap::getSnapToken($params);


        // Simpan ke tabel Order
        $order = new OrderPenyewaan();
        $order->nomor_order = $nomorOrder;
        $order->id_penyewa = auth()->user()->id;
        $order->id_toko = $produk->toko->id;
        $order->id_produk = $produk->id;
        $order->ukuran = $request->size;
        $order->tujuan_pengiriman = "Atas Nama " . $request->nama . " " . auth()->user()->no_telp . ", " . $request->alamat . ", " . auth()->user()->kota . ", " . auth()->user()->provinsi . ", " . auth()->user()->kode_pos;
        $order->metode_kirim = $request->metodekirim;
        $order->tanggal_mulai = $request->mulaisewa;
        $order->tanggal_selesai = $request->akhirsewa;
        $order->biaya_cuci = $hargaCuci;
        $order->ongkir_default = 30000;
        $order->fee_admin = $biayaAdmin;
        $order->total_harga = $totalKatalog;
        $order->grand_total = $totalHarga;
        $order->jaminan = $biayaJaminan;
        $order->total_penghasilan = $totalKatalog - $biayaAdmin;
        $order->additional = $additionalItems;
        $order->status = "Pending";
        $order->snapToken = $snapToken;

        $produk->status_produk = 'tidak ready';

        $order->save();
        $produk->save();

        return redirect()->route('viewCheckout')->with('success', 'Order berhasil dibuat.');
    }

    public function viewCheckout()
    {

        $checkout = OrderPenyewaan::with('produk')
            ->where('id_penyewa', auth()->user()->id)
            ->where('status', 'Pending')
            ->get();

        return view('penyewa.pemesanan.checkout', compact('checkout'));
    }

    public function getTransaction(Request $request)
    {
        $serverKey = config('midtrans.serverKey');

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode($serverKey . ':')
        ])->get("https://app.sandbox.midtrans.com/snap/v1/transactions/" . $request->transactionId);

        $data = $response->json();

        if (isset($data['error_messages'])) {
            $checkout = OrderPenyewaan::find($request->nomor_order);
            if ($data['error_messages'][0] == "token is expired") {
                $produk = Produk::where('id', $checkout->id_produk)->first();
                $produk->status_produk = 'aktif';
                $produk->save();
                $checkout->delete();
                $redirectUrl = route('viewCheckout');
                return response()->json(['success' => false, 'message' => 'expired', 'redirect_url' => $redirectUrl]);
            } else if ($data['error_messages'][0] == "transaction has been succeed") {
                $order = OrderPenyewaan::where('nomor_order', $request->nomor_order)->first();
                $order->status = "Menunggu di Proses";
                $order->save();
                $redirectUrl = route('viewHistoryMenungguDiproses');

                //TAMBAH SERIES KE TOP SERIES
                $idseries = $checkout->produk->id_series;
                $topSeries = TopSeries::where('id_series', $idseries)->whereMonth('created_at', Carbon::now()->translatedFormat('m'))->whereYear('created_at', Carbon::now()->translatedFormat('Y'))->first();
                if ($topSeries) {
                    $topSeries->banyak_dipesan += 1;
                    $topSeries->save();
                } else {
                    TopSeries::create([
                        'id_series' => $idseries,
                        'banyak_dipesan' => 1
                    ]);
                }
                return response()->json(['success' => false, 'message' => 'success', 'redirect_url' => $redirectUrl]);
            }
            return response()->json(['success' => false, 'message' => 'error lain']);
        }
        return response()->json(['success' => true]);
    }

    public function updateCheckout(Request $request)
    {
        $checkout = OrderPenyewaan::find($request->nomor_order);
        if ($checkout) {
            $checkout->status = "Menunggu di Proses";
            $checkout->save();

            //TAMBAH SERIES KE TOP SERIES
            $idseries = $checkout->produk->id_series;
            $topSeries = TopSeries::where('id_series', $idseries)->whereMonth('created_at', Carbon::now()->translatedFormat('m'))->whereYear('created_at', Carbon::now()->translatedFormat('Y'))->first();
            if ($topSeries) {
                $topSeries->banyak_dipesan += 1;
                $topSeries->save();
            } else {
                TopSeries::create([
                    'id_series' => $idseries,
                    'banyak_dipesan' => 1
                ]);
            }

            $redirectUrl = route('viewHistoryMenungguDiproses'); // Assuming 'viewHistory' is the name of your route
            return response()->json(['success' => true, 'redirect_url' => $redirectUrl]);
        }
        return response()->json(['success' => false], 400);
    }

    public function viewDetailPemesanan($orderId)
    {
        $order = OrderPenyewaan::where('nomor_order', $orderId)->first(); // Mengambil satu order berdasarkan nomor order
        if ($order) {
            // Ambil nama produk dan foto produk
            $produk = Produk::findOrFail($order->id_produk);
            $user = User::findOrFail($order->id_penyewa);
            $order->harga_katalog = $produk->harga;
            $order->nama_produk = $produk->nama_produk; // Tambahkan kolom nama_produk ke dalam objek order
            $order->foto_produk = $produk->fotoProduk->path; // Tambahkan kolom foto_produk ke dalam objek order
            $order->nama_penyewa = $user->nama;
            $uangKembali = $order->jaminan + $order->ongkir_default;

            // Pastikan additional adalah array
            if (!is_array($order->additional)) {
                $order->additional = json_decode($order->additional, true);
            }
        }

        if ($order->status == "Pending") {
            return redirect()->route('viewCheckout');
        }

        return view('penyewa.transaksi.detailPemesanan', compact('order', 'uangKembali'));
    }

    public function terimaBarang(Request $request, $orderId)
    {
        $order = OrderPenyewaan::findOrFail($orderId);
        $validator = Validator::make($request->all(), [
            'bukti_diterima' => 'required|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $photoPath = $request->file('bukti_diterima')->store('public/bukti_foto');
        $photoPath = Str::replaceFirst('public/', 'storage/', $photoPath);

        $order->bukti_penerimaan = $photoPath;
        $order->tanggal_diterima = today();
        $order->status = "Sedang Berlangsung";
        $order->save();

        return redirect()->route('viewHistorySedangBerlangsung')->with('success', 'Bukti Diterima berhasil di Update');
    }

    public function viewPengembalianBarang($orderId)
    {
        $order = OrderPenyewaan::where('nomor_order', $orderId)->first(); // Mengambil satu order berdasarkan nomor order

        $produk = Produk::findOrFail($order->id_produk);
        $user = User::findOrFail($order->id_penyewa);
        $nama_produk = $produk->nama_produk;
        $nama_penyewa = $user->nama;
        $harga_katalog = $produk->harga;

        if ($order->jaminan < 0) {
            $uangKembali = $order->ongkir_default;
            $totalDenda = $order->jaminan;
        } else {
            $uangKembali = $order->jaminan + $order->ongkir_default;
        }
        $order->save();
        
        $denda_lain = PengajuanDenda::where('nomor_order', $orderId)->sum('sisa_denda') ?? 0;

        return view('penyewa.transaksi.detailPengembalian', compact('order', 'uangKembali', 'nama_produk', 'nama_penyewa', 'harga_katalog', 'denda_lain'));
    }

    public function createSnapTokenDenda($orderId)
    {
        $order = OrderPenyewaan::where('nomor_order', $orderId)->first(); // Mengambil satu order berdasarkan nomor order
        $totalDenda = $order->jaminan;

        // Midtrans
        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $nomorDenda = $order->nomor_order . "_denda";

        try {
            $params = array(
                'transaction_details' => array(
                    'order_id' => $nomorDenda,
                    'gross_amount' => abs($totalDenda),
                ),
                'expiry' => [
                    'start_time' => Carbon::now()->format('Y-m-d H:i:s T'), // waktu mulai
                    'unit' => 'minute', // satuan waktu
                    'duration' => 15 // durasi dalam satuan yang telah ditentukan
                ],
            );
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $order->snapToken = $snapToken;
            $order->save();

            return response()->json(['success' => true, 'snap' => $snapToken]);
        } catch (\Exception $e) {
            Log::error('Midtrans API error: ' . $e->getMessage());

            $redirectUrl = route('viewHistorySedangBerlangsung');
            if ($order->jaminan == 0) {
                return response()->json(['success' => false, 'message' => 'terbayar', 'redirect_url' => $redirectUrl]);
            }

            // Check if the error message indicates a duplicate order_id
            if (strpos($e->getMessage(), 'transaction_details.order_id has already been taken') !== false) {
                $order->total_penghasilan += abs($order->jaminan);
                $order->jaminan = 0;
                $order->save();
                return response()->json(['success' => false, 'message' => 'terbayar', 'redirect_url' => $redirectUrl]);
                // return redirect()->route('viewHistorySedangBerlangsung')->render();
            } else {
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    public function createSnapTokenDendaLain($orderId)
    {
        $order = OrderPenyewaan::where('nomor_order', $orderId)->first(); // Mengambil satu order berdasarkan nomor order
        $totalDenda = PengajuanDenda::where('nomor_order', $orderId)->where('status', 'diproses')->sum('sisa_denda');

        // Midtrans
        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $nomorDenda = $order->nomor_order . "_dendaLain";

        try {
            $params = array(
                'transaction_details' => array(
                    'order_id' => $nomorDenda,
                    'gross_amount' => abs($totalDenda),
                ),
                'expiry' => [
                    'start_time' => Carbon::now()->format('Y-m-d H:i:s T'), // waktu mulai
                    'unit' => 'minute', // satuan waktu
                    'duration' => 15 // durasi dalam satuan yang telah ditentukan
                ],
            );
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $order->snapToken = $snapToken;
            $order->save();

            return response()->json(['success' => true, 'snap' => $snapToken]);
        } catch (\Exception $e) {
            Log::error('Midtrans API error: ' . $e->getMessage());

            $redirectUrl = route('viewHistoryTelahKembali');

            // Check if the error message indicates a duplicate order_id
            if (strpos($e->getMessage(), 'transaction_details.order_id has already been taken') !== false) {
                $order->total_penghasilan += abs($totalDenda);
                $dendas = PengajuanDenda::where('nomor_order', $orderId)->where('status', 'diproses')->get();
                foreach ($dendas as $denda) {
                    $denda->status = "lunas";
                    $denda->sisa_denda = 0;
                    $denda->save();
                }
                $order->save();
                return response()->json(['success' => false, 'message' => 'terbayar', 'redirect_url' => $redirectUrl]);
                // return redirect()->route('viewHistorySedangBerlangsung')->render();
            } else {
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    public function createSnapTokenDendaRetur($orderId)
    {
        $order = OrderPenyewaan::where('nomor_order', $orderId)->first(); // Mengambil satu order berdasarkan nomor order
        $totalDenda = $order->jaminan;

        // Midtrans
        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $nomorDenda = $order->nomor_order . "_dendaOngkirOnly";

        try {
            $params = array(
                'transaction_details' => array(
                    'order_id' => $nomorDenda,
                    'gross_amount' => abs($totalDenda),
                ),
                'expiry' => [
                    'start_time' => Carbon::now()->format('Y-m-d H:i:s T'), // waktu mulai
                    'unit' => 'minute', // satuan waktu
                    'duration' => 15 // durasi dalam satuan yang telah ditentukan
                ],
            );
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $order->snapToken = $snapToken;
            $order->save();

            return response()->json(['success' => true, 'snap' => $snapToken]);
        } catch (\Exception $e) {
            Log::error('Midtrans API error: ' . $e->getMessage());

            $redirectUrl = route('viewDetailPemesanan', $orderId);
            if ($order->jaminan == 0) {
                return response()->json(['success' => false, 'message' => 'terbayar', 'redirect_url' => $redirectUrl]);
            }

            // Check if the error message indicates a duplicate order_id
            if (strpos($e->getMessage(), 'transaction_details.order_id has already been taken') !== false) {
                $order->total_penghasilan += abs($order->jaminan);
                $order->jaminan = 0;
                $order->save();
                return response()->json(['success' => false, 'message' => 'terbayar', 'redirect_url' => $redirectUrl]);
                // return redirect()->route('viewHistorySedangBerlangsung')->render();
            } else {
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    public function updatePenghasilan($orderId)
    {
        $order = OrderPenyewaan::where('nomor_order', $orderId)->first(); // Mengambil satu order berdasarkan nomor order

        $order->total_penghasilan += abs($order->jaminan);
        $order->jaminan = 0;
        $order->save();

        $redirectUrl = route('viewHistorySedangBerlangsung');

        return response()->json(['success' => true, 'message' => 'success', 'redirect_url' => $redirectUrl]);
    }

    public function updatePenghasilanDendaLain($orderId)
    {
        $order = OrderPenyewaan::where('nomor_order', $orderId)->first(); // Mengambil satu order berdasarkan nomor order
        $totalDenda = PengajuanDenda::where('nomor_order', $orderId)->where('status', 'diproses')->sum('sisa_denda');

        $order->total_penghasilan += abs($totalDenda);
        $dendas = PengajuanDenda::where('nomor_order', $orderId)->where('status', 'diproses')->get();
        foreach ($dendas as $denda) {
            $denda->status = "lunas";
            $denda->sisa_denda = 0;
            $denda->save();
        }
        $order->save();

        $redirectUrl = route('viewHistoryTelahKembali');

        return response()->json(['success' => true, 'message' => 'success', 'redirect_url' => $redirectUrl]);
    }

    public function updatePenghasilanDendaRetur($orderId)
    {
        $redirectUrl = route('viewDetailPemesanan', $orderId);

        try {
            $order = OrderPenyewaan::where('nomor_order', $orderId)->first(); // Mengambil satu order berdasarkan nomor order

            $order->total_penghasilan += abs($order->jaminan);
            $order->jaminan = 0;
            $order->save();


            return response()->json(['success' => true, 'message' => 'success', 'redirect_url' => $redirectUrl]);

        } catch (\Exception $e) {

            return response()->json(['success' => false, 'message' => $e->getMessage(), 'redirect_url' => $redirectUrl]);

        }

    }

    public function returBarang(Request $request, $orderId)
    {
        $order = OrderPenyewaan::findOrFail($orderId);

        if ($order->jaminan < 0) {
            return redirect()->route('viewHistorySedangBerlangsung')->with('error', 'Anda memiliki denda, silahkan bayarkan terlebih dahulu');
        }

        $validator = Validator::make($request->all(), [
            'bukti_resi_penyewa' => 'required|max:5120',
            'nomor_resi' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'ulasankostum' => 'required|string',
            'dokumentasi_kostum.*' => 'nullable|file|mimes:jpg,png,jpeg',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->with('error');
        }

        // Save Retur
        $photoPath = $request->file('bukti_resi_penyewa')->store('public/bukti_foto');
        $photoPath = Str::replaceFirst('public/', 'storage/', $photoPath);

        $order->nomor_resi = $request->nomor_resi;
        $order->tanggal_pengembalian = today();
        $order->bukti_resi_pengembalian = $photoPath;
        $order->status = "Telah Kembali";
        $order->save();

        // Save review
        $uploadedImages = [];
        if ($request->hasFile('dokumentasi_kostum')) {
            foreach ($request->file('dokumentasi_kostum') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $dokumentasiPath = $file->storeAs('public/review_foto', $filename);
                $dokumentasiPath = Str::replaceFirst('public/', 'storage/', $dokumentasiPath);
                $uploadedImages[] = $dokumentasiPath;
            }
        } else {
            return redirect()->back()->with('error', 'Terjadi Error dengan foto');
        }

        $review = new Review();
        $review->id_penyewa = $order->id_penyewa;
        $review->id_toko = $order->id_toko;
        $review->id_produk = $order->id_produk;
        $review->komentar = $request->ulasankostum;
        $review->nilai = $request->rating;
        $review->foto = json_encode($uploadedImages); // Mengonversi array path menjadi JSON
        $review->tipe = 'review_produk';

        // if ($request->hasFile('foto_ulasan')) {
        //     $file = $request->file('foto_ulasan');
        //     $filename = time() . '_' . $file->getClientOriginalName();
        //     $photoUlasanPath = $file->storeAs('public/review_foto', $filename);
        //     $photoUlasanPath = Str::replaceFirst('public/', 'storage/', $photoUlasanPath);

        //     // Simpan file path sebagai JSON di kolom foto_ulasan
        //     $review->foto = json_encode([$photoUlasanPath]);
        // } else {
        //     return redirect()->back()->with('error', 'Foto review tidak ditemukan');
        // }

        $review->save();

        return redirect()->route('viewHistoryTelahKembali')->with('success', 'Nomor Resi dan Review Berhasil di Update');
    }

    public function viewPenyewaanSelesai($orderId)
    {
        $order = OrderPenyewaan::where('nomor_order', $orderId)->first(); // Mengambil satu order berdasarkan nomor order
        $dendas = PengajuanDenda::where('nomor_order', $orderId)->where('status', 'diproses')->sum('sisa_denda');
        $dendalunas = PengajuanDenda::where('nomor_order', $orderId)->whereIn('status', ['lunas', 'diproses'])->sum('nominal_denda');

        if ($order) {
            // Ambil nama produk dan foto produk
            $produk = Produk::findOrFail($order->id_produk);
            $user = User::findOrFail($order->id_penyewa);
            $order->harga_katalog = $produk->harga;
            $order->nama_produk = $produk->nama_produk; // Tambahkan kolom nama_produk ke dalam objek order
            $order->foto_produk = $produk->fotoProduk->path; // Tambahkan kolom foto_produk ke dalam objek order
            $order->nama_penyewa = $user->nama;
            $uangKembali = $order->jaminan + $order->ongkir_default;

            // Pastikan additional adalah array
            if (!is_array($order->additional)) {
                $order->additional = json_decode($order->additional, true);
            }
        }

        if ($order->jaminan < 0) {
            $uangKembali = $order->ongkir_default;
        } else {
            $uangKembali = $order->jaminan + $order->ongkir_default;
        }

        $order->total_tagihan = $order->total_harga + $order->biaya_cuci + $order->fee_admin + 50000;

        return view('penyewa.transaksi.detailSelesai', compact('order', 'uangKembali', 'dendas', 'dendalunas'));
    }

    public function viewDibatalkanPemilikSewa($orderId)
    {
        $order = OrderPenyewaan::where('nomor_order', $orderId)->first(); // Mengambil satu order berdasarkan nomor order
        if ($order) {
            // Ambil nama produk dan foto produk
            $produk = Produk::findOrFail($order->id_produk);
            $user = User::findOrFail($order->id_penyewa);
            $order->harga_katalog = $produk->harga;
            $order->nama_produk = $produk->nama_produk; // Tambahkan kolom nama_produk ke dalam objek order
            $order->foto_produk = $produk->fotoProduk->path; // Tambahkan kolom foto_produk ke dalam objek order
            $order->nama_penyewa = $user->nama;
            $uangKembali = $order->jaminan + $order->ongkir_default;

            // Pastikan additional adalah array
            if (!is_array($order->additional)) {
                $order->additional = json_decode($order->additional, true);
            }
        }

        if ($order->status == "Pending") {
            return redirect()->route('viewCheckout');
        }

        return view('penyewa.transaksi.dibatalkanPemilikSewa', compact('order', 'uangKembali'));
    }

    public function ajukanRefund($orderId)
    {
        $order = OrderPenyewaan::findOrFail($orderId);
        // Save Retur
        $order->status = "Retur";
        $order->save();

        return redirect()->route('viewHistoryDiretur')->with('success', 'Berhasil mengajukan Retur');
    }

    public function returBarangRefund(Request $request, $orderId)
    {
        $order = OrderPenyewaan::findOrFail($orderId);

        $validator = Validator::make($request->all(), [
            'bukti_resi_penyewa' => 'required|max:5120',
            'nomor_resi' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->with('error');
        }

        // Save Retur
        $photoPath = $request->file('bukti_resi_penyewa')->store('public/bukti_foto');
        $photoPath = Str::replaceFirst('public/', 'storage/', $photoPath);
        $order->nomor_resi = $request->nomor_resi;
        $order->tanggal_pengembalian = today();
        $order->bukti_resi_pengembalian = $photoPath;
        $order->status = "Retur Dalam Pengiriman";
        $order->save();

        return redirect()->route('viewHistoryDiretur')->with('success', 'Nomor Resi Pengembalian Berhasil di Update');
    }



}