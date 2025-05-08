@extends('layout.layout-seller')
@section('content')
@include('layout.navbar')

<section style="background-color: #f6f6f6;">
    <div class="px-4 py-5 px-md-5 text-center text-lg-start" style="background-color: hsl(0, 0%, 96%)">
        <div class="container">
            <div class="row gx-lg-5 align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <h1 class="my-5 display-3 fw-bold ls-tight">
                        Masukkan kode OTP
                    </h1>
                    <form id="formRegister" action="{{ route('verifyOTP') }}" method="POST">
                        @csrf
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <!-- Form -->
                        <div data-mdb-input-init class="form-outline mb-4">
                            <label class="form-label" for="formPenyewaEmail">Kode OTP</label>
                            <input type="text" id="OTP" name="kode" class="form-control" required>
                        </div>
                        <button class="btn btn-kalasewa mb-5" type="submit">Submit</button>
                    </form>
                    <h6 class="ls-tight text-secondary">
                        Kode OTP telah dikirimkan ke email anda di <div class="fw-bold kalasewa-color">
                            {{ session('email') }}</p>
                            <p class="fw-bold text-danger"> Mohon cek pada bagiam SPAM atau tunggu beberapa saat</p>
                    </h6>
                </div>
            </div>
        </div>
    </div>
    </div>
</Section>
@include('layout.footer')
@endsection