@extends('layout.layout-seller')
@section('content')
@include('layout.navbar')

<section style="background-color: #f6f6f6;">
    <div class="px-4 py-5 px-md-5 text-center text-lg-start" style="background-color: hsl(0, 0%, 96%)">
        <div class="container">
            <div class="row gx-lg-5 align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <h1 class="my-5 display-3 fw-bold ls-tight">
                        Email Telah Terverifikasi
                    </h1>
                    <h4 class="ls-tight text-secondary">
                        Mohon tunggu verifikasi admin 1x24 jam
                    </h4>
                </div>
            </div>
        </div>
    </div>
</Section>
@include('layout.footer')
@endsection