@extends('admin.layout.app')

@section('title', 'Dashboard')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Tambah Nama Bank</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.banks.index') }}">Kelola Daftar Bank</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tambah Nama Bank</li>
    </ol>
</div>

@if($errors->has('nama'))
    <div class="alert alert-danger mt-2">
        {{ $errors->first('nama') }}
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.banks.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="nama">Nama Bank</label>
                <input type="text" class="form-control" id="nama" name="nama" required value="{{ old('nama') }}">
            </div>
            <button type="submit" class="btn btn-primary">Tambah</button>
            <a href="{{ route('admin.banks.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

@endsection