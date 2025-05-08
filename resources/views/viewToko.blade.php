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
    padding-right: 130%;
    cursor: pointer;
}

.bio {
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 5;
    /* Number of lines to show */
    -webkit-box-orient: vertical;
}

.bio.expanded {
    -webkit-line-clamp: unset;
    /* Show all lines when expanded */
}

.toggle-bio {
    display: block;
    margin-top: 10px;
    cursor: pointer;
    color: #dc3545;
    /* Bootstrap's text-danger color */
    font-weight: bold;
    text-decoration: none;
}

.no-underline {
    text-decoration: none;
    /* Remove underline */
    color: inherit;
    /* Inherit the color from the parent element or set it explicitly */
}
</style>


<section>

    <div class="profil-toko">
        <div class="container-fluid mt-5">
            <div class="container">
                <div class="row">
                    <div class="col-1">
                        @foreach ($user->where('id', $toko->id_user) as $usr)
                        <img class="profile-container" src="{{ asset($usr->foto_profil) }}" alt="User"
                            style="border-radius: 50px; width: 100px;" />
                        @endforeach
                    </div>
                    <div class="col-2">
                        <h4 style="margin-bottom: 0px;">{{ $toko->nama_toko }}</strong></h4>
                        <p style="margin-bottom: 5px;">Rating Toko:
                            @if ($averageTokoRating)
                            {{ number_format($averageTokoRating, 1) }}
                            @else
                            0
                            @endif
                            <i class="ri-star-line"></i>
                        </p>
                        @if ($averageTokoRating >= 4)
                        <span class="badge text-white"
                            style="background: linear-gradient(to right, #EAD946, #D99C00);">Terpercaya</span>
                        @elseif ($averageTokoRating > 0 && $averageTokoRating < 2.5) <span class="badge text-bg-danger">
                            Bermasalah</span>
                            @elseif ($averageTokoRating >= 2.5 && $averageTokoRating < 4) <span class="badge text-white"
                                style="background-color: #EB7F01;">Standar</span>
                                @else
                                <span class="badge text-white" style="background-color: 6DC0D0;">
                                    Pendatang</span>
                                @endif
                    </div>
                    <div class="col-9">
                        <div class="form-filter">
                            <form action="{{ route('searchProdukToko', ['id' => $toko->id]) }}" method="GET" class="">
                                @csrf
                                <div class="searchbar my-3">
                                    <div class="input-group">
                                        <input type="text" name="search"
                                            class="form-control form-search custom-search-bar"
                                            placeholder="Cari katalog di toko ini" aria-label="Search" />
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
                                                        {{ request()->input('gender') == 'Wanita' ? 'selected' : '' }}>
                                                        Wanita
                                                    </option>
                                                    <option value="Semua Gender"
                                                        {{ request()->input('gender') == 'Semua Gender' ? 'selected' : '' }}>
                                                        Semua</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-filter">
                                                <select class="form-select select" name="size" id="selectSize"
                                                    aria-label="Default select example">
                                                    <option></option>
                                                    <option value="XS"
                                                        {{ request()->input('size') == 'XS' ? 'selected' : '' }}>
                                                        XS
                                                    </option>
                                                    <option value="S"
                                                        {{ request()->input('size') == 'S' ? 'selected' : '' }}>S
                                                    </option>
                                                    <option value="M"
                                                        {{ request()->input('size') == 'M' ? 'selected' : '' }}>M
                                                    </option>
                                                    <option value="L"
                                                        {{ request()->input('size') == 'L' ? 'selected' : '' }}>L
                                                    </option>
                                                    <option value="XL"
                                                        {{ request()->input('size') == 'XL' ? 'selected' : '' }}>
                                                        XL
                                                    </option>
                                                    <option value="XXL"
                                                        {{ request()->input('size') == 'XXL' ? 'selected' : '' }}>
                                                        XXL
                                                    </option>
                                                    <option value="All_Size"
                                                        {{ request()->input('size') == 'All_Size' ? 'selected' : '' }}>
                                                        All
                                                        Size
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
                                                        {{ $seri->series }}
                                                    </option>
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
                                                        {{ $brandItem }}
                                                    </option>
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
                                                        {{ request()->input('grade') == 'Grade 1' ? 'selected' : '' }}>
                                                        Grade 1
                                                    </option>
                                                    <option value="Grade 2"
                                                        {{ request()->input('grade') == 'Grade 2' ? 'selected' : '' }}>
                                                        Grade 2
                                                    </option>
                                                    <option value="Grade 3"
                                                        {{ request()->input('grade') == 'Grade 3' ? 'selected' : '' }}>
                                                        Grade 3
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
        </div>
    </div>

    <div class="header-toko">
        <div class="container-fluid">
            <div class="container">
                <p id="bioToko" class="bio">
                    {!! nl2br(e($toko->bio_toko)) !!}
                </p>
                <span id="toggleBio" class="toggle-bio" style="margin-top: -15px;">Lihat Selengkapnya</span>
                <a href="{{ $toko->user->link_sosial_media }}" target="_blank" class="no-underline">
                    <button type="button" class="btn btn-outline-danger mt-1">Kunjungi Sosial
                        Media</button>
                </a>
                <a href="{{ url('/chat' . '/' . $toko->id_user) }}" target="_blank" class="no-underline"><button
                        type="button" class="btn btn-outline-success mt-1">Chat Toko</button></a>
            </div>
        </div>
    </div>

    <div class="container-fluid mt-3">
        <div class="container">
            @foreach ($produk->chunk(5) as $chunk)
            <div class="row-kartu d-flex mb-3">
                @foreach ($chunk as $prod)
                <div class="col-2" style="margin-right: 43px;">
                    <a href="{{ route('viewDetail', ['id' => $prod->id]) }}" class="card-link">
                        <div class="card custom-card text-bg-dark border-secondary" style="width: 250px; height: 100%;">
                            @foreach ($fotoproduk->where('id_produk', $prod->id)->take(1) as $foto)
                            <img src="{{ asset($foto->path) }}" class="card-img-top img-fluid h-50" alt="fotoproduk"
                                style="object-fit: cover;">
                            @endforeach
                            <div class=" card-body">
                                <div class="d-flex">
                                    <div class="avatar avatar-card me-2">
                                        @foreach ($toko->where('id', $prod->id_toko) as $tk)
                                        @foreach ($user->where('id', $tk->id_user) as $usr)
                                        <img class="avatar-img" src="{{ asset($usr->foto_profil) }}" alt="User"
                                            style="border-radius: 30px;" />
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
                                <h5 class="card-title" style="margin-bottom: 2px;">{{ $prod->nama_produk }}
                                </h5>
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

    @include('layout.footer')

</section>
@endsection

<script>
document.addEventListener("DOMContentLoaded", function() {
    const bioElement = document.getElementById('bioToko');
    const toggleLink = document.getElementById('toggleBio');

    // Check if the content overflows
    const isOverflowing = bioElement.scrollHeight > bioElement.clientHeight;

    if (!isOverflowing) {
        // If not overflowing, hide the toggle link
        toggleLink.style.display = 'none';
    }

    toggleLink.addEventListener('click', function() {
        bioElement.classList.toggle('expanded');
        if (bioElement.classList.contains('expanded')) {
            toggleLink.textContent = "Lihat Lebih Sedikit";
        } else {
            toggleLink.textContent = "Lihat Selengkapnya";
        }
    });
});
</script>