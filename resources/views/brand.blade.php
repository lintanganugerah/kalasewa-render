@extends('layout.template')
@extends('layout.navbar')

@section('content')
<link rel="stylesheet" type="text/css" href="{{ asset('css/homepage.css') }}" />

<section>
    <div class="main-container container">
        <div class="row py-5">
            <div class="header">
                <img src="{{ asset('images/kalasewa.png') }}" alt="kalasewa" class="header-image">
                <h1>K A L A S E W A</h1>
                <h4>Wujudkan impian cosplaymu bersama-sama!</h4>
            </div>
        </div>
        
        @include('layout.searchbar')

        <div class="row">
            <div class="list-group">
                <h3><strong>BRAND</strong></h3>
                <div class="container d-flex flex-wrap gap-2">
                    @foreach($brand as $brandItem)
                        <a href="{{ route('search', ['brand' => $brandItem->brand]) }}" class="list-group-item list-group-item-action">{{ $brandItem->brand }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

@include('layout.footer')
@endsection
