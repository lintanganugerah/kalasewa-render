@extends('layout.template')
@extends('layout.navbar')
@section('content')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/detail.css') }}" />

    <section class="py-5">
        <style>
            .carousel-item {
                transition-duration: 0.3s !important;
            }

            .no-underline {
                text-decoration: none;
                color: inherit;
            }

            .carousel-control-prev-icon,
            .carousel-control-next-icon {
                background-color: rgba(0, 0, 0, 0.7);
                padding: 10px;
                border-radius: 10%;
            }
        </style>

        <div class="container">
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

            <div class="row">
                <!-- Product Images -->
                <div class="col-md-3">
                    <div class="carousel-container">
                        <div id="carouselExampleIndicators" class="carousel slide">
                            <div class="carousel-indicators">
                                @foreach ($fotoproduk->where('id_produk', $produk->id) as $index => $foto)
                                    <button type="button" data-bs-target="#carouselExampleIndicators"
                                        data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}"
                                        aria-current="{{ $index == 0 ? 'true' : 'false' }}"
                                        aria-label="Slide {{ $index + 1 }}"></button>
                                @endforeach
                            </div>
                            <div class="carousel-inner">
                                @foreach ($fotoproduk->where('id_produk', $produk->id) as $index => $foto)
                                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                        <img src="{{ asset($foto->path) }}" class="d-block w-100"
                                            alt="{{ $produk->nama_produk }}" style="height: 400px; object-fit: cover;">
                                    </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($fotoproduk->where('id_produk', $produk->id) as $index => $foto)
                                <div class="thumbnail-container" data-bs-target="#carouselExampleIndicators"
                                    data-bs-slide-to="{{ $index }}">
                                    <img src="{{ asset($foto->path) }}" class="img-thumbnail"
                                        alt="{{ $produk->nama_produk }}"
                                        style="width: 80px; height: 80px; object-fit: cover;">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="col-md-6">
                    <h1 class="display-5 fw-bold mb-3">{{ $produk->nama_produk }}</h1>
                    <h3 class="text-warning fw-bold mb-3">Rp{{ number_format($produk->harga, 0, '', '.') }} / 3 Hari</h3>
                    <p class="mb-3">
                        Rating:
                        @if ($averageRating)
                            {{ number_format($averageRating, 1) }}
                        @else
                            Belum ada rating
                        @endif
                    </p>

                    <hr class="my-4">

                    <h3 class="fw-bold mb-3">KETERANGAN KOSTUM</h3>
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <span
                            class="badge border border-secondary text-secondary px-3 py-2">{{ $produk->ukuran_produk }}</span>
                        <span class="badge border border-secondary text-secondary px-3 py-2">{{ $produk->grade }}</span>
                        @if ($produk->brand)
                            <span class="badge border border-secondary text-secondary px-3 py-2">Costume Brand:
                                {{ $produk->brand }}</span>
                        @endif
                        @if ($produk->brand_wig)
                            <span class="badge border border-secondary text-secondary px-3 py-2">Wig Brand:
                                {{ $produk->brand_wig }}</span>
                        @endif
                        @if ($produk->keterangan_wig)
                            <span class="badge border border-secondary text-secondary px-3 py-2">Wig Styling:
                                {{ $produk->keterangan_wig }}</span>
                        @endif
                        @if ($produk->biaya_cuci)
                            <span class="badge border border-secondary text-secondary px-3 py-2">Biaya Cuci:
                                Rp{{ number_format($produk->biaya_cuci, 0, '', '.') }}</span>
                        @endif
                    </div>

                    <h3 class="fw-bold mb-3">PILIHAN ADDITIONAL</h3>
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        @if ($produk->additional)
                            @foreach (json_decode($produk->additional, true) as $nama => $harga)
                                <span
                                    class="badge border border-secondary text-secondary px-3 py-2">{{ $nama }}</span>
                            @endforeach
                        @else
                            <p class="text-muted">Maaf, Produk ini tidak memiliki additional</p>
                        @endif
                    </div>

                    <h3 class="fw-bold mb-3">DESKRIPSI KOSTUM</h3>
                    <p class="mb-4">{!! nl2br(e($produk->deskripsi_produk)) !!}</p>

                    <hr class="my-4">
                </div>

                <!-- Store Info -->
                <div class="col-md-3">
                    <div class="card border text-dark border-secondary">
                        <div class="card-body">
                            @if ($produk->toko && $produk->toko->user)
                                <div class="text-center mb-3">
                                    <img src="{{ asset($produk->toko->user->foto_profil) }}" alt="User"
                                        class="rounded-circle mb-3" style="width: 60px; height: 60px; object-fit: cover;">
                                    <a href="{{ route('viewToko', ['id' => $produk->toko->id]) }}" class="no-underline">
                                        <h5 class="card-title fw-bold">{{ $produk->toko->nama_toko }}</h5>
                                    </a>
                                </div>
                                <div class="d-flex justify-content-around align-items-center mb-3">
                                    <div>
                                        <i class="ri-star-line">
                                            @if ($averageTokoRating)
                                                {{ number_format($averageTokoRating, 1) }}
                                            @else
                                                0
                                            @endif
                                        </i>
                                    </div>
                                    @if ($averageTokoRating >= 4)
                                        <span class="badge"
                                            style="background: linear-gradient(to right, #EAD946, #D99C00);">Terpercaya</span>
                                    @elseif ($averageTokoRating > 0 && $averageTokoRating < 2.5)
                                        <span class="badge bg-danger">Bermasalah</span>
                                    @elseif ($averageTokoRating >= 2.5 && $averageTokoRating < 4)
                                        <span class="badge" style="background-color: #EB7F01;">Standar</span>
                                    @else
                                        <span class="badge" style="background-color: #6DC0D0;">Pendatang</span>
                                    @endif
                                </div>
                                <p class="card-text mb-4">{!! nl2br(e($produk->toko->bio_toko)) !!}</p>

                                <div class="d-grid gap-2">
                                    @if (auth()->check() && auth()->user()->role === 'penyewa')
                                        @if ($produk->status_produk == 'tidak ready')
                                            <button type="button" class="btn btn-danger w-100 py-2" disabled>Rental
                                                Produk</button>
                                            <p class="text-center">Produk ini sedang dirental!</p>
                                        @else
                                            <form action="{{ route('viewOrder', ['id' => $produk->id]) }}">
                                                @csrf
                                                @if (
                                                    $produk->grade == 'Grade 3' &&
                                                        auth()->user()->total_review_penyewa() >= 3 &&
                                                        auth()->user()->avg_nilai_penyewa() >= 4)
                                                    <button type="submit" class="btn btn-danger w-100 py-2">Rental
                                                        Produk</button>
                                                @elseif ($produk->grade == 'Grade 2' && auth()->user()->total_review_penyewa() > 0)
                                                    <button type="submit" class="btn btn-danger w-100 py-2">Rental
                                                        Produk</button>
                                                @elseif ($produk->grade == 'Grade 1')
                                                    <button type="submit" class="btn btn-danger w-100 py-2">Rental
                                                        Produk</button>
                                                @else
                                                    <button type="submit" class="btn btn-danger w-100 py-2"
                                                        disabled>Rental Produk</button>
                                                    <span class="text-center">Mengapa saya tidak bisa menyewa produk ini<a
                                                            data-bs-toggle="modal" data-bs-target="#infoModalGrade"><i
                                                                class="fa-solid fa-regular fa-circle-info ms-2"></i></a></span>
                                                @endif
                                            </form>
                                        @endif

                                        @if ($produk->isInWishlist())
                                            <form action="{{ route('wishlist.remove', ['id' => $produk->id]) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-danger w-100 py-2">Hapus
                                                    Wishlist</button>
                                            </form>
                                        @else
                                            <form action="{{ route('wishlist.add', ['id' => $produk->id]) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger w-100 py-2">Tambah
                                                    Wishlist</button>
                                            </form>
                                        @endif

                                        <a href="{{ url('/chat' . '/' . $produk->toko->id_user) }}" target="_blank"
                                            class="btn btn-outline-success w-100 py-2">Chat Toko</a>
                                    @else
                                        <button type="button" class="btn btn-danger w-100 py-2" disabled>Rental
                                            Produk</button>
                                        <button type="button" class="btn btn-outline-danger w-100 py-2" disabled>Tambah
                                            Wishlist</button>
                                        <button type="button" class="btn btn-outline-success w-100 py-2" disabled>Chat
                                            Toko</button>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Store Rules -->
            <div class="mt-5">
                <hr>
                <h2 class="text-center fw-bold mb-4">ATURAN TOKO</h2>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nama Peraturan</th>
                                        <th>Deskripsi</th>
                                        <th>Denda</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($aturan as $atur)
                                        <tr>
                                            <td>{{ $atur->nama }}</td>
                                            <td>{{ $atur->deskripsi }}</td>
                                            @if ($atur->terdapat_denda)
                                                @if ($atur->denda_pasti)
                                                    <td>Rp{{ number_format($atur->denda_pasti, 0, '', '.') }}</td>
                                                @else
                                                    <td>{{ $atur->denda_kondisional }}</td>
                                                @endif
                                            @else
                                                <td>-</td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews -->
            <div class="mt-5">
                <hr>
                <h2 class="fw-bold mb-4">Review Produk</h2>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="review-table">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Komentar</th>
                                        <th>Nilai</th>
                                        <th class="text-end">Foto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($review as $rev)
                                        <tr>
                                            <td>{{ $rev->user->nama }}</td>
                                            <td>{{ $rev->komentar }}</td>
                                            <td>
                                                @for ($i = 0; $i < $rev->nilai; $i++)
                                                    <i class="ri-star-fill"></i>
                                                @endfor
                                            </td>
                                            <td class="text-end">
                                                <button type="button" class="btn btn-outline-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#exampleModal-{{ $loop->index }}">
                                                    Lihat Foto Review
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Other Products from Store -->
            <div class="mt-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold mb-0">Lainnya dari {{ $produk->toko->nama_toko }}</h2>
                    <a href="{{ route('viewToko', ['id' => $produk->toko->id]) }}" class="btn btn-outline-danger">Lihat
                        Semua</a>
                </div>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-5 g-4">
                    @foreach ($produkLain as $prod)
                        <div class="col">
                            <a href="{{ route('viewDetail', ['id' => $prod->id]) }}" class="text-decoration-none">
                                <div class="card h-100 bg-dark text-white border-secondary">
                                    @foreach ($fotoProdukLain->where('id_produk', $prod->id)->take(1) as $foto)
                                        <img src="{{ asset($foto->path) }}" class="card-img-top" alt="fotoproduk"
                                            style="height: 200px; object-fit: cover;">
                                    @endforeach
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="me-2">
                                                <img src="{{ asset($prod->toko->user->foto_profil) }}" alt="User"
                                                    class="rounded-circle"
                                                    style="width: 30px; height: 30px; object-fit: cover;">
                                            </div>
                                            <div class="small">
                                                <div class="fw-bold text-truncate">{{ $prod->toko->nama_toko }}</div>
                                            </div>
                                        </div>
                                        <h5 class="card-title">{{ $prod->nama_produk }}</h5>
                                        <p class="card-text text-warning fw-bold">Rp{{ number_format($prod->harga) }} / 3
                                            Hari</p>
                                        <p class="card-text small">
                                            <img src="{{ asset('storage/icon/box-seam.png') }}" alt="box-seam"
                                                class="me-1" style="width: 1em; height: 1em;">
                                            {{ $prod->brand }}
                                        </p>
                                        <p class="card-text small">
                                            <img src="{{ asset('storage/icon/tv.png') }}" alt="tv" class="me-1"
                                                style="width: 1em; height: 1em;">
                                            {{ $prod->seriesDetail->series }}
                                        </p>
                                        <div class="mt-auto pt-2">
                                            <div class="d-flex flex-wrap gap-1">
                                                <span class="badge bg-secondary">{{ $prod->ukuran_produk }}</span>
                                                <span class="badge bg-secondary">{{ $prod->gender }}</span>
                                                @if ($prod->additional)
                                                    <span class="badge bg-secondary">+Additional</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Same Series Products -->
            <div class="mt-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold mb-0">{{ $produk->SeriesDetail->series }}</h2>
                    <a href="{{ route('viewPencarian', ['id_series' => $produk->id_series]) }}"
                        class="btn btn-outline-danger">Lihat Semua</a>
                </div>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-5 g-4">
                    @foreach ($produkSeriesSama as $prod)
                        <div class="col">
                            <a href="{{ route('viewDetail', ['id' => $prod->id]) }}" class="text-decoration-none">
                                <div class="card h-100 bg-dark text-white border-secondary">
                                    @foreach ($fotoProdukLain->where('id_produk', $prod->id)->take(1) as $foto)
                                        <img src="{{ asset($foto->path) }}" class="card-img-top" alt="fotoproduk"
                                            style="height: 200px; object-fit: cover;">
                                    @endforeach
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="me-2">
                                                <img src="{{ asset($prod->toko->user->foto_profil) }}" alt="User"
                                                    class="rounded-circle"
                                                    style="width: 30px; height: 30px; object-fit: cover;">
                                            </div>
                                            <div class="small">
                                                <div class="fw-bold text-truncate">{{ $prod->toko->nama_toko }}</div>
                                            </div>
                                        </div>
                                        <h5 class="card-title">{{ $prod->nama_produk }}</h5>
                                        <p class="card-text text-warning fw-bold">Rp{{ number_format($prod->harga) }} / 3
                                            Hari</p>
                                        <p class="card-text small">
                                            <img src="{{ asset('storage/icon/box-seam.png') }}" alt="box-seam"
                                                class="me-1" style="width: 1em; height: 1em;">
                                            {{ $prod->brand }}
                                        </p>
                                        <p class="card-text small">
                                            <img src="{{ asset('storage/icon/tv.png') }}" alt="tv" class="me-1"
                                                style="width: 1em; height: 1em;">
                                            {{ $prod->seriesDetail->series }}
                                        </p>
                                        <div class="mt-auto pt-2">
                                            <div class="d-flex flex-wrap gap-1">
                                                <span class="badge bg-secondary">{{ $prod->ukuran_produk }}</span>
                                                <span class="badge bg-secondary">{{ $prod->gender }}</span>
                                                @if ($prod->additional)
                                                    <span class="badge bg-secondary">+Additional</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        @include('layout.footer')

        <!-- Review Photo Modals -->
        @foreach ($review as $index => $rev)
            <div class="modal fade" id="exampleModal-{{ $index }}" tabindex="-1"
                aria-labelledby="exampleModalLabel-{{ $index }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel-{{ $index }}">Review
                                {{ $rev->user->nama }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            @if ($rev->foto)
                                @foreach (json_decode($rev->foto) as $foto)
                                    <img class="img-fluid rounded mb-3" src="{{ asset($foto) }}"
                                        alt="Review {{ $rev->user->nama }}">
                                @endforeach
                            @else
                                <p>Tidak ada Foto Review</p>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Grade Info Modal -->
        <div class="modal fade" id="infoModalGrade" tabindex="-1" aria-labelledby="infoModalGradeLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="infoModalGradeLabel">Informasi Grade Kostum</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if (auth()->user())
                            <div class="text-danger mb-4">
                                <p class="fw-bold">Keterangan Akun</p>
                                <p>Sejauh ini, anda memiliki rating sebesar:
                                    <strong>{{ number_format(auth()->user()->avg_nilai_penyewa(), 2) }}</strong><br>
                                    dan baru pernah menyewa sebanyak:
                                    <strong>{{ number_format(auth()->user()->total_review_penyewa()) }}x</strong>
                                </p>
                                <p>Kostum ini memiliki <strong>{{ $produk->grade }}</strong></p>
                            </div>
                        @endif

                        <p class="fw-bold">Apa Itu Grade Kostum ?</p>
                        <p>Sistem grade digunakan untuk membedakan kostum-kostum berdasarkan tingkat kompleksitas, kualitas,
                            dan harga, serta untuk menentukan siapa yang memenuhi syarat untuk menyewa setiap grade kostum
                            tersebut.</p>

                        <p><span class="fw-bold">Jika penyewa tidak memenuhi syarat, maka ia tidak akan bisa merental
                                kostum tersebut.</span> Berikut adalah penjelasan mengenai sistem grade yang kami terapkan:
                        </p>

                        <div class="mb-3">
                            <p class="fw-bold">Grade 1</p>
                            <ul>
                                <li><span class="fw-bold">Syarat Penyewa :</span> Dapat disewa oleh siapa saja, termasuk
                                    penyewa baru atau newbie.</li>
                            </ul>
                        </div>

                        <div class="mb-3">
                            <p class="fw-bold">Grade 2</p>
                            <ul>
                                <li><span class="fw-bold">Syarat Penyewa :</span> Hanya bisa disewa oleh pelanggan yang
                                    sudah memiliki pengalaman menyewa minimal 1x di kalasewa sebelumnya.</li>
                            </ul>
                        </div>

                        <div class="mb-3">
                            <p class="fw-bold">Grade 3</p>
                            <ul>
                                <li><span class="fw-bold">Syarat Penyewa :</span> Hanya bisa disewa oleh pelanggan yang
                                    sudah memiliki pengalaman menyewa di kalasewa sebelumnya sebanyak 3x, dan penyewa
                                    memiliki rating review setidaknya 4.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
