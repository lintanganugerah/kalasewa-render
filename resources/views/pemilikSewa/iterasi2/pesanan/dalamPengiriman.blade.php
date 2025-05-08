@extends('layout.selllayout')
@section('content')
    <div class="row">
        <div class="col">
            <div class="text-left mb-5 mt-3">
                <h1 class="fw-bold text-secondary">Status Penyewaan</h1>
                <h4 class="fw-semibold text-secondary">Lihat, dan kelola Penyewaan anda disini</h4>
            </div>

            <div class="row gx-5">

                <div class="card">
                    <div class="card-header">
                        <!-- Nav tabs -->
                        @include('pemilikSewa.iterasi2.pesanan.navtabs')
                    </div>
                    <div class="card-body">
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane fade show active mt-3" id="Informasi" role="tabpanel"
                                aria-labelledby="Informasi-tab">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        {{ $errors->first() }}
                                    </div>
                                @endif
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
                                <div class="text-dark rounded-3">
                                    <table id="tabel"
                                        class="no-more-tables table w-100 tabel-data align-items-cente table-responsive"
                                        style="word-wrap: break-word;">
                                        @if ($order)
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Tanggal Transaksi</th>
                                                    <th>Nomor Order</th>
                                                    <th class="col-2">Produk</th>
                                                    <th>Penyewa</th>
                                                    <th>Ukuran</th>
                                                    <th>Kurir</th>
                                                    <th class="col-3">Tujuan Pengiriman</th>
                                                    <th>Additional</th>
                                                    <th>Periode Sewa</th>
                                                    <th>Harga Penyewaan</th>
                                                    <th>Nomor Resi</th>
                                                    <th class="col-3">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($order as $ord)
                                                    <tr class="mb-5">
                                                        <td data-title="#" class="align-middle">
                                                            {{ $loop->iteration }}</td>
                                                        <td data-title="Tanggal Transaksi" class="align-middle">
                                                            {{ $ord->tanggalFormatted($ord->created_at) }}
                                                        </td>
                                                        <td data-title="No. Order" class="align-middle">
                                                            {{ $ord->nomor_order }}</td>
                                                        <td data-title="Produk" class="align-middle">
                                                            <h5 class="">{{ $ord->id_produk_order->nama_produk }}</h5>
                                                            <small class="fw-light" href=""
                                                                style="font-size:14px">{{ $ord->id_produk_order->brand }},
                                                                Rp.{{ number_format($ord->id_produk_order->harga) }}/3hari</small>
                                                        </td>
                                                        <td data-title="Nama Penyewa" class="align-middle">
                                                            <h5>{{ $ord->id_penyewa_order->nama }}</h5>
                                                            <a class="fw-light"
                                                                href="{{ route('seller.view.penilaian.reviewPenyewa', $ord->id_penyewa_order->id) }}"
                                                                style="font-size:14px">Lihat Review
                                                                Penyewa</a>
                                                        </td>
                                                        <td data-title="Ukuran" class="align-middle">
                                                            {{ $ord->id_produk_order->ukuran_produk }}</td>
                                                        <td data-title="Kurir" class="align-middle">
                                                            {{ $ord->metode_kirim }}</td>
                                                        <td data-title="Tujuan Pengiriman" class="align-middle">
                                                            {{ $ord->tujuan_pengiriman }}
                                                        </td>
                                                        <td data-title="Additional" class="align-middle text-opacity-75">
                                                            @if ($ord->additional)
                                                                <ul>
                                                                    @foreach ($ord->additional as $add)
                                                                        <li>{{ $add['nama'] }} +
                                                                            {{ number_format($add['harga'], 0, '', '.') }}
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            @else
                                                                <div class="text-opacity-25">-</div>
                                                            @endif
                                                            @if ($ord->id_produk_order->biaya_cuci)
                                                                <ul>
                                                                    <li>Biaya cuci +
                                                                        {{ number_format($ord->id_produk_order->biaya_cuci, 0, '', '.') }}
                                                                    </li>
                                                                </ul>
                                                            @endif
                                                        </td>
                                                        <td data-title="Periode Sewa" class="align-middle">
                                                            {{ $ord->tanggalFormatted($ord->tanggal_mulai) }} <span
                                                                class="fw-bolder"> s.d. </span>
                                                            {{ $ord->tanggalFormatted($ord->tanggal_selesai) }}
                                                        </td>
                                                        <td data-title="Harga Penyewaan" class="align-middle">
                                                            Rp {{ number_format($ord->total_harga, 0, '', '.') }}</td>
                                                        <td data-title="Nomor Resi" class="align-middle">
                                                            {{ $ord->nomor_resi }}
                                                        </td>
                                                        <td data-title="Tujuan Pengiriman" class="align-middle">
                                                            Tunggu hingga penyewa mengonfirmasi bahwa barang telah diterima.
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('.tabel-data').DataTable({
            lengthMenu: [
                [5, 10, 25, -1],
                [5, 10, 25, 'All']
            ],
            // fixedHeader: true,
            // order: [
            //     [6, 'asc']
            // ],
            // rowGroup: {
            //     dataSrc: 6
            // }
        });
    </script>
    <script src="{{ asset('seller/modal_auto_muncul.js') }}"></script>
    <script src="{{ asset('seller/inputangka.js') }}"></script>
    <script src="{{ asset('seller/inputfotoproduk.js') }}"></script>
@endsection
