@extends('layout.selllayout')
@section('content')
    <div class="row">
        <div class="col">
            <div class="text-left mb-5 mt-3">
                <h1 class="fw-bold text-secondary">Penilaian</h1>
                <h4 class="fw-semibold text-secondary">Lihat penilaian setiap produk anda disini</h4>
            </div>

            <div class="row gx-5">
                <div class="card">
                    <div class="tab-content">
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane fade show active mt-3" id="Informasi" role="tabpanel"
                                    aria-labelledby="Informasi-tab">
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            {{ $errors->first() }}
                                        </div>
                                    @endif
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
                                    <div class="text-dark rounded-3">
                                        <table id="tabel"
                                            class="no-more-tables table table-sm table-light w-100 tabel-data align-items-center"
                                            style="word-wrap: break-word;" cellspacing="0">
                                            @if ($produk)
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Produk</th>
                                                        <th>Avg Nilai</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($produk as $prdk)
                                                        <tr>
                                                            <td data-title="#" class="align-middle">{{ $loop->iteration }}
                                                            </td>
                                                            <td data-title="Produk" class="align-middle">
                                                                <div class="d-flex align-items-center">
                                                                    <div
                                                                        class="product-image-container product-image-container-tabel">
                                                                        <img src="{{ asset($prdk->getFotoProdukFirst($prdk->id)->path) }}"
                                                                            alt="Produk" class="product-image">
                                                                        <!-- Foto Produk yang pertama dari id_produk di tabel review -->
                                                                    </div>
                                                                    <div class="margin-start">
                                                                        <h5>{{ $prdk->nama_produk }}</h5>
                                                                        <!-- Nama Produk sesuai dari id_produk di tabel review -->
                                                                        <small
                                                                            class="fw-light text-secondary">{{ $prdk->brand }},
                                                                            {{ $prdk->gender }},
                                                                            {{ $prdk->series->series }},
                                                                            {{ $prdk->ukuran_produk }}</small>
                                                                        <!-- Detail dari Produk yaitu brand, gender, series, ukuran -->
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td data-title="Avg Penilaian" class="align-middle">
                                                                @if ($prdk->cek_nilai($prdk->id))
                                                                    {{ number_format($prdk->avg_nilai_produk($prdk->id), 1) }}/5
                                                                    <small>
                                                                        ({{ $prdk->total_review_produk($prdk->id) }}
                                                                        review)
                                                                    </small>
                                                                @else
                                                                    Belum ada Penilaian
                                                                @endif
                                                            </td>
                                                            <!-- Penilaian dari tabel review berdasarkan produk -->
                                                            <td data-title="Aksi" class="align-middle">
                                                                <a href="{{ route('seller.view.penilaian.detailPenilaianProduk', $prdk->id) }}"
                                                                    class="btn btn-outline" id="proses"
                                                                    style="color:#CE2525">Lihat
                                                                    Detail</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $('.tabel-data').DataTable({
                lengthMenu: [
                    [25, -1],
                    [25, 'All']
                ],
            });
        </script>
        <script src="{{ asset('seller/modal_auto_muncul.js') }}"></script>
        <script src="{{ asset('seller/inputangka.js') }}"></script>
        <script src="{{ asset('seller/inputfotoproduk.js') }}"></script>
    </div>
@endsection
<link type="text/css" href="{{ asset('seller/review.css') }}" rel="stylesheet">
