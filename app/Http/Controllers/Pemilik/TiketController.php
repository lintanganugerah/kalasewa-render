<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\KategoriTiket;
use App\Models\Tiket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TiketController extends Controller
{
    public function index()
    {
        $items = Tiket::where('user_id', Auth::id())->orderByDesc('updated_at')->get();
        return view('pemilikSewa.iterasi3.tiket.ticketing', compact('items'));
    }

    public function createTicketing()
    {
        $data_kategori = KategoriTiket::all();
        return view('pemilikSewa.iterasi3.tiket.createTicketing', compact('data_kategori'));
    }

    public function storeTicketingAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kategori_tiket_id' => 'required|exists:kategori_tiket,id',
            'deskripsi' => 'required',
            'bukti' => 'required',
            'bukti.*' => 'required|file|mimes:jpg,jpeg,png,webp|max:20480'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data_bukti = request()->file('bukti');
        $foto_bukti = [];

        foreach ($data_bukti as $bukti) {
            $photoPath = $bukti->store('public/ticket');
            $photoPath = Str::replaceFirst('public/', 'storage/', $photoPath);
            $foto_bukti[] = $photoPath;
        }

        $tiket = Tiket::create([
            'user_id' => Auth::id(),
            'status' => "Menunggu Konfirmasi",
            'kategori_tiket_id' => $request->kategori_tiket_id,
            'deskripsi' => $request->deskripsi,
            'bukti_tiket' => $foto_bukti,
        ]);


        return redirect()->route('seller.tiket.index')->with('Ticket berhasil dibuat.');
    }
}