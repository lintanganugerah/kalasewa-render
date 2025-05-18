@extends('layout.template')
@extends('layout.navbar')

@section('content')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/searchbar.css') }}" />

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

    <section class="py-5">
        <div class="container">
            <!-- Hero Section -->
            <div class="row justify-content-center mb-5">
                <div class="col-md-8 text-center">
                    <img src="{{ asset('images/kalasewa.png') }}" alt="Logo Kalasewa" class="img-fluid mb-4"
                        style="width: 100px; height: 100px;">
                    <h1 class="display-4 fw-bold mb-3">KALASEWA</h1>
                    <h4 class="text-muted">Mau cari kostum apa hari ini?</h4>
                </div>
            </div>

            <!-- Search Section -->
            <form action="{{ route('searchProduk') }}" method="GET" class="mb-5">
                @csrf
                <div class="row g-3">
                    <div class="col-12">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control form-control-lg"
                                placeholder="Mau cosplay apa hari ini?" aria-label="Search"
                                value="{{ request()->input('search') }}">
                            <button class="btn btn-danger" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select select2" name="gender" id="selectGender">
                            <option></option>
                            <option value="Pria" {{ request()->input('gender') == 'Pria' ? 'selected' : '' }}>Pria
                            </option>
                            <option value="Wanita" {{ request()->input('gender') == 'Wanita' ? 'selected' : '' }}>Wanita
                            </option>
                            <option value="Semua Gender"
                                {{ request()->input('gender') == 'Semua Gender' ? 'selected' : '' }}>Semua</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select select2" name="size" id="selectSize">
                            <option></option>
                            <option value="XS" {{ request()->input('size') == 'XS' ? 'selected' : '' }}>XS</option>
                            <option value="S" {{ request()->input('size') == 'S' ? 'selected' : '' }}>S</option>
                            <option value="M" {{ request()->input('size') == 'M' ? 'selected' : '' }}>M</option>
                            <option value="L" {{ request()->input('size') == 'L' ? 'selected' : '' }}>L</option>
                            <option value="XL" {{ request()->input('size') == 'XL' ? 'selected' : '' }}>XL</option>
                            <option value="XXL" {{ request()->input('size') == 'XXL' ? 'selected' : '' }}>XXL</option>
                            <option value="All_Size" {{ request()->input('size') == 'All_Size' ? 'selected' : '' }}>All
                                Size</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select select2" name="series" id="selectSeries">
                            <option></option>
                            @foreach ($series as $seri)
                                <option value="{{ $seri->id }}"
                                    {{ request()->input('series') == $seri->id ? 'selected' : '' }}>
                                    {{ $seri->series }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select select2" name="brand" id="selectBrand">
                            <option></option>
                            @foreach ($brand as $brandItem)
                                <option value="{{ $brandItem }}"
                                    {{ request()->input('brand') == $brandItem ? 'selected' : '' }}>
                                    {{ $brandItem }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select select2" name="grade" id="selectGrade">
                            <option></option>
                            <option value="Grade 1" {{ request()->input('grade') == 'Grade 1' ? 'selected' : '' }}>Grade 1
                            </option>
                            <option value="Grade 2" {{ request()->input('grade') == 'Grade 2' ? 'selected' : '' }}>Grade 2
                            </option>
                            <option value="Grade 3" {{ request()->input('grade') == 'Grade 3' ? 'selected' : '' }}>Grade 3
                            </option>
                        </select>
                    </div>
                </div>
            </form>

            <!-- Products Grid -->
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-5 g-4">
                @foreach ($produk as $prod)
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
                                        <img src="{{ asset('storage/icon/box-seam.png') }}" alt="box-seam" class="me-1"
                                            style="width: 1em; height: 1em;">
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
        </div>

        @include('layout.footer')
    </section>
@endsection
