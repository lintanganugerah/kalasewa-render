@extends('admin.layout.app')

@section('title', 'Dashboard')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Tambah Series</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.series.index') }}">Manajemen Series</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tambah Series</li>
    </ol>
</div>

@if($errors->has('series'))
    <div class="alert alert-danger mt-2">
        {{ $errors->first('series') }}
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.series.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="series">Nama Series</label>
                <input type="text" class="form-control" id="series" name="series" required value="{{ old('series') }}">
            </div>
            <button type="submit" class="btn btn-primary">Tambah</button>
            <a href="{{ route('admin.series.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

@endsection