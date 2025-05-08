@extends('admin.layout.app')

@section('title', 'Ubah Peraturan')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Ubah Peraturan</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Ubah Peraturan</li>
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

<form action="{{ route('admin.regulations.update') }}" method="POST">
    @csrf
    <div class="card mb-5">
        <div class="card-body">
            <h5 class="card-title">{{ $peraturan->Judul }}</h5>
            <div class="form-group">
                <textarea class="form-control" name="Peraturan[{{ $peraturan->id }}]" rows="10"
                    id="editor">{{ old('Peraturan.' . $peraturan->id, $peraturan->Peraturan) }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </div>
</form>

@endsection