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

<section>

    <div class="header-title">
        <div class="container-fluid mt-3">
            <div class="container">
                <div class="row">
                    <div class="hero text-center mt-5">
                        <img src="{{ asset('images/kalasewa.png') }}" alt="Logo Kalasewa"
                            style="width: 100px; height: 100px;">
                        <h1><strong>KALASEWA</strong></h1>
                        <h4>Mau cari kostum apa hari ini?</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="filter-select">
        <div class="container-fluid">
            <div class="container">
                <div class="form-filter">
                    <form action="{{ route('searchProduk') }}" method="GET" class="">
                        @csrf
                        <div class="searchbar my-3">
                            <div class="input-group">
                                <input type="text" name="search"
                                    class="form-control form-search custom-search-bar text-dark"
                                    placeholder="Mau cosplay apa hari ini?" aria-label="Search"
                                    value="{{ request()->input('search') }}" />
                                <button class="btn py-2 custom-search-button" type="submit" id="search-button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="container">
                            <div class="row" style="margin-left: -24px; margin-top: -10px;">
                                <div class="col">
                                    <div class="form-filter">
                                        <select class="form-select select2" name="gender" id="selectGender"
                                            aria-label="Default select example">
                                            <option></option>
                                            <option value="Pria"
                                                {{ request()->input('gender') == 'Pria' ? 'selected' : '' }}>
                                                Pria</option>
                                            <option value="Wanita"
                                                {{ request()->input('gender') == 'Wanita' ? 'selected' : '' }}>Wanita
                                            </option>
                                            <option value="Semua Gender"
                                                {{ request()->input('gender') == 'Semua Gender' ? 'selected' : '' }}>
                                                Semua</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-filter">
                                        <select class="form-select select2" name="size" id="selectSize"
                                            aria-label="Default select example">
                                            <option></option>
                                            <option value="XS" {{ request()->input('size') == 'XS' ? 'selected' : '' }}>
                                                XS
                                            </option>
                                            <option value="S" {{ request()->input('size') == 'S' ? 'selected' : '' }}>S
                                            </option>
                                            <option value="M" {{ request()->input('size') == 'M' ? 'selected' : '' }}>M
                                            </option>
                                            <option value="L" {{ request()->input('size') == 'L' ? 'selected' : '' }}>L
                                            </option>
                                            <option value="XL" {{ request()->input('size') == 'XL' ? 'selected' : '' }}>
                                                XL
                                            </option>
                                            <option value="XXL"
                                                {{ request()->input('size') == 'XXL' ? 'selected' : '' }}>
                                                XXL
                                            </option>
                                            <option value="All_Size"
                                                {{ request()->input('size') == 'All_Size' ? 'selected' : '' }}>All Size
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-filter">
                                        <select class="form-select select2" name="series" id="selectSeries"
                                            aria-label="Default select example">
                                            <option></option>
                                            @foreach ($series as $seri)
                                            <option value="{{ $seri->id }}"
                                                {{ request()->input('series') == $seri->id ? 'selected' : '' }}>
                                                {{ $seri->series }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-filter">
                                        <select class="form-select select2" name="brand" id="selectBrand"
                                            aria-label="Default select example">
                                            <option></option>
                                            @foreach ($brand as $brandItem)
                                            <option value="{{ $brandItem }}"
                                                {{ request()->input('brand') == $brandItem ? 'selected' : '' }}>
                                                {{ $brandItem }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-filter">
                                        <select class="form-select select2" name="grade" id="selectGrade"
                                            aria-label="Default select example">
                                            <option></option>
                                            <option value="Grade 1"
                                                {{ request()->input('grade') == 'Grade 1' ? 'selected' : '' }}>Grade 1
                                            </option>
                                            <option value="Grade 2"
                                                {{ request()->input('grade') == 'Grade 2' ? 'selected' : '' }}>Grade 2
                                            </option>
                                            <option value="Grade 3"
                                                {{ request()->input('grade') == 'Grade 3' ? 'selected' : '' }}>Grade 3
                                            </option>
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

    <div class="show-produk mt-5">
        <div class="container-fluid">
            <div class="container">
                @foreach ($produk->chunk(5) as $chunk)
                <div class="row-kartu d-flex mb-3">
                    @foreach ($chunk as $prod)
                    <div class="col-2" style="margin-right: 43px;">
                        <a href="{{ route('viewDetail', ['id' => $prod->id]) }}" class="card-link">
                            <div class="card custom-card text-bg-dark border-secondary"
                                style="width: 250px; height: 100%;">
                                @foreach ($fotoproduk->where('id_produk', $prod->id)->take(1) as $foto)
                                <img src="{{ asset($foto->path) }}" class="card-img-top img-fluid h-50" alt="fotoproduk"
                                    style="object-fit: cover;">
                                @endforeach
                                <div class=" card-body">
                                    <div class="d-flex">
                                        <div class="avatar avatar-card me-2">
                                            @foreach ($toko->where('id', $prod->id_toko) as $tk)
                                            @foreach ($user->where('id', $tk->id_user) as $usr)
                                            <img class="avatar-img" src="{{ asset($usr->foto_profil) }}" alt="User" />
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
                                    <h5 class="card-title" style="margin-bottom: 2px;">
                                        {{ $prod->nama_produk }}</h5>
                                    <p class="card-text" style="color: orange;">
                                        <strong>Rp{{ number_format($prod->harga) }}
                                            /
                                            3 Hari</strong>
                                    </p>

                                    <p class="card-text">
                                        <img src="{{ asset('storage/icon/box-seam.png') }}" alt="box-seam"
                                            style="width: 1em; height: 1em; vertical-align: middle; fill: white;">
                                        {{ $prod->brand }}
                                    </p>

                                    <p class="card-text">
                                        <img src="{{ asset('storage/icon/tv.png') }}" alt="tv"
                                            style="width: 1em; height: 1em; vertical-align: middle; fill: white;">
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
                @endforeach
            </div>
        </div>
    </div>

    @include('layout.footer')

</section>
@endsection