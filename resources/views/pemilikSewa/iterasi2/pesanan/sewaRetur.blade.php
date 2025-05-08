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
                                    <table id="tabel" class="no-more-tables table w-100 tabel-data align-items-center"
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
                                                    <th>Harga Penyewaan</th>
                                                    <th>Nomor Resi Pengembalian</th>
                                                    <th>Dikembalikan Tanggal</th>
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
                                                        <td data-title="Harga Penyewaan" class="align-middle">
                                                            Rp {{ number_format($ord->total_harga, 0, '', '.') }}</td>
                                                        <td data-title="Nomor Resi Pengiriman" class="align-middle">
                                                            {{ $ord->status == 'Retur dalam Pengiriman' ? $ord->nomor_resi : 'Belum ada' }}
                                                        </td>
                                                        <td data-title="Dikembalikan" class="align-middle">
                                                            {{ !$ord->tanggal_pengembalian ? 'Belum ada' : $ord->tanggalFormatted($ord->tanggal_pengembalian) }}
                                                        </td>
                                                        <td data-title="aksi" class="align-middle">
                                                            @if ($ord->status == 'Retur dalam Pengiriman')
                                                                <a data-bs-toggle="modal"
                                                                    data-bs-target="#selesaikanPenyewaan-1"
                                                                    class="d-grid btn btn-success mb-2"
                                                                    id="proses">Konfirmasi
                                                                    Diterima</a>
                                                            @elseif($ord->status == 'Retur')
                                                                <p> Retur menunggu diproses oleh admin. Anda akan dikontak
                                                                    admin mengenai retur ini melalui whatsapp</p>
                                                            @elseif($ord->status == 'Retur Dikonfirmasi')
                                                                <p> Retur telah dikonfirmasi. Menunggu dikirimkan
                                                                    oleh penyewa</p>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @if ($ord->status == 'Retur dalam Pengiriman')
                                                        <!-- Modal Konfirmasi Sewa -->
                                                        <div class="modal fade" id="selesaikanPenyewaan-1" tabindex="-1"
                                                            aria-labelledby="inputResi-1Label" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered">

                                                                <form
                                                                    action="{{ route('seller.statuspenyewaan.returSelesai', ['nomor_order' => $ord->nomor_order]) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h1 class="modal-title fs-5"
                                                                                id="inputResi-1Label">
                                                                                Konfirmasi Barang
                                                                                Diterima</h1>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <p for="exampleFormControlInput1"
                                                                                class="form-label">Nomor Order :
                                                                                {{ $ord->nomor_order }}</p>
                                                                            <p for="exampleFormControlInput1"
                                                                                class="form-label">Produk :
                                                                                {{ $ord->id_produk_order->nama_produk }}
                                                                            </p>
                                                                            <small class="fw-light mb-3" href=""
                                                                                style="font-size:14px">{{ $ord->id_produk_order->brand }},
                                                                                Ukuran
                                                                                {{ $ord->id_produk_order->ukuran_produk }}</small>
                                                                            <hr>
                                                                            <p class="mt-2">
                                                                                Setelah konfirmasi barang retur diterima,
                                                                                status
                                                                                produk akan
                                                                                menjadi "arsip". <span
                                                                                    class="fw-bold text-danger">Mohon
                                                                                    aktifkan
                                                                                    Produk secara manual pada
                                                                                    menu produk </span> agar bisa disewa
                                                                                kembali
                                                                                ketika sudah ready. </p>
                                                                            </p>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary"
                                                                                data-bs-dismiss="modal">Tutup</button>
                                                                            <button type="submit"
                                                                                class="btn btn-kalasewa">Submit</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    @endif
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
