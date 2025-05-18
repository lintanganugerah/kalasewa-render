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
                            <a class="nav-link active" href="{{ route('viewHistoryDiretur') }}">
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
                                        @if ($order->status == 'Retur' or $order->status == 'Retur Dikonfirmasi' or $order->status == 'Retur dalam Pengiriman')
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
                                                    @if ($order->status == 'Retur')
                                                        <span class="badge bg-warning">Sedang di review Admin dan
                                                            Toko</span>
                                                    @elseif ($order->status == 'Retur Dikonfirmasi')
                                                        <span class="badge bg-info">Retur di Konfirmasi</span>
                                                    @elseif ($order->status == 'Retur dalam Pengiriman')
                                                        <span class="badge bg-primary">Retur Dalam Pengiriman</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($order->status == 'Retur')
                                                        <button class="btn btn-danger btn-sm w-100" disabled>Retur
                                                            Barang</button>
                                                    @elseif ($order->status == 'Retur Dikonfirmasi')
                                                        <button type="button" class="btn btn-danger btn-sm w-100"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#refundModal-{{ $loop->iteration }}">
                                                            Retur Barang
                                                        </button>
                                                    @elseif ($order->status == 'Retur dalam Pengiriman')
                                                        <button class="btn btn-danger btn-sm w-100" disabled>Retur
                                                            Barang</button>
                                                    @endif
                                                </td>
                                            </tr>

                                            <!-- Modal for Resi -->
                                            <div class="modal fade" id="resiModal-{{ $loop->iteration }}" tabindex="-1"
                                                aria-labelledby="resiModalLabel-{{ $loop->iteration }}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="resiModalLabel-{{ $loop->iteration }}">
                                                                Bukti Resi
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            @if ($order->status == 'Retur')
                                                                <p class="mb-2">Nomor Resi:
                                                                    <strong>{{ $order->nomor_resi }}</strong>
                                                                </p>
                                                                <img src="{{ asset($order->bukti_resi) }}" alt="Resi"
                                                                    class="img-fluid rounded shadow-sm">
                                                            @elseif ($order->status == 'Retur Dikonfirmasi')
                                                                <p class="mb-2">Nomor Resi:
                                                                    <strong>{{ $order->nomor_resi }}</strong>
                                                                </p>
                                                                <img src="{{ asset($order->bukti_resi) }}" alt="Resi"
                                                                    class="img-fluid rounded shadow-sm">
                                                            @elseif ($order->status == 'Retur dalam Pengiriman')
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

                                            <!-- Refund Modal -->
                                            <div class="modal fade" id="refundModal-{{ $loop->iteration }}"
                                                tabindex="-1" aria-labelledby="refundModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="refundModalLabel">Retur Barang
                                                            </h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form
                                                            action="{{ route('returBarangRefund', ['orderId' => $order->nomor_order]) }}"
                                                            method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Alamat Pengembalian</label>
                                                                    <textarea name="alamatpengembalian" class="form-control" readonly>{{ $order->id_produk_order->getalamatproduk($order->id_produk_order->alamat, $order->id_produk_order->toko->id_user) }}</textarea>
                                                                    <div class="form-text">Silahkan lakukan pengiriman
                                                                        kembali ke alamat yang tertera!</div>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Nomor Resi<span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="text" name="nomor_resi"
                                                                        class="form-control" placeholder="Nomor Resi"
                                                                        required>
                                                                    <div class="form-text">Nomor resi pengiriman untuk
                                                                        pelacakan</div>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Bukti Resi / Pengiriman<span
                                                                            class="text-danger">*</span></label>
                                                                    <input class="form-control" type="file"
                                                                        name="bukti_resi_penyewa"
                                                                        accept=".jpg,.png,.jpeg,.webp" required>
                                                                    <div class="form-text">Silahkan berikan bukti gambar
                                                                        resi atau screenshot pengiriman barang!</div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Tutup</button>
                                                                <button type="submit"
                                                                    class="btn btn-primary">Kirim</button>
                                                            </div>
                                                        </form>
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
