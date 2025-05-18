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
                            <a class="nav-link active" href="{{ route('viewHistoryDibatalkan') }}">
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
                                            <td class="text-danger">{{ $order->alasan_pembatalan }}</td>
                                            <td>
                                                @if ($order->status == 'Dibatalkan Pemilik Sewa')
                                                    <div class="alert alert-warning mb-0 py-2">
                                                        Dibatalkan Toko, Silahkan cek <a
                                                            href="{{ route('viewPenarikan') }}" class="alert-link">halaman
                                                            penarikan saldo</a> untuk
                                                        melakukan penarikan
                                                    </div>
                                                @elseif ($order->status == 'Refund di Ajukan')
                                                    <div class="alert alert-info mb-0 py-2">
                                                        Silahkan cek <a href="{{ route('viewPenarikan') }}"
                                                            class="alert-link">halaman penarikan saldo</a> untuk
                                                        melakukan penarikan
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>

                                        <!-- Refund Modal -->
                                        <div class="modal fade" id="refundModal-{{ $loop->iteration }}" tabindex="-1"
                                            aria-labelledby="refundModalLabel-{{ $loop->iteration }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="refundModalLabel-{{ $loop->iteration }}">
                                                            Ajukan Refund
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <form
                                                        action="{{ route('setRekening', ['orderId' => $order->nomor_order]) }}"
                                                        method="post">
                                                        @csrf
                                                        <div class="modal-body">
                                                            @if ($saldos->nomor_rekening ?? false)
                                                            @else
                                                                <div class="mb-3">
                                                                    <label class="form-label">Bank/E-Wallet<span
                                                                            class="text-danger">*</span></label>
                                                                    <select class="form-select form-select-lg"
                                                                        id="selectRekening" name="tujuan_rek" required>
                                                                        <option></option>
                                                                        @foreach ($rekenings as $rekening)
                                                                            <option value="{{ $rekening->id }}">
                                                                                {{ $rekening->nama }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div class="form-text">Silahkan pilih Bank atau E-Wallet
                                                                        yang anda gunakan</div>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label class="form-label">Nomor Rekening
                                                                        Bank/E-Wallet<span
                                                                            class="text-danger">*</span></label>
                                                                    <input class="form-control form-control-lg"
                                                                        type="number" name="nomor_rekening" required>
                                                                    <div class="form-text">Silahkan masukkan nomor
                                                                        rekening/e-wallet anda</div>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label class="form-label">Atas Nama<span
                                                                            class="text-danger">*</span></label>
                                                                    <input class="form-control form-control-lg"
                                                                        type="text" name="nama_rekening" required>
                                                                    <div class="form-text">Silahkan masukkan nama yang
                                                                        terdaftar di Rekening/E-Wallet anda</div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Tutup</button>
                                                            <button type="submit" class="btn btn-primary">Submit</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
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
