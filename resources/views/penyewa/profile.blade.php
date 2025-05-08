@extends('layout.layout-seller')
@extends('layout.navbar')
@section('content')
<section class="vh-100">
    <div class="container py-5 h-100">
        <div class="mb-3 pb-1">
            <span class="h1 fw-bold mb-0">Informasi Anda</span>
        </div>

        <div class="card mb-3">
            <div class="row">
                <div class="d-md-block align-items-center">
                    <div class="card-body p-4 p-lg-5 text-black">
                        <h2><strong>Informasi Kredibilitas</strong></h2>
                        <span>
                            <p>Sejauh ini, anda memiliki rating sebesar:
                                <strong>{{ number_format(auth()->user()->avg_nilai_penyewa(), 2) }}</strong> <br>dan
                                baru
                                pernah
                                menyewa sebanyak:
                                <strong>{{ number_format(auth()->user()->total_review_penyewa()) }}x</strong>
                            </p>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card" style="border-radius: 1rem;">
            <div class="row">
                <div class="d-md-block align-items-center">
                    <div class="card-body p-4 p-lg-5 text-black">

                        <form action="{{ route('updateProfilAction') }}" method="POST" enctype="multipart/form-data">
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

                            <div class="mb-3">
                                <label for="userPhoto" class="form-label">Foto Profil</label>
                                <div class="d-flex align-items-start">
                                    <div id="userPhotoContainer" class="me-3">
                                        <img id="userPhotoPreview" src="{{ asset(session('profilpath')) }}"
                                            style="width:150px; height:150px; object-fit: cover;" alt="User Photo"
                                            class="img-thumbnail">
                                    </div>
                                    <div class="flex-grow-1">
                                        <small class="form-text text-muted">
                                            <ul>
                                                <li>Disarankan Rasio foto 1:1 atau object berada di tengah</li>
                                                <li>Ukuran max 5MB</li>
                                                <li>JPG, JPEG, PNG, WEBP</li>
                                            </ul>
                                        </small>
                                        <input type="file" name="foto" class="form-control mb-2" id="userPhoto"
                                            accept=".jpg,.png,.jpeg,.webp">
                                    </div>
                                </div>
                            </div>

                            <div class="form-outline mb-4">
                                <label class="form-label" for="email">Email</label>
                                <input type="text" id="email" class="form-control form-control-lg"
                                    value="{{ $user->email }}" disabled />
                                <div id="password" class="form-text">Email tidak dapat diubah untuk kebutuhan verifikasi
                                    identitas</div>
                            </div>

                            <h5 class="fw-bold mb-3 pb-3" style="letter-spacing: 1px;">Informasi Pribadi
                            </h5>

                            <div class="form-outline mb-4">
                                <label class="form-label" for="nama">Nama Lengkap</label>
                                <input type="text" id="nama" class="form-control form-control-lg"
                                    value="{{ $user->nama }}" disabled />
                                <div id="password" class="form-text">Nama tidak dapat diubah untuk kebutuhan verifikasi
                                    identitas</div>
                            </div>

                            <div class="form-outline mb-4">
                                <label class="form-label" for="form2Example17">Nomor Identitas Kependudukan
                                    (NIK)</label>
                                <input type="text" id="form2Example17" class="form-control form-control-lg"
                                    value="{{ $user->NIK }}" disabled />
                                <div id="password" class="form-text">Nomor Kependudukan tidak dapat diubah untuk
                                    kebutuhan verifikasi
                                    identitas</div>
                            </div>

                            <div class="form-outline mb-4">
                                <label class="form-label" for="nomor_telpon">Nomor Telpon</label>
                                <input type="text" id="nomor_telpon" class="form-control form-control-lg"
                                    name="nomor_telpon" pattern="[0-9]*" minlength="10" maxlength="14"
                                    value="{{ $user->no_telp }}" required />
                                <div id="password" class="form-text">Nomor pribadi anda yang dapat dihubungi</div>
                            </div>

                            <div class="form-outline mb-4">
                                <label class="form-label" for="nomor_telpon_darurat">Nomor Telpon Darurat</label>
                                <input type="text" id="nomor_telpon_darurat" class="form-control form-control-lg"
                                    name="nomor_telpon_darurat" pattern="[0-9]*" minlength="10" maxlength="14"
                                    value="{{ $user->no_darurat }}" required />
                                <div id="password" class="form-text">Nomor darurat yang dapat dihubungi. Nomor darurat
                                    tidak boleh sama dengan nomor pribadi!</div>
                            </div>

                            <div class="form-outline mb-4">
                                <label class="form-label" for="form2Example17">Link Sosial Media</label>
                                <input type="text" id="form2Example17" class="form-control form-control-lg"
                                    value="{{ $user->link_sosial_media }}" name="link_sosial_media" required />
                                <div id="password" class="form-text">Misal https://www.instagram.com/akun_anda/</div>
                            </div>

                            <div data-mdb-input-init class="form-outline mb-4">
                                <label class="form-label" for="form2Example27">Alamat</label>
                                <textarea type="text" id="alamat" class="form-control form-control-lg" name="alamat"
                                    required> {{ $user->alamat }} </textarea>
                                <div id="HELPER" class="form-text">Pastikan alamat yang digunakan ditulis dengan detail!
                                    ini akan memudahkan anda saat pengiriman barang rental!</div>
                            </div>


                            <div class="form-floating mb-4">
                                <select class="form-select" id="floatingSelect" name="provinsi"
                                    aria-label="Floating label select example" required>
                                    <option selected> {{ $user->provinsi }} </option>
                                    <option value="Jawa Barat">Jawa Barat</option>
                                </select>
                                <label for="floatingSelect">Provinsi</label>
                                <div id="provinsi" class="form-text">Mohon maaf saat ini kami hanya melayani
                                    wilayah
                                    bandung saja
                                </div>
                            </div>

                            <div class="form-floating mb-4">
                                <select class="form-select" id="floatingSelect" name="kota"
                                    aria-label="Floating label select example" required>
                                    <option selected> {{ $user->kota }} </option>
                                    <option value="Kota Bandung">Kota Bandung</option>
                                    <option value="Kabupaten Bandung">Kabupaten Bandung</option>
                                </select>
                                <label for="floatingSelect">Kota/Kabupaten</label>
                                <div id="provinsi" class="form-text">Mohon maaf saat ini kami hanya melayani wilayah
                                    bandung saja</div>
                            </div>

                            <div class="d-grid mb-5">
                                <div class="form-outline">
                                    <label class="form-label" for="kodePos">Kode Pos</label>
                                    <input type="text" id="kodePos" class="form-control" name="kodePos" pattern="[0-9]*"
                                        minlength="5" maxlength="6" value="{{ $user->kode_pos }}" required />
                                </div>
                                <div id="provinsi" class="form-text">Mohon maaf saat ini kami hanya melayani wilayah
                                    bandung saja</div>
                            </div>

                            <div class="d-grid mb-5">
                                <button data-mdb-button-init data-mdb-ripple-init
                                    class="btn btn-kalasewa btn-lg btn-block" type="submit">Simpan</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('seller/inputangka.js') }}"></script>
</section>
@endsection