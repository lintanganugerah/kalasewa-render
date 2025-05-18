@extends('layout.template')
@extends('layout.navbar')

@section('content')
    <section class="py-5">
        <div class="container">
            <!-- Header Section -->
            <div class="row mb-4">
                <div class="col-12 text-center">
                    <h1 class="display-4 fw-bold">INFORMASI PEMESANAN</h1>
                </div>
            </div>

            <!-- Alert Section -->
            <div class="row mb-4">
                <div class="col-12">
                    @csrf
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ $errors->first() }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
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
                </div>
            </div>

            <!-- Main Content -->
            <div class="row">
                <div class="col-12">
                    <form action="{{ route('createOrder', ['id' => $produk->id]) }}" method="POST">
                        @csrf
                        <div class="row g-4">
                            <!-- Product Image -->
                            <div class="col-md-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        @foreach ($fotoproduk->where('id_produk', $produk->id)->take(1) as $foto)
                                            <img src="{{ asset($foto->path) }}" class="img-fluid rounded" alt="fotoproduk">
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Product Details -->
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h2 class="card-title fw-bold mb-4">{{ $produk->nama_produk }}</h2>

                                        @if ($produk->additional)
                                            <div class="mb-4">
                                                <h4 class="fw-bold mb-3">Pilih Additional</h4>
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach (json_decode($produk->additional, true) as $nama => $harga)
                                                        <div class="form-check">
                                                            <input type="checkbox" class="btn-check additional-check"
                                                                id="btn-check-additional-{{ $loop->index }}"
                                                                name="additional[]" value="{{ $nama }}"
                                                                data-nama="{{ $nama }}"
                                                                data-harga="{{ $harga }}" autocomplete="off">
                                                            <label class="btn btn-outline-dark"
                                                                for="btn-check-additional-{{ $loop->index }}">{{ $nama }}</label>
                                                            <p class="harga-additional-value d-none">Rp0</p>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div class="form-text">Silahkan pilih additional yang tersedia (opsional)
                                                </div>
                                            </div>
                                        @endif

                                        <div class="row g-3 mb-4">
                                            <div class="col-md-6">
                                                <label class="form-label">Tanggal Mulai<span
                                                        class="text-danger">*</span></label>
                                                <input type="date" id="mulaisewa" name="mulaisewa"
                                                    class="form-control form-control-lg" required>
                                                <div class="form-text">Silahkan pilih tanggal mulai sewa anda!</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Tanggal Selesai<span
                                                        class="text-danger">*</span></label>
                                                <input type="date" id="akhirsewa" name="akhirsewa"
                                                    class="form-control form-control-lg" required readonly>
                                                <div class="form-text">Sistem akan otomatis memilih hari akhir sewa! (durasi
                                                    3 hari)</div>
                                            </div>
                                        </div>

                                        <div class="mb-4" hidden>
                                            <label class="form-label">Ukuran<span class="text-danger">*</span></label>
                                            <input type="text" name="size" class="form-control form-control-lg"
                                                value="{{ $produk->ukuran_produk }}" readonly>
                                            <div class="form-text">Ukuran Produk yang Dipilih</div>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label">Nama Penyewa<span class="text-danger">*</span></label>
                                            <input type="text" name="nama" class="form-control form-control-lg"
                                                value="{{ auth()->user()->nama }}" required>
                                            <div class="form-text">Masukkan nama anda</div>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label">Alamat Penyewa<span
                                                    class="text-danger">*</span></label>
                                            <textarea name="alamat" class="form-control form-control-lg" required>{{ auth()->user()->alamat }}</textarea>
                                            <div class="form-text">Pastikan alamat anda diisi dengan lengkap dan detail
                                                untuk memudahkan pengiriman!</div>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label">Metode Pengiriman<span
                                                    class="text-danger">*</span></label>
                                            <select name="metodekirim" class="form-select form-select-lg">
                                                @foreach ($produk->metode_kirim ?? [] as $ekspedisi)
                                                    <option value="{{ $ekspedisi }}">{{ $ekspedisi }}</option>
                                                @endforeach
                                            </select>
                                            <div class="form-text">Silahkan pilih metode pengiriman dari toko yang
                                                tersedia!</div>
                                        </div>

                                        <input type="hidden" id="additional-items" name="additional_items">
                                    </div>
                                </div>
                            </div>

                            <!-- Order Summary -->
                            <div class="col-md-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title fw-bold mb-4">Ringkasan Belanja</h5>

                                        <div class="mb-4">
                                            <h6 class="fw-bold">Total Barang</h6>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-secondary">Harga Katalog</span>
                                                <span
                                                    id="harga-katalog">Rp{{ number_format($produk->harga, 0, '', '.') }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-secondary">Harga Additional</span>
                                                <span id="harga-additional-total">Rp0</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-secondary">Harga Cuci</span>
                                                <span id="harga-cuci">
                                                    @if ($produk->biaya_cuci)
                                                        Rp{{ number_format($produk->biaya_cuci, 0, '', '.') }}
                                                    @else
                                                        Rp0
                                                    @endif
                                                </span>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <h6 class="fw-bold">Biaya Transaksi</h6>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-secondary">Jaminan Ongkir</span>
                                                <span id="ongkos-kirim">Rp30.000</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-secondary">Jaminan Kostum</span>
                                                <span>Rp50.000</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-secondary">Biaya Admin</span>
                                                <span id="biaya-admin-value">Rp0</span>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <h6 class="fw-bold">Total Tagihan</h6>
                                            <h4 class="fw-bold" id="total-tagihan">Rp0</h4>
                                        </div>

                                        <hr>

                                        <p class="text-secondary small">
                                            (<span class="text-danger">*</span>) Jaminan akan dikembalikan kepada penyewa
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#infoJaminan">
                                                <i class="fa-solid fa-regular fa-circle-info ms-2"></i>
                                            </a>
                                        </p>

                                        <button type="submit" class="btn btn-danger w-100">BAYAR</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Info Jaminan -->
        <div class="modal fade" id="infoJaminan" tabindex="-1" aria-labelledby="infoJaminanLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="infoJaminanLabel">Informasi Pengembalian Jaminan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h6 class="fw-bold">Bagaimana cara jaminan bekerja?</h6>
                        <p>Setiap penyewa akan membayarkan 2 jenis jaminan, yaitu <strong>jaminan ongkos kirim dan jaminan
                                kostum</strong>.
                            Kedua jaminan ini akan digunakan ketika terdapat denda atau pembayaran ongkos kirim dari toko.
                            Jaminan yang tersisa akan dikirimkan kembali kepada penyewa setelah penyewaan selesai.</p>

                        <h6 class="fw-bold mt-4">Perhitungan Jaminan Ongkir</h6>
                        <img src="{{ asset('images/bukti_ongkir.png') }}" alt="Input Ongkir"
                            class="img-fluid rounded mb-3">
                        <p>Jaminan ongkir akan dikalkulasi secara otomatis oleh Kalasewa <strong>setelah diinput oleh
                                toko</strong>.
                            Jaminan ongkos kirim akan dikurangi dengan harga ongkos kirim yang sebenarnya. Jika harga ongkos
                            kirim yang sebenarnya lebih besar daripada harga jaminan, maka kalasewa akan memotong dari
                            jaminan kostum.</p>

                        <img src="{{ asset('images/bukti_denda.png') }}" alt="Bukti denda"
                            class="img-fluid rounded mb-3">
                        <p>Selain dari ongkos kirim, jaminan juga akan berpengaruh dari <strong>Denda</strong> seperti
                            keterlambatan atau kerusakan yang dilakukan oleh penyewa.</p>

                        <img src="{{ asset('images/bukti_ongkir_penyewa.png') }}" alt="Informasi Ongkir"
                            class="img-fluid rounded mb-3">
                        <p>Setelah inputan dari toko, maka penyewa akan mendapatkan informasi berupa sisa jaminan yang
                            tersisa yang dapat dikembalikan dari kalasewa.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        @include('layout.footer')
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const hargaKatalogElement = document.getElementById('harga-katalog');
            const hargaAdditionalLabels = document.querySelectorAll('#harga-additional-label');
            const hargaAdditionalValues = document.querySelectorAll('.harga-additional-value');
            const biayaAdminElement = document.getElementById('biaya-admin-value');
            const hargaCuciValues = document.getElementById('harga-cuci');
            const hargaOngkosKirim = document.getElementById('ongkos-kirim');
            const totalTagihanElement = document.getElementById('total-tagihan');
            const additionalCheckboxes = document.querySelectorAll('.additional-check');

            const updateCosts = () => {
                let hargaKatalog = parseInt(hargaKatalogElement.textContent.replace('Rp', '').replace(/\./g,
                    ''));
                let hargaCuci = parseInt(hargaCuciValues.textContent.replace('Rp', '').replace(/\./g, ''));
                let ongkosKirim = parseInt(hargaOngkosKirim.textContent.replace('Rp', '').replace(/\./g, ''));
                let totalHargaAdditional = 0;
                let additionalItems = [];

                additionalCheckboxes.forEach((checkbox, index) => {
                    if (checkbox.checked) {
                        let namaAdditional = checkbox.getAttribute('data-nama');
                        let hargaAdditional = parseInt(checkbox.getAttribute('data-harga'));
                        totalHargaAdditional += hargaAdditional;
                        additionalItems.push({
                            nama: namaAdditional,
                            harga: hargaAdditional
                        });
                    }
                });

                document.getElementById('harga-additional-total').textContent =
                    `Rp${totalHargaAdditional.toLocaleString('id-ID')}`;

                let biayaAdmin = (hargaKatalog + totalHargaAdditional) * 0.05;
                biayaAdminElement.textContent = `Rp${biayaAdmin.toLocaleString('id-ID')}`;

                let totalTagihan = hargaKatalog + totalHargaAdditional + biayaAdmin + hargaCuci + ongkosKirim +
                    50000;
                totalTagihanElement.textContent = `Rp${totalTagihan.toLocaleString('id-ID')}`;

                document.getElementById('additional-items').value = JSON.stringify(additionalItems);
            };

            additionalCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateCosts);
            });

            updateCosts();
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var today = new Date();
            var yyyy = today.getFullYear();
            var mm = String(today.getMonth() + 1).padStart(2, '0');
            var dd = String(today.getDate()).padStart(2, '0');

            var minDate = new Date(today);
            minDate.setDate(today.getDate() + 2);
            var minyyyy = minDate.getFullYear();
            var minmm = String(minDate.getMonth() + 1).padStart(2, '0');
            var mindd = String(minDate.getDate()).padStart(2, '0');
            var minDateString = minyyyy + '-' + minmm + '-' + mindd;

            var maxDate = new Date(today);
            maxDate.setDate(today.getDate() + 7);
            var maxyyyy = maxDate.getFullYear();
            var maxmm = String(maxDate.getMonth() + 1).padStart(2, '0');
            var maxdd = String(maxDate.getDate()).padStart(2, '0');
            var maxDateString = maxyyyy + '-' + maxmm + '-' + maxdd;

            var mulaisewaInput = document.getElementById('mulaisewa');
            var akhirsewaInput = document.getElementById('akhirsewa');

            mulaisewaInput.setAttribute('min', minDateString);
            mulaisewaInput.setAttribute('max', maxDateString);

            mulaisewaInput.addEventListener('change', function() {
                var selectedDate = new Date(this.value);
                var akhirDate = new Date(selectedDate);
                akhirDate.setDate(selectedDate.getDate() + 2);
                var akhiryyyy = akhirDate.getFullYear();
                var akhirmm = String(akhirDate.getMonth() + 1).padStart(2, '0');
                var akhirdd = String(akhirDate.getDate()).padStart(2, '0');
                var akhirDateString = akhiryyyy + '-' + akhirmm + '-' + akhirdd;

                akhirsewaInput.value = akhirDateString;
            });
        });
    </script>
@endsection
