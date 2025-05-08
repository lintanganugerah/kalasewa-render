@extends('layout.template')
@extends('layout.navbar')

@section('content')
<link rel="stylesheet" type="text/css" href="{{ asset('css/searchbar.css') }}" />
<section>

    <div class="header-title">
        <div class="container-fluid mt-5">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center">
                        <h1><strong>Cari toko favoritmu!</strong></h1>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="filter-select">
        <div class="container-fluid">
            <div class="container">
                <div class="form-filter">
                    <form action="{{ route('searchToko') }}" method="GET" class="">
                        @csrf
                        <div class="searchbar my-3">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control form-search custom-search-bar"
                                    placeholder="Toko mana yang akan dikunjungi hari ini?" aria-label="Search" />
                                <button class="btn py-2 custom-search-button" type="submit" id="search-button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="container">
            @foreach ($toko->chunk(5) as $chunk)
            <div class="row-kartu d-flex mb-3">
                @foreach ($chunk as $tk)
                <div class="col-2" style="margin-right: 43px;">
                    <a href="{{ route('viewToko', ['id' => $tk->id]) }}" class="card-link">
                        <div class="card custom-card text-bg-dark border-secondary" style="width: 250px; height: 100%;">
                            <img src="{{ asset($tk->user->foto_profil) }}" class="card-img-top img-fluid h-100"
                                alt="fotoproduk" style="object-fit: cover;">
                            <div class="card-body">
                                <h5 style="margin-bottom: 5px;"><strong>{{ $tk->nama_toko }}</strong></h5>
                                <p style="margin-bottom: 5px;">Rating Toko:
                                    @if(isset($averageRatings[$tk->id]))
                                    {{ number_format($averageRatings[$tk->id], 1) }}
                                    @else
                                    0
                                    @endif
                                    <i class="ri-star-line"></i>
                                </p>
                                @if (isset($averageRatings[$tk->id]) && $averageRatings[$tk->id] >= 4)
                                <span class="badge text-white"
                                    style="background: linear-gradient(to right, #EAD946, #D99C00);">Terpercaya</span>
                                @elseif (
                                isset($averageRatings[$tk->id]) && $averageRatings[$tk->id] > 0 &&
                                $averageRatings[$tk->id] < 2.5 ) <span class="badge text-bg-danger">Bermasalah</span>
                                    @elseif (
                                    isset($averageRatings[$tk->id]) && $averageRatings[$tk->id] >= 2.5 &&
                                    $averageRatings[$tk->id] < 4 ) <span class="badge text-white"
                                        style="background-color: #EB7F01;">Standar</span>
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
            @endforeach
        </div>
    </div>

    @include('layout.footer')

</section>

@endsection