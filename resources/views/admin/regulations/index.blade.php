@extends('admin.layout.app')

@section('title', 'Peraturan Platform')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Peraturan Platform</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Peraturan Platform</li>
    </ol>
</div>

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

<div class="card mb-5">
    <div class="card-body">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="umum-tab" data-toggle="tab" href="#umum" role="tab" aria-controls="umum"
                    aria-selected="true">Umum</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="penyewa-tab" data-toggle="tab" href="#penyewa" role="tab"
                    aria-controls="penyewa" aria-selected="false">Penyewa</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pemilik-sewa-tab" data-toggle="tab" href="#pemilik-sewa" role="tab"
                    aria-controls="pemilik-sewa" aria-selected="false">Pemilik Sewa</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="umum" role="tabpanel" aria-labelledby="umum-tab">
                @include('admin.regulations.partials.table', ['peraturans' => $peraturansUmum])
            </div>
            <div class="tab-pane fade" id="penyewa" role="tabpanel" aria-labelledby="penyewa-tab">
                @include('admin.regulations.partials.table', ['peraturans' => $peraturansPenyewa])
            </div>
            <div class="tab-pane fade" id="pemilik-sewa" role="tabpanel" aria-labelledby="pemilik-sewa-tab">
                @include('admin.regulations.partials.table', ['peraturans' => $peraturansPemilikSewa])
            </div>
        </div>
    </div>
</div>

@endsection