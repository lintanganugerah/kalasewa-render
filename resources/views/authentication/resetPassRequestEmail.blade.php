@extends('layout.layout-seller')
@section('content')
@include('layout.navbar')
<section class="vh-100">
    <div class="container py-5 h-100">
        <div class="mb-3 pb-1">
            <span class="h1 fw-bold mb-0">Lupa Password</span>
        </div>
        <div class="card" style="border-radius: 1rem;">
            <div class="row">

                <div class="d-md-block align-items-center">
                    <div class="card-body p-4 p-lg-5 text-black">

                        <form action="{{ route('ForgotPassAction') }}" method="POST">
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
                            <h5 class="fw-bold mb-3 pb-3" style="letter-spacing: 1px;">Masukan Email
                            </h5>

                            <div data-mdb-input-init class="form-outline mb-4">
                                <label class="form-label" for="form2Example17">Email</label>
                                <input type="email" id="form2Example17" class="form-control form-control-lg"
                                    name="email" required />
                            </div>

                            <div class="d-grid mb-5">
                                <button data-mdb-button-init data-mdb-ripple-init
                                    class="btn btn-kalasewa btn-lg btn-block" type="submit">Reset Password</button>
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