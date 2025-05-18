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

            @if ($errors->any())
                <div class="row justify-content-center mb-4">
                    <div class="col-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ $errors->first() }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="row justify-content-center mb-4">
                    <div class="col-12">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row g-4">
                <!-- Product Image Column -->
                <div class="col-lg-3">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ asset($order->foto_produk) }}" class="card-img-top p-3" alt="fotoproduk">
                    </div>
                </div>

                <!-- Product Details Column -->
                <div class="col-lg-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h2 class="card-title fw-bold mb-4">{{ $order->nama_produk }}</h2>

                            <h4 class="fw-bold mb-3">Informasi Pilihan Kostum</h4>
                            <div class="mb-4">
                                @if (!empty($order->additional) && is_array($order->additional))
                                    @foreach ($order->additional as $additionalItem)
                                        @if (is_array($additionalItem) && isset($additionalItem['nama']))
                                            <span
                                                class="badge bg-outline-dark me-2 mb-2">{{ $additionalItem['nama'] }}</span>
                                        @endif
                                    @endforeach
                                @endif
                                <span class="badge bg-outline-dark">{{ $order->ukuran }}</span>
                                <div class="form-text mt-2">Berikut adalah pilihan additional dan ukuran yang kamu pilih
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal Mulai</label>
                                    <input type="text" class="form-control" value="{{ $order->tanggal_mulai }}" readonly>
                                    <div class="form-text">Tanggal mulai sewa anda!</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal Selesai</label>
                                    <input type="text" class="form-control" value="{{ $order->tanggal_selesai }}"
                                        readonly>
                                    <div class="form-text">Tanggal akhir sewa anda! (3 hari penyewaan)</div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Nama Penyewa</label>
                                <input type="text" class="form-control" value="{{ $order->nama_penyewa }}" readonly>
                                <div class="form-text">Nama yang terdaftar sebagai penyewa</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Alamat Penyewa<span class="text-danger">*</span></label>
                                <textarea class="form-control" rows="3" readonly>{{ $order->tujuan_pengiriman }}</textarea>
                                <div class="form-text">Pastikan alamat anda diisi dengan lengkap dan detail untuk memudahkan
                                    pengiriman!</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Metode Kirim</label>
                                <input type="text" class="form-control" value="{{ $order->metode_kirim }}" readonly>
                                <div class="form-text">Metode kirim yang dipilih</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Nomor Resi</label>
                                <input type="text" class="form-control" value="{{ $order->nomor_resi }}" readonly>
                                <div class="form-text">Nomor resi pengiriman untuk pelacakan. klik <a
                                        href="https://www.cekresi.com" target="_blank"
                                        class="text-decoration-none">disini</a> untuk mengecek resi</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary Column -->
                <div class="col-lg-3">
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-4">Ringkasan Belanja</h5>
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td colspan="2" class="fw-bold">Total Barang</td>
                                        </tr>
                                        <tr>
                                            <td class="text-secondary">Harga Katalog</td>
                                            <td class="text-end">Rp{{ number_format($order->harga_katalog, 0, '', '.') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-secondary">Harga Additional</td>
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
                                            <td class="text-secondary">Harga Cuci</td>
                                            <td class="text-end">Rp{{ number_format($order->biaya_cuci ?? 0, 0, '', '.') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="fw-bold pt-3">Biaya Transaksi</td>
                                        </tr>
                                        <tr>
                                            <td class="text-secondary">Jaminan Ongkir</td>
                                            <td class="text-end">Rp30.000</td>
                                        </tr>
                                        <tr>
                                            <td class="text-secondary">Jaminan Kostum</td>
                                            <td class="text-end">Rp50.000</td>
                                        </tr>
                                        <tr>
                                            <td class="text-secondary">Biaya Admin</td>
                                            <td class="text-end">Rp{{ number_format($order->fee_admin, 0, '', '.') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <h5 class="fw-bold mt-4">Total Tagihan</h5>
                            <h4 class="fw-bold text-primary">Rp{{ number_format($order->grand_total, 0, '', '.') }}</h4>

                            <hr>

                            <h5 class="fw-bold">Catatan Pembayaran</h5>
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td colspan="2" class="fw-bold">Denda dan Ongkos Kirim</td>
                                        </tr>
                                        <tr>
                                            <td class="text-secondary">Ongkos Kirim</td>
                                            <td class="text-end">
                                                Rp{{ number_format($order->ongkir_pengiriman ?? 0, 0, '', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-secondary">Denda Terlambat</td>
                                            <td class="text-end">
                                                Rp{{ number_format($order->denda_keterlambatan ?? 0, 0, '', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="fw-bold pt-3">Sisa Jaminan</td>
                                        </tr>
                                        <tr>
                                            <td class="text-secondary">Jaminan Ongkir</td>
                                            <td class="text-end">
                                                Rp{{ number_format($order->ongkir_default ?? 0, 0, '', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-secondary">Jaminan Kostum</td>
                                            <td class="text-end">Rp{{ number_format($order->jaminan ?? 0, 0, '', '.') }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            @if ($order->jaminan >= 0)
                                <h5 class="fw-bold mt-4">Total Uang Kembali <i class="ri-information-line"
                                        style="cursor: pointer" data-bs-toggle="modal"
                                        data-bs-target="#strukBelanjaModal"></i></h5>
                                <h4 class="fw-bold text-success">Rp{{ number_format(abs($uangKembali), 0, '', '.') }}</h4>
                            @elseif ($order->jaminan < 0)
                                <h5 class="fw-bold mt-4 text-danger">Total Hutang <i class="ri-information-line"
                                        style="cursor: pointer" data-bs-toggle="modal"
                                        data-bs-target="#strukBelanjaModal"></i></h5>
                                <h4 class="fw-bold text-danger">Rp{{ number_format(abs($order->jaminan), 0, '', '.') }}
                                </h4>
                            @endif
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#receivedModal">
                            <i class="ri-check-line me-2"></i>Barang Diterima
                        </button>
                        @if ($order->jaminan < 0)
                            <button id="pay-button" class="btn btn-outline-danger">
                                <i class="ri-money-dollar-circle-line me-2"></i>Retur + Bayar Hutang Ongkir
                            </button>
                        @else
                            <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#refundModal">
                                <i class="ri-arrow-left-right-line me-2"></i>Retur Barang
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('layout.footer')

    <!-- Modal Diterima -->
    <div class="modal fade" id="receivedModal" tabindex="-1" aria-labelledby="receivedModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="receivedModalLabel">Bukti Barang Diterima</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('terimaBarang', ['orderId' => $order->nomor_order]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Bukti Barang Diterima<span
                                    class="text-danger">*</span></label>
                            <input class="form-control" type="file" id="formFile" name="bukti_diterima"
                                accept=".jpg,.png,.jpeg,.webp" required>
                            <div class="form-text text-danger">Pastikan gambar yang dikirim mencakup seluruh barang yang
                                diterima!</div>
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

    <!-- Modal Refund -->
    <div class="modal fade" id="refundModal" tabindex="-1" aria-labelledby="refundModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="refundModalLabel">Pengajuan Refund</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('ajukanRefund', ['orderId' => $order->nomor_order]) }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <p class="mb-0">Apakah kamu ingin mengajukan refund?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Ajukan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Struk Belanja -->
    <div class="modal fade" id="strukBelanjaModal" tabindex="-1" aria-labelledby="strukBelanjaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="strukBelanjaModalLabel">Struk Belanja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td colspan="3" class="fw-bold text-center">Tagihan awal</td>
                                </tr>
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
                                <tr>
                                    <td colspan="3" class="fw-bold text-center">Ongkos Kirim dan Denda</td>
                                </tr>
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
                                    <td class="text-end">
                                        Rp{{ number_format($order->denda_keterlambatan ?? 0, 0, '', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="fw-bold text-center">Sisa Jaminan</td>
                                </tr>
                                <tr>
                                    <td>Jaminan Ongkir</td>
                                    <td class="text-end">:</td>
                                    <td class="text-end">Rp{{ number_format($order->ongkir_default ?? 0, 0, '', '.') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Jaminan Kostum</td>
                                    <td class="text-end">:</td>
                                    <td class="text-end">Rp{{ number_format($order->jaminan ?? 0, 0, '', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="fw-bold text-center">Tagihan yang Belum Dibayar</td>
                                </tr>
                                @if ($order->jaminan < 0)
                                    <tr class="text-danger fw-bold">
                                        <td>Hutang</td>
                                        <td class="text-end">:</td>
                                        <td class="text-end">Rp{{ number_format($order->jaminan ?? 0, 0, '', '.') }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td>Hutang</td>
                                        <td class="text-end">:</td>
                                        <td class="text-end">Rp0</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}">
    </script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function() {
            $.ajax({
                url: '{{ route('createSnapTokenDendaRetur', $order->nomor_order) }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    if (response.success) {
                        snap.pay(response.snap, {
                            onSuccess: function(result) {
                                $.ajax({
                                    url: '{{ route('updatePenghasilanDendaRetur', $order->nomor_order) }}',
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
                                        alert(response.message);
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
                            alert('Terjadi kesalahan, mohon refresh halaman');
                        }
                    }
                },
                error: function() {
                    alert('Terjadi Kesalahan. Mohon Refresh halaman anda');
                }
            });
        };
    </script>
@endsection
