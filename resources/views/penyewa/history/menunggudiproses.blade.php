@extends('layout.template')
@extends('layout.navbar')

@section('content')
    <div class="container-fluid py-5">
        <div class="container">
            <!-- Header Section -->
            <div class="row mb-4">
                <div class="col-12 text-center">
                    <h1 class="display-4 fw-bold">History Penyewaan</h1>
                </div>
            </div>

            <!-- Alert Section -->
            @csrf
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Navigation Tabs -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <ul class="nav nav-tabs card-header-tabs" id="historyTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('viewHistoryMenungguDiproses') }}">
                                Menunggu Konfirmasi
                                @if ($countMenungguDiproses)
                                    <span class="badge rounded-pill bg-danger">{{ $countMenungguDiproses }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-secondary" href="{{ route('viewHistoryDalamPengiriman') }}">
                                Dalam Pengiriman
                                @if ($countDalamPengiriman)
                                    <span class="badge rounded-pill bg-danger">{{ $countDalamPengiriman }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-secondary" href="{{ route('viewHistorySedangBerlangsung') }}">
                                Sedang Digunakan
                                @if ($countSedangBerlangsung)
                                    <span class="badge rounded-pill bg-danger">{{ $countSedangBerlangsung }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-secondary" href="{{ route('viewHistoryTelahKembali') }}">
                                Dikirim Kembali
                                @if ($countTelahKembali)
                                    <span class="badge rounded-pill bg-danger">{{ $countTelahKembali }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-secondary" href="{{ route('viewHistoryDibatalkan') }}">
                                Dibatalkan
                                @if ($countDibatalkan)
                                    <span class="badge rounded-pill bg-danger">{{ $countDibatalkan }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-secondary" href="{{ route('viewHistoryDiretur') }}">
                                Diretur
                                @if ($countDiretur)
                                    <span class="badge rounded-pill bg-danger">{{ $countDiretur }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-secondary" href="{{ route('viewHistoryPenyewaanSelesai') }}">
                                Penyewaan Selesai
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    @if ($orders)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="table-history">
                                <thead class="table-light">
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
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        @if ($order->status == 'Menunggu di Proses' || $order->status == 'Pending')
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>{{ $order->nomor_order }}</td>
                                                <td>{{ $order->nama_produk }}</td>
                                                <td>
                                                    @if (!empty($order->additional) && is_array($order->additional))
                                                        @foreach ($order->additional as $additionalItem)
                                                            @if (is_array($additionalItem) && isset($additionalItem['nama']))
                                                                <span
                                                                    class="badge bg-secondary me-1">{{ $additionalItem['nama'] }}</span>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>{{ $order->nama_toko }}</td>
                                                <td>{{ $order->tanggal_mulai }}</td>
                                                <td>{{ $order->tanggal_selesai }}</td>
                                                <td>Rp{{ number_format($order->grand_total, 0, '', '.') }}</td>
                                                <td>
                                                    @if ($order->status == 'Menunggu di Proses')
                                                        <span class="badge bg-warning">Menunggu Konfirmasi Toko</span>
                                                    @elseif ($order->status == 'Pending')
                                                        <span class="badge bg-info">Menunggu Pembayaran</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-grid gap-2">
                                                        @if ($order->status == 'Menunggu di Proses')
                                                            <button class="btn btn-danger btn-sm" disabled>Detail</button>
                                                        @elseif ($order->status == 'Pending')
                                                            <a href="{{ route('viewCheckout') }}"
                                                                class="btn btn-danger btn-sm">Bayar</a>
                                                        @endif
                                                        <a href="{{ url('/chat' . '/' . $order->toko->id_user) }}"
                                                            target="_blank" class="btn btn-outline-success btn-sm">Chat
                                                            Toko</a>
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Modal for Resi -->
                                            <div class="modal fade" id="resiModal-{{ $loop->iteration }}" tabindex="-1"
                                                aria-labelledby="resiModalLabel-{{ $loop->iteration }}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="resiModalLabel-{{ $loop->iteration }}">Bukti Resi</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <img src="{{ asset($order->bukti_resi) }}" alt="Resi"
                                                                class="img-fluid rounded shadow-sm">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Warning Modal -->
            <div class="modal fade" id="modal_auto" tabindex="-1" aria-labelledby="exampleModalCenterTitle"
                aria-modal="true" role="dialog">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold" id="exampleModalCenterTitle">Mohon Perhatian</h5>
                        </div>
                        <div class="modal-body">
                            <p>Harap <span class="fw-bold text-danger">rekam video saat unboxing atau menerima
                                    barang</span> sebagai bukti jika terdapat kendala yang terjadi kedepannya</p>
                            <p>Mohon kembalikan barang <span class="fw-bold text-danger">maksimal h+1 dari tanggal
                                    selesai</span>. Jika akhir sewa adalah tanggal 15, maka paling lambat dikembalikan
                                tanggal 16</p>
                        </div>
                        <div class="modal-footer">
                            <small class="text-muted">Klik diluar popup ini untuk menutup</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layout.footer')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modalAuto = new bootstrap.Modal(document.getElementById('modal_auto'));
            modalAuto.show();
        });
    </script>

    <script src="{{ asset('seller/modal_auto_muncul.js') }}"></script>
@endsection
