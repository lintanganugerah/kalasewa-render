@extends('admin.layout.app')

@section('title', 'Peraturan Platform')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Tentang Kalasewa</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tentang Kalasewa</li>
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

<div class="card mb-3">
    <div class="card-body">
        <p class="card-text">{!! $aboutUs->content !!}</p>
    </div>
</div>

<div class="mb-4">
    <a href="{{ route('admin.aboutus.edit') }}" class="btn btn-primary btn-block">Edit</a>
</div>

@endsection