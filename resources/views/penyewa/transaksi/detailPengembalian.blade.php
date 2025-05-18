@extends('layout.template')
@extends('layout.navbar')

@section('content')

    <section class="py-5">
        <div class="container">
            <!-- Header Section -->
            <div class="row mb-4">
                <div class="col-12 text-center">
                    <h1 class="display-4 fw-bold">Detail Pengembalian</h1>
                </div>
            </div>

            <!-- Alert Section -->
            <div class="row mb-4">
                <div class="col-12">
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
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

            <!-- Main Content -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <!-- Order Info -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5 class="card-title mb-3">Informasi Pesanan</h5>
                                    <p class="mb-1"><strong>Nomor Order:</strong> {{ $pengembalian->nomor_order }}</p>
                                    <p class="mb-1"><strong>Tanggal Pengembalian:</strong>
                                        {{ \Carbon\Carbon::parse($pengembalian->created_at)->format('d M Y H:i') }}</p>
                                    <p class="mb-1"><strong>Status:</strong>
                                        <span
                                            class="badge bg-{{ $pengembalian->status == 'pending' ? 'warning' : ($pengembalian->status == 'success' ? 'success' : 'danger') }}">
                                            {{ ucfirst($pengembalian->status) }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <h5 class="card-title mb-3">Total Pengembalian</h5>
                                    <h2 class="text-primary fw-bold">
                                        Rp{{ number_format($pengembalian->total_pengembalian, 0, '', '.') }}</h2>
                                </div>
                            </div>

                            <!-- Product Details -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="card-title mb-3">Detail Produk</h5>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Produk</th>
                                                    <th>Ukuran</th>
                                                    <th>Kondisi</th>
                                                    <th>Catatan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ asset($pengembalian->produk->fotoProduk->path) }}"
                                                                alt="{{ $pengembalian->produk->nama_produk }}"
                                                                class="img-thumbnail me-3" style="width: 80px;">
                                                            <div>
                                                                <h6 class="mb-0">{{ $pengembalian->produk->nama_produk }}
                                                                </h6>
                                                                @if (!empty($pengembalian->additional))
                                                                    <small class="text-muted">
                                                                        Additional:
                                                                        @foreach ($pengembalian->additional as $additional)
                                                                            {{ $additional['nama'] }}{{ !$loop->last ? ', ' : '' }}
                                                                        @endforeach
                                                                    </small>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $pengembalian->ukuran }}</td>
                                                    <td>
                                                        <span
                                                            class="badge bg-{{ $pengembalian->kondisi == 'baik' ? 'success' : 'danger' }}">
                                                            {{ ucfirst($pengembalian->kondisi) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $pengembalian->catatan ?? '-' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Return Details -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5 class="card-title mb-3">Detail Pengembalian</h5>
                                    <p class="mb-1"><strong>Tanggal Sewa:</strong>
                                        {{ \Carbon\Carbon::parse($pengembalian->tanggal_sewa)->format('d M Y') }}</p>
                                    <p class="mb-1"><strong>Tanggal Kembali:</strong>
                                        {{ \Carbon\Carbon::parse($pengembalian->tanggal_kembali)->format('d M Y') }}</p>
                                    <p class="mb-1"><strong>Durasi Sewa:</strong> {{ $pengembalian->durasi_sewa }} hari
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="card-title mb-3">Biaya Tambahan</h5>
                                    <p class="mb-1"><strong>Denda Keterlambatan:</strong>
                                        Rp{{ number_format($pengembalian->denda_keterlambatan ?? 0, 0, '', '.') }}</p>
                                    <p class="mb-1"><strong>Biaya Perbaikan:</strong>
                                        Rp{{ number_format($pengembalian->biaya_perbaikan ?? 0, 0, '', '.') }}</p>
                                    <p class="mb-1"><strong>Biaya Pencucian:</strong>
                                        Rp{{ number_format($pengembalian->biaya_cuci ?? 0, 0, '', '.') }}</p>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <a href="{{ route('transaksi') }}"
                                            class="btn btn-outline-secondary btn-lg me-md-2">
                                            <i class="fas fa-arrow-left me-2"></i>Kembali
                                        </a>
                                        @if ($pengembalian->status == 'pending')
                                            <button type="button" class="btn btn-danger btn-lg" data-bs-toggle="modal"
                                                data-bs-target="#cancelModal">
                                                <i class="fas fa-times me-2"></i>Batalkan Pengembalian
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cancel Modal -->
        <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cancelModalLabel">Batalkan Pengembalian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('batalkanPengembalian', $pengembalian->id) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <p>Apakah Anda yakin ingin membatalkan pengembalian ini?</p>
                            <div class="mb-3">
                                <label for="alasan" class="form-label">Alasan Pembatalan</label>
                                <textarea class="form-control" id="alasan" name="alasan" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-danger">Batalkan Pengembalian</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    @include('layout.footer')

@endsection
