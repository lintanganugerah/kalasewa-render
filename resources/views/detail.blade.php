@extends('layout.template')
@extends('layout.navbar')
@section('content')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/detail.css') }}" />

    <section>

        <style>
            .carousel-item {
                transition-duration: 0.3s !important;
                /* 300 milliseconds */
            }

            .no-underline {
                text-decoration: none;
                /* Remove underline */
                color: inherit;
                /* Inherit the color from the parent element or set it explicitly */
            }

            .carousel-control-prev-icon,
            .carousel-control-next-icon {
                background-color: rgba(0, 0, 0, 0.7);
                padding: 10px;
                border-radius: 10%;
            }
        </style>

        <div class="container mt-2 mb-3">
            @csrf
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
        </div>

        <div class="container-fluid">
            <div class="container">
                <div class="row">
                    <div class="col-3">
                        <div class="carousel-container">
                            <div id="carouselExampleIndicators" class="carousel slide">
                                <div class="carousel-indicators">
                                    @foreach ($fotoproduk->where('id_produk', $produk->id) as $index => $foto)
                                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $index }}"
                                            class="{{ $index == 0 ? 'active' : '' }}" aria-current="{{ $index == 0 ? 'true' : 'false' }}"
                                            aria-label="Slide {{ $index + 1 }}"></button>
                                    @endforeach
                                </div>
                                <div class="carousel-inner">
                                    @foreach ($fotoproduk->where('id_produk', $produk->id) as $index => $foto)
                                        <div class="carousel-item-detail carousel-item {{ $index == 0 ? 'active' : '' }}">
                                            <img src="{{ asset($foto->path) }}" class="d-block w-100" alt="{{ $produk->nama_produk }}">
                                        </div>
                                    @endforeach
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        </div>
                        <div class="carousel-thumbnail">
                            <div class="row mt-3">
                                <div class="d-flex flex-wrap">
                                    @foreach ($fotoproduk->where('id_produk', $produk->id) as $index => $foto)
                                        <div class="p-1">
                                            <div class="thumbnail-container" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $index }}">
                                                <img src="{{ asset($foto->path) }}" class="img-thumbnail thumbnail-image" alt="{{ $produk->nama_produk }}">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 text-start">
                        <h1><strong>{{ $produk->nama_produk }}</strong></h1>
                        <h3 style="color: orange;"><strong>Rp{{ number_format($produk->harga, 0, '', '.') }} / 3
                                Hari</strong></h3>
                        <p>Rating:
                            @if ($averageRating)
                                {{ number_format($averageRating, 1) }}
                            @else
                                Belum ada rating
                            @endif
                        </p>

                        <hr>

                        <h3 class="mt-2"><strong>KETERANGAN KOSTUM</strong></h3>
                        <button type="button" class="btn btn-outline-dark mb-2">Size:
                            {{ $produk->ukuran_produk }}</button>
                        <button type="button" class="btn btn-outline-dark mb-2">{{ $produk->grade }}</button>
                        @if ($produk->brand)
                            <button type="button" class="btn btn-outline-dark mb-2">Costume Brand:
                                {{ $produk->brand }}</button>
                        @endif
                        @if ($produk->brand_wig)
                            <button type="button" class="btn btn-outline-dark mb-2">Wig Brand:
                                {{ $produk->brand_wig }}</button>
                        @endif
                        @if ($produk->keterangan_wig)
                            <button type="button" class="btn btn-outline-dark mb-2">Wig Styling:
                                {{ $produk->keterangan_wig }}</button>
                        @endif
                        @if ($produk->biaya_cuci)
                            <button type="button" class="btn btn-outline-dark mb-2">Biaya Cuci:
                                Rp{{ number_format($produk->biaya_cuci, 0, '', '.') }}</button>
                        @endif

                        <h3 class="mt-4"><strong>PILIHAN ADDITIONAL</strong></h3>
                        @if ($produk->additional)
                            @foreach (json_decode($produk->additional, true) as $nama => $harga)
                                <button type="button" class="btn btn-outline-dark">{{ $nama }}</button>
                            @endforeach
                        @else
                            <p>Maaf, Produk ini tidak memiliki additional</p>
                        @endif
                        <h3 class="mt-4"><strong>DESKRIPSI KOSTUM</strong></h3>
                        <p>{!! nl2br(e($produk->deskripsi_produk)) !!}</p>

                        <hr>

                    </div>
                    <div class="col-3">
                        <div class="card" style="width: 18rem;">
                            <div class="container">
                                <div class="card-body text-center">
                                    @if ($produk->toko && $produk->toko->user)
                                        <img class="avatar-img" src="{{ asset($produk->toko->user->foto_profil) }}" alt="User" style="border-radius: 30px; width: 60px;" />
                                        <a href="{{ route('viewToko', ['id' => $produk->toko->id]) }}" class="no-underline">
                                            <h5 class="card-title text-center">
                                                <strong>{{ $produk->toko->nama_toko }}</strong>
                                            </h5>
                                        </a>
                                    @endif
                                    <div class="container-fluid d-flex justify-content-around">
                                        <i class="ri-star-line">
                                            @if ($averageTokoRating)
                                                {{ number_format($averageTokoRating, 1) }}
                                            @else
                                                0
                                            @endif
                                        </i>
                                        @if ($averageTokoRating >= 4)
                                            <span class="badge text-white" style="background: linear-gradient(to right, #EAD946, #D99C00);">Terpercaya</span>
                                        @elseif ($averageTokoRating > 0 && $averageTokoRating < 2.5)
                                            <span class="badge text-bg-danger">
                                                Bermasalah</span>
                                        @elseif ($averageTokoRating >= 2.5 && $averageTokoRating < 4)
                                            <span class="badge text-white" style="background-color: #EB7F01;">Standar</span>
                                        @else
                                            <span class="badge text-white" style="background-color: 6DC0D0;">
                                                Pendatang</span>
                                        @endif
                                    </div>
                                    <p class="card-text text-start mt-2">{!! nl2br(e($produk->toko->bio_toko)) !!}</p>
                                    <div class="pilihan-user mt-2">
                                        @if (auth()->check() && auth()->user()->role === 'penyewa')
                                            <div class="col">
                                                @if ($produk->status_produk == 'tidak ready')
                                                    <button type="submit" class="btn btn-danger w-100" disabled>Rental
                                                        Produk</button>
                                                    <p>Produk ini sedang dirental!</p>
                                                @else
                                                    <form action="{{ route('viewOrder', ['id' => $produk->id]) }}">
                                                        @csrf
                                                        @if ($produk->grade == 'Grade 3' && auth()->user()->total_review_penyewa() >= 3 && auth()->user()->avg_nilai_penyewa() >= 4)
                                                            <button type="submit" class="btn btn-danger w-100">Rental
                                                                Produk</button>
                                                        @elseif ($produk->grade == 'Grade 2' && auth()->user()->total_review_penyewa() > 0)
                                                            <button type="submit" class="btn btn-danger w-100">Rental
                                                                Produk</button>
                                                        @elseif ($produk->grade == 'Grade 1')
                                                            <button type="submit" class="btn btn-danger w-100">Rental
                                                                Produk</button>
                                                        @else
                                                            <button type="submit" class="btn btn-danger w-100" disabled>Rental
                                                                Produk</button>
                                                            <span>Mengapa saya tidak bisa menyewa produk ini<a data-bs-toggle="modal" data-bs-target="#infoModalGrade"><i
                                                                        class="fa-solid fa-regular fa-circle-info ms-2"></i></a></span>
                                                        @endif
                                                    </form>
                                                @endif
                                            </div>
                                            <div class="col">
                                                @if ($produk->isInWishlist())
                                                    <form action="{{ route('wishlist.remove', ['id' => $produk->id]) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger w-100">Hapus
                                                            Wishlist</button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('wishlist.add', ['id' => $produk->id]) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-danger w-100">Tambah
                                                            Wishlist</button>
                                                    </form>
                                                @endif
                                            </div>
                                            <div class="col">
                                                <a href="{{ url('/chat' . '/' . $produk->toko->id_user) }}" target="_blank" class="no-underline"><button type="button"
                                                        class="btn btn-outline-success w-100">Chat Toko</button></a>
                                            </div>
                                        @else
                                            <div class="col my-2">
                                                <button type="button" class="btn btn-danger w-100" disabled>Rental
                                                    Produk</button>
                                            </div>
                                            <div class="col">
                                                <button type="button" class="btn btn-outline-danger w-100" disabled>Tambahkan
                                                    Wishlist</button>
                                            </div>
                                            <div class="col">
                                                <button type="button" class="btn btn-outline-success mt-2 w-100" disabled>Chat
                                                    Toko</button>
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

        <div class="container-fluid mt-5">
            <div class="container">
                <hr>
                <h1 class="text-center text-decoration mt-4"><strong>ATURAN TOKO</strong></h1>
                <div class="card">
                    <div class="card-body">
                        <table class="table w-100">
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
                                                <td data-title="Denda" class="align-middle">
                                                    Rp{{ number_format($atur->denda_pasti, 0, '', '.') }}
                                                </td>
                                            @else
                                                <td data-title="Denda" class="align-middle">
                                                    {{ $atur->denda_kondisional }}
                                                </td>
                                            @endif
                                        @else
                                            <td data-title="Denda" class="align-middle">-
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div
        </div>

        <div class="container-fluid mt-5">
            <div class="container">
                <hr>
                <h1 class="mb-3"><strong>Review Produk</strong></h1>
                <div class="card">
                    <div class="card-body">
                        <table class="table w-100" id="review-table">
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
                                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#exampleModal-{{ $loop->index }}">
                                                Lihat Foto Review
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr>
            </div>
        </div>

        <div class="container-fluid mt-5">
            <div class="container">
                <div class="row align-items-end">
                    <div class="col-6">
                        <h1><strong>Lainnya dari {{ $produk->toko->nama_toko }}</strong></h1>
                    </div>
                    <div class="col-6 text-end">
                        <a href="{{ route('viewToko', ['id' => $produk->toko->id]) }}" class="no-underline">
                            <button class="btn btn-outline-danger mb-2">Lihat Semua</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="container">
                <div class="row-kartu d-flex mb-3">
                    @foreach ($produkLain as $prod)
                        <div class="col-2" style="margin-right: 43px;">
                            <a href="{{ route('viewDetail', ['id' => $prod->id]) }}" class="card-link">
                                <div class="card custom-card text-bg-dark border-secondary" style="width: 250px; height: 100%;">
                                    @foreach ($fotoProdukLain->where('id_produk', $prod->id)->take(1) as $foto)
                                        <img src="{{ asset($foto->path) }}" class="card-img-top img-fluid h-50" alt="fotoproduk" style="object-fit: cover;">
                                    @endforeach
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="avatar avatar-card me-2">
                                                <img class="avatar-img" src="{{ asset($prod->toko->user->foto_profil) }}" alt="User" style="border-radius: 30px;" />
                                            </div>
                                            <div class="fs-08-rem user-card">
                                                @foreach ($toko->where('id', $prod->id_toko)->take(1) as $tk)
                                                    <div class="fw-bold text-truncate">
                                                        {{ $tk->nama_toko }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <h5 class="card-title" style="margin-bottom: 2px;">{{ $prod->nama_produk }}</h5>
                                        <p class="card-text" style="color: orange;">
                                            <strong>Rp{{ number_format($prod->harga) }}
                                                / 3 Hari</strong>
                                        </p>
                                        <p class="card-text">
                                            <img src="{{ asset('storage/icon/box-seam.png') }}" alt="box-seam"
                                                style="width: 1em; height: 1em; vertical-align: middle; fill: white;">
                                            {{ $prod->brand }}
                                        </p>
                                        <p class="card-text">
                                            <img src="{{ asset('storage/icon/tv.png') }}" alt="tv" style="width: 1em; height: 1em; vertical-align: middle; fill: white;">
                                            {{ $prod->seriesDetail->series }}
                                        </p>
                                        <button type="button" class="btn btn-sm btn-outline-light" disabled>
                                            {{ $prod->ukuran_produk }}
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-light" disabled>
                                            {{ $prod->gender }}
                                        </button>
                                        @if ($prod->additional)
                                            <button type="button" class="btn btn-sm btn-outline-light" disabled>
                                                +Additional
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="container-fluid mt-5">
            <div class="container">
                <div class="row align-items-end">
                    <div class="col-6">
                        <h1><strong>{{ $produk->SeriesDetail->series }}</strong></h1>
                    </div>
                    <div class="col-6 text-end">
                        <a href="{{ route('viewPencarian', ['id_series' => $produk->id_series]) }}" class="no-underline">
                            <button class="btn btn-outline-danger mb-2">Lihat Semua</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="container">
                <div class="row-kartu d-flex mb-3">
                    @foreach ($produkSeriesSama as $prod)
                        <div class="col-2" style="margin-right: 43px;">
                            <a href="{{ route('viewDetail', ['id' => $prod->id]) }}" class="card-link">
                                <div class="card custom-card text-bg-dark border-secondary" style="width: 250px; height: 100%;">
                                    @foreach ($fotoProdukLain->where('id_produk', $prod->id)->take(1) as $foto)
                                        <img src="{{ asset($foto->path) }}" class="card-img-top img-fluid h-50" alt="fotoproduk" style="object-fit: cover;">
                                    @endforeach
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="avatar avatar-card me-2">
                                                <img class="avatar-img" src="{{ asset($prod->toko->user->foto_profil) }}" alt="User" style="border-radius: 30px;" />
                                            </div>
                                            <div class="fs-08-rem user-card">
                                                @foreach ($toko->where('id', $prod->id_toko)->take(1) as $tk)
                                                    <div class="fw-bold text-truncate">
                                                        {{ $tk->nama_toko }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <h5 class="card-title" style="margin-bottom: 2px;">{{ $prod->nama_produk }}</h5>
                                        <p class="card-text" style="color: orange;">
                                            <strong>Rp{{ number_format($prod->harga) }}
                                                / 3 Hari</strong>
                                        </p>
                                        <p class="card-text">
                                            <img src="{{ asset('storage/icon/box-seam.png') }}" alt="box-seam"
                                                style="width: 1em; height: 1em; vertical-align: middle; fill: white;">
                                            {{ $prod->brand }}
                                        </p>
                                        <p class="card-text">
                                            <img src="{{ asset('storage/icon/tv.png') }}" alt="tv" style="width: 1em; height: 1em; vertical-align: middle; fill: white;">
                                            {{ $prod->seriesDetail->series }}
                                        </p>
                                        <button type="button" class="btn btn-sm btn-outline-light" disabled>
                                            {{ $prod->ukuran_produk }}
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-light" disabled>
                                            {{ $prod->gender }}
                                        </button>
                                        @if ($prod->additional)
                                            <button type="button" class="btn btn-sm btn-outline-light" disabled>
                                                +Additional
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        @include('layout.footer')

        @foreach ($review as $index => $rev)
            <!-- Modal -->
            <div class="modal fade" id="exampleModal-{{ $index }}" tabindex="-1" aria-labelledby="exampleModalLabel-{{ $index }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content text-center">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel-{{ $index }}">Review
                                {{ $rev->user->nama }}
                            </h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @if ($rev->foto)
                                @foreach (json_decode($rev->foto) as $foto)
                                    <img class="rounded img-fluid mb-3" src="{{ asset($foto) }}" alt="Review {{ $rev->user->nama }}">
                                @endforeach
                            @else
                                <p> Tidak ada Foto Review </p>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Modal Grade -->
        <div class="modal fade" id="infoModalGrade" tabindex="-1" aria-labelledby="infoModalGradeLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="infoModalGradeLabel">Informasi Grade Kostum</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if (auth()->user())
                            <span class='text-danger'>
                                <p class="fw-bold">Keterangan Akun</p>
                                <p>Sejauh ini, anda memiliki rating sebesar:
                                    <strong>{{ number_format(auth()->user()->avg_nilai_penyewa(), 2) }}</strong> <br>dan
                                    baru
                                    pernah
                                    menyewa sebanyak:
                                    <strong>{{ number_format(auth()->user()->total_review_penyewa()) }}x</strong>
                                </p>
                                <p>Kostum ini memiliki <strong>{{ $produk->grade }}</strong></p>
                            </span>
                        @endif

                        <p class="fw-bold">Apa Itu Grade Kostum ?</p>
                        <p>Sistem grade digunakan untuk membedakan kostum-kostum berdasarkan tingkat
                            kompleksitas, kualitas, dan harga, serta untuk menentukan siapa yang memenuhi syarat
                            untuk menyewa setiap grade kostum tersebut.</p>

                        <p><span class="fw-bold">Jika penyewa
                                tidak memenuhi syarat,
                                maka ia tidak akan bisa merental kostum tersebut.</span> Berikut adalah
                            penjelasan mengenai
                            sistem grade yang kami terapkan:</p>

                        <p class="fw-bold">Grade 1</p>
                        <ul>
                            <li><span class="fw-bold">Syarat Penyewa :</span> Dapat disewa oleh siapa saja,
                                termasuk penyewa baru atau newbie.
                            </li>
                        </ul>
                        <p class="fw-bold">Grade 2</p>
                        <ul>
                            <li><span class="fw-bold">Syarat Penyewa :</span> Hanya bisa disewa oleh pelanggan
                                yang sudah memiliki pengalaman menyewa minimal 1x di kalasewa sebelumnya.
                            </li>
                        </ul>

                        <p class="fw-bold">Grade 3</p>
                        <ul>
                            <li><span class="fw-bold">Syarat Penyewa :</span> Hanya bisa disewa oleh pelanggan
                                yang sudah memiliki pengalaman menyewa di kalasewa sebelumnya sebanyak 3x, dan
                                penyewa memiliki
                                rating review setidaknya 4.
                            </li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

    </section>

@endsection
