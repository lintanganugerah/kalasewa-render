<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Toko extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_toko',
        'isAlamatTambahan',
        'bio_toko',
        'id_user',
    ];

    protected $casts = [
        'isAlamatTambahan' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function peraturanSewaToko()
    {
        return $this->hasMany(PeraturanSewa::class, 'id_toko');
    }

    public function id_review_toko()
    {
        return $this->hasMany(Review::class, 'id_toko');
    }

    public function produks()
    {
        return $this->hasMany(Produk::class, 'id_toko');
    }

    public function produkReview()
    {
        return $this->hasMany(Review::class, 'id_toko');
    }

    public function alamatTambahan()
    {
        return $this->hasMany(AlamatTambahan::class, 'id_toko');
    }

    public function saldo_tertunda()
    {
        $produk = Produk::where('id_toko', $this->id)->get()->pluck('id')->toArray();
        $order = OrderPenyewaan::whereIn('id_produk', $produk)->whereNotIn('status', ['Penyewaan Selesai', 'Retur Selesai', 'Retur Dikonfirmasi', 'Retur dalam Pengiriman', 'Retur', 'Dibatalkan Penyewa', 'Dibatalkan Pemilik Sewa'])->sum('total_penghasilan') ?? 0;

        return $order;
    }

    public function penghasilan_bulan_ini()
    {
        $produk = Produk::where('id_toko', $this->id)->withTrashed()->pluck('id');
        $order = OrderPenyewaan::whereIn('id_produk', $produk)->where('status', 'Penyewaan Selesai')->whereMonth('created_at', Carbon::now()->translatedFormat('m'))->whereYear('created_at', Carbon::now()->translatedFormat('Y'))->sum('total_penghasilan') ?? 0;
        return $order;
    }

    public function pengajuan_denda()
    {
        return $this->hasMany(PengajuanDenda::class, 'id_toko');
    }

    public function countPenyewaan($status)
    {
        $produk = Produk::where('id_toko', $this->id)->get()->pluck('id')->toArray();
        $order = OrderPenyewaan::whereIn('id_produk', $produk)->where('status', $status)->count() ?? 0;

        return $order;
    }

    public function riwayatPenyewaan()
    {
        $produk = Produk::where('id_toko', $this->id)->get()->pluck('id')->toArray();
        $order = OrderPenyewaan::whereIn('id_produk', $produk)->whereIn('status', ['Penyewaan Selesai', 'Retur Selesai', 'Dibatalkan Penyewa', 'Dibatalkan Pemilik Sewa'])->count() ?? 0;

        return $order;
    }
    public function countPenyewaanAll()
    {
        $produk = Produk::where('id_toko', $this->id)->get()->pluck('id')->toArray();
        $order = OrderPenyewaan::whereIn('id_produk', $produk)->whereMonth('created_at', Carbon::now()->translatedFormat('m'))->count() ?? 0;
        $orderBulanLalu = OrderPenyewaan::whereIn('id_produk', $produk)->whereMonth('created_at', Carbon::now()->subMonth()->translatedFormat('m'))->count() ?? 0;

        if ($orderBulanLalu > 0) {
            $persentasePerubahan = (($order - $orderBulanLalu) / $orderBulanLalu) * 100;
            if ($persentasePerubahan > 0) {
                $status = "naik";
            } elseif ($persentasePerubahan < 0) {
                $status = "turun";
                $persentasePerubahan = abs($persentasePerubahan); // Mengubah nilai negatif menjadi positif untuk tampilan
            } else {
                $status = "tidak berubah";
            }
        } else {
            if ($order > 0) {
                $persentasePerubahan = 100; // Jika bulan lalu tidak ada pesanan dan bulan ini ada pesanan, anggap pertumbuhan 100%
                $status = "naik";
            } else {
                $persentasePerubahan = 0; // Jika tidak ada pesanan di kedua bulan, pertumbuhan adalah 0%
                $status = "tidak berubah";
            }
        }

        return [
            $order,
            $persentasePerubahan,
            $status,
            $orderBulanLalu
        ];
    }
    public function countPenyewaanAllBerstatus($status)
    {
        $produk = Produk::where('id_toko', $this->id)->get()->pluck('id')->toArray();
        if ($status == 'ReturAll') {
            $order = OrderPenyewaan::whereIn('id_produk', $produk)->whereIn('status', ['Retur dalam Pengiriman', 'Retur', 'Retur Dikonfirmasi', 'Retur Selesai'])->whereMonth('created_at', Carbon::now()->translatedFormat('m'))->count() ?? 0;
            $orderBulanLalu = OrderPenyewaan::whereIn('id_produk', $produk)->whereIn('status', ['Retur dalam Pengiriman', 'Retur', 'Retur Dikonfirmasi', 'Retur Selesai'])->whereMonth('created_at', Carbon::now()->subMonth()->translatedFormat('m'))->count() ?? 0;
        } else {
            $order = OrderPenyewaan::whereIn('id_produk', $produk)->where('status', $status)->whereMonth('created_at', Carbon::now()->translatedFormat('m'))->count() ?? 0;
            $orderBulanLalu = OrderPenyewaan::whereIn('id_produk', $produk)->where('status', $status)->whereMonth('created_at', Carbon::now()->subMonth()->translatedFormat('m'))->count() ?? 0;
        }


        if ($orderBulanLalu > 0) {
            $persentasePerubahan = (($order - $orderBulanLalu) / $orderBulanLalu) * 100;
            if ($persentasePerubahan > 0) {
                $status = "naik";
            } elseif ($persentasePerubahan < 0) {
                $status = "turun";
                $persentasePerubahan = abs($persentasePerubahan); // Mengubah nilai negatif menjadi positif untuk tampilan
            } else {
                $status = "tidak berubah";
            }
        } else {
            if ($order > 0) {
                $persentasePerubahan = 100; // Jika bulan lalu tidak ada pesanan dan bulan ini ada pesanan, anggap pertumbuhan 100%
                $status = "naik";
            } else {
                $persentasePerubahan = 0; // Jika tidak ada pesanan di kedua bulan, pertumbuhan adalah 0%
                $status = "tidak berubah";
            }
        }

        return [
            $order,
            $persentasePerubahan,
            $status,
            $orderBulanLalu
        ];
    }

    public function countPenyewaanAllBerstatusTabs($status)
    {
        $produk = Produk::where('id_toko', $this->id)->get()->pluck('id')->toArray();
        $order = OrderPenyewaan::whereIn('id_produk', $produk)->where('status', $status)->count() ?? 0;

        return $order;
    }
}