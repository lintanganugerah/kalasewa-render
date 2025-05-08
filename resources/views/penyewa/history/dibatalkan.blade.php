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
                                    <a class="nav-link active" aria-current="page" href="{{ route('viewHistoryDibatalkan') }}">Dibatalkan @if ($countDibatalkan)
                                            <span class="position-top badge rounded-pill bg-danger">
                                                {{ $countDibatalkan }}
                                            </span>
                                        @endif

                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-secondary" href="{{ route('viewHistoryDiretur') }}">Diretur
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
                                                <th>Alasan Pembatalan</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orders as $order)
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
                                                    <td class="text-danger">{{ $order->alasan_pembatalan }}</td>
                                                    <td>
                                                        @if ($order->status == 'Dibatalkan Pemilik Sewa')
                                                            Dibatalkan Toko, Silahkan cek <a href="{{ route('viewPenarikan') }}" class="text-danger fw-bold">halaman penarikan
                                                                saldo</a> untuk melakukan penarikan
                                                        @elseif ($order->status == 'Refund di Ajukan')
                                                            Silahkan cek <a href="{{ route('viewPenarikan') }}" class="text-danger fw-bold">halaman penarikan
                                                                saldo</a> untuk melakukan penarikan
                                                        @endif
                                                    </td>
                                                </tr>

                                                <!-- Modal for Resi -->
                                                <div class="modal fade" id="refundModal-{{ $loop->iteration }}" tabindex="-1"
                                                    aria-labelledby="refundModalLabel-{{ $loop->iteration }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5" id="refundModalLabel-{{ $loop->iteration }}">
                                                                    Ajukan Refund</h1>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            @csrf
                                                            <form action="{{ route('setRekening', ['orderId' => $order->nomor_order]) }}" method="post">
                                                                @csrf
                                                                <div class="modal-body">

                                                                    @if ($saldos->nomor_rekening ?? false)
                                                                    @else
                                                                        <div class="bank-wallet" style="margin-top: -15px;">
                                                                            <label for="exampleInputEmail1" class="form-label">Bank/E-Wallet<span class="text-danger">*</span></label>
                                                                            <select class="form-select form-control-lg select2" id="selectRekening" aria-label="Default select example"
                                                                                name='tujuan_rek' required>
                                                                                <option></option>
                                                                                @foreach ($rekenings as $rekening)
                                                                                    <option value="{{ $rekening->id }}">
                                                                                        {{ $rekening->nama }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                            <div id="emailHelp" class="form-text">Silahkan
                                                                                pilih Bank
                                                                                atau E-Wallet yang anda gunakan</div>
                                                                        </div>

                                                                        <div class="nomor-rekening mt-2">
                                                                            <label for="exampleInputEmail1" class="form-label">Nomor
                                                                                Rekening Bank/E-Wallet<span class="text-danger">*</span></label>
                                                                            <input class="form-control form-control-lg" type="number" name='nomor_rekening' required>
                                                                            <div id="emailHelp" class="form-text">Silahkan
                                                                                masukkan
                                                                                nomor rekening/e-wallet anda</div>
                                                                        </div>

                                                                        <div class="atas-nama mt-2">
                                                                            <label for="exampleInputEmail1" class="form-label">Atas
                                                                                Nama<span class="text-danger">*</span></label>
                                                                            <input class="form-control form-control-lg" type="text" name='nama_rekening' required>
                                                                            <div id="emailHelp" class="form-text">Silahkan
                                                                                masukkan
                                                                                nama yang terdaftar di Rekening/E-Wallet
                                                                                anda</div>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
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
