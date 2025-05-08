@extends('layout.layout-seller')
@extends('layout.navbar')
@section('content')
    <section class="vh-100">
        <div class="container py-5 h-100">
            <div class="mb-3 pb-1">
                <div class="h1 fw-bold mb-3">Register Sebagai Penyewa</div>
                <div class="h3 fw-bold mb-0">Mohon isi informasi Anda</div>
            </div>
            <div class="card" style="border-radius: 1rem;">
                <div class="row">
                    <!-- FORM REGISTER PERTAMA -->
                    <div class="d-md-block align-items-center">
                        <div class="card-body p-4 p-lg-5 text-black">

                            <form action="{{ route('registerInformationActionPenyewa') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        {{ $errors->first() }}
                                    </div>
                                @endif
                                <div class="form-outline mb-4">
                                    <label class="form-label" for="email">Email<span class="text-danger">*</span></label>
                                    <input type="text" id="email" class="form-control form-control-lg" name="email"
                                        value="{{ session('email') }}" disabled />
                                    <div id="password" class="form-text">Email sudah disesuaikan dengan yang diinputkan
                                    </div>
                                </div>

                                <div class="form-outline mb-4">
                                    <label for="validpassword" class="form-label">Password<span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="validpassword" name="password"
                                            minlength="8" required>
                                        <button type="button" class="btn btn-outline-secondary" id="toggle-password"
                                            onclick="togglePassword()">
                                            <i class="fas fa-eye" id="toggle-icon"></i></button>
                                    </div>
                                    <div id="password" class="form-text">
                                        Password harus <strong>memiliki panjang 8 karakter, kapital, angka, dan
                                            simbol</strong>
                                    </div>
                                </div>

                                <div class="form-outline mb-4">
                                    <label for="validpassword" class="form-label">Konfirmasi Password<span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="Password" class="form-control" id="password_confirmation"
                                            name="confPassword" minlength="8" required>
                                        <button type="button" class="btn btn-outline-secondary" id="toggle-password"
                                            onclick="konfirmasitogglePassword()">
                                            <i class="fas fa-eye" id="toggle-icon-konfirmasi"></i></button>
                                    </div>
                                    <div id="password" class="form-text">
                                        Konfirmasi password anda!
                                    </div>
                                </div>

                                <h5 class="fw-bold mb-3 pb-3" style="letter-spacing: 1px;">Informasi Pribadi</h5>

                                <div class="d-grid">
                                    <div class="mb-4">
                                        <label for="formFile" class="form-label">Foto KTP<span
                                                class="text-danger">*</span></label>
                                        <input class="form-control" type="file" id="formFile" name="foto_identitas"
                                            accept=".jpg,.png,.jpeg,.webp" required>
                                        <div id="password" class="form-text">Dapat Menggunakan Kartu Identitas Orang
                                            Tua/Wali jika anda masih dibawah umur</div>
                                    </div>
                                </div>
                                <div class="form-outline mb-4">
                                    <label class="form-label" for="nama">Nama Lengkap<span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="nama" class="form-control form-control-lg" name="nama"
                                        value="{{ old('nama') }}" required />
                                    <div id="HELPER" class="form-text">Nama harus sesuai dengan yang ada di KTP!</div>
                                </div>

                                <div class="form-outline mb-4">
                                    <label class="form-label" for="form2Example17">Nomor Identitas Kependudukan (NIK)<span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="form2Example17" class="form-control form-control-lg"
                                        name="nomor_identitas" value="{{ old('nomor_identitas') }}" required />
                                    <div id="HELPER" class="form-text">Nomor Identitas harus sesuai dengan yang ada di
                                        KTP!
                                    </div>
                                </div>

                                <div class="form-outline mb-4">
                                    <label class="form-label" for="nomor_telpon">Nomor Telpon<span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="nomor_telpon" class="form-control form-control-lg"
                                        name="nomor_telpon" pattern="[0-9]*" minlength="10" maxlength="14"
                                        value="{{ old('nomor_telpon') }}" required />
                                    <div id="HELPER" class="form-text">Nomor pribadi anda yang dapat dihubungi</div>
                                </div>

                                <div class="form-outline mb-4">
                                    <label class="form-label" for="ket_no_darurat">Kategori Nomor Darurat<span
                                            class="text-danger">*</span></label>
                                    <select class="form-select form-select-lg" id="ket_no_darurat" name="ket_no_darurat"
                                        required>
                                        @foreach ($enumOptions as $option)
                                            <option value="{{ $option }}"
                                                {{ old('ket_no_darurat') == $option ? 'selected' : '' }}>{{ $option }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="HELPER" class="form-text">Pilih kategori nomor darurat.</div>
                                </div>

                                <div class="form-outline mb-4">
                                    <label class="form-label" for="nomor_telpon_darurat">Nomor Telpon Darurat<span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="nomor_telpon_darurat" class="form-control form-control-lg"
                                        name="nomor_telpon_darurat" pattern="[0-9]*" minlength="10" maxlength="14"
                                        value="{{ old('nomor_telpon_darurat') }}" required />
                                    <div id="HELPER" class="form-text">Nomor darurat <strong>tidak dapat sama</strong>
                                        dengan nomor telpon
                                        anda!</div>
                                </div>

                                <div class="d-grid">
                                    <div class="mb-4">
                                        <label for="formFile" class="form-label">Foto Diri<span
                                                class="text-danger">*</span></label>
                                        <input class="form-control" type="file" id="formFile" name="foto_diri"
                                            accept=".jpg,.png,.jpeg,.webp" required>
                                        <div id="HELPER" class="form-text">Pastikan <strong>wajah anda terlihat dengan
                                                KTP</strong> secara JELAS!</div>
                                    </div>
                                </div>

                                <div class="form-outline mb-4">
                                    <label class="form-label" for="form2Example17">Link Sosial Media<span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="form2Example17" class="form-control form-control-lg"
                                        name="link_sosial_media" value="{{ old('link_sosial_media') }}" required />
                                    <div id="password" class="form-text">Misal https://www.instagram.com/akun_anda/</div>
                                </div>

                                <div data-mdb-input-init class="form-outline mb-4">
                                    <label class="form-label" for="form2Example27">Alamat<span
                                            class="text-danger">*</span></label>
                                    <textarea type="text" id="alamat" class="form-control form-control-lg" name="alamat" required>{{ old('alamat') }}</textarea>
                                    <div id="HELPER" class="form-text">Pastikan alamat yang digunakan ditulis dengan
                                        detail!
                                        ini akan memudahkan anda saat pengiriman barang rental!</div>
                                </div>

                                <div class="form-outline mb-4">
                                    <label for="floatingSelect">Provinsi<span class="text-danger">*</span></label>
                                    <select class="form-select form-select-lg" id="floatingSelect" name="provinsi"
                                        aria-label="Floating label select example" required>
                                        <option selected> </option>
                                        <option value="Jawa Barat"
                                            {{ old('provinsi') == 'Jawa Barat' ? 'selected' : '' }}>
                                            Jawa Barat</option>
                                    </select>
                                    <div id="provinsi" class="form-text">Mohon maaf saat ini kami hanya melayani wilayah
                                        bandung saja</div>
                                </div>

                                <div class="form-outline mb-4">
                                    <label for="floatingSelect">Kota/Kabupaten<span class="text-danger">*</span></label>
                                    <select class="form-select form-select-lg" id="floatingSelect" name="kota"
                                        aria-label="Floating label select example" required>
                                        <option selected> </option>
                                        <option value="Kota Bandung"
                                            {{ old('kota') == 'Kota Bandung' ? 'selected' : '' }}>
                                            Kota Bandung</option>
                                        <option value="Kabupaten Bandung"
                                            {{ old('kota') == 'Kabupaten Bandung' ? 'selected' : '' }}>Kabupaten Bandung
                                        </option>
                                    </select>
                                    <div id="provinsi" class="form-text">Mohon maaf saat ini kami hanya melayani wilayah
                                        bandung saja</div>
                                </div>

                                <div class="d-grid mb-5">
                                    <div class="form-outline">
                                        <label class="form-label" for="kodePos">Kode Pos<span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="kodePos" class="form-control form-control-lg"
                                            name="kodePos" pattern="[0-9]*" minlength="5" maxlength="6"
                                            value="{{ old('kodePos') }}" required />
                                    </div>
                                    <div id="provinsi" class="form-text">Mohon maaf saat ini kami hanya melayani wilayah
                                        bandung saja</div>
                                </div>

                                <div class="d-grid mb-5">
                                    <button data-mdb-button-init data-mdb-ripple-init
                                        class="btn btn-kalasewa btn-lg btn-block" type="submit">Daftar</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="{{ asset('seller/inputangka.js') }}"></script>
    </section>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('validpassword');
            const toggleIcon = document.getElementById('toggle-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        function konfirmasitogglePassword() {
            const passwordInput = document.getElementById('password_confirmation');
            const toggleIcon = document.getElementById('toggle-icon-konfirmasi');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
@endsection
