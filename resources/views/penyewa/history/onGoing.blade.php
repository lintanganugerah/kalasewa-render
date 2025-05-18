@extends('layout.template')
@extends('layout.navbar')

@section('content')
    <div class="container-fluid py-5">
        <div class="container">
            <!-- Header Section -->
            <div class="row mb-4">
                <div class="col-12 text-center">
                    <h1 class="display-4 fw-bold">Udah Rental Apa Aja Nih?</h1>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="btn-group" role="group">
                        <a href="{{ route('viewHistory') }}" class="btn btn-outline-danger">Semua</a>
                        <a href="{{ route('viewHistoryOngoing') }}" class="btn btn-danger">Sedang Berlangsung</a>
                        <a href="{{ route('viewHistoryFinish') }}" class="btn btn-outline-danger">Selesai</a>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="table-history">
                            <thead class="table-light">
                                <tr>
                                    <th>Nomor Order</th>
                                    <th>Gambar</th>
                                    <th>Nama Produk</th>
                                    <th>Ukuran</th>
                                    <th>Additional</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Metode Kirim</th>
                                    <th>Total Biaya</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    @if ($order->status != 'Penyewaan Selesai')
                                        <tr>
                                            <td>{{ $order->nomor_order }}</td>
                                            <td>
                                                <img class="img-thumbnail" src="{{ asset($order->foto_produk) }}"
                                                    alt="Produk" style="width: 100px; height: 100px; object-fit: cover;">
                                            </td>
                                            <td>{{ $order->nama_produk }}</td>
                                            <td>{{ $order->ukuran }}</td>
                                            <td>
                                                @if ($order->additional)
                                                    @php
                                                        $additionalData = is_array($order->additional)
                                                            ? $order->additional
                                                            : json_decode($order->additional, true);
                                                    @endphp
                                                    @foreach ($additionalData as $nama => $harga)
                                                        <span class="badge bg-secondary me-1">{{ $nama }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>{{ $order->tanggal_mulai }}</td>
                                            <td>{{ $order->tanggal_selesai }}</td>
                                            <td>{{ $order->metode_kirim }}</td>
                                            <td>Rp{{ number_format($order->total_harga, 0, '', '.') }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ $order->status }}</span>
                                            </td>
                                            <td>
                                                <div class="d-grid gap-2">
                                                    @if ($order->status == 'Sedang Berlangsung')
                                                        <a class="btn btn-outline-danger btn-sm"
                                                            href="{{ route('viewPengembalianBarang', ['orderId' => $order->nomor_order]) }}">Detail</a>
                                                    @else
                                                        <a class="btn btn-outline-danger btn-sm"
                                                            href="{{ route('viewDetailPemesanan', ['orderId' => $order->nomor_order]) }}">Detail</a>
                                                    @endif
                                                    <a class="btn btn-danger btn-sm" href="">Cetak</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layout.footer')
@endsection
