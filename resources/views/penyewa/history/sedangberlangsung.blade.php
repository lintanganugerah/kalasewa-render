@extends('layout.template')
@extends('layout.navbar')

@section('content')

    <style>
        .no-underline {
            text-decoration: none;
            /* Remove underline */
            color: inherit;
            /* Inherit the color from the parent element or set it explicitly */
        }

        .star-rating {
            direction: rtl;
            display: inline-block;
        }

        .star-rating input[type="radio"] {
            display: none;
        }

        .star-rating label {
            font-size: 2em;
            color: #d3d3d3;
            cursor: pointer;
        }

        .star-rating input[type="radio"]:checked~label {
            color: #ffca08;
        }

        .star-rating label:hover,
        .star-rating label:hover~label {
            color: #ffca08;
        }
    </style>

    <section>

        <div class="header-text-content mt-5 text-center">
            <div class="container-fluid">
                <div class="container">
                    <h1><strong>History Penyewaan</strong></h1>
                </div>
            </div>
        </div>

        <div class="container mt-2">
            @csrf
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif
        </div>

        <div class="button-content mt-5">
            <div class="container-fluid">
                <div class="container">
                    <div class="card">
                        <div class="card-header">
                            <ul class="nav nav-tabs card-header-tabs" id="historyTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link text-secondary" href="{{ route('viewHistoryMenungguDiproses') }}">Menunggu Konfirmasi
                                        @if ($countMenungguDiproses)
                                            <span class="position-top badge rounded-pill bg-danger">
                                                {{ $countMenungguDiproses }}
                                            </span>
                                        @endif
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-secondary" href="{{ route('viewHistoryDalamPengiriman') }}">Dalam
                                        Pengiriman @if ($countDalamPengiriman)
                                            <span class="position-top badge rounded-pill bg-danger">
                                                {{ $countDalamPengiriman }}
                                            </span>
                                        @endif
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="{{ route('viewHistorySedangBerlangsung') }}">Sedang
                                        Digunakan @if ($countSedangBerlangsung)
                                            <span class="position-top badge rounded-pill bg-danger">
                                                {{ $countSedangBerlangsung }}
                                            </span>
                                        @endif
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-secondary" href="{{ route('viewHistoryTelahKembali') }}">Dikirim
                                        Kembali @if ($countTelahKembali)
                                            <span class="position-top badge rounded-pill bg-danger">
                                                {{ $countTelahKembali }}
                                            </span>
                                        @endif
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-secondary" href="{{ route('viewHistoryDibatalkan') }}">Dibatalkan
                                        @if ($countDibatalkan)
                                            <span class="position-top badge rounded-pill bg-danger">
                                                {{ $countDibatalkan }}
                                            </span>
                                        @endif
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-secondary" href="{{ route('viewHistoryDiretur') }}">Diretur
                                        @if ($countDiretur)
                                            <span class="position-top badge rounded-pill bg-danger">
                                                {{ $countDiretur }}
                                            </span>
                                        @endif

                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-secondary" href="{{ route('viewHistoryPenyewaanSelesai') }}">Penyewaan
                                        Selesai</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                @if ($orders)
                                    <table class="table w-100" id="table-history">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nomor Order</th>
                                                <th>Nama Produk</th>
                                                <th>Additional</th>
                                                <th>Toko</th>
                                                <th>Tanggal Mulai</th>
                                                <th>Tanggal Selesai</th>
                                                <th>Total Biaya</th>
                                                <th>Status</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orders as $order)
                                                @if ($order->status == 'Sedang Berlangsung')
                                                    <tr>
                                                        <td data-title="#" class="text-center">
                                                            {{ $loop->iteration }}
                                                        </td>
                                                        <td>{{ $order->nomor_order }}</td>
                                                        <td>{{ $order->nama_produk }}</td>
                                                        <td>
                                                            @if (!empty($order->additional) && is_array($order->additional))
                                                                @foreach ($order->additional as $additionalItem)
                                                                    @if (is_array($additionalItem) && isset($additionalItem['nama']))
                                                                        {{ $additionalItem['nama'] }}
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                <p>-</p>
                                                            @endif
                                                        </td>
                                                        <td>{{ $order->nama_toko }}</td>
                                                        <td>{{ $order->tanggal_mulai }}</td>
                                                        <td>{{ $order->tanggal_selesai }}</td>
                                                        <td>Rp{{ number_format($order->grand_total, 0, '', '.') }}</td>
                                                        <td>
                                                            @if ($order->status == 'Sedang Berlangsung')
                                                                @if ($order->jaminan < 0 && $order->denda_keterlambatan > 0)
                                                                    Kamu terlambat mengembalikan kostum, silahkan bayar denda terlebih dahulu!
                                                                @elseif ($order->jaminan < 0 && $order->ongkir_default == 0)
                                                                    Jaminan ongkir kamu tidak tercukupi. silahkan bayar ongkos kirim yang kurang!
                                                                @else
                                                                    Sedang Digunakan
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($order->jaminan < 0)
                                                                <a href="{{ route('viewPengembalianBarang', ['orderId' => $order->nomor_order]) }}"
                                                                    class="btn btn-danger w-100">Bayar</a>
                                                            @else
                                                                <a class="btn btn-danger w-100" href="#" role="button" data-bs-toggle="modal"
                                                                    data-bs-target="#returModal-{{ $loop->iteration }}">Kembalikan Barang</a>
                                                            @endif
                                                            <a href="{{ url('/chat' . '/' . $order->toko->id_user) }}" target="_blank" class="no-underline"><button type="button"
                                                                    class="btn btn-outline-success w-100 mt-1">Chat Toko</button></a>
                                                        </td>
                                                    </tr>

                                                    <!-- Modal for Resi -->
                                                    <div class="modal fade" id="resiModal-{{ $loop->iteration }}" tabindex="-1"
                                                        aria-labelledby="resiModalLabel-{{ $loop->iteration }}" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h1 class="modal-title fs-5" id="resiModalLabel-{{ $loop->iteration }}">
                                                                        Bukti
                                                                        Resi</h1>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <img src="{{ asset($order->bukti_resi) }}" alt="Resi" class="img-fluid">
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Modal Retur -->
                                                    <div class="modal fade" id="returModal-{{ $loop->iteration }}" tabindex="-1"
                                                        aria-labelledby="exampleModalLabel-{{ $loop->iteration }}" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                                                                        Retur Barang
                                                                    </h1>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <form action="{{ route('returBarang', ['orderId' => $order->nomor_order]) }}" method="POST"
                                                                    enctype="multipart/form-data">
                                                                    @csrf
                                                                    <div class="modal-body">
                                                                        <div class="alamat-pengembalian" style="margin-top: -20px;">
                                                                            <label for="exampleInputEmail1" class="form-label">Alamat
                                                                                Pengembalian</label>
                                                                            <textarea name="alamatpengembalian" placeholder="Alamat Pengembalian Produk" class="form-control form-control-lg w-100" readonly>{{ $order->id_produk_order->getalamatproduk($order->id_produk_order->alamat, $order->id_produk_order->toko->id_user) }}</textarea>
                                                                            <div id="emailHelp" class="form-text">Silahkan
                                                                                lakukan
                                                                                pengiriman kembali ke alamat yang tertera!
                                                                            </div>
                                                                        </div>
                                                                        <div class="nomor-resi mt-2">
                                                                            <label for="exampleInputEmail1" class="form-label">Nomor
                                                                                Resi<span class="text-danger">*</span></label>
                                                                            <input type="text" name="nomor_resi" placeholder="Nomor Resi"
                                                                                class="form-control form-control-lg w-100" />
                                                                            <div id="emailHelp" class="form-text">Nomor
                                                                                resi pengiriman
                                                                                untuk pelacakan</div>
                                                                        </div>
                                                                        <div class="bukti-resi mt-2">
                                                                            <label for="formFile" class="form-label">Bukti
                                                                                Resi /
                                                                                Pengiriman<span class="text-danger">*</span></label>
                                                                            <input class="form-control" type="file" id="formFile" name="bukti_resi_penyewa"
                                                                                accept=".jpg,.png,.jpeg,.webp" required>
                                                                            <div id="emailHelp" class="form-text">Silahkan
                                                                                berikan bukti
                                                                                gambar resi atau screenshot pengiriman
                                                                                barang!</div>
                                                                        </div>

                                                                        <hr>

                                                                        <div class="rating-kostum mt-2">
                                                                            <label for="rating-kostum" class="form-label">Rating
                                                                                Kostum<span class="text-danger">*</span></label><br>
                                                                            <div class="star-rating">
                                                                                <input type="radio" name="rating" id="star5" value="5"><label for="star5"
                                                                                    title="Nilai 5">&#9733;</label>
                                                                                <input type="radio" name="rating" id="star4" value="4"><label for="star4"
                                                                                    title="Nilai 4">&#9733;</label>
                                                                                <input type="radio" name="rating" id="star3" value="3"><label for="star3"
                                                                                    title="Nilai 3">&#9733;</label>
                                                                                <input type="radio" name="rating" id="star2" value="2"><label for="star2"
                                                                                    title="Nilai 2">&#9733;</label>
                                                                                <input type="radio" name="rating" id="star1" value="1"><label for="star1"
                                                                                    title="Nilai 1">&#9733;</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="ulasan-kostum mt-2">
                                                                            <label for="exampleInputEmail1" class="form-label">Ulasan
                                                                                Kostum<span class="text-danger">*</span></label>
                                                                            <textarea name="ulasankostum" placeholder="Ulasan Kostum yang Dirental" class="form-control form-control-lg w-100" required></textarea>
                                                                            <div id="emailHelp" class="form-text">Silahkan
                                                                                tuliskan
                                                                                ulasan anda terhadap kostum yang anda
                                                                                rental!</div>
                                                                        </div>
                                                                        <div class="dokumentasi-kostum mt-2">
                                                                            <label for="formFile" class="form-label">Tambahkan
                                                                                Foto Testimoni</label>
                                                                            <input class="form-control" type="file" id="formFile" name="dokumentasi_kostum[]"
                                                                                accept=".jpg,.png,.jpeg" multiple>
                                                                            <div id="imageContainer" class="rounded mt-3">
                                                                            </div>
                                                                            <div id="emailHelp" class="form-text">Gambar
                                                                                bisa lebih dari
                                                                                1</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                                                                        <button type="submit" class="btn btn-primary">Kirim</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('layout.footer')

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const stars = document.querySelectorAll('.star-rating input');
                stars.forEach(star => {
                    star.addEventListener('change', function() {
                        const ratingValue = this.value;
                        console.log(`Rated: ${ratingValue} stars`);
                        // You can add more code here to handle the rating value
                    });
                });
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const fileInput = document.querySelector('input[type="file"][name="dokumentasi_kostum[]"]');
                const imageContainer = document.getElementById('imageContainer');

                fileInput.addEventListener('change', function(event) {
                    const files = event.target.files;
                    imageContainer.innerHTML = ''; // Clear previous images

                    Array.from(files).forEach(file => {
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.classList.add('img-thumbnail');
                            img.style.width = '100px'; // Adjust size as needed
                            img.style.marginRight = '10px';
                            imageContainer.appendChild(img);
                        };

                        reader.readAsDataURL(file);
                    });
                });
            });
        </script>


    </section>

@endsection
