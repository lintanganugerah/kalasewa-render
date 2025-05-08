@extends('layout.layout-seller')

@section('content')
<section style="background-color: #d3373a;">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/banned.css') }}" />
    <div class="main-containernya d-flex justify-content-center align-items-center vh-100">
        <div class="container text-center">
            <div class="row align-items-center">
                <div class="col">
                    <!-- <img class="logo-kalasewa" src="{{asset('images/kalasewa.png')}}" alt="Kalasewa"> -->
                    <h1 class="display-3 fw-bold ls-tight text-light">
                        Anda telah di BANNED
                    </h1>
                    <h4 class="ls-tight text-secondary text-light">
                        Mohon maaf, Anda telah di banned dari website Kalasewa
                    </h4>
                    <div class="fw-bold kalasewa-color">{{ session('email') }}</div>
                    <p class="fw-bold text-light">Mohon cek Email anda untuk informasi lebih lanjut</p>
                    <a href="/logout" class=" btn btn-success">Log Out</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection