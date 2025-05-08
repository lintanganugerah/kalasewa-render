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
                                        class="no-more-tables table w-100 tabel-data table-responsive align-items-center"
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
                                                    <th>Additional</th>
                                                    <th>Nomor Resi</th>
                                                    <th>Bukti Penerimaan</th>
                                                    <th>Diterima Tanggal</th>
                                                    <th>Periode Sewa</th>
                                                    <th>Denda Keterlambatan</th>
                                                    <th>Harga Penyewaan</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($order as $ord)
                                                    <tr>
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
                                                        <td data-title="Nomor Resi" class="align-middle">
                                                            {{ $ord->nomor_resi }}
                                                        </td>
                                                        <td data-title="Bukti Penerimaan" class="align-middle">

                                                            <a data-bs-toggle="modal"
                                                                data-bs-target="#buktiPenyewaan-{{ $ord->nomor_order }}"
                                                                class="small text-danger text-link" id="proses">Lihat
                                                                Bukti Penerimaan</a>
                                                        </td>
                                                        <td data-title="Diterima" class="align-middle">
                                                            {{ $ord->tanggalFormatted($ord->tanggal_diterima) }}
                                                        </td>
                                                        <td data-title="Periode Sewa" class="align-middle">
                                                            {{ $ord->tanggalFormatted($ord->tanggal_mulai) }} <span
                                                                class="fw-bolder"> s.d. </span>
                                                            {{ $ord->tanggalFormatted($ord->tanggal_selesai) }}
                                                        </td>
                                                        <td data-title="Denda Keterlambatan" class="align-middle">
                                                            Rp. {{ number_format($ord->denda_keterlambatan, 0, '', '.') }}
                                                            @if ($ord->jaminan < 0)
                                                                <br>(Rp.
                                                                {{ number_format(abs($ord->jaminan), 0, '', '.') }}
                                                                belum lunas)
                                                            @elseif($ord->jaminan == 0)
                                                                (Lunas)
                                                            @endif
                                                        </td>
                                                        <td data-title="Harga Penyewaan" class="align-middle">
                                                            Rp {{ number_format($ord->total_harga, 0, '', '.') }}
                                                        </td>
                                                        <td data-title="Aksi" class="align-middle">
                                                            <a href="{{ url('/chat' . '/' . $ord->id_penyewa_order->id) }}"
                                                                target="_blank" class="d-grid btn btn-outline-success m-2"
                                                                id="proses">Chat</a>
                                                        </td>
                                                    </tr>
                                                    <div class="modal fade" id="buktiPenyewaan-{{ $ord->nomor_order }}"
                                                        tabindex="-1" aria-labelledby="inputResi-1Label"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h1 class="modal-title fs-5" id="inputResi-1Label">
                                                                        Bukti Barang
                                                                        Diterima</h1>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="product-image-container-review">
                                                                        @if ($ord->bukti_penerimaan)
                                                                            <img src="{{ asset($ord->bukti_penerimaan) }}"
                                                                                alt="Produk" class="product-image-review">
                                                                        @else
                                                                            Tidak ada foto bukti
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Tutup</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
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
<link type="text/css" href="{{ asset('seller/review.css') }}" rel="stylesheet">
