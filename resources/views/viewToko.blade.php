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
    </style>


    <section class="py-5">
        <!-- Store Profile Section -->
        <div class="container">
            <div class="row align-items-center mb-4">
                <div class="col-md-3 mb-4">
                    <div class="d-flex align-items-center gap-3">
                        @foreach ($user->where('id', $toko->id_user) as $usr)
                            <img src="{{ asset($usr->foto_profil) }}" alt="User" class="img-fluid rounded-circle"
                                style="width: 100px; height: 100px; object-fit: cover;">
                        @endforeach
                        <div class="d-flex flex-column">
                            <h4 class="mb-2">{{ $toko->nama_toko }}</h4>
                            <p class="mb-2">
                                Rating Toko:
                                @if ($averageTokoRating)
                                    {{ number_format($averageTokoRating, 1) }}
                                @else
                                    0
                                @endif
                                <i class="ri-star-line"></i>
                            </p>
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
                    </div>
                </div>
                <div class="col-md-9">
                    <form action="{{ route('searchProdukToko', ['id' => $toko->id]) }}" method="GET">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control form-control-lg"
                                        placeholder="Cari katalog di toko ini" aria-label="Search">
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
                                    <option value="Wanita" {{ request()->input('gender') == 'Wanita' ? 'selected' : '' }}>
                                        Wanita</option>
                                    <option value="Semua Gender"
                                        {{ request()->input('gender') == 'Semua Gender' ? 'selected' : '' }}>Semua</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select select2" name="size" id="selectSize">
                                    <option></option>
                                    <option value="XS" {{ request()->input('size') == 'XS' ? 'selected' : '' }}>XS
                                    </option>
                                    <option value="S" {{ request()->input('size') == 'S' ? 'selected' : '' }}>S
                                    </option>
                                    <option value="M" {{ request()->input('size') == 'M' ? 'selected' : '' }}>M
                                    </option>
                                    <option value="L" {{ request()->input('size') == 'L' ? 'selected' : '' }}>L
                                    </option>
                                    <option value="XL" {{ request()->input('size') == 'XL' ? 'selected' : '' }}>XL
                                    </option>
                                    <option value="XXL" {{ request()->input('size') == 'XXL' ? 'selected' : '' }}>XXL
                                    </option>
                                    <option value="All_Size"
                                        {{ request()->input('size') == 'All_Size' ? 'selected' : '' }}>All Size</option>
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
                                    <option value="Grade 1" {{ request()->input('grade') == 'Grade 1' ? 'selected' : '' }}>
                                        Grade 1</option>
                                    <option value="Grade 2" {{ request()->input('grade') == 'Grade 2' ? 'selected' : '' }}>
                                        Grade 2</option>
                                    <option value="Grade 3" {{ request()->input('grade') == 'Grade 3' ? 'selected' : '' }}>
                                        Grade 3</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Store Bio Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <p id="bioToko" class="bio mb-2">
                        {!! nl2br(e($toko->bio_toko)) !!}
                    </p>
                    <span id="toggleBio" class="toggle-bio">Lihat Selengkapnya</span>
                    <div class="mt-3">
                        <a href="{{ $toko->user->link_sosial_media }}" target="_blank" class="btn btn-outline-danger me-2">
                            Kunjungi Sosial Media
                        </a>
                        <a href="{{ url('/chat' . '/' . $toko->id_user) }}" target="_blank"
                            class="btn btn-outline-success">
                            Chat Toko
                        </a>
                    </div>
                </div>
            </div>

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
        </div>

        @include('layout.footer')
    </section>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const bioElement = document.getElementById('bioToko');
        const toggleLink = document.getElementById('toggleBio');

        const isOverflowing = bioElement.scrollHeight > bioElement.clientHeight;

        if (!isOverflowing) {
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
