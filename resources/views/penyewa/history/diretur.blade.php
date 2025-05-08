@extends('layout.template')
@extends('layout.navbar')

@section('content')

    <style>
        .no-underline {
            text-decoration: none;
            /* Remove underline */
            color: inherit;
            /* Inherit the color from the parent element or set it explicitly */
        }
    </style>

    <section>

        <div class="header-text-content mt-5 text-center">
            <div class="container-fluid">
                <div class="container">
                    <h1><strong>History Penyewaan</strong></h1>
                </div>
            </div>
        </div>

        <div class="container mt-2">
            @csrf
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

        </div>

        <div class="button-content mt-5">
            <div class="container-fluid">
                <div class="container">
                    <div class="card">
                        <div class="card-header">
                            <ul class="nav nav-tabs card-header-tabs" id="historyTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link text-secondary" href="{{ route('viewHistoryMenungguDiproses') }}">Menunggu Konfirmasi
                                        @if ($countMenungguDiproses)
                                            <span class="position-top badge rounded-pill bg-danger">
                                                {{ $countMenungguDiproses }}
                                            </span>
                                        @endif

                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-secondary" href="{{ route('viewHistoryDalamPengiriman') }}">Dalam
                                        Pengiriman @if ($countDalamPengiriman)
                                            <span class="position-top badge rounded-pill bg-danger">
                                                {{ $countDalamPengiriman }}
                                            </span>
                                        @endif

                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-secondary" href="{{ route('viewHistorySedangBerlangsung') }}">Sedang
                                        Digunakan @if ($countSedangBerlangsung)
                                            <span class="position-top badge rounded-pill bg-danger">
                                                {{ $countSedangBerlangsung }}
                                            </span>
                                        @endif

                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-secondary" href="{{ route('viewHistoryTelahKembali') }}">Dikirim
                                        Kembali @if ($countTelahKembali)
                                            <span class="position-top badge rounded-pill bg-danger">
                                                {{ $countTelahKembali }}
                                            </span>
                                        @endif

                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-secondary" href="{{ route('viewHistoryDibatalkan') }}">Dibatalkan
                                        @if ($countDibatalkan)
                                            <span class="position-top badge rounded-pill bg-danger">
                                                {{ $countDibatalkan }}
                                            </span>
                                        @endif

                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="{{ route('viewHistoryDiretur') }}">Diretur
                                        @if ($countDiretur)
                                            <span class="position-top badge rounded-pill bg-danger">
                                                {{ $countDiretur }}
                                            </span>
                                        @endif


                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-secondary" href="{{ route('viewHistoryPenyewaanSelesai') }}">Penyewaan
                                        Selesai</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                @if ($orders)
                                    <table class="table w-100" id="table-history">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nomor Order</th>
                                                <th>Nama Produk</th>
                                                <th>Additional</th>
                                                <th>Tanggal Mulai</th>
                                                <th>Tanggal Selesai</th>
                                                <th>Total Biaya</th>
                                                <th>Resi</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orders as $order)
                                                @if ($order->status == 'Retur' or $order->status == 'Retur Dikonfirmasi' or $order->status == 'Retur dalam Pengiriman')
                                                    <tr>
                                                        <td data-title="#" class="text-center">
                                                            {{ $loop->iteration }}
                                                        </td>
                                                        <td>{{ $order->nomor_order }}</td>
                                                        <td>{{ $order->nama_produk }}</td>
                                                        <td>
                                                            @if (!empty($order->additional) && is_array($order->additional))
                                                                @foreach ($order->additional as $additionalItem)
                                                                    @if (is_array($additionalItem) && isset($additionalItem['nama']))
                                                                        {{ $additionalItem['nama'] }}
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                <p>-</p>
                                                            @endif

                                                        </td>
                                                        <td>{{ $order->tanggal_mulai }}</td>
                                                        <td>{{ $order->tanggal_selesai }}</td>
                                                        <td>Rp{{ number_format($order->grand_total, 0, '', '.') }}</td>
                                                        @if ($order->bukti_resi)
                                                            <td>
                                                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                                                                    data-bs-target="#resiModal-{{ $loop->iteration }}">
                                                                    Lihat Resi
                                                                </button>
                                                            </td>
                                                        @else
                                                            <td>
                                                                <button type="button" class="btn btn-outline-danger" disabled>
                                                                    Lihat Resi
                                                                </button>
                                                            </td>
                                                        @endif

                                                        <td>
                                                            @if ($order->status == 'Retur')
                                                                Sedang di review Admin dan Toko
                                                            @elseif ($order->status == 'Retur Dikonfirmasi')
                                                                Retur di Konfirmasi
                                                            @elseif ($order->status == 'Retur dalam Pengiriman')
                                                                Retur Dalam Pengiriman
                                                            @endif
                                                        </td>

                                                        <td>
                                                            @if ($order->status == 'Retur')
                                                                <button class="btn btn-danger" disabled>Retur Barang</button>
                                                            @elseif ($order->status == 'Retur Dikonfirmasi')
                                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                                    data-bs-target="#refundModal-{{ $loop->iteration }}">
                                                                    Retur Barang
                                                                </button>
                                                            @elseif ($order->status == 'Retur dalam Pengiriman')
                                                                <button class="btn btn-danger" disabled>Retur Barang</button>
                                                            @endif
                                                        </td>
                                                    </tr>

                                                    <!-- Modal for Resi -->
                                                    <div class="modal fade" id="resiModal-{{ $loop->iteration }}" tabindex="-1"
                                                        aria-labelledby="resiModalLabel-{{ $loop->iteration }}" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h1 class="modal-title fs-5" id="resiModalLabel-{{ $loop->iteration }}">
                                                                        Bukti
                                                                        Resi</h1>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    @if ($order->status == 'Retur')
                                                                        <p>Nomor Resi: <strong>{{ $order->nomor_resi }}</strong></p>
                                                                        <img src="{{ asset($order->bukti_resi) }}" alt="Resi" class="img-fluid">
                                                                    @elseif ($order->status == 'Retur Dikonfirmasi')
                                                                        <p>Nomor Resi: <strong>{{ $order->nomor_resi }}</strong></p>
                                                                        <img src="{{ asset($order->bukti_resi) }}" alt="Resi" class="img-fluid">
                                                                    @elseif ($order->status == 'Retur dalam Pengiriman')
                                                                        <p>Nomor Resi: <strong>{{ $order->nomor_resi }}</strong></p>
                                                                        <img src="{{ asset($order->bukti_resi_pengembalian) }}" alt="Resi" class="img-fluid">
                                                                    @endif
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Refund Modal -->
                                                    <div class="modal fade" id="refundModal-{{ $loop->iteration }}" tabindex="-1" aria-labelledby="refundModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h1 class="modal-title fs-5" id="refundModalLabel">Retur Barang</h1>

                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <form action="{{ route('returBarangRefund', ['orderId' => $order->nomor_order]) }}" method="POST"
                                                                    enctype="multipart/form-data">
                                                                    @csrf
                                                                    <div class="modal-body">
                                                                        <div class="alamat-pengembalian" style="margin-top: -20px;">
                                                                            <label for="exampleInputEmail1" class="form-label">Alamat
                                                                                Pengembalian</label>
                                                                            <textarea name="alamatpengembalian" placeholder="Alamat Pengembalian Produk" class="form-control form-control-lg w-100" readonly>{{ $order->id_produk_order->getalamatproduk($order->id_produk_order->alamat, $order->id_produk_order->toko->id_user) }}</textarea>
                                                                            <div id="emailHelp" class="form-text">Silahkan
                                                                                lakukan
                                                                                pengiriman kembali ke alamat yang tertera!
                                                                            </div>
                                                                        </div>
                                                                        <div class="nomor-resi mt-2">
                                                                            <label for="exampleInputEmail1" class="form-label">Nomor
                                                                                Resi<span class="text-danger">*</span></label>
                                                                            <input type="text" name="nomor_resi" placeholder="Nomor Resi"
                                                                                class="form-control form-control-lg w-100" />
                                                                            <div id="emailHelp" class="form-text">Nomor
                                                                                resi pengiriman
                                                                                untuk pelacakan</div>
                                                                        </div>
                                                                        <div class="bukti-resi mt-2">
                                                                            <label for="formFile" class="form-label">Bukti
                                                                                Resi /
                                                                                Pengiriman<span class="text-danger">*</span></label>
                                                                            <input class="form-control" type="file" id="formFile" name="bukti_resi_penyewa"
                                                                                accept=".jpg,.png,.jpeg,.webp" required>
                                                                            <div id="emailHelp" class="form-text">Silahkan
                                                                                berikan bukti
                                                                                gambar resi atau screenshot pengiriman
                                                                                barang!</div>
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
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('layout.footer')

    </section>

@endsection
