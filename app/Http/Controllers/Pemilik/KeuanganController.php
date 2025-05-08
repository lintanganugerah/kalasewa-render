<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\OrderPenyewaan;
use App\Models\PengajuanDenda;
use App\Models\Toko;
use App\Models\PenarikanSaldo;
use App\Models\Produk;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Barryvdh\DomPDF\Facade\Pdf;

class KeuanganController extends Controller
{
    public function dashboardKeuangan()
    {
        $toko = Toko::where('id_user', Auth::id())->first();
        $produkId = Produk::where('id_toko', $toko->id)->withTrashed()->pluck('id');
        $order = OrderPenyewaan::whereIn('id_produk', $produkId)->get();
        foreach ($order as $ord) {
            if ($ord->additional) {
                foreach ($ord->additional as $add) {
                    $ord->totalAdditional += (int) $add['harga'];
                }
            } else {
                $ord->totalAdditional = 0;
            }
            $dendaLainnyaNominal = PengajuanDenda::where('nomor_order', $ord->nomor_order)->whereIn('status', ['lunas', 'diproses', 'penyewa_kabur'])->sum('nominal_denda') ?? 0;
            $dendaLainnyaSisa = PengajuanDenda::where('nomor_order', $ord->nomor_order)->whereIn('status', ['lunas', 'diproses', 'penyewa_kabur'])->sum('sisa_denda') ?? 0;
            $dendaLainnya = $dendaLainnyaNominal - $dendaLainnyaSisa;
            $ord->totalHargaPenyewaan = $ord->total_harga;
            $ord->dendaBelumLunas = $ord->jaminan < 0 ? $ord->jaminan : 0;
            $ord->hargaPenyewaanDanDenda = $ord->totalHargaPenyewaan + ($ord->denda_keterlambatan ?? 0) + $dendaLainnya - abs($ord->dendaBelumLunas);
            $ord->denda_penyewa = $dendaLainnya;
        }
        $penghasilanBulan = auth()->user()->toko->penghasilan_bulan_ini();

        // Data penghasilan hari bulan tsb
        $startOfMonth = Carbon::today()->startOfMonth();
        $endOfMonth = Carbon::today()->endOfMonth();

        // Mengambil data penghasilan harian
        $dailyData = OrderPenyewaan::whereIn('status', ['Penyewaan Selesai'])->whereIn('id_produk', $produkId)->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->selectRaw('DATE(created_at) as date, SUM(total_penghasilan) as total_income, COUNT(*) as total_orders')
            ->groupBy('date')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    Carbon::parse($item->date)->format('m-d') => [
                        'total_income' => $item->total_income ?? 0,
                        'total_orders' => $item->total_orders ?? 0
                    ]
                ];
            });

        // Mengisi data hari-hari tanpa penghasilan dan order
        $daysInMonth = $endOfMonth->day;
        $allDays = collect(range(1, $daysInMonth))->mapWithKeys(function ($day) use ($startOfMonth) {
            $date = $startOfMonth->copy()->day($day)->format('m-d');
            return [$date => ['total_income' => 0, 'total_orders' => 0]];
        });

        // Gabungkan data yang ada dengan semua hari dalam bulan
        $dailyIncome = $allDays->merge($dailyData)->sortKeys();

        // dd($dailyIncome);

        $startMonth = Carbon::now()->startOfYear();
        $endMonth = Carbon::now()->endOfYear();

        $monthlyData = OrderPenyewaan::whereIn('status', ['Penyewaan Selesai'])->whereIn('id_produk', $produkId)->whereBetween('created_at', [$startMonth, $endMonth])->selectRaw('MONTH(created_at) as month, SUM(total_penghasilan) as total_income, COUNT(*) as total_orders')
            ->groupBy('month')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    Carbon::createFromFormat('m', $item->month)->translatedFormat('F') . ' ' . Carbon::now()->translatedFormat('Y') => [
                        'total_income' => $item->total_income ?? 0,
                        'total_orders' => $item->total_orders ?? 0
                    ]
                ];
            });

        // dd($monthlyData);

        $allMonth = collect(range(1, 12))->mapWithKeys(function ($month) use ($startMonth) {
            $monthDate = $startMonth->copy()->month($month)->translatedFormat('F');
            return [$monthDate . ' ' . Carbon::now()->translatedFormat('Y') => ['total_income' => 0, 'total_orders' => 0]];
        });


        // Gabungkan data yang ada dengan semua hari dalam bulan
        $monthlyIncome = $allMonth->merge($monthlyData);

        // Urutan bulan dalam bahasa Indonesia
        $months = collect([
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ])->map(fn($month) => "$month " . Carbon::now()->translatedFormat('Y'))->toArray();

        // Sort berdasarkan urutan bulan dalam array $months
        $monthlyIncome = $monthlyIncome->sortBy(function ($value, $key) use ($months) {
            return array_search($key, $months);
        });

        // Mengambil data penghasilan tahunan
        $yearlyIncome = OrderPenyewaan::whereIn('status', ['Penyewaan Selesai'])
            ->whereIn('id_produk', $produkId)
            ->selectRaw('YEAR(created_at) as year, SUM(total_penghasilan) as total_income, COUNT(*) as total_orders')
            ->groupBy('year')
            ->get()
            ->mapWithKeys(function ($item) {
                // Menggunakan tahun langsung dari query
                return [
                    $item->year => [
                        'total_income' => $item->total_income ?? 0,
                        'total_orders' => $item->total_orders ?? 0
                    ]
                ];
            });

        return view('pemilikSewa.iterasi3.keuangan.dashboardKeuangan', compact('order', 'toko', 'penghasilanBulan', 'monthlyIncome', 'dailyIncome', 'yearlyIncome'));
    }

    public function viewRiwayatPenarikan()
    {
        $data_penarikan = PenarikanSaldo::where('id_user', auth()->user()->id)->get();
        $penghasilanBulan = auth()->user()->toko->penghasilan_bulan_ini();
        return view('pemilikSewa.iterasi3.keuangan.viewRiwayatPenarikan', compact('data_penarikan', 'penghasilanBulan'));
    }

    public function getPenghasilanBulan($periode)
    {
        $produk = Produk::where('id_toko', auth()->user()->toko->id)->withTrashed()->pluck('id');
        $penghasilanBulan = OrderPenyewaan::whereIn('id_produk', $produk)->where('status', 'Penyewaan Selesai')->whereMonth('created_at', $periode + 1)->whereYear('created_at', Carbon::now()->translatedFormat('Y'))->sum('total_penghasilan') ?? 0;
        return view('pemilikSewa.iterasi3.keuangan.penghasilan', compact('penghasilanBulan'))->render();
    }

    public function export_pdf(Request $request)
    {
        $selectedYear = request()->input('selectedYear'); // Misalnya '2023' atau null jika all year
        $selectedMonth = request()->input('selectedMonth'); // Misalnya '01' atau null jika all month

        // Validasi input tahun
        if ((!is_null($selectedYear) && !ctype_digit((string) $selectedYear)) || (!is_null($selectedMonth) && !ctype_digit((string) $selectedMonth))) {
            return redirect()->back()->with('error', 'Tahun atau bulan tidak valid.');
        }

        $selectedYear = $selectedYear != null ? (int) $selectedYear : $selectedYear;
        $selectedMonth = $selectedMonth != null ? (int) $selectedMonth : $selectedMonth;

        // dd($selectedYear, $selectedMonth);

        // Ambil ID produk
        $produk = Produk::where('id_toko', auth()->user()->toko->id)->withTrashed()->pluck('id');

        // Tentukan rentang tanggal
        if ($selectedYear) {
            $startDate = Carbon::create($selectedYear, 1, 1);
            $endDate = Carbon::create($selectedYear, 12, 31)->endOfDay();
        } else {
            $startDate = Carbon::create(2000, 1, 1); // Awal waktu
            $endDate = Carbon::now()->endOfDay();   // Akhir waktu
        }

        // Jika bulan dipilih, sesuaikan rentang tanggal
        if ($selectedMonth) {
            if ($selectedYear) {
                // Jika tahun dipilih
                $startDate = Carbon::create($selectedYear, $selectedMonth, 1); // Awal bulan yang dipilih
                $endDate = Carbon::create($selectedYear, $selectedMonth, 1)->endOfMonth(); // Akhir bulan yang dipilih
            } else {
                // Jika tahun tidak dipilih, ambil bulan yang dipilih untuk semua tahun
                // `startDate` dan `endDate` sudah diatur untuk mencakup semua tahun
                $startDate = $startDate->month($selectedMonth)->startOfMonth();
                $endDate = $endDate->month($selectedMonth)->endOfMonth();
            }
        }

        // Ambil data sesuai dengan kondisi yang dipilih
        $data = OrderPenyewaan::whereIn('id_produk', $produk)
            ->where('status', 'Penyewaan Selesai')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        // Kelompokkan data sesuai dengan kebutuhan
        if (!$selectedYear && $selectedMonth) {
            $data = $data->filter(function ($item) use ($selectedMonth) {
                return Carbon::parse($item->created_at)->translatedFormat('m') == $selectedMonth;
            });
        }

        $data = $data->groupBy(function ($date) {
            return Carbon::parse($date->created_at)->translatedFormat('Y'); // Kelompokkan berdasarkan tahun
        })->map(function ($yearGroup) {
            return $yearGroup->groupBy(function ($date) {
                return Carbon::parse($date->created_at)->translatedFormat('F'); // Kelompokkan berdasarkan bulan
            });
        });

        if ($data->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data pada tahun dan bulan tersebut');
        }

        // Kalkulasi denda dan totalAdditional
        foreach ($data as $keyYear => $months) {
            foreach ($months as $keyMonth => $orders) {
                foreach ($orders as $ord) {
                    if ($ord->additional) {
                        $ord->totalAdditional = $ord->additional->sum('harga');
                    } else {
                        $ord->totalAdditional = 0;
                    }

                    // Kalkulasi denda
                    $dendaLainnyaNominal = PengajuanDenda::where('nomor_order', $ord->nomor_order)
                        ->whereIn('status', ['lunas', 'diproses', 'penyewa_kabur'])
                        ->sum('nominal_denda') ?? 0;

                    $dendaLainnyaSisa = PengajuanDenda::where('nomor_order', $ord->nomor_order)
                        ->whereIn('status', ['lunas', 'diproses', 'penyewa_kabur'])
                        ->sum('sisa_denda') ?? 0;

                    $ord->denda_penyewa = $dendaLainnyaNominal - $dendaLainnyaSisa;
                }
            }
        }

        $fileName = 'Laporan_' . auth()->user()->toko->nama_toko . '_' . ($selectedYear ? $selectedYear : 'Semua_tahun') .
            ($selectedMonth ? '_Bulan_' . Carbon::create()->month($selectedMonth)->translatedFormat('F') : '_Semua_Bulan') .
            '.pdf';

        $pdf = PDF::loadview('pemilikSewa.iterasi3.keuangan.laporan', ['data' => $data, 'selectedMonth' => $selectedMonth, 'selectedYear' => $selectedYear]);
        return $pdf->download($fileName);
    }
}