@extends('layout.template')
@extends('layout.navbar')

@section('content')
    <section class="py-5">
        <div class="container">
            <!-- Header Section -->
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h1 class="display-4 fw-bold">WISHLIST</h1>
                </div>
            </div>

            <!-- Alert Section -->
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

            <!-- Wishlist Grid -->
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                @foreach ($wishlist as $prod)
                    <div class="col">
                        <a href="{{ route('viewDetail', ['id' => $prod->produk->id]) }}" class="text-decoration-none">
                            <div class="card h-100 bg-dark text-white border-secondary">
                                @foreach ($fotoproduk->where('id_produk', $prod->produk->id)->take(1) as $foto)
                                    <div class="ratio ratio-1x1">
                                        <img src="{{ asset($foto->path) }}" class="card-img-top" alt="fotoproduk"
                                            style="object-fit: cover;">
                                    </div>
                                @endforeach
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="me-2">
                                            @foreach ($toko->where('id', $prod->produk->id_toko) as $tk)
                                                @foreach ($user->where('id', $tk->id_user) as $usr)
                                                    <img src="{{ asset($usr->foto_profil) }}" alt="User"
                                                        class="rounded-circle"
                                                        style="width: 30px; height: 30px; object-fit: cover;">
                                                @endforeach
                                            @endforeach
                                        </div>
                                        <div class="small">
                                            @foreach ($toko->where('id', $prod->produk->id_toko)->take(1) as $tk)
                                                <div class="fw-bold text-truncate">{{ $tk->nama_toko }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <h5 class="card-title">{{ $prod->produk->nama_produk }}</h5>
                                    <p class="card-text text-warning fw-bold">Rp{{ number_format($prod->produk->harga) }} /
                                        3 Hari</p>
                                    <p class="card-text small">
                                        <img src="{{ asset('storage/icon/box-seam.png') }}" alt="box-seam" class="me-1"
                                            style="width: 1em; height: 1em;">
                                        {{ $prod->produk->brand }}
                                    </p>
                                    <p class="card-text small">
                                        <img src="{{ asset('storage/icon/tv.png') }}" alt="tv" class="me-1"
                                            style="width: 1em; height: 1em;">
                                        {{ $prod->produk->seriesDetail->series }}
                                    </p>
                                    <div class="mt-auto pt-2">
                                        <div class="d-flex flex-wrap gap-1">
                                            <span class="badge bg-secondary">{{ $prod->produk->ukuran_produk }}</span>
                                            <span class="badge bg-secondary">{{ $prod->produk->grade }}</span>
                                            <span class="badge bg-secondary">{{ $prod->produk->gender }}</span>
                                            @if ($prod->produk->additional)
                                                <span class="badge bg-secondary">+Additional</span>
                                            @endif
                                        </div>
                                    </div>
                                    <form action="{{ route('wishlist.remove', ['id' => $prod->produk->id]) }}"
                                        method="POST" class="mt-3">
                                        @csrf
                                        <button type="submit" class="btn btn-danger w-100">Hapus dari Wishlist</button>
                                    </form>
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
