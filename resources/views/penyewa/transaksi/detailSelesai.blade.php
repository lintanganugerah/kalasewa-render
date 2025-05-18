@extends('layout.template')
@extends('layout.navbar')

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center mb-4">
                <div class="col-12 text-center">
                    <h1 class="display-4 fw-bold">INFORMASI PEMESANAN</h1>
                </div>
            </div>

            <div class="row justify-content-center mb-4">
                <div class="col-12">
                    @csrf
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ $errors->first() }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3 mb-4">
                    <div class="card h-100">
                        <img src="{{ asset($order->foto_produk) }}" class="card-img-top img-fluid" alt="fotoproduk">
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h2 class="card-title fw-bold mb-4">{{ $order->nama_produk }}</h2>

                            <h4 class="fw-bold mb-3">Informasi Pilihan Kostum</h4>
                            <div class="mb-3">
                                @if ($order->additional)
                                    @if (!empty($order->additional) && is_array($order->additional))
                                        @foreach ($order->additional as $additionalItem)
                                            @if (is_array($additionalItem) && isset($additionalItem['nama']))
                                                <span
                                                    class="badge bg-outline-dark me-2 mb-2">{{ $additionalItem['nama'] }}</span>
                                            @endif
                                        @endforeach
                                    @endif
                                @endif
                                <span class="badge bg-outline-dark me-2 mb-2">{{ $order->ukuran }}</span>
                            </div>
                            <small class="text-muted d-block mb-4">Berikut adalah pilihan additional dan ukuran yang kamu
                                pilih</small>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="tanggalMulai"
                                            value="{{ $order->tanggal_mulai }}" readonly>
                                        <label for="tanggalMulai">Tanggal Mulai</label>
                                    </div>
                                    <small class="text-muted">Tanggal mulai sewa anda!</small>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="tanggalSelesai"
                                            value="{{ $order->tanggal_selesai }}" readonly>
                                        <label for="tanggalSelesai">Tanggal Selesai</label>
                                    </div>
                                    <small class="text-muted">Tanggal akhir sewa anda! (3 hari penyewaan)</small>
                                </div>
                            </div>

                            <div class="form-floating mb-4">
                                <input type="text" class="form-control" id="namaPenyewa"
                                    value="{{ $order->nama_penyewa }}" readonly>
                                <label for="namaPenyewa">Nama Penyewa</label>
                                <small class="text-muted">Nama yang terdaftar sebagai penyewa</small>
                            </div>

                            <div class="form-floating mb-4">
                                <textarea class="form-control" id="alamatPenyewa" style="height: 100px" readonly>{{ $order->tujuan_pengiriman }}</textarea>
                                <label for="alamatPenyewa">Alamat Penyewa<span class="text-danger">*</span></label>
                                <small class="text-muted">Pastikan alamat anda diisi dengan lengkap dan detail untuk
                                    memudahkan pengiriman!</small>
                            </div>

                            <div class="form-floating mb-4">
                                <input type="text" class="form-control" id="metodeKirim"
                                    value="{{ $order->metode_kirim }}" readonly>
                                <label for="metodeKirim">Metode Kirim</label>
                                <small class="text-muted">Metode kirim yang dipilih</small>
                            </div>

                            <div class="form-floating mb-4">
                                <input type="text" class="form-control" id="nomorResi" value="{{ $order->nomor_resi }}"
                                    readonly>
                                <label for="nomorResi">Nomor Resi</label>
                                <small class="text-muted">Nomor resi pengiriman untuk pelacakan. klik <a
                                        href="https://www.cekresi.com" target="_blank"
                                        class="text-decoration-none">disini</a> untuk mengecek resi</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-4">Ringkasan Belanja</h5>

                            <div class="mb-4">
                                <h6 class="fw-bold">Total Barang</h6>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Harga Katalog</span>
                                    <span
                                        class="text-muted">Rp{{ number_format($order->harga_katalog, 0, '', '.') }}</span>
                                </div>

                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Harga Additional</span>
                                    @if ($order->additional)
                                        @foreach ($order->additional as $additionalItem)
                                            <span
                                                class="text-muted">Rp{{ number_format($additionalItem['harga'], 0, '', '.') }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </div>

                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Harga Cuci</span>
                                    <span
                                        class="text-muted">Rp{{ number_format($order->biaya_cuci ?? 0, 0, '', '.') }}</span>
                                </div>

                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Jaminan Ongkir</span>
                                    <span class="text-muted">Rp30.000</span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h6 class="fw-bold">Biaya Transaksi</h6>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Jaminan Katalog</span>
                                    <span class="text-muted">Rp50.000</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Biaya Admin</span>
                                    <span class="text-muted">Rp{{ number_format($order->fee_admin, 0, '', '.') }}</span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h6 class="fw-bold">Total Tagihan</h6>
                                <h4 class="fw-bold text-primary">Rp{{ number_format($order->grand_total, 0, '', '.') }}
                                </h4>
                            </div>

                            <hr>

                            <div class="mb-4">
                                <h6 class="fw-bold">Catatan Pembayaran</h6>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Ongkos Kirim</span>
                                    <span
                                        class="text-muted">Rp{{ number_format($order->ongkir_pengiriman ?? 0, 0, '', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Denda Telat</span>
                                    <span
                                        class="text-muted">Rp{{ number_format($order->denda_keterlambatan ?? 0, 0, '', '.') }}</span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h6 class="fw-bold">Sisa Jaminan</h6>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Jaminan Ongkir</span>
                                    <span
                                        class="text-muted">Rp{{ number_format($order->ongkir_default ?? 0, 0, '', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Jaminan Kostum</span>
                                    @if ($order->jaminan < 0)
                                        <span class="text-muted">Rp0</span>
                                    @else
                                        <span
                                            class="text-muted">Rp{{ number_format($order->jaminan ?? 0, 0, '', '.') }}</span>
                                    @endif
                                </div>
                            </div>

                            @if ($order->cekDendaDiproses())
                                <div class="mb-4">
                                    <h6 class="fw-bold text-danger">Total Hutang <i class="ri-information-line"
                                            style="cursor: pointer" data-bs-toggle="modal"
                                            data-bs-target="#strukBelanjaModal"></i></h6>
                                    <h4 class="fw-bold text-danger">Rp{{ number_format(abs($dendas), 0, '', '.') }}</h4>
                                    <button id="pay-button" class="btn btn-danger w-100 mt-3">Bayar Hutang</button>
                                </div>
                            @else
                                @if ($order->jaminan >= 0)
                                    <div class="mb-4">
                                        <h6 class="fw-bold">Total Uang Kembali <i class="ri-information-line"
                                                style="cursor: pointer" data-bs-toggle="modal"
                                                data-bs-target="#strukBelanjaModal"></i></h6>
                                        <h4 class="fw-bold text-success">
                                            Rp{{ number_format(abs($uangKembali), 0, '', '.') }}</h4>
                                    </div>
                                @elseif ($order->jaminan < 0)
                                    <div class="mb-4">
                                        <h6 class="fw-bold text-danger">Total Hutang</h6>
                                        <h4 class="fw-bold text-danger">
                                            Rp{{ number_format(abs($order->jaminan), 0, '', '.') }}</h4>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('layout.footer')

    <!-- Modal Refund -->
    <div class="modal fade" id="returModal" tabindex="-1" aria-labelledby="returModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="returModalLabel">Retur Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('viewHistory') }}">
                    <div class="modal-body">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="nomorResiRetur" name="nomorresi"
                                placeholder="Nomor Resi">
                            <label for="nomorResiRetur">Nomor Resi<span class="text-danger">*</span></label>
                            <small class="text-muted">Nomor resi pengiriman untuk pelacakan</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Kirim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for Struk Belanja -->
    <div class="modal fade" id="strukBelanjaModal" tabindex="-1" aria-labelledby="strukBelanjaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="strukBelanjaModalLabel">Struk Belanja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th colspan="3" class="text-center fw-bold">Tagihan awal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Harga Katalog</td>
                                    <td class="text-end">:</td>
                                    <td class="text-end">Rp{{ number_format($order->harga_katalog, 0, '', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Biaya Cuci</td>
                                    <td class="text-end">:</td>
                                    <td class="text-end">Rp{{ number_format($order->biaya_cuci ?? 0, 0, '', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Harga Additional</td>
                                    <td class="text-end">:</td>
                                    @if ($order->additional)
                                        @foreach ($order->additional as $additionalItem)
                                            <td class="text-end">
                                                Rp{{ number_format($additionalItem['harga'], 0, '', '.') }}</td>
                                        @endforeach
                                    @else
                                        <td class="text-end">Rp0</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>Jaminan Ongkir</td>
                                    <td class="text-end">:</td>
                                    <td class="text-end">Rp30.000</td>
                                </tr>
                                <tr>
                                    <td>Jaminan Katalog</td>
                                    <td class="text-end">:</td>
                                    <td class="text-end">Rp50.000</td>
                                </tr>
                                <tr>
                                    <td>Biaya Admin</td>
                                    <td class="text-end">:</td>
                                    <td class="text-end">Rp{{ number_format($order->fee_admin, 0, '', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Total Tagihan</td>
                                    <td class="text-end">:</td>
                                    <td class="text-end fw-bold">Rp{{ number_format($order->grand_total, 0, '', '.') }}
                                    </td>
                                </tr>
                            </tbody>
                            <thead>
                                <tr>
                                    <th colspan="3" class="text-center fw-bold">Ongkos Kirim dan Denda</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-danger">
                                    <td>Ongkos Kirim dari Toko</td>
                                    <td class="text-end">:</td>
                                    <td class="text-end">Rp{{ number_format($order->ongkir_pengiriman ?? 0, 0, '', '.') }}
                                    </td>
                                </tr>
                                <tr class="text-danger">
                                    <td>Denda Keterlambatan</td>
                                    <td class="text-end">:</td>
                                    <td class="text-end">
                                        Rp{{ number_format($order->denda_keterlambatan ?? 0, 0, '', '.') }}</td>
                                </tr>
                                <tr class="text-danger">
                                    <td>Denda Lain (Ex: Kerusakan)</td>
                                    <td class="text-end">:</td>
                                    <td class="text-end">Rp{{ number_format(abs($dendalunas), 0, '', '.') }}</td>
                                </tr>
                            </tbody>
                            <thead>
                                <tr>
                                    <th colspan="3" class="text-center fw-bold">Sisa Jaminan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Jaminan Ongkir</td>
                                    <td class="text-end">:</td>
                                    <td class="text-end">Rp{{ number_format($order->ongkir_default ?? 0, 0, '', '.') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Jaminan Kostum</td>
                                    <td class="text-end">:</td>
                                    @if ($order->jaminan < 0)
                                        <td class="text-end">Rp0</td>
                                    @else
                                        <td class="text-end">Rp{{ number_format($order->jaminan ?? 0, 0, '', '.') }}</td>
                                    @endif
                                </tr>
                            </tbody>
                            <thead>
                                <tr>
                                    <th colspan="3" class="text-center fw-bold">Tagihan yang Belum Dibayar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($order->cekDendaDiproses())
                                    <tr class="text-danger fw-bold">
                                        <td>Hutang Denda Keterlambatan</td>
                                        <td class="text-end">:</td>
                                        <td class="text-end">Rp0</td>
                                    </tr>
                                    <tr class="text-danger fw-bold">
                                        <td>Hutang Denda Lain</td>
                                        <td class="text-end">:</td>
                                        <td class="text-end">Rp{{ number_format(abs($dendas), 0, '', '.') }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td>Hutang Denda Keterlambatan</td>
                                        <td class="text-end">:</td>
                                        <td class="text-end">Rp0</td>
                                    </tr>
                                    <tr>
                                        <td>Hutang Denda Lain</td>
                                        <td class="text-end">:</td>
                                        <td class="text-end">Rp0</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}">
    </script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function() {
            $.ajax({
                url: '{{ route('createSnapTokenDendaLain', $order->nomor_order) }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    console.log('Masuk');
                    if (response.success) {
                        console.log('SUKSES RESPONSE NYA');
                        snap.pay(response.snap, {
                            onSuccess: function(result) {
                                $.ajax({
                                    url: '{{ route('updatePenghasilanDendaLain', $order->nomor_order) }}',
                                    type: 'POST',
                                    data: {
                                        _token: '{{ csrf_token() }}',
                                    },
                                    success: function(response) {
                                        if (response.success) {
                                            window.location.href = response
                                                .redirect_url;
                                        } else {
                                            alert(
                                                'Pembayaran berhasil, tetapi gagal memperbarui data toko.'
                                            );
                                        }
                                    },
                                    error: function() {
                                        alert('Terjadi Kesalahan');
                                    }
                                });
                            },
                            onPending: function(result) {
                                document.getElementById('result-json').innerHTML += JSON
                                    .stringify(result, null, 2);
                            },
                            onError: function(result) {
                                document.getElementById('result-json').innerHTML += JSON
                                    .stringify(result, null, 2);
                            }
                        });
                    } else {
                        if (response.message && response.message == 'terbayar') {
                            window.location.href = response.redirect_url;
                        } else {
                            alert(response.message);
                        }
                    }
                },
                error: function() {
                    console.log(response);
                    alert('Terjadi Kesalahan. Mohon Refresh halaman anda');
                }
            });
        };
    </script>
@endsection
