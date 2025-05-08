@extends('layout.layout-seller')
@section('content')
    @include('layout.navbar')

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

    <!-- (Optional) Latest compiled and minified JavaScript translation files -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/i18n/defaults-*.min.js"></script>

    <section style="background-color: #f6f6f6;">
        <div class="px-4 py-5 px-md-5 text-lg-start">
            <div class="container">
                <div class="row gx-lg-5 align-items-center">
                    <div class="col-md-6">
                        <div class="p-5 text-start rounded-3">
                            <h1 class="fw-bolder fs-1"> Daftarkan
                                bisnis
                                rental cosplay Anda di kalasewa!
                            </h1>
                            <p class="col mx-auto fs-5 text-muted">
                                Ayo, bergabung bersama dan bersiaplah
                                untuk menyambut proses penjualan yang mudah. Mari jual beragam kostum cosplay milik anda.
                                Anda
                                bisa gabung sekarang dengan proses registrasi yang mudah dan
                                cepat!
                            </p>
                            <div class="mb-5">
                                <a href="{{ route('registerViewPemilikSewa') }}">
                                    <button class="btn btn-kalasewa btn-lg px-4" type="button">
                                        Gabung Sekarang
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 text-center">
                        <img src="{{ asset('img/kalasewa_logo_big.png') }}" width="300px" alt="Logo">
                    </div>
                </div>

                <div class="row gx-lg-5 align-items-center">
                    <div class="p-5 rounded-3">
                        <h1 class="fw-bolder text-center fs-3 mb-5">Cara Mendaftarkan Bisnis Anda
                        </h1>
                        <div class="row justify-content-center align-items-center">
                            <div class="col">
                                <div class="row align-items-center">
                                    <div class="col-md-3 sm-6 text-center">
                                        <span class="badge rounded-pill d-flex justify-content-center align-items-center"
                                            style="width: 50px; height: 50px; background-color: #FFD6D6; color: #EE1B2F;">1</span>
                                    </div>
                                    <div class="col">
                                        <h5 class="card-title">Lakukan Registrasi</h5>
                                        <p class="card-text">Masukan email pribadi/toko anda untuk membuat akun kalasewa</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="row align-items-center">
                                    <div class="col-md-3 sm-6 text-center ">
                                        <span class="badge rounded-pill d-flex justify-content-center align-items-center"
                                            style="width: 50px; height: 50px; background-color: #FFD6D6; color: #EE1B2F;">2</span>
                                    </div>
                                    <div class="col">
                                        <h5 class="card-title">Lengkapi Informasi Bisnis</h5>
                                        <p class="card-text">Anda hanya perlu mengisi informasi tentang
                                            toko anda. Mudah dan cepat!</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="row align-items-center">
                                    <div class="col-md-3 sm-6 text-center ">
                                        <span class="badge rounded-pill d-flex justify-content-center align-items-center"
                                            style="width: 50px; height: 50px; background-color: #FFD6D6; color: #EE1B2F;">3</span>
                                    </div>
                                    <div class="col">
                                        <h5 class="card-title">Tunggu Verifikasi Admin</h5>
                                        <p class="card-text">Admin akan mengkonfirmasi akun anda 1x24 jam</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </Section>
    <!-- Add Bootstrap JS and jQuery scripts here -->
    @include('layout.footer')
@endsection
