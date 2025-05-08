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
                    <h1><strong>Udah Rental Apa Aja Nih?</strong></h1>
                </div>
            </div>
        </div>

        <div class="button-content mt-5">
            <div class="container-fluid">
                <div class="container">
                    <a href="{{ route('viewHistory') }}" class="no-underline">
                        <button type="button" class="btn btn-outline-danger">Semua</button>
                    </a>
                    <a href="{{ route('viewHistoryOngoing') }}" class="no-underline">
                        <button type="button" class="btn btn-outline-danger">Sedang
                            Berlangsung</button>
                    </a>
                    <a href="{{ route('viewHistoryFinish') }}" class="no-underline">
                        <button type="button" class="btn btn-danger">Selesai</button>
                    </a>
                </div>
            </div>
        </div>

        <div class="table-content mt-5">
            <div class="container-fluid">
                <div class="container">
                    <table id="table-history">
                        <thead>
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
                                @if ($order->status == 'Penyewaan Selesai')
                                    <tr>
                                        <td>{{ $order->nomor_order }}</td>
                                        <td><img class="img-thumbnail" src="{{ asset($order->foto_produk) }}" alt="Produk"
                                                style="width: 150px; height: 150px;">
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
                                                    {{ $nama }}
                                                @endforeach
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $order->tanggal_mulai }}</td>
                                        <td>{{ $order->tanggal_selesai }}</td>
                                        <td>{{ $order->metode_kirim }}</td>
                                        <td>Rp{{ number_format($order->total_harga, 0, '', '.') }}</td>
                                        <td>{{ $order->status }}</td>
                                        <td>
                                            @if ($order->status == 'Sedang Berlangsung')
                                                <a class="btn btn-outline-danger w-100 mb-2"
                                                    href="{{ route('viewPengembalianBarang', ['orderId' => $order->nomor_order]) }}">Detail</a>
                                            @else
                                                <a class="btn btn-outline-danger w-100 mb-2"
                                                    href="{{ route('viewDetailPemesanan', ['orderId' => $order->nomor_order]) }}">Detail</a>
                                            @endif
                                            <a class="btn btn-danger w-100 mb-2" href="">Cetak</a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @include('layout.footer')

    </section>

@endsection
