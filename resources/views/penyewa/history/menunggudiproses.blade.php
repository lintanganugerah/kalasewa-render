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
                                    <a class="nav-link active" aria-current="page" href="{{ route('viewHistoryMenungguDiproses') }}">Menunggu Konfirmasi
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
                                                <th class="text-center">#</th>
                                                <th>Nomor Order</th>
                                                <th>Nama Produk</th>
                                                <th>Additional</th>
                                                <th>Toko</th>
                                                <th>Tanggal Mulai</th>
                                                <th>Tanggal Selesai</th>
                                                <th>Total Biaya</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orders as $order)
                                                @if ($order->status == 'Menunggu di Proses' || $order->status == 'Pending')
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
                                                        <td>{{ $order->nama_toko }}</td>
                                                        <td>{{ $order->tanggal_mulai }}</td>
                                                        <td>{{ $order->tanggal_selesai }}</td>
                                                        <td>Rp{{ number_format($order->grand_total, 0, '', '.') }}</td>
                                                        <td>
                                                            @if ($order->status == 'Menunggu di Proses')
                                                                <p>Menunggu Konfirmasi Toko</p>
                                                            @elseif ($order->status == 'Pending')
                                                                <p>Menunggu Pembayaran</p>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($order->status == 'Menunggu di Proses')
                                                                <button class="btn btn-danger w-100" disabled>Detail</>
                                                                @elseif ($order->status == 'Pending')
                                                                    <a href="{{ route('viewCheckout') }}" class="btn btn-danger w-100">Bayar</a>
                                                            @endif
                                                            <a href="{{ url('/chat' . '/' . $order->toko->id_user) }}" target="_blank" class="no-underline"><button type="button"
                                                                    class="btn btn-outline-success w-100 mt-1">Chat
                                                                    Toko</button></a>
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
                                                                    <img src="{{ asset($order->bukti_resi) }}" alt="Resi" class="img-fluid">
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                </div>
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
                    {{-- Modal Peringatan --}}
                    <div class="modal" id="modal_auto" tabindex="-1" aria-labelledby="exampleModalCenterTitle" aria-modal="true" role="dialog">
                        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5 fw-bolder" id="exampleModalCenterTitle">Mohon Perhatian</h1>
                                </div>
                                <div class="modal-body">
                                    <p>Harap
                                        <span class="fw-bolder text-danger">rekam video saat unboxing atau menerima barang
                                        </span> sebagai bukti jika terdapat kendala yang terjadi kedepannya
                                    </p>
                                    <p>Mohon kembalikan barang
                                        <span class="fw-bolder text-danger">maksimal h+1 dari tanggal selesai</span>. Jika
                                        akhir sewa adalah tanggal 15, maka paling lambat dikembalikan tanggal 16
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    Klik diluar popup ini untuk menutup
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('layout.footer')

    </section>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modalAuto = new bootstrap.Modal(document.getElementById('modal_auto'));
            modalAuto.show();
        });
    </script>

    <script src="{{ asset('seller/modal_auto_muncul.js') }}"></script>

@endsection
