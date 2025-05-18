@extends('layout.template')
@extends('layout.navbar')

@section('content')
    <section class="py-5">
        <div class="container">
            <!-- Header Section -->
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h1 class="display-4 fw-bold">Cari toko favoritmu!</h1>
                </div>
            </div>

            <!-- Search Section -->
            <div class="row mb-5">
                <div class="col-12">
                    <form action="{{ route('searchToko') }}" method="GET">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="search" class="form-control form-control-lg"
                                placeholder="Toko mana yang akan dikunjungi hari ini?" aria-label="Search">
                            <button class="btn btn-danger" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Store List Section -->
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-5 g-4">
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
