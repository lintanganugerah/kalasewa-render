@extends('admin.layout.app')

@section('title', 'Dashboard')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Nama Bank</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.banks.index') }}">Kelola Daftar Bank</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Nama Bank</li>
    </ol>
</div>

@if($errors->has('nama'))
    <div class="alert alert-danger mt-2">
        {{ $errors->first('nama') }}
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.banks.update', $banks->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="nama">Nama Bank</label>
                <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $banks->nama) }}">
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.banks.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

@endsection