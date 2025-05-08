<!-- resources/views/admin/category_edit.blade.php -->

@extends('admin.layout.app')

@section('title', 'Edit Kategori Tiket')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Kategori Tiket</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.ticket.category') }}">Kategori Tiket</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Kategori Tiket</li>
    </ol>
</div>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if($errors->has('nama'))
    <div class="alert alert-danger mt-2">
        {{ $errors->first('nama') }}
    </div>
@endif

<div class="card mb-5">
    <div class="card-body">
        <h5 class="card-title">Edit Kategori Tiket</h5>
        <form action="{{ route('admin.ticket.category.update', $category->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="nama">Nama Kategori</label>
                <input type="text" class="form-control" id="nama" name="nama" value="{{ $category->nama }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.ticket.category') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>

@endsection