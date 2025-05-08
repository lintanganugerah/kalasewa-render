<?php

namespace App\Http\Controllers;

use App\Models\PenarikanSaldo;
use App\Models\User;
use App\Models\Tiket;
use App\Models\Review;
use App\Models\TopSeries;
use App\Models\OrderPenyewaan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Events\UserChangeProfile;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class UserController extends Controller
{
    public function search(Request $request)
    {
        $usersQuery = User::query()->where(function ($query) {
            $query->where('role', 'penyewa')
                ->orWhere('role', 'pemilik_sewa');
        });

        if ($request->has('search')) {
            $searchQuery = $request->search;
            $usersQuery->where('nama', 'LIKE', "%" . $searchQuery . '%');
        }

        $users = $usersQuery->paginate(10);

        $adminUsers = User::where('role', 'admin')
            ->orWhere('role', 'super_admin')
            ->paginate(5, ['*'], 'admin_page');

        return view('admin.users.index', compact('users', 'adminUsers'));
    }

    public function userCount()
    {
        $totalPenyewaTerdaftar = User::where('role', 'penyewa')->where('verifyIdentitas', 'Sudah')->count();
        $totalPemilikSewaTerdaftar = User::where('role', 'pemilik_sewa')->where('verifyIdentitas', 'Sudah')->count();
        $totalPendingVerifikasi = User::where('role', '<>', 'admin')->where('verifyIdentitas', 'Tidak')->count();
        $totalPendingTicket = Tiket::where('status', 'Menunggu Konfirmasi')->count();
        $totalPendingRetur = OrderPenyewaan::where('status', 'Retur')->count();

        $totalPendingFundPenyewa = PenarikanSaldo::join('users', 'penarikan_saldo.id_user', '=', 'users.id')
            ->where('penarikan_saldo.status', 'Menunggu Konfirmasi')
            ->where('users.role', 'penyewa')
            ->count();

        $totalPendingFundPemilik = PenarikanSaldo::join('users', 'penarikan_saldo.id_user', '=', 'users.id')
            ->where('penarikan_saldo.status', 'Menunggu Konfirmasi')
            ->where('users.role', 'pemilik_sewa')
            ->count();

        $totalPendapatan = DB::table('order_penyewaan')
            ->whereIn('status', ['Retur Selesai', 'Penyewaan Selesai', 'Dibatalkan Pemilik Sewa', 'Dibatalkan Penyewa'])
            ->sum('fee_admin');

        $topSeries = TopSeries::with('series')->whereMonth('created_at', Carbon::now()->translatedFormat('m'))
            ->orderBy('banyak_dipesan', 'desc')
            ->take(3)
            ->get();

        return view('admin.dashboard', [
            'totalPenyewaTerdaftar' => $totalPenyewaTerdaftar,
            'totalPemilikSewaTerdaftar' => $totalPemilikSewaTerdaftar,
            'totalPendingVerifikasi' => $totalPendingVerifikasi,
            'totalPendapatan' => $totalPendapatan,
            'totalPendingTicket' => $totalPendingTicket,
            'totalPendingFundPenyewa' => $totalPendingFundPenyewa,
            'totalPendingFundPemilik' => $totalPendingFundPemilik,
            'totalPendingRetur' => $totalPendingRetur,
            'topSeries' => $topSeries,
        ]);
    }
    public function index(Request $request)
    {
        $users = User::where('role', 'penyewa')
            ->orWhere('role', 'pemilik_sewa')
            ->paginate(5, ['*'], 'users_page');

        $adminUsers = User::where('role', 'admin')
            ->orWhere('role', 'super_admin')
            ->paginate(5, ['*'], 'admin_page');

        return view('admin.users.index', compact('users', 'adminUsers'));
    }

    public function index_verify()
    {
        $users = User::where('verifyIdentitas', 'Tidak')->get();
        return view('admin.verify.index', compact('users'));
    }

    public function updateVerification(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($request->input('action') === 'verify') {
            $user->verifyIdentitas = 'Sudah';
            $user->save();

            Mail::send('emails.verified', ['user' => $user], function ($message) use ($user) {
                $message->to($user->email);
                $message->subject('Yeay! Akun Kamu Sudah Terverifikasi! 🎉');
            });

            return redirect()->route('admin.verify.index')->with('success', 'Status verifikasi pengguna berhasil diperbarui.');

        } elseif ($request->input('action') === 'reject') {
            $request->validate([
                'reason' => 'required|string',
            ]);

            $user->verifyIdentitas = 'Ditolak';
            $user->nik = null;
            $user->no_telp = null;
            $user->no_darurat = null;
            $user->ket_no_darurat = null;
            $user->alamat = null;
            $user->provinsi = null;
            $user->link_sosial_media = null;
            $user->kota = null;
            $user->kode_pos = null;

            // Hapus foto dari storage
            $path = $user->foto_diri;
            $path2 = $user->foto_identitas;
            Storage::delete(str_replace('storage/', 'public/', $path));
            Storage::delete(str_replace('storage/', 'public/', $path2));

            $user->foto_diri = null;
            $user->foto_identitas = null;
            $user->save();

            // Simpan alasan penolakan ke dalam tabel alasan_penolakan
            DB::table('alasan_penolakan')->insert([
                'id_user' => $user->id,
                'penolakan' => 'verify',
                'alasan_penolakan' => $request->reason,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Kirim email ke user
            Mail::send('emails.rejection', ['reason' => $request->reason, 'user' => $user], function ($message) use ($user) {
                $message->to($user->email);
                $message->subject('Oops! Verifikasi Akun Kamu Gagal 🥺');
            });

            return redirect()->route('admin.verify.index')->with('success', 'Status verifikasi pengguna dan alasan penolakan berhasil diperbarui, dan email telah dikirim.');
        }

        return redirect()->route('admin.verify.index')->with('error', 'Tindakan tidak dikenal.');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        if ($user->verifyIdentitas !== 'Tidak') {
            return redirect()->route('admin.verify.index')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        return view('admin.verify.detail', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'string|max:255',
            'password' => 'string|min:8',
            'email' => 'string|email|max:255|unique:users,email,' . $id,
            'no_telp' => 'nullable|string|max:15',
        ]);

        $user = User::findOrFail($id);

        $data = $request->all();

        $user->update($data);

        event(new UserChangeProfile($user)); //Trigger event untuk mengubah kolom name, dan avatar chatify sesuai dengan data user

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }


    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255|unique:users,nama',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).+$/',
            ],
            'no_telp' => 'nullable|string|max:15',
            'verifyIdentitas' => 'required|in:Sudah,Tidak,Ditolak',
        ], [
            'password.regex' => 'Field Password harus setidaknya lebih dari 8 karakter, mengandung huruf besar, huruf kecil, angka, dan karakter khusus.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        $data['verifyIdentitas'] = 'Sudah';
        $data['role'] = 'admin';
        $data['password'] = Hash::make($request->password);

        $user = User::create($data);
        $user->save();
        event(new UserChangeProfile($user)); //Trigger event untuk mengubah kolom name, dan avatar chatify sesuai dengan data user

        return redirect()->route('admin.users.index')->with('success', 'Admin berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }

    public function nonaktifkanUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->badge !== 'Banned') {
            $request->validate([
                'reason' => 'required|string',
            ]);

            // Simpan alasan penolakan ke dalam tabel alasan_penolakan
            DB::table('alasan_penolakan')->insert([
                'id_user' => $user->id,
                'penolakan' => 'ban_account',
                'alasan_penolakan' => $request->reason,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $user->badge = 'Banned';

            Mail::send('emails.banned', ['reason' => $request->reason, 'user' => $user], function ($message) use ($user) {
                $message->to($user->email);
                $message->subject('Akun kamu telah diblokir! ⛔');
            });

        } else {
            $user->badge = 'Aktif';

            Mail::send('emails.reactivated', ['user' => $user], function ($message) use ($user) {
                $message->to($user->email);
                $message->subject('Yeay! Akun Kamu Telah Diaktifkan Kembali 🎉');
            });
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Status pengguna berhasil diperbarui.');
    }

}