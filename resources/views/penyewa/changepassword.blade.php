@extends('layout.layout-seller')
@extends('layout.navbar')
@section('content')
<section class="vh-100">
    <div class="container py-5 h-100">
        <div class="mb-3 pb-1">
            <span class="h1 fw-bold mb-0">Informasi Anda</span>
        </div>
        <div class="card" style="border-radius: 1rem;">
            <div class="row">
                <div class="d-md-block align-items-center">
                    <div class="card-body p-4 p-lg-5 text-black">
                        <form action="{{ route('updatePasswordAction') }}" method="POST" enctype="multipart/form-data">
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
                            <h5 class="fw-bold mb-3 pb-3" style="letter-spacing: 1px;">Ubah Password</h5>

                            <div data-mdb-input-init class="form-outline mb-4">
                                <label class="form-label" for="password">Password Lama</label>
                                <div class="input-group">
                                    <input type="password" id="oldPassword" class="form-control form-control-lg"
                                        name="password" required />
                                    <button type="button" class="btn btn-outline-secondary" id="toggle-password"
                                        onclick="toggleOldPassword()">
                                        <i class="fas fa-eye" id="toggle-old-pass"></i></button>
                                </div>
                                <div id="password" class="form-text">
                                    Silahkan input password anda saat ini!
                                </div>
                            </div>

                            <div data-mdb-input-init class="form-outline mb-4">
                                <label class="form-label" for="password">Password Baru</label>
                                <div class="input-group">
                                    <input type="password" id="newPassword" class="form-control form-control-lg"
                                        name="newPassword" minlength="8" required />
                                    <button type="button" class="btn btn-outline-secondary" id="toggle-password"
                                        onclick="toggleNewPassword()">
                                        <i class="fas fa-eye" id="toggle-new-pass"></i></button>
                                </div>
                                <div id="password" class="form-text">
                                    Password harus memiliki panjang 8 karakter, kapital, angka, dan simbol
                                </div>
                            </div>

                            <div data-mdb-input-init class="form-outline mb-4">
                                <label class="form-label" for="password">Konfirmasi Password Baru</label>
                                <div class="input-group">
                                    <input type="password" id="confNewPassword" class="form-control form-control-lg"
                                        name="confNewPassword" minlength="8" required />
                                    <button type="button" class="btn btn-outline-secondary" id="toggle-password"
                                        onclick="toggleNewConfPassword()">
                                        <i class="fas fa-eye" id="toggle-new-pass-konfirmasi"></i></button>
                                </div>
                                <div id="password" class="form-text">
                                    Konfirmasi password anda!
                                </div>
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

<script>
function toggleOldPassword() {
    const passwordInput = document.getElementById('oldPassword');
    const toggleIcon = document.getElementById('toggle-old-pass');
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

function toggleNewPassword() {
    const passwordInput = document.getElementById('newPassword');
    const toggleIcon = document.getElementById('toggle-new-pass');
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

function toggleNewConfPassword() {
    const passwordInput = document.getElementById('confNewPassword');
    const toggleIcon = document.getElementById('toggle-new-pass-konfirmasi');
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