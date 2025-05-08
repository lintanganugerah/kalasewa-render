@extends('layout.template')
@extends('layout.navbar')

@section('content')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/wishlist.css') }}" />

<section>
    <div class="main-container container-fluid px-4">
        <section>
            <div class="container-fluid px-4">
                <div class="row py-5 mb-4 justify-content-center">
                    <div class="header text-center">
                        <img src="{{ asset('images/kalasewa.png') }}" alt="kalasewa"
                            class="header-image img-fluid mx-auto d-block">
                        <h1 class="mt-3">K A L A S E W A</h1>
                        <h4 class="text-center mb-4">Wujudkan impian cosplaymu bersama-sama!</h4>
                        <h2 class="text-center mb-5">REGULASI KALASEWA</h2>
                    </div>
                </div>
            </div>
        </section>

        <div class="row justify-content-center">
            @foreach ($peraturans as $peraturan)
                <div class="col-lg-14 col-md-12 mb-4">
                    <div class="card border-1 shadow-sm">
                        <div class="card-body">
                            <h4 class="card-title"><strong>{{ $peraturan->Judul }}</strong></h4>
                            <p class="card-text">{!! $peraturan->Peraturan !!}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

@include('layout.footer')
@endsection
