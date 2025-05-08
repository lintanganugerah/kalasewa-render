<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\OrderPenyewaan;
use App\Models\PengajuanDenda;
use App\Models\PeraturanSewa;
use App\Models\Tiket;
use App\Models\Produk;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PengajuanDendaController extends Controller
{
    public function viewPengajuanDenda($nomor_order)
    {
        $produk = Produk::where('id_toko', auth()->user()->toko->id)->withTrashed()->pluck('id');
        $order = OrderPenyewaan::where('nomor_order', $nomor_order)->whereIn('id_produk', $produk)->first();
        if (!$order) {
            return redirect()->route('seller.statuspenyewaan.telahkembali')->with('error', 'Tidak menemukan order');
        }
        return view('pemilikSewa.iterasi3.denda.pengajuanDenda', compact('order'));
    }

    public function viewDetailPengajuanDenda($nomor_order)
    {
        $produk = Produk::where('id_toko', auth()->user()->toko->id)->withTrashed()->pluck('id');
        $order = OrderPenyewaan::where('nomor_order', $nomor_order)->whereIn('id_produk', $produk)->first();
        $list_denda = PengajuanDenda::where('nomor_order', $nomor_order)->get();
        if (is_null($order) || is_null($list_denda)) {
            return redirect()->route('seller.statuspenyewaan.telahkembali')->with('error', 'Tidak menemukan order / Order tidak memiliki denda');
        }
        return view('pemilikSewa.iterasi3.denda.detailPengajuanDenda', compact('order', 'list_denda'));
    }

    public function pengajuanDendaAction(Request $request, $nomor_order)
    {
        $produk = Produk::where('id_toko', auth()->user()->toko->id)->withTrashed()->pluck('id');
        $order = OrderPenyewaan::where('nomor_order', $nomor_order)->whereIn('id_produk', $produk)->first();
        if (!$order) {
            return redirect()->route('seller.statuspenyewaan.telahkembali')->with('error', 'Tidak menemukan order');
        }
        $validator = Validator::make($request->all(), [
            'peraturan' => 'required|exists:peraturan_sewa_toko,id',
            'nominal_denda' => 'required|numeric',
            'penjelasan' => 'required',
            'bukti' => 'required',
            'bukti.*' => 'required|file|mimes:jpg,jpeg,png,webp|max:5120'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $peraturan = PeraturanSewa::find($request->peraturan);
        $denda = $peraturan->denda_pasti ?? $request->nominal_denda;
        $denda = (int) str_replace('.', '', $denda);

        if (is_null($peraturan) || $peraturan->id_toko != $order->id_produk_order->toko->id) {
            return redirect()->route('seller.statuspenyewaan.telahkembali')->with('error', 'Peraturan tidak ada di toko anda');
        } else if ($peraturan->nama == "Terlambat Mengembalikan Kostum") {
            return redirect()->route('seller.statuspenyewaan.telahkembali')->with('error', 'Denda Keterlambatan Telah dihitung secara otomatis. Anda tidak dapat mengajukan denda ini');
        }

        $data_bukti = request()->file('bukti');
        $foto_bukti = [];

        foreach ($data_bukti as $bukti) {
            $photoPath = $bukti->store('public/ticket');
            $photoPath = Str::replaceFirst('public/', 'storage/', $photoPath);
            $foto_bukti[] = $photoPath;
        }

        $pengajuan = PengajuanDenda::create([
            'id_penyewa' => $order->id_penyewa,
            'id_toko' => $order->id_produk_order->toko->id,
            'id_peraturan' => $peraturan->id,
            'nomor_order' => $order->nomor_order,
            'status' => "pending",
            'nominal_denda' => $denda,
            'sisa_denda' => $denda,
            'penjelasan' => $request->penjelasan,
            'foto_bukti' => $foto_bukti,
        ]);

        return redirect()->route('seller.statuspenyewaan.telahkembali')->with('success', 'Denda berhasil diajukan untuk produk dengan nomor order' . $nomor_order);
    }

    public function batalkanPengajuanDenda(Request $request, $nomor_order, $id)
    {
        $produk = Produk::where('id_toko', auth()->user()->toko->id)->withTrashed()->pluck('id');
        $order = OrderPenyewaan::where('nomor_order', $nomor_order)->whereIn('id_produk', $produk)->first();
        if (!$order) {
            return redirect()->route('seller.viewDetailPengajuanDenda', $nomor_order)->with('error', 'Tidak menemukan order');
        }

        $denda = PengajuanDenda::find($id);

        if (is_null($denda)) {
            return redirect()->route('seller.viewDetailPengajuanDenda', $nomor_order)->with('error', 'Pengajuan Denda tidak ada');
        }

        $peraturan = PeraturanSewa::find($denda->id_peraturan);

        if (is_null($peraturan) || $peraturan->id_toko != auth()->user()->toko->id || $denda->id_toko != auth()->user()->toko->id) {
            return redirect()->route('seller.viewDetailPengajuanDenda', $nomor_order)->with('error', 'Peraturan tidak ada di toko anda');
        }

        $denda->status = 'dibatalkan';
        $denda->save();

        return redirect()->route('seller.viewDetailPengajuanDenda', $nomor_order)->with('success', 'Pengajuan denda dibatalkan');
    }
}