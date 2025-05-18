@extends('layout.template')
@extends('layout.navbar')

@section('content')
    <style>
        .select2-container .select2-selection--single .select2-selection__clear {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            padding-right: 150%;
            cursor: pointer;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/searchbar.css') }}" />

    <section class="py-5">
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

            <!-- Hero Section -->
            <div class="row align-items-center mb-5">
                <div class="col-lg-7">
                    <div class="text-center text-lg-start">
                        <img src="{{ asset('images/kalasewa.png') }}" alt="Logo Kalasewa" class="img-fluid mb-4"
                            style="width: 150px; height: 150px;">
                        <h1 class="display-4 fw-bold mb-3">KALASEWA</h1>
                        <h4 class="text-muted">Ayo mulai wujudkan impian cosplaymu<br>bersama Kalasewa</h4>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner rounded shadow">
                            <div class="carousel-item active">
                                <img src="{{ asset('images/carousel1.jpg') }}" class="d-block w-100" alt="Carousel image"
                                    style="height: 400px; object-fit: cover;">
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('images/carousel2.jpg') }}" class="d-block w-100" alt="Carousel image"
                                    style="height: 400px; object-fit: cover;">
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('images/carousel3.jpg') }}" class="d-block w-100" alt="Carousel image"
                                    style="height: 400px; object-fit: cover;">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Search Section -->
            <form action="{{ route('searchProduk') }}" method="GET" class="mb-5">
                @csrf
                <div class="row g-3">
                    <div class="col-12">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control form-control-lg"
                                placeholder="Mau cosplay apa hari ini?" aria-label="Search">
                            <button class="btn btn-danger" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select select2" name="gender" id="selectGender">
                            <option></option>
                            <option value="Pria">Pria</option>
                            <option value="Wanita">Wanita</option>
                            <option value="Semua Gender">Semua</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select select2" name="size" id="selectSize">
                            <option></option>
                            <option value="XS">XS</option>
                            <option value="S">S</option>
                            <option value="M">M</option>
                            <option value="L">L</option>
                            <option value="XL">XL</option>
                            <option value="XXL">XXL</option>
                            <option value="All_Size">All Size</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select select2" name="series" id="selectSeries">
                            <option></option>
                            @foreach ($series as $seri)
                                <option value="{{ $seri->id }}">{{ $seri->series }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select select2" name="brand" id="selectBrand">
                            <option></option>
                            @foreach ($brand as $brandItem)
                                <option value="{{ $brandItem }}">{{ $brandItem }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select select2" name="grade" id="selectGrade">
                            <option></option>
                            <option value="Grade 1">Grade 1</option>
                            <option value="Grade 2">Grade 2</option>
                            <option value="Grade 3">Grade 3</option>
                        </select>
                    </div>
                </div>
            </form>

            <!-- Latest Costumes Section -->
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h2 class="fw-bold">KOSTUM TERBARU</h2>
                </div>
                <div class="col text-end">
                    <a href="{{ route('viewPencarian') }}" class="btn btn-outline-danger">Lihat Semua</a>
                </div>
            </div>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-5 g-4 mb-5">
                @foreach ($produk->take(5) as $prod)
                    <div class="col">
                        <a href="{{ route('viewDetail', ['id' => $prod->id]) }}" class="text-decoration-none">
                            <div class="card h-100 bg-dark text-white border-secondary">
                                @foreach ($fotoproduk->where('id_produk', $prod->id)->take(1) as $foto)
                                    <div class="ratio ratio-1x1">
                                        <img src="{{ asset($foto->path) }}" class="card-img-top" alt="fotoproduk"
                                            style="object-fit: cover;">
                                    </div>
                                @endforeach
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="me-2">
                                            @foreach ($toko->where('id', $prod->id_toko) as $tk)
                                                @foreach ($user->where('id', $tk->id_user) as $usr)
                                                    <img src="{{ asset($usr->foto_profil) }}" alt="User"
                                                        class="rounded-circle"
                                                        style="width: 30px; height: 30px; object-fit: cover;">
                                                @endforeach
                                            @endforeach
                                        </div>
                                        <div class="small">
                                            @foreach ($toko->where('id', $prod->id_toko)->take(1) as $tk)
                                                <div class="fw-bold text-truncate">{{ $tk->nama_toko }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <h5 class="card-title">{{ $prod->nama_produk }}</h5>
                                    <p class="card-text text-warning fw-bold">Rp{{ number_format($prod->harga) }} / 3 Hari
                                    </p>
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
                                            <span class="badge bg-secondary">{{ $prod->grade }}</span>
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

            @if ($topSeries && $topProduk->isNotEmpty())
                <!-- Top Series Section -->
                <div class="row align-items-center mb-4">
                    <div class="col">
                        <h2 class="fw-bold">Top Series di Kalasewa</h2>
                    </div>
                    <div class="col text-end">
                        <a href="{{ route('viewPencarian', ['id_series' => $topSeries->id_series]) }}"
                            class="btn btn-outline-danger">Lihat Semua</a>
                    </div>
                </div>

                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-5 g-4 mb-5">
                    @foreach ($topProduk->take(5) as $prod)
                        <div class="col">
                            <a href="{{ route('viewDetail', ['id' => $prod->id]) }}" class="text-decoration-none">
                                <div class="card h-100 bg-dark text-white border-secondary">
                                    @foreach ($fotoproduk->where('id_produk', $prod->id)->take(1) as $foto)
                                        <div class="ratio ratio-1x1">
                                            <img src="{{ asset($foto->path) }}" class="card-img-top" alt="fotoproduk"
                                                style="object-fit: cover;">
                                        </div>
                                    @endforeach
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="me-2">
                                                @foreach ($toko->where('id', $prod->id_toko) as $tk)
                                                    @foreach ($user->where('id', $tk->id_user) as $usr)
                                                        <img src="{{ asset($usr->foto_profil) }}" alt="User"
                                                            class="rounded-circle"
                                                            style="width: 30px; height: 30px; object-fit: cover;">
                                                    @endforeach
                                                @endforeach
                                            </div>
                                            <div class="small">
                                                @foreach ($toko->where('id', $prod->id_toko)->take(1) as $tk)
                                                    <div class="fw-bold text-truncate">{{ $tk->nama_toko }}</div>
                                                @endforeach
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
                                        <div class="d-flex flex-wrap gap-1">
                                            <span class="badge bg-secondary">{{ $prod->ukuran_produk }}</span>
                                            <span class="badge bg-secondary">{{ $prod->grade }}</span>
                                            <span class="badge bg-secondary">{{ $prod->gender }}</span>
                                            @if ($prod->additional)
                                                <span class="badge bg-secondary">+Additional</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Store List Section -->
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h2 class="fw-bold">DAFTAR TOKO</h2>
                </div>
                <div class="col text-end">
                    <a href="{{ route('viewListToko') }}" class="btn btn-outline-danger">Lihat Semua</a>
                </div>
            </div>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-5 g-4 mb-5">
                @foreach ($toko as $tk)
                    <div class="col">
                        <a href="{{ route('viewToko', ['id' => $tk->id]) }}" class="text-decoration-none">
                            <div class="card h-100 bg-dark text-white border-secondary">
                                <div class="ratio ratio-1x1">
                                    <img src="{{ asset($tk->user->foto_profil) }}" class="card-img-top" alt="fotoproduk"
                                        style="object-fit: cover;">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title fw-bold">{{ $tk->nama_toko }}</h5>
                                    <p class="card-text mb-2">
                                        Rating Toko:
                                        @if (isset($averageRatings[$tk->id]))
                                            {{ number_format($averageRatings[$tk->id], 1) }}
                                        @else
                                            0
                                        @endif
                                        <i class="ri-star-line"></i>
                                    </p>
                                    @if (isset($averageRatings[$tk->id]) && $averageRatings[$tk->id] >= 4)
                                        <span class="badge"
                                            style="background: linear-gradient(to right, #EAD946, #D99C00);">Terpercaya</span>
                                    @elseif (isset($averageRatings[$tk->id]) && $averageRatings[$tk->id] > 0 && $averageRatings[$tk->id] < 2.5)
                                        <span class="badge bg-danger">Bermasalah</span>
                                    @elseif (isset($averageRatings[$tk->id]) && $averageRatings[$tk->id] >= 2.5 && $averageRatings[$tk->id] < 4)
                                        <span class="badge" style="background-color: #EB7F01;">Standar</span>
                                    @else
                                        <span class="badge" style="background-color: #6DC0D0;">Pendatang Baru</span>
                                    @endif
                                    <p class="card-text text-warning mt-2">
                                        <i class="ri-t-shirt-line"></i>
                                        {{ $tk->produks_count }} Kostum
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        @include('layout.footer')
    </section>
@endsection
