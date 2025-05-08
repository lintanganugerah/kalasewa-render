@extends('layout.selllayout')
@section('content')
    <div class="row">
        <div class="col">
            <div class="text-left mb-5 mt-3">
                <!-- Tombol Back -->
                <div class="text-left mt-3 mb-3">
                    <a href="{{ url()->previous() }}" class="btn btn-outline kalasewa-color"><i
                            class="fa-solid fa-arrow-left fa-regular me-2"></i>Kembali</a>
                </div>
                <h1 class="fw-bold text-secondary">Detail Penilaian Produk</h1>
                <h4 class="fw-semibold text-secondary">Lihat komentar dan rating dari penyewa anda disini</h4>
            </div>
            <hr class="bg-dark border-2 border-top border-dark" />
            <div class="row my-4 mx-4">
                <div class="col-md-6 d-flex align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="product-image-container me-3">
                            <img src="{{ asset($produk->getFotoProdukFirst($produk->id)->path) }}" alt="Produk"
                                class="product-image">
                        </div>
                        <div>
                            <h5>{{ $produk->nama_produk }}</h5>
                            <small class="fw-light text-secondary">{{ $produk->brand }},
                                {{ $produk->gender }},
                                {{ $produk->series->series }},
                                {{ $produk->ukuran_produk }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-respon">
                    <h1 class="fw-bold" style="color:#CE2525">Total Rating</h1>
                    <h2 class="fw-semibold">
                        @if ($produk->cek_nilai($produk->id))
                            {{ number_format($produk->avg_nilai_produk($produk->id), 1) }}/5
                            <small>
                                ({{ $produk->total_review_produk($produk->id) }} review)
                            </small>
                        @else
                            --/5
                        @endif
                    </h2>
                </div>
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
                                            @if ($penilaianProduk)
                                                <thead>
                                                    <tr>
                                                        <th>Penyewa</th>
                                                        <th class="col-5">Komentar</th>
                                                        <th>Nilai</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($penilaianProduk as $review)
                                                        <tr>
                                                            <td data-title="Penyewa" class="align-middle">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="image-tabel">
                                                                        <div
                                                                            class="product-image-container product-image-container-tabel">
                                                                            <img src="{{ asset($review->id_review_penyewa->foto_profil) }}"
                                                                                alt="Produk" class="product-image">
                                                                            <!-- Foto User -->
                                                                        </div>
                                                                    </div>
                                                                    <div class="margin-start">
                                                                        <h5>{{ $review->id_review_penyewa->nama }}</h5>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td data-title="Komentar" class="align-middle">
                                                                {{ $review->komentar }}</td>
                                                            <td data-title="Penilaian" class="align-middle">
                                                                {{ $review->nilai }}/5</td>
                                                            <td data-title="Aksi" class="align-middle">
                                                                <button data-bs-toggle="modal"
                                                                    data-bs-target="#modal-foto-{{ $review->id }}"
                                                                    class="btn btn-outline" id="proses"
                                                                    style="color:#CE2525">Lihat
                                                                    Foto</button>
                                                            </td>
                                                        </tr>
                                                        <div class="modal fade" id="modal-foto-{{ $review->id }}"
                                                            tabindex="-1"
                                                            aria-labelledby="modal-foto-{{ $review->id }}Label"
                                                            aria-hidden="true">
                                                            <div
                                                                class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h1 class="modal-title fs-5"
                                                                            id="modal-foto-{{ $review->id }}Label">
                                                                            Foto Review</h1>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div
                                                                            class="d-flex justify-content-start align-items-center">
                                                                            <div class="row">
                                                                                @if ($review->foto)
                                                                                    @foreach (json_decode($review->foto) as $f)
                                                                                        <div class="col my-2">
                                                                                            <div
                                                                                                class="product-image-container-review">
                                                                                                <img src="{{ asset($f) }}"
                                                                                                    alt="Produk"
                                                                                                    class="product-image-review">
                                                                                            </div>
                                                                                        </div>
                                                                                    @endforeach
                                                                                @else
                                                                                    <p> Tidak ada Foto Review </p>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
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

        <!-- Image Enlarge Modal -->
        <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-body">
                        <img src="" class="img-fluid" id="enlargedImg" alt="Enlarged Image">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="closeImageModalBtn">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $('.tabel-data').DataTable({
                lengthMenu: [
                    [5, 15, 30, -1],
                    [5, 15, 30, 'All']
                ],
            });
        </script>
        <script src="{{ asset('seller/modal_auto_muncul.js') }}"></script>
        <script src="{{ asset('seller/inputangka.js') }}"></script>
        <script src="{{ asset('seller/inputfotoproduk.js') }}"></script>
    </div>
@endsection
<link type="text/css" href="{{ asset('seller/review.css') }}" rel="stylesheet">
