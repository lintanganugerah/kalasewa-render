@extends('layout.template')
@extends('layout.navbar')

@section('content')

    <section>

        <div class="container-fluid mt-5">
            <div class="container text-center">
                <h1><strong>INFORMASI PEMESANAN</strong></h1>
            </div>
        </div>

        <div class="container mt-2 mb-3">
            @csrf
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
        </div>

        <div class="container-fluid mt-5">
            <div class="container">
                <form action="{{ route('createOrder', ['id' => $produk->id]) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-3">

                            @foreach ($fotoproduk->where('id_produk', $produk->id)->take(1) as $foto)
                                <img src="{{ asset($foto->path) }}" class="img-thumbnail" alt="fotoproduk">
                            @endforeach

                        </div>
                        <div class="col-6">


                            <h1><strong>{{ $produk->nama_produk }}</strong></h1>

                            @if ($produk->additional)
                                <h3><strong>Pilih Additional</strong></h3>
                                @foreach (json_decode($produk->additional, true) as $nama => $harga)
                                    <input type="checkbox" class="btn-check additional-check"
                                        id="btn-check-additional-{{ $loop->index }}" name="additional[]"
                                        value="{{ $nama }}" data-nama="{{ $nama }}"
                                        data-harga="{{ $harga }}" autocomplete="off">
                                    <label class="btn btn-outline-dark"
                                        for="btn-check-additional-{{ $loop->index }}">{{ $nama }}</label>
                                    <p class="harga-additional-value" hidden>Rp0</p> <!-- Tambahkan kelas unik di sini -->
                                @endforeach
                                <div id="emailHelp" class="form-text">Silahkan pilih additional yang tersedia (opsional)
                                </div>
                            @endif

                            <div class="datepckr d-flex mt-3">
                                <div class="col mx-1">
                                    <label for="exampleInputEmail1" class="form-label">Tanggal Mulai<span
                                            class="text-danger">*</span></label>
                                    <input type="date" id="mulaisewa" name="mulaisewa" placeholder="Tanggal Mulai Sewa"
                                        class="form-control form-control-lg w-100" required />
                                    <div id="emailHelp" class="form-text">Silahkan pilih tanggal mulai sewa anda!</div>
                                </div>
                                <div class="col mx-1">
                                    <label for="exampleInputEmail1" class="form-label">Tanggal Selesai<span
                                            class="text-danger">*</span></label>
                                    <input type="date" id="akhirsewa" name="akhirsewa" placeholder="Tanggal Akhir Sewa"
                                        class="form-control form-control-lg w-100" required readonly />
                                    <div id="emailHelp" class="form-text">Sistem akan otomatis memilih hari akhir sewa!
                                        (durasi 3 hari)</div>
                                </div>
                            </div>

                            <div class="sizeproduk-ctner mt-3" hidden>
                                <label for="exampleInputEmail1" class="form-label">Ukuran<span
                                        class="text-danger">*</span></label>
                                <input type="text" name="size" placeholder="Ukuran Produk"
                                    class="form-control form-control-lg w-100" value="{{ $produk->ukuran_produk }}" />
                                <div id="emailHelp" class="form-text">Ukuran Produk yang Dipilih</div>
                            </div>

                            <div class="namapenyewa-ctner mt-3">
                                <label for="exampleInputEmail1" class="form-label">Nama Penyewa<span
                                        class="text-danger">*</span></label>
                                <input type="text" name="nama" placeholder="Nama Penyewa"
                                    class="form-control form-control-lg w-100" value="{{ auth()->user()->nama }}"
                                    required />
                                <div id="emailHelp" class="form-text">Masukkan nama anda</div>
                            </div>

                            <div class="alamat-ctner mt-3">
                                <label for="exampleInputEmail1" class="form-label">Alamat Penyewa<span
                                        class="text-danger">*</span></label>
                                <textarea name="alamat" placeholder="Alamat Penyewa" class="form-control form-control-lg w-100" required>{{ auth()->user()->alamat }}</textarea>
                                <div id="emailHelp" class="form-text">Pastikan alamat anda diisi dengan lengkap dan
                                    detail
                                    untuk memudahkan pengiriman!</div>
                            </div>

                            <div class="metodekirim-ctner mt-3">
                                <label class="form-label">Metode Pengiriman<span class="text-danger">*</span></label><br>
                                <select name="metodekirim" class="form-select" aria-label="Default select example">
                                    @foreach ($produk->metode_kirim ?? [] as $ekspedisi)
                                        <option value="{{ $ekspedisi }}">{{ $ekspedisi }}</option>
                                    @endforeach
                                </select>
                                <div id="emailHelp" class="form-text">Silahkan pilih metode pengiriman dari toko yang
                                    tersedia!</div>
                            </div>

                            <input type="hidden" id="additional-items" name="additional_items">


                        </div>
                        <div class="col-3">

                            <div class="card">
                                <div class="card-body">
                                    <h5><strong>Ringkasan Belanja</strong></h5>
                                    <table class="w-100">
                                        <tr>
                                            <td class="fw-bold" colspan="3">Total Barang</td>
                                        </tr>
                                        <tr class="text-secondary">
                                            <td>Harga Katalog</td>
                                            <td id="harga-katalog" class="text-end">
                                                Rp{{ number_format($produk->harga, 0, '', '.') }}</td>
                                        </tr>
                                        <tr class="text-secondary">
                                            <td>Harga Additional</td>
                                            <td id="harga-additional-total" class="text-end">Rp0</td>
                                        </tr>
                                        <tr class="text-secondary">
                                            <td>Harga Cuci</td>
                                            @if ($produk->biaya_cuci)
                                                <td id="harga-cuci" class="text-end">
                                                    Rp{{ number_format($produk->biaya_cuci, 0, '', '.') }}</td>
                                            @else
                                                <td id="harga-cuci" class="text-end">Rp{{ number_format(0) }}</td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td class="fw-bold" colspan="3">Biaya Transaksi</td>
                                        </tr>
                                        <tr class="text-secondary">
                                            <td>Jaminan Ongkir</td>
                                            <td id="ongkos-kirim" class="text-end">
                                                Rp{{ number_format(30000, 0, '', '.') }}</td>
                                        </tr>
                                        <tr class="text-secondary">
                                            <td>Jaminan Kostum</td>
                                            <td class="text-end">Rp50.000</td>
                                        </tr>
                                        <tr class="text-secondary">
                                            <td id="biaya-admin-label">Biaya Admin</td>
                                            <td id="biaya-admin-value" class="text-end">Rp0</td>
                                        </tr>
                                    </table>

                                    <h5 class="mt-2"><strong>Total Tagihan</strong></h5>
                                    <h4><strong id="total-tagihan">Rp0</strong>
                                    </h4>
                                    <hr>
                                    <p class="text-secondary">(<span class="text-danger">*</span>) Jaminan akan
                                        dikembalikan
                                        kepada penyewa<a data-bs-toggle="modal" data-bs-target="#infoJaminan"><i
                                                class="fa-solid fa-regular fa-circle-info ms-2"></i></a></p>

                                    <button type="submit" class="btn btn-danger w-100 mt-2">BAYAR</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Grade -->
        <div class="modal fade" id="infoJaminan" tabindex="-1" aria-labelledby="infoJaminanLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="infoJaminanLabel">Informasi Pengembalian Jaminan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="fw-bold">Bagaimana cara jaminan bekerja?</p>
                        <p>Setiap penyewa akan membayarkan 2 jenis jaminan, yaitu <strong>jaminan ongkos kirim dan jaminan
                                i kostum</strong>.
                            Kedua jaminan ini akan digunakan ketika terdapat denda atau pembayaran ongkos kirim dari toko.
                            Jaminan yang tersisa akan dikirimkan kembali kepada penyewa setelah penyewaan selesai.</p>

                        <p class="fw-bold">Perhitungan Jaminan Ongkir</p>
                        <img src="{{ asset('images/bukti_ongkir.png') }}" alt="Input Ongkir"
                            class="img-thumbnail w-100">
                        <p>Jaminan ongkir akan dikalkulasi secara otomatis oleh Kalasewa <strong>setelah diinput oleh
                                toko</strong>.
                            Jaminan ongkos kirim akan dikurangi dengan harga ongkos kirim yang sebenarnya. Jika harga ongkos
                            kirim yang sebenarnya lebih besar daripada harga jaminan, maka kalasewa akan memotong dari
                            jaminan kostum.</p>

                        <img src="{{ asset('images/bukti_denda.png') }}" alt="Bukti denda" class="img-thumbnail w-100">
                        <p>Selain dari ongkos kirim, jaminan juga akan berpengaruh dari <strong>Denda</strong> seperti
                            keterlambatan atau kerusakan yang dilakukan oleh penyewa.</p>

                        <img src="{{ asset('images/bukti_ongkir_penyewa.png') }}" alt="Informasi Ongkir"
                            class="img-thumbnail w-100">
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
                    50000; // 50000 adalah Biaya Jaminan tetap
                totalTagihanElement.textContent = `Rp${totalTagihan.toLocaleString('id-ID')}`;

                // Simpan additionalItems ke dalam hidden input untuk dikirimkan ke server
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
            var mm = String(today.getMonth() + 1).padStart(2, '0'); // Months are zero-based
            var dd = String(today.getDate()).padStart(2, '0');

            // Calculate minDate (H+2)
            var minDate = new Date(today);
            minDate.setDate(today.getDate() + 2);
            var minyyyy = minDate.getFullYear();
            var minmm = String(minDate.getMonth() + 1).padStart(2, '0');
            var mindd = String(minDate.getDate()).padStart(2, '0');
            var minDateString = minyyyy + '-' + minmm + '-' + mindd;

            // Calculate maxDate (H+7)
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
