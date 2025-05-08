@extends('layout.selllayout')
@section('content')
    <div class="row">
        <div class="col">
            <div class="text-left mb-5 mt-3">
                <h1 class="fw-bold text-secondary">Produk</h1>
                <h4 class="fw-semibold text-secondary">Manajemen Produk Anda disini</h4>
            </div>

            <div class="row gx-5">

                <div class="card">
                    <div class="card-header">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                            <li class="nav-item fw-bold" role="presentation">
                                <button class="nav-link active text-secondary fw-bold" id="Produkanda-tab"
                                    data-bs-toggle="tab" onclick="window.location.href='/produk/produkanda'" type="button"
                                    role="tab" aria-controls="Produkanda" aria-selected="true">Produk
                                    Anda</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link text-secondary" id="tambahProduk-tab"
                                    onclick="window.location.href='/produk/tambahproduk'" type="button" role="tab"
                                    aria-controls="tambahProduk" aria-selected="false">Tambah
                                    Produk</button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="Informasi" role="tabpanel"
                                aria-labelledby="Informasi-tab">
                                <h5 class="card-title">Produk Aktif</h5>
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
                                <div class="row row-cols-1 row-cols-md-4 g-4">
                                    @foreach ($produk as $prod)
                                        @if ($prod->status_produk == 'aktif')
                                            <div class="col mb-4">
                                                <div class="card h-100">
                                                    <div class="p-3">
                                                        <div class="image-container">
                                                            <img src="{{ asset($prod->getFotoProdukFirst($prod->id)->path) }}"
                                                                class="card-img-top" alt="...">
                                                        </div>
                                                        <div class="product-details mt-3">
                                                            <h5 class="card-title fw-bolder fs-5">{{ $prod->nama_produk }}
                                                            </h5>
                                                            <p class="cut-text fw-bold fs-6">Rp
                                                                {{ number_format($prod->harga, 0, '', '.') }} / 3 hari</p>
                                                            <p class="cut-text">{{ $prod->brand }}, {{ $prod->gender }},
                                                                @foreach ($series as $sr)
                                                                    @if ($sr->id == $prod->id_series)
                                                                        {{ $sr->series }}
                                                                    @endif
                                                                @endforeach
                                                            </p>
                                                            <p class="mb-1">Ukuran : {{ $prod->ukuran_produk }}</p>
                                                            <p class="mb-1">Grade : {{ $prod->grade }}</p>
                                                            @if ($prod->brand_wig && $prod->keterangan_wig)
                                                                <p class="mb-1">Wig : {{ $prod->brand_wig }}
                                                                    ({{ $prod->keterangan_wig }})
                                                                </p>
                                                            @else
                                                                <p class="mb-1">Wig : Tidak ada</p>
                                                            @endif
                                                            @if ($prod->biaya_cuci)
                                                                <p class="mb-1">Biaya Cuci : Rp
                                                                    {{ number_format($prod->biaya_cuci, 0, '', '.') }}</p>
                                                            @else
                                                                <p class="mb-1">Biaya Cuci : Tidak ada</p>
                                                            @endif
                                                            @if ($prod->id_alamat)
                                                                <p class="mb-1">Lokasi Produk: {{ $prod->alamat->nama }}
                                                                </p>
                                                            @else
                                                                <p class="mb-1">Lokasi Produk: Alamat Utama</p>
                                                            @endif
                                                            <p class="mb-1">Metode Kirim :</p>
                                                            <ul>
                                                                @foreach ($prod->metode_kirim ?? [] as $metode)
                                                                    <li>{{ $metode }}</li>
                                                                @endforeach
                                                            </ul>
                                                            @if ($prod->additional)
                                                                <p>Additional :</p>
                                                                <ul>
                                                                    @foreach (json_decode($prod->additional, true) as $nama => $harga)
                                                                        <li>Add {{ $nama }} + Rp
                                                                            {{ number_format($harga, 0, '', '.') }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            @else
                                                                <p>Additional : Tidak ada</p>
                                                            @endif
                                                        </div>
                                                        <div class="d-flex mt-auto flex-wrap">
                                                            <a href="{{ route('seller.viewEditProduk', $prod->id) }}"
                                                                class="btn btn-primary d-grid mb-2 me-2">Edit</a>
                                                            <form action="{{ route('seller.arsipProduk', $prod->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-primary d-grid mb-2 me-2">Arsipkan</button>
                                                            </form>
                                                            <form action="{{ route('seller.hapusProduk', $prod->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-danger d-grid mb-2 me-2"
                                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                    @if ($produk->where('status_produk', 'aktif')->isEmpty())
                                        <div class="col">
                                            <div class="text-secondary fw-light opacity-50">Buat produk baru atau klik
                                                Aktifkan pada produk untuk menampilkan nya disini</div>
                                        </div>
                                    @endif
                                </div>
                                <hr class="bg-secondary border-5 border-top border-secondary mb-5" />
                                <h5 class="card-title">Produk Diarsipkan (Tidak ditampilkan pada marketplace)</h5>
                                <div class="row row-cols-1 row-cols-md-4 g-4">
                                    @foreach ($produk as $prod)
                                        @if ($prod->status_produk == 'arsip')
                                            <div class="col mb-4">
                                                <div class="card h-100">
                                                    <div class="p-3">
                                                        <div class="image-container">
                                                            @foreach ($fotoProduk->where('id_produk', $prod->id)->take(1) as $fp)
                                                                <img src="{{ asset($fp->path) }}" class="card-img-top"
                                                                    alt="...">
                                                            @endforeach
                                                        </div>
                                                        <div class="product-details mt-3">
                                                            <h5 class="card-title fw-bolder fs-5">{{ $prod->nama_produk }}
                                                            </h5>
                                                            <p class="cut-text fw-bold fs-6">Rp
                                                                {{ number_format($prod->harga, 0, '', '.') }} / 3 hari</p>
                                                            <p class="cut-text">{{ $prod->brand }}, {{ $prod->gender }},
                                                                @foreach ($series as $sr)
                                                                    @if ($sr->id == $prod->id_series)
                                                                        {{ $sr->series }}
                                                                    @endif
                                                                @endforeach
                                                            </p>
                                                            <p class="mb-1">Ukuran : {{ $prod->ukuran_produk }}</p>
                                                            <p class="mb-1">Grade : {{ $prod->grade }}</p>
                                                            @if ($prod->brand_wig && $prod->keterangan_wig)
                                                                <p class="mb-1">Wig : {{ $prod->brand_wig }}
                                                                    ({{ $prod->keterangan_wig }})
                                                                </p>
                                                            @else
                                                                <p class="mb-1">Wig : Tidak ada</p>
                                                            @endif
                                                            @if ($prod->biaya_cuci)
                                                                <p class="mb-1">Biaya Cuci : Rp
                                                                    {{ number_format($prod->biaya_cuci, 0, '', '.') }}</p>
                                                            @else
                                                                <p class="mb-1">Biaya Cuci : Tidak ada</p>
                                                            @endif
                                                            @if ($prod->id_alamat)
                                                                <p class="mb-1">Lokasi Produk: {{ $prod->alamat->nama }}
                                                                </p>
                                                            @else
                                                                <p class="mb-1">Lokasi Produk: Alamat Utama</p>
                                                            @endif
                                                            <p class="mb-1">Metode Kirim :</p>
                                                            <ul>
                                                                @foreach ($prod->metode_kirim ?? [] as $metode)
                                                                    <li>{{ $metode }}</li>
                                                                @endforeach
                                                            </ul>
                                                            @if ($prod->additional)
                                                                <p>Additional :</p>
                                                                <ul>
                                                                    @foreach (json_decode($prod->additional, true) as $nama => $harga)
                                                                        <li>Add {{ $nama }} + Rp
                                                                            {{ number_format($harga, 0, '', '.') }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            @else
                                                                <p>Additional : Tidak ada</p>
                                                            @endif
                                                        </div>
                                                        @if ($prod->LastOrder)
                                                            @if (!$prod->LastOrder->ready_status)
                                                                <hr>
                                                                <p class="cut-text fw-bold fs-6"> Informasi Order </p>
                                                                <small class="mb-3"> Produk ini sebelumnya telah disewa
                                                                    dan
                                                                    selesai. Sistem
                                                                    secara default mengarsipkan produk ini. <span
                                                                        class="fw-bold text-danger">Mohon
                                                                        klik "aktifkan" jika produk sudah ready </span> agar
                                                                    dapat
                                                                    disewa kembali </small>
                                                                <hr>
                                                            @endif
                                                        @endif
                                                        <div class="d-flex mt-auto flex-wrap">
                                                            <a href="{{ route('seller.viewEditProduk', $prod->id) }}"
                                                                class="btn btn-primary d-grid mb-2 me-2">Edit</a>
                                                            <form action="{{ route('seller.aktifkanProduk', $prod->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-primary mb-2 me-2 d-grid">Aktifkan</button>
                                                            </form>
                                                            <form action="{{ route('seller.hapusProduk', $prod->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-danger d-grid mb-2 me-2"
                                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                    @if ($produk->where('status_produk', 'arsip')->isEmpty())
                                        <div class="col">
                                            <div class="text-secondary fw-light opacity-50">Anda Belum Memiliki Produk yang
                                                diarsipkan. Klik Arsipkan pada Produk untuk menonaktifkan dari marketplace
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- Produk dipesan --}}
                                <hr class="bg-secondary border-5 border-top border-secondary mb-5" />
                                <h5 class="card-title">Produk Yang Sedang Disewa</h5>
                                <div class="row row-cols-1 row-cols-md-4 g-4">
                                    @foreach ($produk as $prod)
                                        @if ($prod->status_produk == 'tidak ready')
                                            <div class="col mb-4">
                                                <div class="card h-100">
                                                    <div class="p-3">
                                                        <div class="image-container">
                                                            @foreach ($fotoProduk->where('id_produk', $prod->id)->take(1) as $fp)
                                                                <img src="{{ asset($fp->path) }}" class="card-img-top"
                                                                    alt="...">
                                                            @endforeach
                                                        </div>
                                                        <div class="product-details mt-3">
                                                            <h5 class="card-title fw-bolder fs-5">{{ $prod->nama_produk }}
                                                            </h5>
                                                            <p class="cut-text fw-bold fs-6">Rp
                                                                {{ number_format($prod->harga, 0, '', '.') }} / 3 hari</p>
                                                            <p class="cut-text">{{ $prod->brand }}, {{ $prod->gender }},
                                                                @foreach ($series as $sr)
                                                                    @if ($sr->id == $prod->id_series)
                                                                        {{ $sr->series }}
                                                                    @endif
                                                                @endforeach
                                                            </p>
                                                            <p class="mb-1">Ukuran : {{ $prod->ukuran_produk }}</p>
                                                            <p class="mb-1">Grade : {{ $prod->grade }}</p>
                                                            @if ($prod->brand_wig && $prod->keterangan_wig)
                                                                <p class="mb-1">Wig : {{ $prod->brand_wig }}
                                                                    ({{ $prod->keterangan_wig }})
                                                                </p>
                                                            @else
                                                                <p class="mb-1">Wig : Tidak ada</p>
                                                            @endif
                                                            @if ($prod->biaya_cuci)
                                                                <p class="mb-1">Biaya Cuci : Rp
                                                                    {{ number_format($prod->biaya_cuci, 0, '', '.') }}</p>
                                                            @else
                                                                <p class="mb-1">Biaya Cuci : Tidak ada</p>
                                                            @endif
                                                            @if ($prod->id_alamat)
                                                                <p class="mb-1">Lokasi Produk: {{ $prod->alamat->nama }}
                                                                </p>
                                                            @else
                                                                <p class="mb-1">Lokasi Produk: Alamat Utama</p>
                                                            @endif
                                                            <p class="mb-1">Metode Kirim :</p>
                                                            <ul>
                                                                @foreach ($prod->metode_kirim ?? [] as $metode)
                                                                    <li>{{ $metode }}</li>
                                                                @endforeach
                                                            </ul>
                                                            @if ($prod->additional)
                                                                <p>Additional :</p>
                                                                <ul>
                                                                    @foreach (json_decode($prod->additional, true) as $nama => $harga)
                                                                        <li>Add {{ $nama }} + Rp
                                                                            {{ number_format($harga, 0, '', '.') }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            @else
                                                                <p>Additional : Tidak ada</p>
                                                            @endif
                                                        </div>
                                                        <hr>
                                                        <p class="cut-text fw-bold fs-6"> Informasi Order </p>
                                                        <p> Nomor Order : {{ $prod->LastOrder->nomor_order }} </p>
                                                        <p> Disewa oleh : {{ $prod->LastOrder->user->nama }} </p>
                                                        <p> Status Order : {{ $prod->LastOrder->status }} </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                    @if ($produk->where('status_produk', 'tidak ready')->isEmpty())
                                        <div class="col">
                                            <div class="text-secondary fw-light opacity-50 mb-5">Tidak ada
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Bootstrap JS (Optional, if you need Bootstrap JS features) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('seller/inputangka.js') }}"></script>
    <script src="{{ asset('seller/inputfotoproduk.js') }}"></script>
@endsection
