<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPenyewaan extends Model
{
    use HasFactory;

    protected $table = 'order_penyewaan';

    protected $primaryKey = 'nomor_order'; //Karena kita di skema tidak memakai id() jadi kita tetapkan ini adalah primary

    public $incrementing = false; //nomor_order tidak auto increment karena menggunakan unix time sebagai Unique nya

    protected $keyType = 'string'; //Tipe primary kita adalah string, berbeda dengan default id() milik laravel, jadi kita tetapkan

    protected $fillable = [
        'nomor_order',
        'id_penyewa',
        'id_produk',
        'ukuran',
        'tujuan_pengiriman',
        'metode_kirim',
        'tanggal_mulai',
        'tanggal_selesai',
        'fee_admin',
        'bukti_penerimaan',
        'biaya_cuci',
        'pembayaran_via',
        'nomor_resi',
        'total_harga',
        'jaminan',
        'denda_keterlambatan',
        'total_penghasilan',
        'denda_lainnya',
        'additional',
        'tanggal_diterima',
        'tanggal_pengembalian',
        'ready_status',
        'status'
    ];

    protected $casts = [
        'additional' => 'array', //Dikonversi jadi array ketika kita ambil data nya
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'tanggal_diterima' => 'datetime',
        'tanggal_pengembalian' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_penyewa');
    }
    public function id_penyewa_order()
    {
        return $this->belongsTo(User::class, 'id_penyewa', 'id');
    }

    public function toko()
    {
        return $this->belongsTo(Toko::class, 'id_toko', 'id');
    }

    public function id_produk_order()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id')->withTrashed();
    }

    public function cekDenda()
    {
        $denda = PengajuanDenda::where('nomor_order', $this->nomor_order)->first() ?? false;
        return $denda;
    }

    public function cekDendaMasihBerjalan()
    {
        $denda = PengajuanDenda::where('nomor_order', $this->nomor_order)->whereIn('status', ['pending', 'diproses'])->first() ?? false;
        return $denda;
    }

    public function tanggalFormatted($tanggal)
    {
        $bulanTeks = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
        $hari = $tanggal->format('d');
        $bulan = (int) $tanggal->format('m');

        return $hari . ' ' . $bulanTeks[$bulan] . ' ' . $tanggal->format('Y');
    }

    public function penyewa()
    {
        return $this->belongsTo(User::class, 'id_penyewa');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk')->withTrashed();
    }

    public function denda()
    {
        return $this->hasMany(PengajuanDenda::class, 'nomor_order');
    }

    public function cekDendaDiproses()
    {
        $denda = PengajuanDenda::where('nomor_order', $this->nomor_order)->where('status', 'diproses')->first() ?? false;
        return $denda;
    }
}