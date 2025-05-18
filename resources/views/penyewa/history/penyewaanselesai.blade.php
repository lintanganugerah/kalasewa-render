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
                            <a class="nav-link text-secondary" href="{{ route('viewHistoryMenungguDiproses') }}">
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
                            <a class="nav-link active" href="{{ route('viewHistoryPenyewaanSelesai') }}">
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
                                        <th>Tanggal Mulai</th>
                                        <th>Tanggal Selesai</th>
                                        <th>Total Biaya</th>
                                        <th>Resi</th>
                                        <th>Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        @if ($order->status == 'Penyewaan Selesai' or $order->status == 'Retur Selesai')
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
                                                <td>{{ $order->tanggal_mulai }}</td>
                                                <td>{{ $order->tanggal_selesai }}</td>
                                                <td>Rp{{ number_format($order->grand_total, 0, '', '.') }}</td>
                                                <td>
                                                    @if ($order->bukti_resi)
                                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#resiModal-{{ $loop->iteration }}">
                                                            Lihat Resi
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                                            disabled>
                                                            Lihat Resi
                                                        </button>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($order->status == 'Penyewaan Selesai')
                                                        <span class="badge bg-success">Penyewaan Selesai</span>
                                                    @elseif ($order->status == 'Retur Selesai')
                                                        <span class="badge bg-info">Retur Selesai</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-grid gap-2">
                                                        <a class="btn btn-danger btn-sm"
                                                            href="{{ route('viewPenyewaanSelesai', ['orderId' => $order->nomor_order]) }}">Detail</a>
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
                                                            @if ($order->status == 'Penyewaan Selesai')
                                                                <p class="mb-2">Nomor Resi:
                                                                    <strong>{{ $order->nomor_resi }}</strong>
                                                                </p>
                                                                <img src="{{ asset($order->bukti_resi) }}" alt="Resi"
                                                                    class="img-fluid rounded shadow-sm">
                                                            @elseif ($order->status == 'Retur Selesai')
                                                                <p class="mb-2">Nomor Resi:
                                                                    <strong>{{ $order->nomor_resi }}</strong>
                                                                </p>
                                                                <img src="{{ asset($order->bukti_resi_pengembalian) }}"
                                                                    alt="Resi" class="img-fluid rounded shadow-sm">
                                                            @endif
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
        </div>
    </div>

    @include('layout.footer')
@endsection
