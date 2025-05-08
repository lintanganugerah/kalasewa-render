<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\SaldoUser;
use App\Models\TujuanRekening;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class RekeningController extends Controller
{
    public function viewSetRekening()
    {
        $rekening = SaldoUser::where('id_user', auth()->user()->id)->first();
        $list_bank = TujuanRekening::all();
        return view('pemilikSewa.iterasi3.keuangan.setRekening', compact('rekening', 'list_bank'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomor_rekening' => 'required|numeric|min_digits:10',
            'nama_rekening' => 'required|string',
            'tujuan_rek' => 'required|exists:tujuan_rekening,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        SaldoUser::updateOrCreate(
            ['id_user' => Auth::id()],
            $data
        );
        return redirect()->route('seller.keuangan.dashboardKeuangan')->with('success', 'Rekening berhasil di set');
    }
}