@extends('layout.template')
@extends('layout.navbar')

@section('content')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/searchbar.css') }}" />

    <section>
        <style>
            .no-underline {
                text-decoration: none;
                /* Remove underline */
                color: inherit;
                /* Inherit the color from the parent element or set it explicitly */
            }

            .select2-container .select2-selection--single .select2-selection__clear {
                position: absolute;
                right: 10px;
                top: 50%;
                transform: translateY(-50%);
                padding-right: 150%;
                cursor: pointer;
            }
        </style>
        <div class="container-fluid mt-3 align-items-center">
            <div class="container">
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
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-7">
                                <div class="hero mt-5">
                                    <img src="{{ asset('images/kalasewa.png') }}" alt="Logo Kalasewa" style="width: 150px; height: 150px;">
                                    <h1><strong>KALASEWA</strong></h1>
                                    <h4>Ayo mulai wujudkan impian cosplaymu<br>bersama Kalasewa</h4>
                                </div>
                            </div>
                            <div class="col-5">
                                <div id="carouselExampleAutoplaying" class="carousel slide w-100 text-end" data-bs-ride="carousel">
                                    <div class="carousel-inner" style="object-fit: cover;">
                                        <div class="carousel-homepage carousel-item active">
                                            <img src="{{ asset('images/carousel1.jpg') }}" class="d-block w-100" alt="Carousel image">
                                        </div>
                                        <div class="carousel-homepage carousel-item">
                                            <img src="{{ asset('images/carousel2.jpg') }}" class="d-block w-100" alt="Carousel image">
                                        </div>
                                        <div class="carousel-homepage carousel-item">
                                            <img src="{{ asset('images/carousel3.jpg') }}" class="d-block w-100" alt="Carousel image">
                                        </div>
                                    </div>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <form action="{{ route('searchProduk') }}" method="GET" class="">
                            @csrf
                            <div class="searchbar my-3">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control form-search custom-search-bar" placeholder="Mau cosplay apa hari ini?"
                                        aria-label="Search" />
                                    <button class="btn py-2 custom-search-button" type="submit" id="search-button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="container">
                                <div class="row" style="margin-left: -24px; margin-top: -10px;">
                                    <div class="col">
                                        <div class="form-filter">
                                            <select class="form-select select2" name="gender" id="selectGender" aria-label="Default select example">
                                                <option></option>
                                                <option value="Pria">Pria</option>
                                                <option value="Wanita">Wanita</option>
                                                <option value="Semua Gender">Semua</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-filter">
                                            <select class="form-select select2" name="size" id="selectSize" aria-label="Default select example">
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
                                    </div>
                                    <div class="col">
                                        <div class="form-filter">
                                            <select class="form-select select2" name="series" id="selectSeries" aria-label="Default select example">
                                                <option></option>
                                                @foreach ($series as $seri)
                                                    <option value="{{ $seri->id }}">{{ $seri->series }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-filter">
                                            <select class="form-select select2" name="brand" id="selectBrand" aria-label="Default select example">
                                                <option></option>
                                                @foreach ($brand as $brandItem)
                                                    <option value="{{ $brandItem }}">{{ $brandItem }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-filter">
                                            <select class="form-select select2" name="grade" id="selectGrade" aria-label="Default select example">
                                                <option></option>
                                                <option value="Grade 1">Grade 1</option>
                                                <option value="Grade 2">Grade 2</option>
                                                <option value="Grade 3">Grade 3</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid mt-3">
            <div class="container">
                <div class="row align-items-end">
                    <div class="col-6">
                        <h2><strong>KOSTUM TERBARU</strong></h2>
                    </div>
                    <div class="col-6 text-end">
                        <a href="{{ route('viewPencarian') }}" class="no-underline">
                            <button class="btn btn-outline-danger mb-2">Lihat Semua</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="container">
                <div class="row-kartu d-flex mb-3">
                    @foreach ($produk->take(5) as $prod)
                        <div class="col-2" style="margin-right: 43px;">
                            <a href="{{ route('viewDetail', ['id' => $prod->id]) }}" class="card-link">
                                <div class="card custom-card text-bg-dark border-secondary" style="width: 250px; height: 100%;">
                                    @foreach ($fotoproduk->where('id_produk', $prod->id)->take(1) as $foto)
                                        <img src="{{ asset($foto->path) }}" class="card-img-top img-fluid h-50" alt="fotoproduk" style="object-fit: cover;">
                                    @endforeach
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="avatar avatar-card me-2">
                                                @foreach ($toko->where('id', $prod->id_toko) as $tk)
                                                    @foreach ($user->where('id', $tk->id_user) as $usr)
                                                        <img class="avatar-img" src="{{ asset($usr->foto_profil) }}" alt="User" style="border-radius: 30px;" />
                                                    @endforeach
                                                @endforeach
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
                                        <button type="button" class="btn btn-sm btn-outline-light mb-2" disabled>
                                            {{ $prod->ukuran_produk }}
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-light mb-2" disabled>
                                            {{ $prod->grade }}
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-light mb-2" disabled>
                                            {{ $prod->gender }}
                                        </button>
                                        @if ($prod->additional)
                                            <button type="button" class="btn btn-sm btn-outline-light mb-2" disabled>
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

        @if ($topSeries && $topProduk->isNotEmpty())
            <div class="container-fluid mt-5">
                <div class="container">
                    <div class="row align-items-end">
                        <div class="col-6">
                            <h2><strong>Top Series di Kalasewa</strong></h2>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('viewPencarian', ['id_series' => $topSeries->id_series]) }}" class="no-underline">
                                <button class="btn btn-outline-danger mb-2">Lihat Semua</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                <div class="container">
                    <div class="row-kartu d-flex mb-3">
                        @foreach ($topProduk->take(5) as $prod)
                            <div class="col-2" style="margin-right: 43px;">
                                <a href="{{ route('viewDetail', ['id' => $prod->id]) }}" class="card-link">
                                    <div class="card custom-card text-bg-dark border-secondary" style="width: 250px; height: 100%;">
                                        @foreach ($fotoproduk->where('id_produk', $prod->id)->take(1) as $foto)
                                            <img src="{{ asset($foto->path) }}" class="card-img-top img-fluid h-50" alt="fotoproduk" style="object-fit: cover;">
                                        @endforeach
                                        <div class="card-body">
                                            <div class="d-flex">
                                                <div class="avatar avatar-card me-2">
                                                    @foreach ($toko->where('id', $prod->id_toko) as $tk)
                                                        @foreach ($user->where('id', $tk->id_user) as $usr)
                                                            <img class="avatar-img" src="{{ asset($usr->foto_profil) }}" alt="User" style="border-radius: 30px;" />
                                                        @endforeach
                                                    @endforeach
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
                                            <button type="button" class="btn btn-sm btn-outline-light mb-2" disabled>
                                                {{ $prod->ukuran_produk }}
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-light mb-2" disabled>
                                                {{ $prod->grade }}
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-light mb-2" disabled>
                                                {{ $prod->gender }}
                                            </button>
                                            @if ($prod->additional)
                                                <button type="button" class="btn btn-sm btn-outline-light mb-2" disabled>
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
        @endif


        <div class="container-fluid mt-5">
            <div class="container">
                <div class="row align-items-end">
                    <div class="col-6">
                        <h2><strong>DAFTAR TOKO</strong></h2>
                    </div>
                    <div class="col-6 text-end">
                        <a href="{{ route('viewListToko') }}" class="no-underline">
                            <button class="btn btn-outline-danger mb-2">Lihat Semua</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="container">
                <div class="row-kartu d-flex mb-3">
                    @foreach ($toko as $tk)
                        <div class="col-2" style="margin-right: 43px;">
                            <a href="{{ route('viewToko', ['id' => $tk->id]) }}" class="card-link">
                                <div class="card custom-card text-bg-dark border-secondary" style="width: 250px; height: 100%;">
                                    <img src="{{ asset($tk->user->foto_profil) }}" class="card-img-top img-fluid h-100" alt="fotoproduk" style="object-fit: cover;">
                                    <div class="card-body">
                                        <h5><strong>{{ $tk->nama_toko }}</strong></h5>
                                        <p style="margin-bottom: 5px;">Rating Toko:
                                            @if (isset($averageRatings[$tk->id]))
                                                {{ number_format($averageRatings[$tk->id], 1) }}
                                            @else
                                                0
                                            @endif
                                            <i class="ri-star-line"></i>
                                        </p>
                                        @if (isset($averageRatings[$tk->id]) && $averageRatings[$tk->id] >= 4)
                                            <span class="badge text-white" style="background: linear-gradient(to right, #EAD946, #D99C00);">Terpercaya</span>
                                        @elseif (isset($averageRatings[$tk->id]) && $averageRatings[$tk->id] > 0 && $averageRatings[$tk->id] < 2.5)
                                            <span class="badge text-bg-danger">Bermasalah</span>
                                        @elseif (isset($averageRatings[$tk->id]) && $averageRatings[$tk->id] >= 2.5 && $averageRatings[$tk->id] < 4)
                                            <span class="badge text-white" style="background-color: #EB7F01;">Standar</span>
                                        @else
                                            <span class="badge text-white" style="background-color: 6DC0D0;">Pendatang
                                                Baru</span>
                                        @endif
                                        <p class="card-text" style="color: orange;">
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
        </div>

        @include('layout.footer')

    </section>
@endsection
