@extends('layout.selllayout')
@section('content')
    <div class="row">
        <div class="col">
            <div class="text-left mb-5 mt-3 ml-4">
                <!-- Tombol Back -->
                <div class="text-left mt-3 mb-4">
                    <a href="{{ route('seller.profil.viewPeraturanSewaToko') }}" class="btn btn-outline kalasewa-color"><i
                            class="fa-solid fa-arrow-left fa-regular me-2"></i>Kembali</a>
                </div>
                <h1 class="fw-bold text-secondary">Tambah Peraturan Sewa</h1>
                <h4 class="fw-semibold text-secondary">Masukan informasi detail peraturan tersebut disini</h4>
            </div>

            <div class="row gx-5">

                <div class="card">
                    <div class="card-body">
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="Informasi" role="tabpanel"
                                aria-labelledby="Informasi-tab">
                                <h4 class="mb-3">Informasi Peraturan</h4>
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
                                <form action="{{ route('seller.profil.TambahPeraturanSewaAction') }}" id="formproduk"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="exampleFormControlInput1" class="form-label">Nama Peraturan<span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="exampleFormControlInput1"
                                            name="namaPeraturan"
                                            placeholder="Contoh: Wig Dipotong, Kostum Rusak, Aksesori Hilang" required>
                                        <div id="namaPeraturan" class="form-text" style="opacity: 75%;">Nama
                                            adalah
                                            kesimpulan dari penjelasan/deskripsi peraturan tersebut</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="deskripsi" class="form-label">Deskripsi Peraturan<span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control"
                                            placeholder="Contoh : Jika ada bagian kostum yang rusak, aksesoris yang hilang, wig yang dipotong, dan sejenisnya. Maka harus mengganti barang asli atau seharga barang yang dihilangkan atau dirusak."
                                            id="deskripsi" rows="10" name="deskripsiPeraturan" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Apakah terdapat denda?</label><br>
                                        <input type="radio" id="dendaTidak" name="denda" value="tidak"
                                            onchange="toggleDendaOptions()" required> Tidak
                                        <input type="radio" id="dendaYa" name="denda" value="ya"
                                            onchange="toggleDendaOptions()"> Ya
                                    </div>
                                    <div id="options" class="mb-3">
                                        {{-- Bakal di isi nanti disini --}}
                                    </div>

                                    <div class="d-grid mb-5 mt-5">
                                        <button class="btn btn-kalasewa btn-lg btn-block" type="submit">Buat
                                            Peraturan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="infoModalLabel">Informasi Denda</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <h5 class="fw-bold">Saya bingung dengan pertanyaan ini.</h5>
                                    <ul>
                                        <li><strong>Jika denda memiliki rentang nominal atau harga denda tergantung suatu
                                                kondisi</strong>, pilih
                                            opsi <strong>Tidak</strong>. Berikan penjelasan secara deskriptif mengenai denda
                                            tersebut.
                                        </li>

                                        <li> <strong>Jika Anda sudah mengetahui pasti nominal denda</strong>, maka pilih
                                            opsi
                                            <strong>Ya</strong>. Pastikan Anda mengisi angka tanpa titik.
                                        </li>
                                    </ul>
                                    <p> Kedua tipe denda yang anda isikan ini nanti akan ditampilkan kepada penyewa
                                        sebelum melakukan sewa </p>

                                    <h5 class="fw-bold">Mengapa tipe denda dibedakan?</h5>
                                    <p>Ketika penyewa melanggar peraturan ini, Anda dapat mengajukan denda penyewaan. Anda
                                        akan memilih nama peraturan yang dilanggar dan biaya denda tersebut.</p>

                                    <ul>
                                        <li>Ketika peraturan memiliki denda dengan nominal yang sudah pasti, maka sistem
                                            akan
                                            otomatis menetapkan nominal denda tersebut sesuai yang Anda berikan sekarang.
                                            Seperti gambar dibawah ini: <br>
                                            <img src="{{ asset('storage/helper_foto/peraturan_pasti.png') }}"
                                                class="img-fluid">
                                            <br>
                                        </li>
                                        <br>
                                        <li>Sebaliknya, ketika nominal denda masih memiliki rentang atau tidak pasti, maka
                                            Anda harus mengisi secara manual nantinya pada saat pengajuan denda. Seperti
                                            dibawah ini: <br>
                                            <img src="{{ asset('storage/helper_foto/peraturan_tidak_pasti.png') }}"
                                                class="img-fluid">
                                            <br>
                                        </li>
                                    </ul>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('seller/optionDenda.js') }}"></script>
    <script src="{{ asset('seller/inputfotoproduk.js') }}"></script>
@endsection
