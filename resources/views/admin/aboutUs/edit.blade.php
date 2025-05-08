@extends('admin.layout.app')

@section('title', 'Edit Tentang Kalasewa')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Tentang Kalasewa</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Tentang Kalasewa</li>
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

<form method="POST" action="{{ route('admin.aboutus.update') }}">
    @csrf
    <div class="card mb-5">
        <div class="card-body">
            <div class="form-group">
                <textarea name="content" id="editor" rows="10"
                    class="form-control">{{ old('content', $aboutUs->content) }}</textarea>
                @error('content')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary full-width-btn btn-block">Simpan</button>
        </div>
    </div>
</form>

@include('layout.footer')
@endsection