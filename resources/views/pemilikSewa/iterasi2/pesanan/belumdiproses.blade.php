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
                                    <div class="table-responsive">
                                        <table id="tabel"
                                            class="no-more-tables table w-100 tabel-data align-items-center"
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
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($order as $ord)
                                                        <tr>
                                                            <td data-title="#" class="align-middle">
                                                                {{ $loop->iteration }}
                                                            </td>
                                                            <td data-title="Tanggal Transaksi" class="align-middle">
                                                                {{ $ord->tanggalFormatted($ord->created_at) }}
                                                            </td>
                                                            <td data-title="No. Order" class="align-middle">
                                                                {{ $ord->nomor_order }}
                                                            </td>
                                                            <td data-title="Produk" class="align-middle">
                                                                <h5 class="">{{ $ord->id_produk_order->nama_produk }}
                                                                </h5>
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
                                                                {{ $ord->id_produk_order->ukuran_produk }}
                                                            </td>
                                                            <td data-title="Kurir" class="align-middle">
                                                                {{ $ord->metode_kirim }}
                                                            </td>
                                                            <td data-title="Tujuan Pengiriman" class="align-middle">
                                                                {{ $ord->tujuan_pengiriman }}
                                                            </td>
                                                            <td data-title="Additional"
                                                                class="align-middle text-opacity-75">
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
                                                            <td data-title="Total Harga" class="align-middle">
                                                                Rp {{ number_format($ord->total_harga, 0, '', '.') }}</td>
                                                            <td data-title="aksi" class="align-middle">
                                                                <a type="button" data-bs-toggle="modal"
                                                                    data-bs-target="#inputResi-{{ $ord->nomor_order }}"
                                                                    class="d-grid btn btn-success m-2"
                                                                    id="proses-{{ $ord->nomor_order }}">Input
                                                                    Resi</a>
                                                                <a href="{{ url('/chat' . '/' . $ord->id_penyewa_order->id) }}"
                                                                    target="_blank"
                                                                    class="d-grid btn btn-outline-success m-2"
                                                                    id="proses">Chat</a>
                                                                <a class="d-grid btn btn-outline-danger m-2"
                                                                    id="aksi-batalkan-{{ $ord->nomor_order }}"
                                                                    type="button" data-bs-toggle="modal"
                                                                    data-bs-target="#batalkan-{{ $ord->nomor_order }}">Batalkan</button>
                                                            </td>
                                                        </tr>
                                                        <!-- Modal Input Resi -->
                                                        <div class="modal fade" id="inputResi-{{ $ord->nomor_order }}"
                                                            tabindex="-1"
                                                            aria-labelledby="inputResi-{{ $ord->nomor_order }}Label"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <form
                                                                    action="{{ route('seller.statuspenyewaan.inputResi', $ord->nomor_order) }}"
                                                                    method="POST" enctype="multipart/form-data">
                                                                    @csrf
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h1 class="modal-title fs-5"
                                                                                id="inputResi-{{ $ord->nomor_order }}Label">
                                                                                Input Resi</h1>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="m-2">
                                                                                <p for="exampleFormControlInput1"
                                                                                    class="form-label">
                                                                                    Nomor Order :
                                                                                    {{ $ord->nomor_order }}
                                                                                </p>
                                                                                <p for="exampleFormControlInput1"
                                                                                    class="form-label">
                                                                                    Produk :
                                                                                    {{ $ord->id_produk_order->nama_produk }}
                                                                                </p>
                                                                                <small class="fw-light mb-3"
                                                                                    style="font-size:14px">{{ $ord->id_produk_order->brand }},
                                                                                    Rp.{{ number_format($ord->id_produk_order->harga) }}/3hari,
                                                                                    Ukuran
                                                                                    {{ $ord->id_produk_order->ukuran_produk }}</small>
                                                                                <hr
                                                                                    class="border border-secondary opacity-50 my-4">
                                                                                <label for="inputResi"
                                                                                    class="form-label">Nomor
                                                                                    Resi<span
                                                                                        class="text-danger">*</span></label>
                                                                                <input type="text"
                                                                                    class="form-control mb-3"
                                                                                    name="nomor_resi" id="inputResi"
                                                                                    required>
                                                                                <label for="inputResi"
                                                                                    class="form-label">Foto Bukti
                                                                                    Resi<span
                                                                                        class="text-danger">*</span></label>
                                                                                <input type="file"
                                                                                    class="form-control mb-3"
                                                                                    name="foto_bukti_resi" id="fotoresi"
                                                                                    accept=".jpg,.png,.jpeg,.webp"
                                                                                    required>
                                                                                <label for="fotoresi"
                                                                                    class="form-label">Ongkir<span
                                                                                        class="text-danger">*</span></label>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-text"
                                                                                        id="basic-addon1">Rp</span>
                                                                                    <input type="number"
                                                                                        class="form-control"
                                                                                        name="biaya_pengiriman"
                                                                                        id="biaya_pengiriman"
                                                                                        placeholder="Masukkan biaya ongkir yang anda bayar"
                                                                                        required>
                                                                                </div>
                                                                            </div>
                                                                            <p class="m-2 mt-2">Setelah submit nomor resi
                                                                                maka
                                                                                status akan berubah
                                                                                menjadi
                                                                                pengiriman.
                                                                                Pastikan bahwa nomor resi telah sesuai
                                                                            </p>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                class="btn btn-secondary"
                                                                                data-bs-dismiss="modal">Tutup</button>
                                                                            <button type="submit"
                                                                                class="btn btn-kalasewa">Submit</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <!-- Modal Batalkan -->
                                                        <div class="modal fade" id="batalkan-{{ $ord->nomor_order }}"
                                                            tabindex="-1"
                                                            aria-labelledby="batalkan-{{ $ord->nomor_order }}Label"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <form
                                                                    action="{{ route('seller.statuspenyewaan.dibatalkanPemilikSewa', $ord->nomor_order) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h1 class="modal-title fs-5"
                                                                                id="batalkan-{{ $ord->nomor_order }}Label">
                                                                                Batalkan</h1>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="m-2">
                                                                                <p class="mt-2 fw-bold">Anda akan
                                                                                    membatalkan Penyewaan berikut :
                                                                                </p>
                                                                                <p for="exampleFormControlInput1"
                                                                                    class="form-label">
                                                                                    Nomor Order :
                                                                                    {{ $ord->nomor_order }}
                                                                                </p>
                                                                                <p for="exampleFormControlInput1"
                                                                                    class="form-label">
                                                                                    Produk :
                                                                                    {{ $ord->id_produk_order->nama_produk }}
                                                                                </p>
                                                                                <small class="fw-light mb-3"
                                                                                    href=""
                                                                                    style="font-size:14px">{{ $ord->id_produk_order->brand }},
                                                                                    Rp.{{ number_format($ord->id_produk_order->harga) }}/3hari,
                                                                                    Ukuran
                                                                                    {{ $ord->id_produk_order->ukuran_produk }}</small>
                                                                                <hr
                                                                                    class="border border-secondary opacity-50 my-4">
                                                                                <label for="alasan_batal"
                                                                                    class="form-label">Alasan
                                                                                    Pembatalan</label>
                                                                                <textarea type="text" class="form-control" name="alasan_batal" id="alasan_batal" required> </textarea>
                                                                            </div>
                                                                            <small class="m-2 mt-2">Pastikan anda telah
                                                                                berdiskusi
                                                                                dengan user terkait pembatalan penyewaan ini
                                                                            </small>
                                                                            <hr
                                                                                class="border border-secondary opacity-50 my-4">
                                                                            <p class="m-2 mt-2">Jika produk telah anda
                                                                                batalkan, status produk akan menjadi
                                                                                "arsip".
                                                                                <span class="fw-bold text-danger">Mohon
                                                                                    aktifkan
                                                                                    Produk secara manual pada
                                                                                    menu produk </span> agar bisa disewa
                                                                                kembali
                                                                                ketika sudah ready
                                                                            </p>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                class="btn btn-secondary"
                                                                                data-bs-dismiss="modal">Tutup</button>
                                                                            <button type="submit"
                                                                                class="btn btn-kalasewa">Submit</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </tbody>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                                {{-- Modal Peringatan --}}
                                <div class="modal" id="modal_auto" tabindex="-1"
                                    aria-labelledby="exampleModalCenterTitle" aria-modal="true" role="dialog">
                                    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5 fw-bolder" id="exampleModalCenterTitle">
                                                    Mohon Perhatian</h1>
                                            </div>
                                            <div class="modal-body">
                                                <p>Harap
                                                    <span class="fw-bolder text-danger">rekam video saat pengemasan sebelum
                                                        mengirimkan barang </span> sebagai bukti jika barang
                                                    bermasalah saat tiba di penyewa atau saat kembali lagi ke anda
                                                </p>
                                                <p>Mohon kirimkan barang
                                                    <span class="fw-bolder text-danger">sebelum awal mulai periode
                                                        sewa</span>.
                                                    Jika awal mulai sewa adalah 15, maka kirimkan tanggal 14
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                Klik diluar popup ini untuk menutup
                                            </div>
                                        </div>
                                    </div>
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
        });
    </script>
    <script src="{{ asset('seller/modal_auto_muncul.js') }}"></script>
    <script src="{{ asset('seller/inputangka.js') }}"></script>
    <script src="{{ asset('seller/inputfotoproduk.js') }}"></script>
@endsection
