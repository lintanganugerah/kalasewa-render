<?php

namespace App\Http\Controllers;

use App\Models\OrderPenyewaan;
use App\Models\PenarikanSaldo;
use App\Models\PengajuanDenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FundsController extends Controller
{
    public function index(Request $request)
    {
        $refunds = PenarikanSaldo::where('status', 'Menunggu Konfirmasi')
            ->orwhere('status', 'Sedang Diproses')
            ->get();

        $refundsHistory = PenarikanSaldo::where('status', 'Selesai')
            ->orWhere('status', 'Ditolak')
            ->paginate(5, ['*'], 'refund_page');

        return view('admin.refunds.index', compact('refunds', 'refundsHistory'));
    }

    public function process(Request $request)
    {
        $refund = PenarikanSaldo::findOrFail($request->input('refund_id'));
        $refund->status = 'Sedang Diproses';
        $refund->save();

        return redirect()->route('admin.refunds.index')->with('success', 'Status refund berhasil diperbarui menjadi Sedang Diproses.');
    }

    public function show($id)
    {
        $refund = PenarikanSaldo::find($id);

        if (!$refund) {
            return redirect()->route('admin.refunds.index')->with('error', 'Pengembalian dana tidak ditemukan.');
        }

        return view('admin.refunds.show', [
            'refund' => $refund,
        ]);
    }

    public function transfer(Request $request, $id)
    {
        // Validasi file upload
        $validator = Validator::make($request->all(), [
            'bukti_transfer' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Handle file upload
        $file = $request->file('bukti_transfer');
        $filename = time() . '_' . $file->getClientOriginalName();
        $dokumentasiPath = $file->storeAs('public/bukti_tiket', $filename);
        $dokumentasiPath = Str::replaceFirst('public/', 'storage/', $dokumentasiPath);

        // Find the refund entry
        $refund = PenarikanSaldo::find($id);

        if (!$refund) {
            return redirect()->route('admin.refunds.index')->with('error', 'Pengembalian dana tidak ditemukan.');
        }

        // Update refund status and save
        $refund->status = 'Selesai';
        $refund->bukti_transfer = $dokumentasiPath; // Directly assign array
        $refund->save();

        return redirect()->route('admin.refunds.show', $refund->id)->with('success', 'Transfer telah berhasil dikirim.');
    }

    public function reject(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:penarikan_saldo,id',
            'alasan_penolakan' => 'required|string',
        ]);


        $refund = PenarikanSaldo::find($validated['id']);
        $refund->status = 'ditolak';
        $refund->alasan_penolakan = $validated['alasan_penolakan'];

        $saldoUser = $refund->user->saldo_user;
        $saldoUser->saldo += $refund->nominal;
        $saldoUser->save();


        $refund->save();

        return redirect()->back()->with('success', 'Refund telah ditolak.');
    }

    // Penalty / Denda
    public function indexPenalty(Request $request)
    {
        $penaltys = PengajuanDenda::where('status', 'pending')->get();

        return view('admin.penalty.index', compact('penaltys'));
    }

    public function showPenalty($id)
    {
        $penalty = PengajuanDenda::find($id);

        if (!$penalty) {
            return redirect()->route('admin.penalty.index')->with('error', 'Pengajuan Denda tidak ditemukan.');
        }

        return view('admin.penalty.show', [
            'penalty' => $penalty,
        ]);
    }

    public function confirmPenalty(Request $request, $id)
    {
        $penalty = PengajuanDenda::findOrFail($id);
        $order = OrderPenyewaan::where('nomor_order', $penalty->nomor_order)->first();
        $penalty->status = 'diproses';

        $sisa_jaminan = $order->jaminan - $penalty->nominal_denda;

        if ($sisa_jaminan < 0) {
            $penalty->sisa_denda = abs($sisa_jaminan);
            if ($order->jaminan > 0) {
                $order->total_penghasilan += $order->jaminan; //Tambahin total penghasilan dengan denda yang sudah lunas di cover jaminan
            }
            $order->jaminan = 0;

            //Kalo jaminan udah ga ada atau 0, maka tidak ada denda yang lunas, jadinya tidak ditambahkan ke total_penghasilan toko
        } else if ($sisa_jaminan >= 0) {
            $penalty->sisa_denda = 0;
            $order->jaminan = $sisa_jaminan;
            $order->total_penghasilan += $penalty->nominal_denda; //Karena sisa jaminan >= 0 maka dari itu lunas, dan tambahin ke penghasilan pemilik
            $penalty->status = 'lunas';
        }

        $order->save();
        $penalty->save();

        return redirect()->route('admin.penalty.index')->with('success', 'Denda berhasil dikonfirmasi.');
    }

    public function hitnrunPenalty(Request $request, $id)
    {
        $penalty = PengajuanDenda::findOrFail($id);
        $penalty->status = 'penyewa_kabur';
        $penalty->save();

        return redirect()->route('admin.penalty.index')->with('success', 'Pemilik Sewa telah diinfokan.');
    }

    public function rejectPenalty(Request $request, $id)
    {
        $validated = $request->validate([
            'alasan_penolakan' => 'required|string',
        ]);

        $penalty = PengajuanDenda::findOrFail($id);
        $penalty->status = 'ditolak';
        $penalty->alasan_penolakan = $validated['alasan_penolakan'];
        $penalty->save();

        return redirect()->route('admin.penalty.index')->with('success', 'Pengajuan Denda telah ditolak.');
    }
}