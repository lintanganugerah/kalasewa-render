@extends('layout.template')
@extends('layout.navbar')

@section('content')
<link rel="stylesheet" type="text/css" href="{{ asset('css/series.css') }}" />

<section>
    <div class="main-container container">
        <div class="row py-5">
            <div class="header">
                <img src="{{ asset('images/kalasewa.png') }}" alt="kalasewa" class="header-image">
                <h1>K A L A S E W A</h1>
                <h4>Wujudkan impian cosplaymu bersama-sama!</h4>
            </div>
        </div>

        <div class="row">
            <h1 class="text-center mb-3"><strong>SERIES</strong></h1>
            <div class="list-group">
                <div class="container gap-2 series-container">
                    @foreach ($groupedSeries as $letter => $seriesGroup)
                        <div class="series-group">
                            <h4 class="series-letter text-center">{{ $letter }}</h4>
                            <div class="container">
                                <div
                                    class="row row-cols-2 row-cols-md-2 row-cols-lg-4 row-cols-xl-5 g-2  d-flex justify-content-start">
                                    @foreach ($seriesGroup as $seriesItem)
                                        <div class="col">
                                            <a href="{{ route('search', ['series' => $seriesItem->id]) }}"
                                                class="list-group-item list-group-item-action"
                                                style="border-radius: 5px;">{{ $seriesItem->series }}</a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

@include('layout.footer')
@endsection