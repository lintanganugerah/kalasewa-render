@extends('layout.template')
@extends('layout.navbar')

@section('content')

    <section>

        <div class="container-fluid mt-5">
            <div class="container text-center">
                <h1><strong>INFORMASI PEMESANAN</strong></h1>
            </div>
        </div>

        <div class="container mt-2">
            @csrf
            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <div class="container-fluid mt-5">
            <div class="container">
                <div class="row">
                    <div class="col-3">

                        <img src="{{ asset($order->foto_produk) }}" class="img-thumbnail" alt="fotoproduk">

                    </div>
                    <div class="col-6">

                        <form action="">

                            <h1><strong>{{ $order->nama_produk }}</strong></h1>

                            <h3><strong>Informasi Pilihan Kostum</strong></h3>
                            @if ($order->additional)
                                @if (!empty($order->additional) && is_array($order->additional))
                                    @foreach ($order->additional as $additionalItem)
                                        @if (is_array($additionalItem) && isset($additionalItem['nama']))
                                            <button class="btn btn-outline-dark"
                                                type="button">{{ $additionalItem['nama'] }}</button>
                                        @endif
                                    @endforeach
                                @endif
                            @endif
                            <button class="btn btn-outline-dark" type="button">{{ $order->ukuran }}</button>
                            <div id="emailHelp" class="form-text">Berikut adalah pilihan additional dan ukuran yang kamu
                                pilih</div>

                            <div class="datepckr d-flex mt-3">
                                <div class="col mx-1">
                                    <label for="exampleInputEmail1" class="form-label">Tanggal Mulai</label>
                                    <input type="text" name="infomulai" placeholder="Tanggal Mulai Sewa"
                                        class="form-control form-control-lg w-100" value="{{ $order->tanggal_mulai }}"
                                        readonly />
                                    <div id="emailHelp" class="form-text">Tanggal mulai sewa anda!</div>
                                </div>
                                <div class="col mx-1">
                                    <label for="exampleInputEmail1" class="form-label">Tanggal Selesai</label>
                                    <input type="text" name="infoselesai" placeholder="Tanggal Akhir Sewa"
                                        class="form-control form-control-lg w-100" value="{{ $order->tanggal_selesai }}"
                                        readonly />
                                    <div id="emailHelp" class="form-text">Tanggal akhir sewa anda! (3 hari penyewaan)</div>
                                </div>
                            </div>

                            <div class="namapenyewa-ctner mt-3">
                                <label for="exampleInputEmail1" class="form-label">Nama Penyewa</label>
                                <input type="text" name="nama" placeholder="Nama Penyewa"
                                    class="form-control form-control-lg w-100" value="{{ $order->nama_penyewa }}"
                                    readonly />
                                <div id="emailHelp" class="form-text">Nama yang terdaftar sebagai penyewa</div>
                            </div>

                            <div class="alamat-ctner mt-3">
                                <label for="exampleInputEmail1" class="form-label">Alamat Penyewa<span
                                        class="text-danger">*</span></label>
                                <textarea name="alamat" placeholder="Alamat Penyewa" class="form-control form-control-lg w-100" readonly>{{ $order->tujuan_pengiriman }}</textarea>
                                <div id="emailHelp" class="form-text">Pastikan alamat anda diisi dengan lengkap dan detail
                                    untuk memudahkan pengiriman!</div>
                            </div>

                            <div class="metodekirim-ctner mt-3">
                                <label for="exampleInputEmail1" class="form-label">Metode Kirim</label>
                                <input type="text" name="metodekirim" placeholder="Metode Kirim"
                                    class="form-control form-control-lg w-100" value="{{ $order->metode_kirim }}"
                                    readonly />
                                <div id="emailHelp" class="form-text">Metode kirim yang dipilih</div>
                            </div>

                            <div class="noresi-ctner mt-3">
                                <label for="exampleInputEmail1" class="form-label">Nomor Resi</label>
                                <input type="text" name="nomorresi" placeholder="Nomor Resi"
                                    class="form-control form-control-lg w-100" value="{{ $order->nomor_resi }}" readonly />
                                <div id="emailHelp" class="form-text">Nomor resi pengiriman untuk pelacakan. klik <span><a
                                            href="https://www.cekresi.com" target="_blank">disini</a></span> untuk mengecek
                                    resi</div>
                            </div>
                    </div>

                    </form>

                    <div class="col-3">

                        <div class="card">
                            <div class="card-body">
                                <h5><strong>Ringkasan Belanja</strong></h5>
                                <p><strong>Total Barang</strong></p>
                                <div class="d-flex">
                                    <div class="col text-start">
                                        <p class="text-secondary">Harga Katalog</p>
                                        <p class="text-secondary" id="harga-additional-label">Harga Additional</p>
                                        <p class="text-secondary">Harga Cuci</p>
                                        <p class="text-secondary">Jaminan Ongkir</p>
                                    </div>
                                    <div class="col text-end">
                                        <p class="text-secondary" id="harga-katalog">
                                            Rp{{ number_format($order->harga_katalog, 0, '', '.') }}</p>

                                        @if ($order->additional)
                                            @foreach ($order->additional as $additionalItem)
                                                <p class="text-secondary" id="harga-additional-total">
                                                    Rp{{ number_format($additionalItem['harga'], 0, '', '.') }}</p>
                                            @endforeach
                                        @else
                                            <p class="text-secondary" id="harga-additional-total"> - </p>
                                        @endif

                                        <p class="text-secondary" id="harga-cuci">
                                            Rp{{ number_format($order->biaya_cuci ?? 0, 0, '', '.') }}</p>
                                        <p class="text-secondary" id="harga-cuci">
                                            Rp30.000</p>
                                    </div>
                                </div>
                                <p class="mt-2"><strong>Biaya Transaksi</strong></p>
                                <div class="d-flex">
                                    <div class="col text-start">
                                        <p class="text-secondary">Jaminan Katalog</p>
                                        <p class="text-secondary" id="biaya-admin-label">Biaya Admin</p>
                                    </div>
                                    <div class="col text-end">
                                        <p class="text-secondary">Rp50.000</p>
                                        <p class="text-secondary" id="biaya-admin-value">
                                            Rp{{ number_format($order->fee_admin, 0, '', '.') }}</p>
                                    </div>
                                </div>
                                <h5 class="mt-2"><strong>Total Tagihan</strong></h5>
                                <h4><strong
                                        id="total-tagihan">Rp{{ number_format($order->grand_total, 0, '', '.') }}</strong>
                                </h4>

                                <hr>

                                <h5 class="mt-2"><strong>Catatan Pembayaran</strong></h5>
                                <p class="mt-2"><strong>Denda dan Ongkos Kirim</strong></p>
                                <div class="d-flex">
                                    <div class="col text-start">
                                        <p class="text-secondary">Ongkos Kirim</p>
                                        <p class="text-secondary" id="harga-additional-label">Denda Telat</p>
                                    </div>
                                    <div class="col text-end">
                                        <p class="text-secondary">
                                            Rp{{ number_format($order->ongkir_pengiriman ?? 0, 0, '', '.') }}
                                        </p>
                                        <p class="text-secondary">
                                            Rp{{ number_format($order->denda_keterlambatan ?? 0, 0, '', '.') }}
                                        </p>
                                    </div>
                                </div>

                                <p><strong>Sisa Jaminan</strong></p>
                                <div class="d-flex">
                                    <div class="col text-start">
                                        <p class="text-secondary" id="harga-additional-label">Jaminan Ongkir</p>
                                        <p class="text-secondary">Jaminan Kostum</p>
                                    </div>
                                    <div class="col text-end">
                                        <p class="text-secondary">
                                            Rp{{ number_format($order->ongkir_default ?? 0, 0, '', '.') }}
                                        </p>
                                        @if ($order->jaminan < 0)
                                            <p class="text-secondary">Rp0</p>
                                        @else
                                            <p class="text-secondary">
                                                Rp{{ number_format($order->jaminan ?? 0, 0, '', '.') }}</p>
                                        @endif
                                    </div>
                                </div>


                                @if ($order->cekDendaDiproses())
                                    <h5 class="mt-2 text-danger"><strong>Total Hutang <i class="ri-information-line"
                                                style="cursor: pointer" data-bs-toggle="modal"
                                                data-bs-target="#strukBelanjaModal"></i></strong></h5>
                                    <h4 class="text-danger"><strong
                                            id="total-tagihan">Rp{{ number_format(abs($dendas), 0, '', '.') }}</strong>
                                    </h4>

                                    <div class="choice-cntr mt-2">
                                        <button id="pay-button" class="btn btn-danger w-100">Bayar Hutang</button>
                                    </div>
                                @else
                                    @if ($order->jaminan >= 0)
                                        <h5 class="mt-2"><strong>Total Uang Kembali <i class="ri-information-line"
                                                    style="cursor: pointer" data-bs-toggle="modal"
                                                    data-bs-target="#strukBelanjaModal"></i></strong></h5>
                                        <h4><strong
                                                id="total-tagihan">Rp{{ number_format(abs($uangKembali), 0, '', '.') }}</strong>
                                        </h4>
                                    @elseif ($order->jaminan < 0)
                                        <h5 class="mt-2 text-danger"><strong>Total Hutang</strong>
                                        </h5>
                                        <h4 class="text-danger"><strong
                                                id="total-tagihan">Rp{{ number_format(abs($order->jaminan), 0, '', '.') }}</strong>
                                        </h4>
                                    @endif
                                @endif

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>

        @include('layout.footer')

        <!-- Modal Refund -->
        <div class="modal fade" id="returModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Retur Barang</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('viewHistory') }}">
                        <div class="modal-body">
                            <div class="nomor-resi">
                                <label for="exampleInputEmail1" class="form-label">Nomor Resi<span
                                        class="text-danger">*</span></label>
                                <input type="text" name="nomorresi" placeholder="Nomor Resi"
                                    class="form-control form-control-lg w-100" />
                                <div id="emailHelp" class="form-text">Nomor resi pengiriman untuk pelacakan</div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Kirim</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal for Struk Belanja -->
        <div class="modal fade" id="strukBelanjaModal" tabindex="-1" aria-labelledby="strukBelanjaModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="strukBelanjaModalLabel">Struk Belanja</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="w-100 mb-3">
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
                                        <td class="text-end">Rp{{ number_format($additionalItem['harga'], 0, '', '.') }}
                                        </td>
                                    @endforeach
                                @else
                                    <td class="text-end" id="harga-additional-total">Rp0</td>
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
                                <td class="text-end fw-bold">Rp{{ number_format($order->grand_total, 0, '', '.') }}</td>
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
                                <td class="text-end">Rp{{ number_format($order->denda_keterlambatan ?? 0, 0, '', '.') }}
                                </td>
                            </tr>
                            <tr class="text-danger">
                                <td>Denda Lain (Ex: Kerusakan)</td>
                                <td class="text-end">:</td>
                                <td class="text-end">Rp{{ number_format(abs($dendalunas), 0, '', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="fw-bold text-center">Sisa Jaminan</td>
                            </tr>
                            <tr>
                                <td>Jaminan Ongkir</td>
                                <td class="text-end">:</td>
                                <td class="text-end">Rp{{ number_format($order->ongkir_default ?? 0, 0, '', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Jaminan Kostum</td>
                                <td class="text-end">:</td>
                                @if ($order->jaminan < 0)
                                    <td class="text-end">Rp{{ number_format(0) }}</td>
                                @else
                                    <td class="text-end">Rp{{ number_format($order->jaminan ?? 0, 0, '', '.') }}</td>
                                @endif
                            </tr>
                            <tr>
                                <td colspan="3" class="fw-bold text-center">Tagihan yang Belum Dibayar</td>
                            </tr>
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
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </section>

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
                                        alert(
                                            'Terjadi Kesalahan'
                                        );
                                    }
                                });
                            },
                            // Optional
                            onPending: function(result) {
                                /* You may add your own js here, this is just example */
                                document.getElementById('result-json').innerHTML += JSON
                                    .stringify(
                                        result, null, 2);
                            },
                            // Optional
                            onError: function(result) {
                                /* You may add your own js here, this is just example */
                                document.getElementById('result-json').innerHTML += JSON
                                    .stringify(
                                        result, null, 2);
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
                    alert(
                        'Terjadi Kesahalah. Mohon Refresh halaman anda'
                    );
                }
            });
        };
    </script>

@endsection
