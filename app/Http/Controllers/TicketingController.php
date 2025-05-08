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
use App\Models\Tiket;
use App\Models\KategoriTiket;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

class TicketingController extends Controller
{
    //
    public function viewTicketing()
    {
        $ticketing = Tiket::where('user_id', Auth()->user()->id)->get();

        return view('penyewa.ticketing.ticketing', compact('ticketing'));
    }

    //
    public function viewNewTicketing()
    {
        $jenistiket = KategoriTiket::all();
        return view('penyewa.ticketing.newTicketing', compact('jenistiket'));
    }

    public function createTicket(Request $request)
    {
        // Validasi data
        $validator = Validator::make($request->all(), [
            'jenis_ticketing' => 'required|string',
            'deskripsi_ticketing' => 'required|string',
            'bukti_tiket.*' => 'nullable|file|mimes:jpg,png,jpeg|max:5120',
        ]);

        // Jika validasi gagal, kembali dengan pesan kesalahan
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $uploadedImages = [];
        if ($request->hasFile('bukti_tiket')) {
            foreach ($request->file('bukti_tiket') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $dokumentasiPath = $file->storeAs('public/bukti_tiket', $filename);
                $dokumentasiPath = Str::replaceFirst('public/', 'storage/', $dokumentasiPath);
                $uploadedImages[] = $dokumentasiPath;
            }
        } else {
            return redirect()->back()->with('error', 'Terjadi Error dengan foto');
        }

        $ticketing = new Tiket();
        $ticketing->kategori_tiket_id = $request->jenis_ticketing;
        $ticketing->deskripsi = $request->deskripsi_ticketing;
        $ticketing->status = "Menunggu Konfirmasi";
        $ticketing->user_id = Auth()->User()->id;
        $ticketing->bukti_tiket = ($uploadedImages);
        $ticketing->save();

        return redirect()->route('viewTicketing')->with('success', 'Berhasil Mengajukan Ticketing');
    }

    // admin
    public function index()
    {
        $tickets = Tiket::with(['user', 'kategori' => function($query) {
            $query->withTrashed(); // Include categories that are soft deleted
            }])->whereIn('status', ['Menunggu Konfirmasi', 'Sedang Diproses'])->get();

        $completedOrRejectedTickets = Tiket::with(['user', 'kategori' => function($query) {
            $query->withTrashed(); }])
            ->whereIn('status', ['Selesai', 'Ditolak'])
            ->get();

        return view('admin.ticket.index', compact('tickets', 'completedOrRejectedTickets'));
    }

    public function show($id)
    {
        $ticket = Tiket::with(['user', 'kategori' => function($query) {
            $query->withTrashed(); }])->findOrFail($id);
        return view('admin.ticket.show', compact('ticket'));
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:255',
        ]);

        $ticket = Tiket::findOrFail($id);
        $ticket->status = 'Ditolak';
        $ticket->alasan_penolakan = $request->alasan_penolakan;
        $ticket->save();

        return redirect()->route('admin.ticket.index')->with('success', 'Ticket telah ditolak.');
    }

    public function process($id)
    {
        $ticket = Tiket::findOrFail($id);
        $ticket->status = 'Sedang Diproses';
        $ticket->save();

        return redirect()->route('admin.ticket.index')->with('success', 'Ticket sedang diproses.');
    }

    public function complete($id)
    {
        $ticket = Tiket::findOrFail($id);
        $ticket->status = 'Selesai';
        $ticket->save();

        return redirect()->route('admin.ticket.index')->with('success', 'Tiket berhasil diselesaikan.');
    }

    // Kategori Tiket
    public function showTicketCategory()
    {
        $categories = KategoriTiket::all();
        return view('admin.ticket.category', compact('categories'));
    }

    public function storeTicketCategory(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $cekTiket = KategoriTiket::where('nama', $request->nama)->first();
        
        if ($cekTiket) {
            return redirect()->route('admin.ticket.category')->withErrors(['error' => 'Kategori tiket sudah ada.'])->withInput();
        }
        
        KategoriTiket::create([
            'nama' => $request->nama,
        ]);

        return redirect()->route('admin.ticket.category')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function editTicketCategory($id)
    {
        $category = KategoriTiket::findOrFail($id);
        return view('admin.ticket.editCategory', compact('category'));
    }

    public function updateTicketCategory(Request $request, $id)
    {
        $category = KategoriTiket::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255|unique:kategori_tiket,nama,' . $category->id,
        ]);
        $category->update([
            'nama' => $request->nama,
        ]);

        return redirect()->route('admin.ticket.category')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroyTicketCategory($id)
    {
        $category = KategoriTiket::find($id);
        if ($category) {
            $category->delete();
            return redirect()->route('admin.ticket.category')->with('success', 'Kategori berhasil dihapus.');
        }
        return redirect()->route('admin.ticket.category')->withErrors('Kategori tidak ditemukan.');
    }

    // Pengajuan Retur

    public function indexRetur()
    {
        $returs = OrderPenyewaan::with(['penyewa', 'produk'])
            ->where('status', 'Retur')
            ->get();

        return view('admin.retur.index', compact('returs'));
    }

    public function showRetur($nomor_order)
    {
        $retur = OrderPenyewaan::with(['penyewa', 'produk'])->where('nomor_order', $nomor_order)->firstOrFail();
        return view('admin.retur.show', compact('retur'));
    }

    public function completeRetur($id)
    {
        $retur = OrderPenyewaan::findOrFail($id);
        $retur->status = 'Retur Dikonfirmasi';
        $retur->save();

        return redirect()->route('admin.retur.index')->with('success', 'Retur telah dikonfirmasi.');
    }

    public function rejectRetur(Request $request, $id)
    {
        $retur = OrderPenyewaan::findOrFail($id);
        $retur->status = 'Sedang Berlangsung';
        $retur->tanggal_diterima = Carbon::now()->startOfDay();
        $retur->save();

        return redirect()->route('admin.retur.index')->with('success', 'Retur telah ditolak.');
    }
}